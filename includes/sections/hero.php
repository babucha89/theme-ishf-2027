<?php
// -----------------------------------------------------------------------------
//  Section: HERO (Home)
//  Figma: Home / Hero. Eyebrow, H1 claim, date, lead, two CTAs (Call for Sessions
//  + Partnership, same targets as the landing page), organisers' logos
//  (includes/organisers.php), wide photo.
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
//
//  Content (back office, translatable with WPML): the "Homepage: hero and journeys"
//  box of the page 'sections/hero' (includes/home-fields.php): eyebrow, title, dates,
//  text, main button (one label until 31 Dec 2026 -> Call for Sessions, another from
//  1 Jan 2027 -> attendee registration; optional own link), second button (opens the
//  partnership form unless it has a link). Photo: the page's featured image.
//  Any empty field falls back to the translated default in languages/*.po.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$hero_page    = ishf_home_content_page(); // page sections/hero, current language (includes/home-fields.php)
$hero_phase   = ishf_get_register_phase(); // 'b2b' until 31 Dec 2026, 'b2c' from 1 Jan 2027
$hero_eyebrow = ishf_home_get('ishf_hero_eyebrow');
$hero_title   = ishf_home_get('ishf_hero_title');
$hero_date    = ishf_home_get('ishf_hero_date');
$hero_lead    = ishf_home_get('ishf_hero_lead');
$hero_cta_1   = ishf_home_get($hero_phase === 'b2c' ? 'ishf_hero_cta_primary_2027' : 'ishf_hero_cta_primary');
$hero_cta_1_url = ishf_home_get('ishf_hero_cta_primary_url') ?: ($hero_phase === 'b2c' ? ishf_get_register_url() : ishf_get_call_for_sessions_url());
$hero_cta_2   = ishf_home_get('ishf_hero_cta_secondary');
$hero_cta_2_url = ishf_home_get('ishf_hero_cta_secondary_url'); // empty: opens the partnership form

$hero_image_id   = (int) apply_filters('ishf_hero_image_id', 0);
if (!$hero_image_id && $hero_page) {
  $hero_image_id = (int) get_post_thumbnail_id($hero_page->ID);
}
$hero_file_path  = get_template_directory() . '/img/lisbon/hp-lisbon.jpg';
$has_hero_file   = file_exists($hero_file_path);
?>
<section id="hero" class="ishf-hero" aria-labelledby="hero-title">
  <div class="uk-container uk-container-large">
    <div data-uk-grid class="uk-grid-large uk-flex-middle">

      <div class="uk-width-1-1 uk-width-1-3@s">
        <p class="ishf-eyebrow uk-margin-remove-bottom"><?php echo esc_html($hero_eyebrow); ?></p>
        <h1 id="hero-title" class="hero-title uk-margin-small-top"><?php echo esc_html($hero_title); ?></h1>
        <p class="hero-date uk-margin-remove-top">
          <time datetime="2027-06-07/2027-06-09"><?php echo esc_html($hero_date); ?></time>
        </p>
        <p class="uk-text-lead hero-lead uk-width-xlarge">
          <?php echo nl2br(esc_html($hero_lead)); ?>
        </p>
        <div class="hero-ctas uk-flex uk-flex-wrap">
          <a class="uk-button uk-button-primary" id="btn-hero-call-for-sessions" href="<?php echo esc_url($hero_cta_1_url); ?>" target="_blank" rel="noopener noreferrer">
            <?php echo esc_html($hero_cta_1); ?><span class="uk-visually-hidden"> <?php _e('(opens in a new tab)', TEXT_DOMAIN); ?></span>
          </a>
          <?php if ($hero_cta_2_url !== ''): ?>
            <a class="uk-button uk-button-default" href="<?php echo esc_url($hero_cta_2_url); ?>"><?php echo esc_html($hero_cta_2); ?></a>
          <?php else: ?>
            <button type="button" class="uk-button uk-button-default" data-uk-toggle="target: #modal-partnership" aria-haspopup="dialog" aria-controls="modal-partnership">
              <?php echo esc_html($hero_cta_2); ?>
            </button>
          <?php endif; ?>
        </div>
        <?php get_template_part('includes/organisers', null, array('context' => 'header')); // Organised by (Sponsors: first sponsor type) ?>
      </div>

      <div class="uk-width-1-1 uk-width-expand@s">
        <div class="hero-art uk-position-relative uk-width-large uk-width-1-1@m uk-margin-auto" aria-hidden="true">
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
