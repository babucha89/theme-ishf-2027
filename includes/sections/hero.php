<?php
// -----------------------------------------------------------------------------
//  Section: HERO (Home)
//  Figma: Home / Hero. Eyebrow, H1 claim, date, lead, two CTAs, photo slot with
//  key + wave decoration. The only <h1> of the page.
//  Photo: drop a licensed image at img/hero-lisbon.jpg (landscape, min 1200px);
//  until then a tinted placeholder is shown. Decorative art is aria-hidden.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$hero_image_path = get_template_directory() . '/img/hero-lisbon.jpg';
$has_hero_image  = file_exists($hero_image_path);
?>
<section id="hero" class="ishf-hero" aria-labelledby="hero-title">
  <div class="uk-container">
    <div data-uk-grid class="uk-grid-large uk-flex-middle">

      <div class="uk-width-1-1 uk-width-3-5@m">
        <p class="ishf-eyebrow uk-margin-remove-bottom"><?php _e('International Social Housing Festival', TEXT_DOMAIN); ?></p>
        <h1 id="hero-title" class="hero-title uk-margin-small-top"><?php _e('Cities with Soul: It Begins with a Key', TEXT_DOMAIN); ?></h1>
        <p class="hero-date uk-margin-remove-top">
          <time datetime="2027-06-07/2027-06-09"><?php _e('7 – 9 June 2027', TEXT_DOMAIN); ?></time> · <?php _e('Lisbon, Portugal', TEXT_DOMAIN); ?>
        </p>
        <p class="uk-text-lead hero-lead">
          <?php _e('The international meeting point for people, ideas and solutions to build more inclusive, sustainable and resilient cities.', TEXT_DOMAIN); ?>
        </p>
        <div class="hero-ctas uk-flex uk-flex-wrap">
          <a class="uk-button uk-button-primary" href="<?php echo esc_url(ishf_get_register_url()); ?>" target="_blank" rel="noopener noreferrer">
            <?php _e('Register Now', TEXT_DOMAIN); ?><span class="uk-visually-hidden"> <?php _e('(opens in a new tab)', TEXT_DOMAIN); ?></span>
          </a>
          <a class="uk-button uk-button-default" href="<?php echo esc_url(home_url('/programme/')); ?>">
            <?php _e('Explore Programme', TEXT_DOMAIN); ?>
          </a>
        </div>
      </div>

      <div class="uk-width-1-1 uk-width-2-5@m">
        <div class="hero-art uk-position-relative" aria-hidden="true">
          <?php if ($has_hero_image): ?>
            <img class="hero-photo" src="<?php echo esc_url(BASE_IMAGES . 'hero-lisbon.jpg'); ?>" alt="" width="440" height="440" loading="eager" />
          <?php else: ?>
            <div class="hero-photo hero-photo--placeholder"></div>
          <?php endif; ?>
          <img class="hero-key light-mode-img" src="<?php echo esc_url(BASE_ASSETS . 'icon-key-light.svg'); ?>" alt="" />
          <img class="hero-key dark-mode-img" src="<?php echo esc_url(BASE_ASSETS . 'icon-key-dark.svg'); ?>" alt="" />
          <img class="hero-wave" src="<?php echo esc_url(BASE_ASSETS . 'icon-wave.svg'); ?>" alt="" />
        </div>
      </div>

    </div>
  </div>
</section>
