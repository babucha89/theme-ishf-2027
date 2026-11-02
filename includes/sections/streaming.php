<?php
// -----------------------------------------------------------------------------
//  Section: STREAMING EVENT
//  Displays a live or recorded streaming event block (Inline Embed or Modal)
//  Data source: WP Page ('sections/streaming' or 'streaming', or custom page_path/page_id)
//  Title, intro content, poster image, and [youtube] shortcode are all defined
//  directly inside the WordPress page so the block can be reused across occasions.
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 30-OUT-2026
// -----------------------------------------------------------------------------

$page_path = !empty($args['page_path']) ? sanitize_text_field($args['page_path']) : 'sections/streaming';
$page_streaming = !empty($args['page_id'])
  ? get_post((int) $args['page_id'])
  : get_page_by_path($page_path);

if (empty($page_streaming) && $page_path === 'sections/streaming') {
  $page_streaming = get_page_by_path('streaming');
}

if (!empty($page_streaming)):
  if ($page_streaming->post_status == 'publish'):

    $page_id     = $page_streaming->ID;
    $raw_content = get_post($page_id)->post_content;

    // 1. Resolve Title & Content from the Streaming Page
    //    - If the editor placed a Heading block inside the page content, use the first
    //      heading as the section title and remove it from the body content to avoid duplication.
    //    - Otherwise, use the WordPress Page Title (post_title) and the full page content.
    $headings = function_exists('uikit_cbs_get_post_headings')
      ? uikit_cbs_get_post_headings($page_id)
      : array();

    if (!empty($headings)) {
      $title   = wp_strip_all_tags($headings[0]);
      $content = function_exists('uikit_cbs_remove_post_headings')
        ? uikit_cbs_remove_post_headings($page_id, 0)
        : do_blocks($raw_content);
    } else {
      $title   = get_post($page_id)->post_title;
      $content = do_blocks($raw_content);
    }

    // Allow optional override via $args if passed programmatically
    if (!empty($args['title'])) {
      $title = $args['title'];
    }

    // 2. Extract YouTube ID & Shortcode Attributes from the Streaming Page
    $youtubes = function_exists('uikit_cbs_get_post_youtubes')
      ? uikit_cbs_get_post_youtubes($page_id, false)
      : array();
    $extracted_youtube_id = !empty($youtubes) ? trim($youtubes[0]) : '';

    $shortcode_atts = function_exists('uikit_cbs_get_post_youtube_atts')
      ? uikit_cbs_get_post_youtube_atts($page_id)
      : array();

    if (empty($shortcode_atts) && preg_match('/\[youtube\b([^\]]*)\]/i', $raw_content, $matches)) {
      $parsed = shortcode_parse_atts($matches[1]);
      if (is_array($parsed)) {
        $shortcode_atts = $parsed;
      }
    }

    if (empty($extracted_youtube_id) && !empty($shortcode_atts['id'])) {
      $extracted_youtube_id = trim($shortcode_atts['id']);
    }

    // Clean YouTube ID if a full YouTube URL (watch?v=, youtu.be/, embed/, live/) was provided
    if (!empty($extracted_youtube_id)) {
      if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $extracted_youtube_id, $match)) {
        $extracted_youtube_id = $match[1];
      }
    }

    // 3. Remove [youtube] shortcode from text content so it is not rendered twice
    if (function_exists('uikit_cbs_strip_shortcode')) {
      $content = uikit_cbs_strip_shortcode($content, 'youtube');
    } else {
      $content = preg_replace('/\[youtube\b[^\]]*\](?:<\/p>)?/i', '', $content);
    }

    $clean_content = !empty($content) ? apply_filters('the_content', $content) : '';
    $clean_content = str_replace('<p></p>', '', trim($clean_content));

    // 4. Only render if the page has a valid [youtube] video ID
    if (!empty($extracted_youtube_id)):

      // Resolve display mode: 'embed' (default) or 'modal'
      $meta_mode = get_post_meta($page_id, 'streaming_mode', true);
      $display_mode = !empty($args['mode'])
        ? strtolower($args['mode'])
        : (!empty($shortcode_atts['mode'])
          ? strtolower($shortcode_atts['mode'])
          : (!empty($shortcode_atts['display'])
            ? strtolower($shortcode_atts['display'])
            : (!empty($meta_mode) ? strtolower($meta_mode) : 'embed')));

      if (!in_array($display_mode, array('embed', 'modal'), true)) {
        $display_mode = 'embed';
      }

      // Resolve autoplay & auto_fire options from [youtube] shortcode or $args
      $is_autoplay = isset($args['autoplay'])
        ? filter_var($args['autoplay'], FILTER_VALIDATE_BOOLEAN)
        : (isset($shortcode_atts['autoplay']) ? filter_var($shortcode_atts['autoplay'], FILTER_VALIDATE_BOOLEAN) : false);

      $is_auto_fire = isset($args['auto_fire'])
        ? filter_var($args['auto_fire'], FILTER_VALIDATE_BOOLEAN)
        : (isset($shortcode_atts['auto_fire']) ? filter_var($shortcode_atts['auto_fire'], FILTER_VALIDATE_BOOLEAN) : false);

      // Resolve Badge text: Page Excerpt -> [youtube badge="..."] -> Custom Field -> Default
      $page_excerpt = trim(get_post_field('post_excerpt', $page_id));
      $meta_badge   = get_post_meta($page_id, 'streaming_badge', true);
      $badge_text   = !empty($args['badge'])
        ? $args['badge']
        : (!empty($page_excerpt)
          ? $page_excerpt
          : (!empty($shortcode_atts['badge'])
            ? $shortcode_atts['badge']
            : (!empty($meta_badge) ? $meta_badge : __('Live Stream', TEXT_DOMAIN))));

      // Resolve iframe accessible title
      $iframe_title = !empty($shortcode_atts['title'])
        ? wp_strip_all_tags(str_replace('{br}', ' ', $shortcode_atts['title']))
        : (!empty($title) ? wp_strip_all_tags($title) : __('YouTube Video', TEXT_DOMAIN));

      // Resolve Modal Trigger Button text
      $button_text = !empty($args['button_text'])
        ? $args['button_text']
        : (!empty($shortcode_atts['button_text'])
          ? $shortcode_atts['button_text']
          : __('Watch Live Stream', TEXT_DOMAIN));

      // Resolve Poster Image (Page Featured Image -> YouTube High-Res Thumbnail)
      $poster_url = !empty($args['poster']) ? $args['poster'] : '';
      if (empty($poster_url) && has_post_thumbnail($page_id)) {
        $poster_url = get_the_post_thumbnail_url($page_id, 'image-gallery');
        if (empty($poster_url)) {
          $poster_url = get_the_post_thumbnail_url($page_id, 'large');
        }
      }
      if (empty($poster_url)) {
        $poster_url = 'https://img.youtube.com/vi/' . rawurlencode($extracted_youtube_id) . '/maxresdefault.jpg';
      }

      // Unique section & modal IDs when reused across multiple occasions/pages
      $is_default_page = in_array($page_streaming->post_name, array('streaming'), true) && empty($args['page_path']) && empty($args['page_id']);
      $section_id      = $is_default_page ? 'streaming' : 'streaming-' . esc_attr($page_streaming->post_name);
      $title_id        = $section_id . '-title';
      $modal_target_id = !empty($args['modal_id'])
        ? sanitize_html_class($args['modal_id'])
        : (!empty($shortcode_atts['modal_id'])
          ? sanitize_html_class($shortcode_atts['modal_id'])
          : ($is_default_page ? 'modal-streaming-video' : 'modal-streaming-video-' . $page_id));
