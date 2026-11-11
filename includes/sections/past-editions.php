<?php
// -----------------------------------------------------------------------------
//  Section: PAST EDITIONS (timeline of the CPT "editions")
//  One card per edition (logo, title, dates) on a vertical timeline, oldest
//  first, closing with Lisbon 2027. Each card opens a modal with everything:
//  logo, dates, venue, address, map (Mapbox, js/past-editions.js) and content.
//  A modal opens directly from a link to the page with its id as hash,
//  e.g. /past-editions/#edition-dublin-2025.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 11-NOV-2026
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
      <p class="editions-empty"><?php _e('The past editions will be published soon.', TEXT_DOMAIN); ?></p>
    <?php else: ?>

    <ol class="editions-timeline">
      <?php foreach ($ishf_editions as $edition):
        $eid      = $edition->ID;
        $modal_id = 'edition-' . $edition->post_name;
        $title    = get_the_title($eid);
        $dates    = ishf_edition_dates($eid);
        $logo     = $ishf_edition_logo($eid, 'edition-logo');
        $has_dark = (bool) get_post_meta($eid, 'ishf_cf_logo_dark_id', true);
      ?>
      <li class="edition-item">
        <span class="edition-marker" aria-hidden="true"><?php echo esc_html($dates ? $dates['year'] : '—'); ?></span>
        <article class="edition-card" aria-labelledby="<?php echo esc_attr($modal_id); ?>-card-title">
          <?php if ($logo !== ''): ?>
            <div class="edition-card-logo<?php echo $has_dark ? ' has-dark-logo' : ''; ?>"><?php echo $logo; ?></div>
          <?php endif; ?>
          <div class="edition-card-body">
            <h2 id="<?php echo esc_attr($modal_id); ?>-card-title" class="edition-card-title"><?php echo esc_html($title); ?></h2>
            <?php if ($dates): ?>
              <p class="edition-card-dates">
                <span data-uk-icon="icon: calendar; ratio: 0.85" aria-hidden="true"></span>
                <time datetime="<?php echo esc_attr($dates['start'] . ($dates['end'] !== $dates['start'] ? '/' . $dates['end'] : '')); ?>"><?php echo esc_html($dates['label']); ?></time>
              </p>
            <?php endif; ?>
            <button type="button" class="uk-button uk-button-primary uk-border-pill edition-card-button" data-uk-toggle="target: #<?php echo esc_attr($modal_id); ?>" aria-haspopup="dialog" aria-controls="<?php echo esc_attr($modal_id); ?>">
              <span><?php _e('Discover this edition', TEXT_DOMAIN); ?></span>
              <span class="sr-only"><?php echo esc_html(': ' . $title); ?></span>
              <span data-uk-icon="icon: arrow-right; ratio: 0.9" aria-hidden="true"></span>
            </button>
          </div>
        </article>
      </li>
      <?php endforeach; ?>

      <!-- Next edition: Lisbon 2027 -->
      <li class="edition-item edition-item--next">
        <span class="edition-marker" aria-hidden="true">2027</span>
        <div class="edition-card edition-card--next">
          <div class="edition-card-body">
            <p class="edition-card-eyebrow"><?php _e('Next edition', TEXT_DOMAIN); ?></p>
            <h2 class="edition-card-title"><?php _e('Lisbon 2027', TEXT_DOMAIN); ?></h2>
            <p class="edition-card-dates">
              <span data-uk-icon="icon: calendar; ratio: 0.85" aria-hidden="true"></span>
              <time datetime="2027-06-07/2027-06-09"><?php _e('7 – 9 June 2027', TEXT_DOMAIN); ?></time> · <?php _e('Lisbon, Portugal', TEXT_DOMAIN); ?>
            </p>
            <a class="uk-button uk-button-default uk-border-pill edition-card-button" href="<?php echo esc_url(home_url('/')); ?>">
              <span><?php _e('Discover ISHF 2027', TEXT_DOMAIN); ?></span>
              <span data-uk-icon="icon: arrow-right; ratio: 0.9" aria-hidden="true"></span>
            </a>
          </div>
        </div>
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
    $venue    = trim((string) get_post_meta($eid, 'ishf_cf_venue', true));
    $address  = trim((string) get_post_meta($eid, 'ishf_cf_address', true));
    $coords   = ishf_edition_coordinates($eid);
    $content  = apply_filters('the_content', $edition->post_content);
    $place    = $venue !== '' ? $venue : $address;
    $maps_url = $coords
      ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($coords['lat'] . ',' . $coords['lng'])
      : ($address !== '' ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(trim($venue . ' ' . $address)) : '');
  ?>
  <div id="<?php echo esc_attr($modal_id); ?>" class="uk-flex-top modal-ishf-edition" data-uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($modal_id); ?>-title">
      <button class="uk-modal-close-default" type="button" data-uk-close aria-label="<?php esc_attr_e('Close modal', TEXT_DOMAIN); ?>"></button>

      <header class="edition-modal-header">
        <?php if ($logo !== ''): ?>
          <div class="edition-modal-logo-box<?php echo $has_dark ? ' has-dark-logo' : ''; ?>"><?php echo $logo; ?></div>
        <?php endif; ?>
        <div>
          <?php if ($dates): ?><p class="edition-modal-eyebrow"><?php echo esc_html(sprintf(__('ISHF %s', TEXT_DOMAIN), $dates['year'])); ?></p><?php endif; ?>
          <h2 id="<?php echo esc_attr($modal_id); ?>-title" class="edition-modal-title"><?php echo esc_html($title); ?></h2>
        </div>
      </header>

      <ul class="edition-modal-facts">
        <?php if ($dates): ?>
          <li>
            <span class="edition-fact-icon" data-uk-icon="icon: calendar" aria-hidden="true"></span>
            <span><span class="sr-only"><?php _e('Dates', TEXT_DOMAIN); ?>: </span><time datetime="<?php echo esc_attr($dates['start'] . ($dates['end'] !== $dates['start'] ? '/' . $dates['end'] : '')); ?>"><?php echo esc_html($dates['label']); ?></time></span>
          </li>
        <?php endif; ?>
        <?php if ($place !== ''): ?>
          <li>
            <span class="edition-fact-icon" data-uk-icon="icon: location" aria-hidden="true"></span>
            <span>
              <span class="sr-only"><?php _e('Location', TEXT_DOMAIN); ?>: </span>
              <?php if ($venue !== ''): ?><strong class="edition-venue"><?php echo esc_html($venue); ?></strong><?php endif; ?>
              <?php if ($address !== '' && $address !== $venue): ?><span class="edition-address"><?php echo esc_html($address); ?></span><?php endif; ?>
            </span>
          </li>
        <?php endif; ?>
      </ul>

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
    </div>
  </div>
  <?php endforeach; ?>
</section>
