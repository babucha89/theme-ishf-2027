<?php
// -----------------------------------------------------------------------------
//  Template Name: Lisbon 2027
//  Page: LISBON 2027 (the current edition)
//  Used automatically by a page with the slug "lisbon-2027" and selectable for
//  any other page (e.g. the Portuguese "lisboa-2027"). Linked from the "Next
//  edition" card of the Past Editions timeline.
//
//  1. Header ........ title, dates, city; excerpt (optional) as lead
//  2. Page content .. about the Lisbon edition (block editor)
//  3. Organisers and partners: sponsors CPT, one row per sponsor type
//     (includes/sections/lisbon-partners.php)
//  4. Venue & practical info: Primary venue / Transport & participant support /
//     Venue accessibility & transport model.
//     Text: optional custom fields on this page (Custom Fields panel; simple
//     HTML allowed, blank lines make paragraphs), translatable through WPML:
//       ishf_venue_name, ishf_venue_address, ishf_venue_text,
//       ishf_transport_text, ishf_accessibility_text
//     Any empty field falls back to the translated text in languages/*.po.
//  The site-wide sponsors block before the footer is skipped on this page.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 17-NOV-2026
// -----------------------------------------------------------------------------
the_post();
if (function_exists('ishf_set_post_views')) {
  ishf_set_post_views(get_the_ID());
}

$lisbon_id   = get_the_ID();
$lisbon_meta = function ($key) use ($lisbon_id) {
  return trim((string) get_post_meta($lisbon_id, $key, true));
};
$lisbon_text = function ($key, $fallback) use ($lisbon_meta) {
  $value = $lisbon_meta($key);
  return wpautop(wp_kses_post($value !== '' ? $value : $fallback));
};

$venue_name    = $lisbon_meta('ishf_venue_name') ?: __('Centro de Congressos de Lisboa', TEXT_DOMAIN);
$venue_address = $lisbon_meta('ishf_venue_address') ?: __('Praça das Indústrias, 1300-307 Lisboa, Portugal', TEXT_DOMAIN);
$venue_maps    = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($venue_name . ', ' . $venue_address);

ishf_use_full_layout(true);
ishf_show_footer_sponsors(false); // the sponsors are part of this page
get_header();
get_template_part('includes/sections/header');
?>
<main id="content-start" role="main" class="site-main" tabindex="-1">

  <!-- 1. Header -->
  <header class="lisbon-hero">
    <div class="uk-container">
      <p class="ishf-eyebrow lisbon-hero-eyebrow"><?php _e('Next edition · ISHF 2027', TEXT_DOMAIN); ?></p>
      <h1 class="lisbon-hero-title"><?php the_title(); ?></h1>
      <ul class="lisbon-hero-facts">
        <li>
          <span data-uk-icon="icon: calendar" aria-hidden="true"></span>
          <time datetime="2027-06-07/2027-06-09"><?php _e('7 – 9 June 2027', TEXT_DOMAIN); ?></time>
        </li>
        <li>
          <span data-uk-icon="icon: location" aria-hidden="true"></span>
          <span><?php echo esc_html($venue_name); ?> · <?php _e('Lisbon, Portugal', TEXT_DOMAIN); ?></span>
        </li>
      </ul>
      <?php if (has_excerpt()): ?>
        <p class="lisbon-hero-lead"><?php echo esc_html(get_the_excerpt()); ?></p>
      <?php endif; ?>
    </div>
  </header>

  <!-- 2. About the Lisbon edition (page content) -->
  <?php if (trim(get_the_content()) !== ''): ?>
    <div class="uk-container uk-section lisbon-intro">
      <div class="uk-article-content"><?php the_content(); ?></div>
    </div>
  <?php endif; ?>

  <!-- 3. Organisers and partners (sponsors CPT) -->
  <?php get_template_part('includes/sections/lisbon-partners'); ?>

  <!-- 4. Venue & practical info -->
  <section id="venue" class="uk-section lisbon-venue" aria-labelledby="venue-title">
    <div class="uk-container">
      <p class="ishf-eyebrow"><?php _e('Plan your visit', TEXT_DOMAIN); ?></p>
      <h2 id="venue-title" class="lisbon-section-title"><?php _e('Venue & practical info', TEXT_DOMAIN); ?></h2>

      <div class="uk-grid-match uk-child-width-1-1 uk-child-width-1-3@m uk-grid-medium" data-uk-grid>
        <div>
          <article class="lisbon-info-card" aria-labelledby="venue-primary-title">
            <span class="lisbon-info-icon" data-uk-icon="icon: location; ratio: 1.3" aria-hidden="true"></span>
            <h3 id="venue-primary-title" class="lisbon-info-title"><?php _e('Primary venue', TEXT_DOMAIN); ?></h3>
            <p class="lisbon-venue-name"><strong><?php echo esc_html($venue_name); ?></strong><br><span><?php echo esc_html($venue_address); ?></span></p>
            <div class="lisbon-info-text">
              <?php echo $lisbon_text('ishf_venue_text', __('The festival takes place at the Lisbon Congress Centre, by the river Tagus. Its Pavilhão do Rio is the central exhibition area: 3,800 m² with room for 2,000 people.', TEXT_DOMAIN)); ?>
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
            <h3 id="venue-transport-title" class="lisbon-info-title"><?php _e('Transport & participant support', TEXT_DOMAIN); ?></h3>
            <div class="lisbon-info-text">
              <?php echo $lisbon_text('ishf_transport_text', __('Lisbon\'s buses, trams, metro and trains connect the city centre and the airport with the venue. Travel tips, transport passes for participants and the help desk opening hours will be published here before the festival.', TEXT_DOMAIN)); ?>
            </div>
          </article>
        </div>
        <div>
          <article class="lisbon-info-card" aria-labelledby="venue-access-title">
            <span class="lisbon-info-icon" data-uk-icon="icon: world; ratio: 1.3" aria-hidden="true"></span>
            <h3 id="venue-access-title" class="lisbon-info-title"><?php _e('Venue accessibility & transport model', TEXT_DOMAIN); ?></h3>
            <div class="lisbon-info-text">
              <?php echo $lisbon_text('ishf_accessibility_text', __('We encourage everyone to reach the festival by public transport, on foot or by bike. Information on step-free routes, accessible facilities and assistance on request will be published here; if you have specific needs, contact the organisation in advance.', TEXT_DOMAIN)); ?>
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>

</main>
<?php get_footer();
