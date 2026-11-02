<?php
// -----------------------------------------------------------------------------
//  Section: HERO (Home)
//  Figma: Home / Hero. Eyebrow, H1 claim, date, lead, two CTAs (Call for Sessions
//  + Partnership, same targets as the landing page), wide photo.
//  The only <h1> of the page.
//
//  Photo (art direction):
//    - >= 960px (laptop/desktop): 'image-gallery' (1600x900, 16:9)
//    - <  960px (mobile/tablet) : 'image-speaker-face' (450x450, 1:1)
//  Source, in order of preference:
//    1. A Media Library attachment, returned by the 'ishf_hero_image_id' filter
//       (add_filter('ishf_hero_image_id', fn() => 123);) so WordPress serves the two
//       registered crops (functions.php: add_image_size).
//    2. The theme file img/lisbon/hp-lisbon.jpg, cropped by CSS to the same ratios.
//    3. A skyline illustration placeholder.
//  Decorative art is aria-hidden.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$hero_image_id   = (int) apply_filters('ishf_hero_image_id', 0);
$hero_file_path  = get_template_directory() . '/img/lisbon/hp-lisbon.jpg';
$has_hero_file   = file_exists($hero_file_path);
?>
<section id="hero" class="ishf-hero" aria-labelledby="hero-title">
  <div class="uk-container">
    <div data-uk-grid class="uk-grid-large uk-flex-middle">

      <div class="uk-width-1-1 uk-width-2-5@m">
        <p class="ishf-eyebrow uk-margin-remove-bottom"><?php _e('International Social Housing Festival', TEXT_DOMAIN); ?></p>
        <h1 id="hero-title" class="hero-title uk-margin-small-top"><?php _e('Cities with Soul: It Begins with a Key', TEXT_DOMAIN); ?></h1>
        <p class="hero-date uk-margin-remove-top">
          <time datetime="2027-06-07/2027-06-09"><?php _e('7 – 9 June 2027', TEXT_DOMAIN); ?></time> · <?php _e('Lisbon, Portugal', TEXT_DOMAIN); ?>
        </p>
        <p class="uk-text-lead hero-lead">
          <?php _e('The international meeting point for people, ideas and solutions to build more inclusive, sustainable and resilient cities.', TEXT_DOMAIN); ?>
        </p>
        <div class="hero-ctas uk-flex uk-flex-wrap">
          <a class="uk-button uk-button-primary" id="btn-hero-call-for-sessions" href="<?php echo esc_url(ishf_get_call_for_sessions_url()); ?>" target="_blank" rel="noopener noreferrer">
            <?php _e('Submit Your Proposal', TEXT_DOMAIN); ?><span class="uk-visually-hidden"> <?php _e('(opens in a new tab)', TEXT_DOMAIN); ?></span>
          </a>
          <button type="button" class="uk-button uk-button-default" data-uk-toggle="target: #modal-partnership" aria-haspopup="dialog" aria-controls="modal-partnership">
            <?php _e('Become a Partner', TEXT_DOMAIN); ?>
          </button>
        </div>
      </div>

      <div class="uk-width-1-1 uk-width-3-5@m">
        <div class="hero-art uk-position-relative" aria-hidden="true">
          <?php if ($hero_image_id && wp_get_attachment_image_url($hero_image_id, 'image-gallery')): ?>
            <picture>
              <source media="(min-width: 960px)" srcset="<?php echo esc_url(wp_get_attachment_image_url($hero_image_id, 'image-gallery')); ?>">
              <img class="hero-photo" src="<?php echo esc_url(wp_get_attachment_image_url($hero_image_id, 'image-speaker-face')); ?>" alt="" width="450" height="450" loading="eager" fetchpriority="high" />
            </picture>
          <?php elseif ($has_hero_file): ?>
            <img class="hero-photo" src="<?php echo esc_url(BASE_IMAGES . 'lisbon/hp-lisbon.jpg'); ?>" alt="" width="1600" height="900" loading="eager" fetchpriority="high" />
          <?php else: ?>
            <div class="hero-photo hero-photo--placeholder"><img src="<?php echo esc_url(BASE_ASSETS . 'skyline-colors.svg'); ?>" alt="" /></div>
          <?php endif; ?>
          <img class="hero-key light-mode-img" src="<?php echo esc_url(BASE_ASSETS . 'icon-key-light.svg'); ?>" alt="" />
          <img class="hero-key dark-mode-img" src="<?php echo esc_url(BASE_ASSETS . 'icon-key-dark.svg'); ?>" alt="" />
          <img class="hero-wave" src="<?php echo esc_url(BASE_ASSETS . 'icon-wave.svg'); ?>" alt="" />
        </div>
      </div>

    </div>
  </div>
</section>
