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
//
//  Content (editable and translatable through WPML): optional page 'sections/hero'
//  (a child of the "sections" page, like Streaming). When it exists and is published:
//    - Page title ........ H1 claim
//    - Excerpt ........... lead paragraph
//    - Featured image .... hero photo (after the 'ishf_hero_image_id' filter)
//    - Custom fields ..... ishf_hero_eyebrow, ishf_hero_date (text shown, e.g. "7 – 9 June 2027 · Lisbon, Portugal"),
//                          ishf_hero_cta_primary, ishf_hero_cta_secondary (button labels)
//  Any empty value falls back to the translated string in languages/*.po.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$hero_page = get_page_by_path('sections/hero');
$hero_page = $hero_page ? ishf_translate_post($hero_page) : null; // current-language version (WPML)
if ($hero_page && $hero_page->post_status !== 'publish') {
  $hero_page = null;
}
$hero_meta = function ($key) use ($hero_page) {
  return $hero_page ? trim((string) get_post_meta($hero_page->ID, $key, true)) : '';
};
$hero_eyebrow = $hero_meta('ishf_hero_eyebrow') ?: __('International Social Housing Festival', TEXT_DOMAIN);
$hero_title   = ($hero_page && trim($hero_page->post_title) !== '') ? $hero_page->post_title : __('Cities with Soul: It Begins with a Key', TEXT_DOMAIN);
$hero_date    = $hero_meta('ishf_hero_date');
$hero_lead    = ($hero_page && trim($hero_page->post_excerpt) !== '') ? $hero_page->post_excerpt : __('The international meeting point for people, ideas and solutions to build more inclusive, sustainable and resilient cities.', TEXT_DOMAIN);
$hero_cta_1   = $hero_meta('ishf_hero_cta_primary') ?: __('Submit Your Proposal', TEXT_DOMAIN);
$hero_cta_2   = $hero_meta('ishf_hero_cta_secondary') ?: __('Become a Partner', TEXT_DOMAIN);

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
          <?php if ($hero_date !== ''): ?>
            <time datetime="2027-06-07/2027-06-09"><?php echo esc_html($hero_date); ?></time>
          <?php else: ?>
            <time datetime="2027-06-07/2027-06-09"><?php _e('7 – 9 June 2027', TEXT_DOMAIN); ?></time> · <?php _e('Lisbon, Portugal', TEXT_DOMAIN); ?>
          <?php endif; ?>
        </p>
        <p class="uk-text-lead hero-lead">
          <?php echo esc_html($hero_lead); ?>
        </p>
        <div class="hero-ctas uk-flex uk-flex-wrap">
          <a class="uk-button uk-button-primary" id="btn-hero-call-for-sessions" href="<?php echo esc_url(ishf_get_call_for_sessions_url()); ?>" target="_blank" rel="noopener noreferrer">
            <?php echo esc_html($hero_cta_1); ?><span class="uk-visually-hidden"> <?php _e('(opens in a new tab)', TEXT_DOMAIN); ?></span>
          </a>
          <button type="button" class="uk-button uk-button-default" data-uk-toggle="target: #modal-partnership" aria-haspopup="dialog" aria-controls="modal-partnership">
            <?php echo esc_html($hero_cta_2); ?>
          </button>
        </div>
      </div>

      <div class="uk-width-1-1 uk-width-expand@s">
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
