<?php
// -----------------------------------------------------------------------------
//  File: SINGLE for SEMINARS
//  The page content is built in the editor with the ISHF blocks (ISHF Intro,
//  ISHF Video Slider, ISHF Seminar Program, ISHF Gallery, ISHF Quotation, ...)
//  and rendered as they are, in the order they were added. No "groups" needed.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------

// -- Initializations
global $featured_articles;
$featured_articles = array();
the_post();
array_push($featured_articles, get_the_ID());
ishf_set_post_views(get_the_ID());

$post_tags = get_the_tags(get_the_ID());
$youtube_id_1 = get_post_meta(get_the_ID(), 'ishf_cf_youtube_id_1', true);
$youtube_id_2 = get_post_meta(get_the_ID(), 'ishf_cf_youtube_id_2', true);
$has_video_slider = has_block('ishf/video-slider', get_the_content());

// -- Broadcast window (live / replay) from the date fields
$tz = new DateTimeZone('Europe/Lisbon');
$ishf_dt = function ($value, $minus = '') use ($tz) {
  $value = trim((string) $value);
  if ($value === '') {
    return null;
  }
  try {
    $dt = new DateTime($value, $tz);
    if ($minus !== '') {
      $dt->sub(new DateInterval($minus));
    }
    return $dt;
  } catch (Exception $e) {
    return null;
  }
};
$date_start_broadcast_1 = $ishf_dt(get_post_meta(get_the_ID(), 'ishf_cf_date_time_1', true), 'PT10M');
$date_end_broadcast_1 = $ishf_dt(get_post_meta(get_the_ID(), 'ishf_cf_date_end_time_1', true));
$date_start_broadcast_2 = $ishf_dt(get_post_meta(get_the_ID(), 'ishf_cf_date_time_2', true), 'PT10M');
$date_end_broadcast_2 = $ishf_dt(get_post_meta(get_the_ID(), 'ishf_cf_date_end_time_2', true));
$now = new DateTime('now', $tz);

$is_streaming_1 = $date_start_broadcast_1 && $date_end_broadcast_1 && $now >= $date_start_broadcast_1 && $now < $date_end_broadcast_1;
$is_streaming_2 = $date_start_broadcast_2 && $date_end_broadcast_2 && $now >= $date_start_broadcast_2 && $now < $date_end_broadcast_2;

// Resolve $youtube_id (favors active session, falls back to ID #1, #2 or custom field)
$youtube_id = $is_streaming_2 && !$is_streaming_1 && !empty($youtube_id_2)
  ? $youtube_id_2
  : (!empty($youtube_id_1) ? $youtube_id_1 : (!empty($youtube_id_2) ? $youtube_id_2 : (string) get_post_meta(get_the_ID(), 'ishf_cf_youtube_id', true)));

// -- This seminar's sessions (dates, calendar links)
$seminar_sessions = array_values(array_filter(ishf_get_programme_sessions(), function ($s) {
  return $s['id'] === get_the_ID() && !empty($s['start']);
}));

// -- Parse blocks to separate ISHF Intro from the rest of the content
$intro_html = '';
$rest_html  = '';

$raw_content = get_the_content();
if (trim((string) $raw_content) !== '') {
  $blocks = parse_blocks($raw_content);
  $intro_block = null;
  $rest_blocks = array();

  foreach ($blocks as $block) {
    if (!$intro_block && ($block['blockName'] ?? '') === 'ishf/intro') {
      $intro_block = $block;
    } else {
      $rest_blocks[] = $block;
    }
  }

  if ($intro_block) {
    $intro_html = trim((string) apply_filters('the_content', serialize_block($intro_block)));
  }

  if (!empty($rest_blocks)) {
    $rest_html = trim((string) apply_filters('the_content', serialize_blocks($rest_blocks)));
  }
}

ishf_use_full_layout(true); // full navigation + footer (inner pages)
get_header();

// -- Section: HEADER (compact on every inner page)
get_template_part('includes/sections/header');
?>

