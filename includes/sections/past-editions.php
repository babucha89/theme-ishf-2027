<?php
// -----------------------------------------------------------------------------
//  Section: PAST EDITIONS (timeline of the CPT "editions")
//  One card per edition (logo, title, dates, city) on a vertical timeline (UIkit
//  grid: marker | card, cards alternating left / right from 960px), oldest
//  first, closing with Lisbon 2027 (links to the Lisbon 2027 page, page-lisbon-2027.php,
//  or to the homepage until that page exists). Each card opens a modal with everything:
//  logo (left column) next to name, dates and venue / address (right column),
//  then map (Mapbox, js/past-editions.js) and content.
//  A modal opens directly from a link to the page with its id as hash,
//  e.g. /past-editions/#edition-dublin-2025.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 17-NOV-2026
// -----------------------------------------------------------------------------
if (!function_exists('ishf_get_editions')) {
  return;
}

$ishf_editions = ishf_get_editions('ASC');

// Logo of an edition: featured image + optional dark-mode version
$ishf_edition_logo = function ($post_id, $class) {
  $alt   = sprintf(__('%s logo', TEXT_DOMAIN), get_the_title($post_id));
  $light = get_the_post_thumbnail($post_id, 'medium', array('class' => $class, 'alt' => $alt, 'loading' => 'lazy'));
  if ($light === '') {
    return '';
  }
  $dark_id = (int) get_post_meta($post_id, 'ishf_cf_logo_dark_id', true);
  if (!$dark_id) {
    return $light;
  }
  $dark = wp_get_attachment_image($dark_id, 'medium', false, array('class' => $class . ' dark-mode-img', 'alt' => $alt, 'loading' => 'lazy'));
  return str_replace('class="' . $class, 'class="' . $class . ' light-mode-img', $light) . $dark;
};
?>
<section id="past-editions" class="ishf-editions" aria-label="<?php esc_attr_e('Timeline of past editions', TEXT_DOMAIN); ?>">
  <div class="uk-container">

    <?php if (empty($ishf_editions)): ?>
      <p class="editions-empty uk-width-2xlarge uk-margin-auto"><?php _e('The past editions will be published soon.', TEXT_DOMAIN); ?></p>
    <?php else: ?>

    <ol class="editions-timeline">
      <?php foreach ($ishf_editions as $ishf_i => $edition):
        $eid      = $edition->ID;
        $modal_id = 'edition-' . $edition->post_name;
        $title    = get_the_title($eid);
        $dates    = ishf_edition_dates($eid);
        $logo     = $ishf_edition_logo($eid, 'edition-logo');
        $side     = $ishf_i % 2 === 0 ? 'left' : 'right'; // desktop: cards alternate around the line
        $has_dark = (bool) get_post_meta($eid, 'ishf_cf_logo_dark_id', true);
        $city     = ishf_edition_location($eid)['city'];
      ?>
      <li class="edition-item uk-grid uk-grid-collapse">
        <div class="edition-marker-col uk-width-auto"><span class="edition-marker" aria-hidden="true"><?php echo esc_html($dates ? $dates['year'] : '—'); ?></span></div>
        <div class="edition-card-col uk-width-expand<?php echo $side === 'left' ? ' uk-flex-first@m' : ''; ?>">
        <article class="edition-card" aria-labelledby="<?php echo esc_attr($modal_id); ?>-card-title">
          <div class="uk-grid-medium uk-flex-middle" data-uk-grid>
          <?php if ($logo !== ''): ?>
            <div class="uk-width-1-1 uk-width-1-3@m"><div class="edition-card-logo<?php echo $has_dark ? ' has-dark-logo' : ''; ?>"><?php echo $logo; ?></div></div>
          <?php endif; ?>
          <div class="edition-card-body uk-width-1-1 uk-width-expand@m">
            <h2 id="<?php echo esc_attr($modal_id); ?>-card-title" class="edition-card-title"><?php echo esc_html($title); ?></h2>
            <?php if ($dates || $city !== ''): ?>
              <p class="edition-card-dates">
                <span data-uk-icon="icon: <?php echo $dates ? 'calendar' : 'location'; ?>; ratio: 0.85" aria-hidden="true"></span>
                <?php if ($dates): ?><time datetime="<?php echo esc_attr($dates['datetime']); ?>"><?php echo esc_html($dates['label']); ?></time><?php endif; ?>
                <?php if ($city !== ''): ?><span class="edition-card-city"><?php if ($dates): ?><span aria-hidden="true">·</span> <?php endif; ?><?php echo esc_html($city); ?></span><?php endif; ?>
              </p>
            <?php endif; ?>
            <button type="button" class="uk-button uk-button-primary uk-border-pill edition-card-button uk-width-1-1 uk-width-auto@s" data-uk-toggle="target: #<?php echo esc_attr($modal_id); ?>" aria-haspopup="dialog" aria-controls="<?php echo esc_attr($modal_id); ?>">
              <span><?php _e('Discover this edition', TEXT_DOMAIN); ?></span>
              <span class="sr-only"><?php echo esc_html(': ' . $title); ?></span>
              <span data-uk-icon="icon: arrow-right; ratio: 0.9" aria-hidden="true"></span>
            </button>
          </div>
          </div>
        </article>
        </div>
        <div class="uk-width-expand uk-visible@m<?php echo $side === 'right' ? ' uk-flex-first@m' : ''; ?>" aria-hidden="true"></div>
      </li>
      <?php endforeach; ?>
      <?php $side = count($ishf_editions) % 2 === 0 ? 'left' : 'right'; // the next edition continues the alternation ?>

      <!-- Current edition: Lisbon 2027 -->
      <li class="edition-item edition-item--next uk-grid uk-grid-collapse">
        <div class="edition-marker-col uk-width-auto"><span class="edition-marker" aria-hidden="true">2027</span></div>
        <div class="edition-card-col uk-width-expand<?php echo $side === 'left' ? ' uk-flex-first@m' : ''; ?>">
        <div class="edition-card edition-card--next">
          <div class="edition-card-body">
            <p class="edition-card-eyebrow"><?php _e('Current edition', TEXT_DOMAIN); ?></p>
            <h2 class="edition-card-title"><?php _e('Lisbon 2027', TEXT_DOMAIN); ?></h2>
            <p class="edition-card-dates">
              <span data-uk-icon="icon: calendar; ratio: 0.85" aria-hidden="true"></span>
              <time datetime="2027-06-07/2027-06-09"><?php _e('7 – 9 June 2027', TEXT_DOMAIN); ?></time> · <?php _e('Lisbon, Portugal', TEXT_DOMAIN); ?>
            </p>
            <?php $ishf_lisbon_url = function_exists('ishf_get_template_page_url') ? ishf_get_template_page_url('page-lisbon-2027.php', 'lisbon-2027') : ''; ?>
            <a class="uk-button uk-button-default uk-border-pill edition-card-button uk-width-1-1 uk-width-auto@s" href="<?php echo esc_url($ishf_lisbon_url !== '' ? $ishf_lisbon_url : home_url('/')); ?>">
              <span><?php _e('Discover ISHF 2027', TEXT_DOMAIN); ?></span>
              <span data-uk-icon="icon: arrow-right; ratio: 0.9" aria-hidden="true"></span>
            </a>
          </div>
        </div>
        </div>
        <div class="uk-width-expand uk-visible@m<?php echo $side === 'right' ? ' uk-flex-first@m' : ''; ?>" aria-hidden="true"></div>
      </li>
    </ol>

    <?php endif; ?>
  </div>

  <?php
  // -- Modals (one per edition)
  foreach ($ishf_editions as $edition):
    $eid      = $edition->ID;
    $modal_id = 'edition-' . $edition->post_name;
    $title    = get_the_title($eid);
    $dates    = ishf_edition_dates($eid);
    $logo     = $ishf_edition_logo($eid, 'edition-modal-logo');
    $has_dark = (bool) get_post_meta($eid, 'ishf_cf_logo_dark_id', true);
    $location = ishf_edition_location($eid);
    $venue    = $location['venue'];
    $address  = $location['address'];
    $city     = $location['city'];
    $coords   = ishf_edition_coordinates($eid);
    $content  = apply_filters('the_content', $edition->post_content);
    $video_id = (string) get_post_meta($eid, 'ishf_cf_youtube_id', true);
    $gallery  = (string) get_post_meta($eid, 'ishf_cf_gallery', true);
    $gallery  = $gallery !== '' ? do_shortcode('[gallery ids="' . esc_attr($gallery) . '" columns="3"]') : ''; // includes/gallery.php
    $place    = $venue !== '' ? $venue : ($address !== '' ? $address : $city); // no venue / address: city only
    $maps_url = $coords
      ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($coords['lat'] . ',' . $coords['lng'])
      : ($address !== '' ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(trim($venue . ' ' . $address)) : '');
  ?>
  <div id="<?php echo esc_attr($modal_id); ?>" class="uk-flex-top modal-ishf-edition" data-uk-modal>
    <div class="uk-modal-dialog uk-width-2xlarge@m uk-margin-auto-vertical" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($modal_id); ?>-title">
      <button class="uk-modal-close-default" type="button" data-uk-close aria-label="<?php esc_attr_e('Close modal', TEXT_DOMAIN); ?>"></button>

      <!-- Two columns: logo | name, dates and location (map and content follow below) -->
      <div class="edition-modal-top uk-grid-medium uk-flex-middle<?php echo $logo !== '' ? ' has-logo' : ''; ?>" data-uk-grid>
        <?php if ($logo !== ''): ?>
          <div class="uk-width-1-1 uk-width-2-5@s"><div class="edition-modal-logo-box<?php echo $has_dark ? ' has-dark-logo' : ''; ?>"><?php echo $logo; ?></div></div>
        <?php endif; ?>
        <div class="edition-modal-info uk-width-1-1 uk-width-expand@s">
          <header class="edition-modal-header">
            <?php if ($dates): ?><p class="edition-modal-eyebrow"><?php echo esc_html(sprintf(__('ISHF %s', TEXT_DOMAIN), $dates['year'])); ?></p><?php endif; ?>
            <h2 id="<?php echo esc_attr($modal_id); ?>-title" class="edition-modal-title"><?php echo esc_html($title); ?></h2>
          </header>

          <ul class="edition-modal-facts">
            <?php if ($dates): ?>
              <li>
                <span class="edition-fact-icon" data-uk-icon="icon: calendar" aria-hidden="true"></span>
                <span><span class="sr-only"><?php _e('Dates', TEXT_DOMAIN); ?>: </span><time datetime="<?php echo esc_attr($dates['datetime']); ?>"><?php echo esc_html($dates['label']); ?></time></span>
              </li>
            <?php endif; ?>
            <?php if ($place !== ''): ?>
              <li>
                <span class="edition-fact-icon" data-uk-icon="icon: location" aria-hidden="true"></span>
                <span>
                  <span class="sr-only"><?php _e('Location', TEXT_DOMAIN); ?>: </span>
                  <?php if ($venue !== ''): ?><strong class="edition-venue"><?php echo esc_html($venue); ?></strong><?php endif; ?>
                  <?php if ($address !== '' && $address !== $venue): ?><span class="edition-address"><?php echo esc_html($address); ?></span><?php endif; ?>
                  <?php if (!$location['exact']): ?><strong class="edition-venue"><?php echo esc_html($city); ?></strong><?php endif; ?>
                </span>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>

      <?php if ($coords || $maps_url !== ''): ?>
        <div class="edition-modal-map">
          <?php if ($coords && SITE_MAPBOX_TOKEN !== ''): ?>
            <div class="edition-map" data-lat="<?php echo esc_attr($coords['lat']); ?>" data-lng="<?php echo esc_attr($coords['lng']); ?>" data-label="<?php echo esc_attr($place); ?>" role="region" aria-label="<?php echo esc_attr(sprintf(__('Map: %s', TEXT_DOMAIN), $place !== '' ? $place : $title)); ?>"></div>
          <?php endif; ?>
          <?php if ($maps_url !== ''): ?>
            <a class="edition-map-link" href="<?php echo esc_url($maps_url); ?>" target="_blank" rel="noopener noreferrer">
              <?php _e('View on Google Maps', TEXT_DOMAIN); ?><span class="sr-only"> (<?php _e('opens in a new tab', TEXT_DOMAIN); ?>)</span>
              <span data-uk-icon="icon: push; ratio: 0.8" aria-hidden="true"></span>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if (trim(wp_strip_all_tags($content)) !== '' || strpos($content, '<img') !== false): ?>
        <div class="edition-modal-content uk-article-content"><?php echo $content; ?></div>
      <?php endif; ?>

      <?php if ($video_id !== ''): // loads only when the popup opens (hidden iframes are lazy); paused when it closes (uk-video) ?>
        <div class="edition-modal-media edition-modal-video">
          <h3 class="edition-modal-subtitle"><?php _e('Video', TEXT_DOMAIN); ?></h3>
          <div class="edition-video-frame">
            <iframe src="<?php echo esc_url('https://www.youtube-nocookie.com/embed/' . rawurlencode($video_id) . '?rel=0&enablejsapi=1'); ?>" width="1280" height="720" title="<?php echo esc_attr(sprintf(__('Video: %s', TEXT_DOMAIN), $title)); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin" data-uk-video="autoplay: false"></iframe>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($gallery !== ''): ?>
        <div class="edition-modal-media edition-modal-gallery">
          <h3 class="edition-modal-subtitle"><?php _e('Photo gallery', TEXT_DOMAIN); ?></h3>
          <?php echo $gallery; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</section>
