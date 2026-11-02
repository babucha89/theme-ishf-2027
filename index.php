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
// Inner pages / master homepage use the full navigation-menu and footer (landing page, 404 and maintenance keep the minimal ones)
ishf_use_full_layout(true);
get_header();
?>

<!-- Banner landmark: navigation-menu (full variant) -->
<header role="banner" class="ishf-site-header">
  <?php get_template_part('includes/navigation-menu'); ?>
</header>

<!-- Main Content Landmark (WCAG 2.1 AA) -->
<main id="content-start" role="main" class="site-main" tabindex="-1">

  <?php
  // -- Section 1: HERO (the only H1)
  get_template_part('includes/sections/hero');

  // -- Section 2: LIVE EVENT (Conditional: renders when the 'sections/streaming' or 'streaming' page is published with a YouTube ID)
  get_template_part('includes/sections/streaming');

  // -- Section 3: USER JOURNEYS (Contribute, Become a Partner, Attend)
  get_template_part('includes/sections/journeys');

  // -- Section 4: PARTNERS (logos; sponsors live here, not in the footer)
  get_template_part('includes/sections/sponsors');

  // -- Section 5: NEWS & ARTICLES (3 latest posts)
  get_template_part('includes/sections/news');

  // -- Section 6: NEWSLETTER (existing form)
  get_template_part('includes/sections/newsletter');

  // -- Partnership modal only (opened by the "Become a Partner" journey card)
  get_template_part('includes/sections/ctas', null, array('show_boxes' => false));
  ?>

</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>