<main id="content-start" role="main" class="site-main" tabindex="-1">

  <section id="seminar-heading" class="uk-section uk-section-small">
    <div class="seminar-head-inner">
      <p class="ishf-eyebrow"><?php esc_html_e('Seminar', TEXT_DOMAIN); ?></p>
      <?php if (has_excerpt()): ?>
        <p class="text-featured"><?php echo esc_html(get_the_excerpt()); ?></p>
      <?php endif; ?>
      <?php if (!empty($seminar_sessions)): ?>
        <ul class="seminar-when">
          <?php foreach ($seminar_sessions as $s):
            $ics = ishf_programme_ics_url($s);
          ?>
            <li>
              <span data-uk-icon="icon: calendar; ratio: .9" aria-hidden="true"></span>
              <time datetime="<?php echo esc_attr($s['start']->format('c')); ?>"><?php echo esc_html(ishf_programme_when_label($s)); ?></time>
              <?php if ($ics && $now <= $s['start']): ?>
                <a class="uk-button uk-button-text uk-button-small" href="<?php echo esc_url($ics); ?>" rel="nofollow"><?php esc_html_e('Add to calendar', TEXT_DOMAIN); ?></a>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </section>

  <?php
  // -- BLOCK: ISHF Intro
  if (!empty($intro_html)):
  ?>
  <div class="ishf-seminar-content ishf-seminar-content--intro">
    <?php echo $intro_html; ?>
  </div>
  <?php endif; ?>

  <?php
  // -- SECTION: LIVESTREAM (if $youtube_id is declared)
  // Replay mode (after the last session, when no ISHF Video Slider block is used)
  if (!empty($youtube_id_1) && !empty($youtube_id_2) && !$has_video_slider && $date_end_broadcast_2 && $now >= $date_end_broadcast_2):
  ?>
  <section id="seminar-video" class="uk-section uk-light">
    <div class="uk-container uk-container-xlarge">
      <div class="uk-text-center">
        <h2 class="uk-h1"><?php esc_html_e('Watch the conferences again', TEXT_DOMAIN); ?></h2>
        <p><?php esc_html_e('Watch the events again and relive the highlights of these conversations.', TEXT_DOMAIN); ?></p>
      </div>
      <div data-uk-grid>
        <?php foreach (array($youtube_id_1, $youtube_id_2) as $yt_id): ?>
          <div class="uk-width-1-1 uk-width-1-2@m"><?php get_template_part('includes/video-youtube', 'embed', array('youtube_id' => $yt_id, 'youtube_title' => get_the_title(), 'autoplay' => false)); ?></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php
  // Livestream section (displayed if $youtube_id is declared)
  elseif (!empty($youtube_id)):
  ?>
  <section id="seminar-video" class="uk-section uk-light">
    <div class="uk-container uk-container-large">
      <div data-uk-grid>
        <div class="uk-width-1-1 uk-width-1-4@s">
          <h6 class="uk-margin-small-bottom uk-text-uppercase"><?php esc_html_e('Streaming', TEXT_DOMAIN); ?></h6>
          <h2 class="uk-h1"><?php esc_html_e('Follow live', TEXT_DOMAIN); ?></h2>
          <p><?php esc_html_e('Watch the conference live and find out more about the topics under discussion.', TEXT_DOMAIN); ?></p>
        </div>
        <div class="uk-width-1-1 uk-width-expand@s">
          <?php get_template_part('includes/video-youtube', 'embed', array('youtube_id' => $youtube_id, 'youtube_title' => get_the_title(), 'autoplay' => false)); ?>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php
  // -- CONTENT: Rest of the ISHF blocks (and any core block)
  if (!empty($rest_html)):
  ?>
  <div class="ishf-seminar-content">
    <?php echo $rest_html; ?>
  </div>
  <?php endif; ?>

  <?php
  // -- Section: SPEAKERS are inside the ISHF Seminar Program block. Sponsors are rendered by the footer.
  ?>
</main>

<?php get_footer(); ?>
