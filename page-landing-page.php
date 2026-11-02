<?php
// -----------------------------------------------------------------------------
//  Page: LANDING PAGE (Slug: landing-page)
//  File: page-landing-page.php
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

// -- Initializations
global $featured_articles;
$featured_articles = array();

$fixed_og_image = BASE_OG_IMAGES . 'og-image-homepage.jpg';
?>

<?php
// -- Block: HEADER (Head & Body Opening, Skip-link)
get_header();
?>

<?php
// -- Section: HEADER (Sticky Nav, Project Logo & Claim Side-by-Side)
get_template_part('includes/sections/header');
?>

<?php
// Main Content Landmark (WCAG 2.1 AA)
?>
<main id="content-start" role="main" class="site-main" tabindex="-1">
  <h1 class="uk-screen-reader-text sr-only">
    <?php echo esc_html(defined('SITE_SHORT_NAME') ? SITE_SHORT_NAME : SITE_NAME); ?>
  </h1>

  <?php
  // -- Section: STREAMING EVENT (Conditional: renders when 'sections/streaming' or 'streaming' page is published with a YouTube ID)
  get_template_part('includes/sections/streaming');
  ?>

  <?php
  // -- Section: SIDE-BY-SIDE CTAS (Call for Sessions & Partnership Form)
  get_template_part('includes/sections/ctas');
  ?>

  <?php
  // -- Section: Newsletter
  get_template_part('includes/sections/newsletter');
  ?>
</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>
