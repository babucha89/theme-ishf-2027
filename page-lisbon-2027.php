<?php
// -----------------------------------------------------------------------------
//  Template Name: Lisbon 2027
//  Page: LISBON 2027 (the current edition)
//  Used automatically by a page with the slug "lisbon-2027" and selectable for
//  any other page (e.g. the Portuguese "lisboa-2027"). Linked from the "Next
//  edition" card of the Past Editions timeline.
//
//  All texts are edited in the page editor, per language (WPML):
//    - Title / Excerpt / body ..... page title, lead under the header, intro text
//    - "Lisbon 2027 page" box ..... header facts, section titles, the three
//      venue cards (includes/lisbon-page.php, meta keys ishf_lisbon_*); an empty
//      field shows its default text from languages/*.po
//  Sections: header, page content, Organisers and partners (sponsors CPT, one row
//  per sponsor type: includes/sections/lisbon-partners.php), Venue & practical info.
//  The site-wide sponsors block before the footer is skipped on this page.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 17-NOV-2026
// -----------------------------------------------------------------------------
the_post();
if (function_exists('ishf_set_post_views')) {
  ishf_set_post_views(get_the_ID());
}

$lisbon_id   = get_the_ID();
$lisbon      = function ($key) use ($lisbon_id) {
  return ishf_lisbon_get($lisbon_id, $key); // includes/lisbon-page.php
};
$lisbon_text = function ($key) use ($lisbon) {
  return wpautop(wp_kses_post($lisbon($key)));
};
$venue_name = $lisbon('venue_name');
$venue_maps = $lisbon('venue_map_url') ?: 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(trim($venue_name . ', ' . $lisbon('venue_address'), ', '));

ishf_use_full_layout(true);
ishf_show_footer_sponsors(false); // the sponsors are part of this page
get_header();
get_template_part('includes/sections/header');
?>
<main id="content-start" role="main" class="site-main" tabindex="-1">

  <!-- 1. Header -->
  <header class="lisbon-hero">
    <div class="uk-container">
      <p class="ishf-eyebrow lisbon-hero-eyebrow"><?php echo esc_html($lisbon('eyebrow')); ?></p>
      <h1 class="lisbon-hero-title"><?php the_title(); ?></h1>
      <ul class="lisbon-hero-facts">
        <li>
          <span data-uk-icon="icon: calendar" aria-hidden="true"></span>
          <time datetime="2027-06-07/2027-06-09"><?php echo esc_html($lisbon('dates')); ?></time>
        </li>
        <li>
          <span data-uk-icon="icon: location" aria-hidden="true"></span>
          <span><?php echo esc_html($lisbon('location')); ?></span>
        </li>
      </ul>
      <?php if (has_excerpt()): ?>
        <p class="lisbon-hero-lead uk-width-2xlarge"><?php echo esc_html(get_the_excerpt()); ?></p>
      <?php endif; ?>
    </div>
  </header>

  <!-- 2. About the Lisbon edition (page content) -->
  <?php if (trim(get_the_content()) !== ''): ?>
    <div class="uk-container uk-section lisbon-intro">
      <div class="uk-article-content uk-width-2xlarge"><?php the_content(); ?></div>
    </div>
  <?php endif; ?>

  <!-- 3. Organisers and partners (sponsors CPT) -->
  <?php get_template_part('includes/sections/lisbon-partners', null, array(
    'eyebrow' => $lisbon('org_eyebrow'),
    'title'   => $lisbon('org_title'),
    'intro'   => $lisbon('org_intro'),
  )); ?>

  <!-- 4. Venue & practical info -->
  <section id="venue" class="uk-section lisbon-venue" aria-labelledby="venue-title">
    <div class="uk-container">
      <p class="ishf-eyebrow"><?php echo esc_html($lisbon('venue_eyebrow')); ?></p>
      <h2 id="venue-title" class="lisbon-section-title"><?php echo esc_html($lisbon('venue_title')); ?></h2>

      <div class="uk-grid-match uk-child-width-1-1 uk-child-width-1-3@m uk-grid-medium" data-uk-grid>
        <div>
          <article class="lisbon-info-card" aria-labelledby="venue-primary-title">
            <span class="lisbon-info-icon" data-uk-icon="icon: location; ratio: 1.3" aria-hidden="true"></span>
            <h3 id="venue-primary-title" class="lisbon-info-title"><?php echo esc_html($lisbon('venue_card_title')); ?></h3>
            <p class="lisbon-venue-name"><strong><?php echo esc_html($venue_name); ?></strong><br><span><?php echo esc_html($lisbon('venue_address')); ?></span></p>
            <div class="lisbon-info-text">
              <?php echo $lisbon_text('venue_text'); ?>
            </div>
            <a class="lisbon-info-link" href="<?php echo esc_url($venue_maps); ?>" target="_blank" rel="noopener noreferrer">
              <?php _e('View on Google Maps', TEXT_DOMAIN); ?><span class="sr-only"> (<?php _e('opens in a new tab', TEXT_DOMAIN); ?>)</span>
              <span data-uk-icon="icon: push; ratio: 0.8" aria-hidden="true"></span>
            </a>
          </article>
        </div>
        <div>
          <article class="lisbon-info-card" aria-labelledby="venue-transport-title">
            <span class="lisbon-info-icon" data-uk-icon="icon: users; ratio: 1.3" aria-hidden="true"></span>
            <h3 id="venue-transport-title" class="lisbon-info-title"><?php echo esc_html($lisbon('transport_title')); ?></h3>
            <div class="lisbon-info-text">
              <?php echo $lisbon_text('transport_text'); ?>
            </div>
          </article>
        </div>
        <div>
          <article class="lisbon-info-card" aria-labelledby="venue-access-title">
            <span class="lisbon-info-icon" data-uk-icon="icon: world; ratio: 1.3" aria-hidden="true"></span>
            <h3 id="venue-access-title" class="lisbon-info-title"><?php echo esc_html($lisbon('access_title')); ?></h3>
            <div class="lisbon-info-text">
              <?php echo $lisbon_text('access_text'); ?>
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>

</main>
<?php get_footer();