?>
<section id="<?php echo esc_attr($section_id); ?>" class="uk-section section-streaming uk-position-relative" aria-labelledby="<?php echo esc_attr($title_id); ?>">
  <div class="uk-container">

    <div class="cta-box cta-box-streaming">

      <!-- Decorative wave (Figma: deco/wave) -->
      <img class="streaming-wave" src="<?php echo esc_url(BASE_ASSETS . 'icon-wave.svg'); ?>" alt="" aria-hidden="true" />

      <div class="streaming-layout uk-grid-large uk-flex-middle" data-uk-grid>

      <div class="uk-width-1-1 uk-width-1-2@m">
      <!-- Streaming Header (Driven by the Streaming Page Title, Excerpt & Content) -->
      <div class="streaming-header">
        <?php if (!empty($badge_text)): ?>
          <span class="cta-badge cta-badge-streaming uk-inline-flex uk-flex-middle">
            <span class="streaming-live-dot" aria-hidden="true"></span>
            <span><?php echo esc_html($badge_text); ?></span>
          </span>
        <?php endif; ?>

        <?php if (!empty($title)): ?>
          <h2 id="<?php echo esc_attr($title_id); ?>" class="cta-title uk-margin-small-top uk-margin-small-bottom">
            <?php echo esc_html($title); ?>
          </h2>
        <?php endif; ?>

        <?php if (!empty($clean_content)): ?>
          <div class="cta-intro streaming-intro">
            <?php echo $clean_content; ?>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($display_mode === 'modal'): ?>
        <!-- Watch live CTA (Figma): opens the video modal; label from [youtube button_text] / $args / default -->
        <button type="button"
                class="uk-button uk-button-primary streaming-watch-btn"
                data-uk-toggle="target: #<?php echo esc_attr($modal_target_id); ?>"
                aria-haspopup="dialog"
                aria-controls="<?php echo esc_attr($modal_target_id); ?>">
          <?php echo esc_html($button_text); ?>
        </button>
      <?php endif; ?>
      </div>

      <div class="uk-width-1-1 uk-width-1-2@m">
      <!-- Streaming Media Output (Embed or Modal Trigger) -->
      <?php if ($display_mode === 'modal'): ?>

        <div class="streaming-modal-preview-container">
          <div class="streaming-video-wrapper streaming-poster-wrapper uk-position-relative uk-border-rounded uk-overflow-hidden">
            <img src="<?php echo esc_url($poster_url); ?>"
                 alt="<?php echo esc_attr($iframe_title); ?>"
                 class="streaming-poster-img uk-width-1-1"
                 loading="lazy" />
            <div class="streaming-poster-overlay uk-position-cover uk-flex uk-flex-column uk-flex-center uk-flex-middle uk-padding">
              <button type="button"
                      class="streaming-play-btn uk-flex uk-flex-center uk-flex-middle"
                      data-uk-toggle="target: #<?php echo esc_attr($modal_target_id); ?>"
                      aria-haspopup="dialog"
                      aria-controls="<?php echo esc_attr($modal_target_id); ?>"
                      aria-label="<?php echo esc_attr(sprintf(__('Play video: %s', TEXT_DOMAIN), $iframe_title)); ?>">
                <span data-uk-icon="icon: play; ratio: 2.2" aria-hidden="true"></span>
              </button>
            </div>
          </div>
        </div>

        <?php
        // -- Include YouTube Modal Template (uikit-boostsolutions pattern)
        global $youtube_id, $modal_id, $auto_fire, $youtube_title;
        $youtube_id    = $extracted_youtube_id;
        $modal_id      = $modal_target_id;
        $auto_fire     = $is_auto_fire;
        $youtube_title = $iframe_title;
        get_template_part('includes/video-youtube', 'modal');
        ?>

      <?php else: ?>

        <div class="streaming-video-wrapper uk-position-relative uk-border-rounded uk-overflow-hidden">
          <?php
          // -- Include YouTube Embed Template (uikit-boostsolutions pattern)
          global $youtube_id, $autoplay, $youtube_title;
          $youtube_id    = $extracted_youtube_id;
          $autoplay      = $is_autoplay;
          $youtube_title = $iframe_title;
          get_template_part('includes/video-youtube', 'embed');
          ?>
        </div>

      <?php endif; ?>
      </div>

      </div><!-- /.streaming-layout -->

    </div>

  </div>
</section>
<?php
    endif;
  endif;
endif;
?>
