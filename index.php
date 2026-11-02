<?php
// -----------------------------------------------------------------------------
//  File: INDEX / HOMEPAGE (Master Festival Edition)
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

// -- Initializations
global $featured_articles;
$featured_articles = array();

// Fixed OG image fallback
$fixed_og_image = BASE_OG_IMAGES . 'og-image-homepage.jpg';
?>

<?php
// -- Block: HEADER (Head & Body Opening, Skip-link)
get_header();
?>

<?php
// -- Section: HEADER (Sticky Nav, Project Logo & Claim)
get_template_part('includes/sections/header');
?>

<!-- Main Content Landmark (WCAG 2.1 AA) -->
<main id="content-start" role="main" class="site-main" tabindex="-1">
  <h1 class="uk-screen-reader-text sr-only">
    <?php echo esc_html(defined('SITE_SHORT_NAME') ? SITE_SHORT_NAME : SITE_NAME); ?>
  </h1>

  <?php
  // -- Section: STREAMING EVENT (Conditional: renders when 'sections/streaming' or 'streaming' page is published with a YouTube ID)
  get_template_part('includes/sections/streaming');
  ?>

  <?php
  // -- Section 1: ABOUT & 4 PILLARS (Affordable Housing, Green Transition, Inclusion, Lisbon)
  get_template_part('includes/sections/about');
  ?>

  <?php
  // -- Section 2: PROGRAM / AGENDA (3 Days: Plenaries, Workshops, Site Visits)
  get_template_part('includes/sections/program');
  ?>

  <?php
  // -- Section 3: KEYNOTE SPEAKERS & VISIONARIES
  get_template_part('includes/sections/speakers');
  ?>

  <?php
  // -- Section 4: DUAL ENGAGEMENT CTAS (Partnership Contact & Newsletter Subscription)
  get_template_part('includes/sections/ctas');
  ?>

  <?php
  // -- Section 5: INSTITUTIONAL PARTNERS & SPONSORS
  get_template_part('includes/sections/sponsors');
  ?>

  <?php
  // -- Section 6: DIRECT CONTACT FORM (Optional on Homepage)
  // get_template_part('includes/sections/contact-form');
  ?>

</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>
