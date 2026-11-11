<?php
// -----------------------------------------------------------------------------
//  Template Name: About
//  Page: ABOUT (page content + Past Editions CTA)
//  Used automatically by a page with the slug "about" and selectable for any
//  other page (e.g. the Portuguese "sobre").
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 11-NOV-2026
// -----------------------------------------------------------------------------

// -- Initializations
the_post();
if (function_exists('ishf_set_post_views')) {
  ishf_set_post_views(get_the_ID());
}

$fixed_og_image = null;
?>

<?php
// -- Block: HEADER
// Inner pages / master homepage use the full navigation-menu and footer (landing page, 404 and maintenance keep the minimal ones)
ishf_use_full_layout(true);
get_header();
?>

<?php
// -- Section: HEADER
get_template_part('includes/sections/header');
?>

<!-- Main Content Landmark -->
<main id="content-start" role="main" class="site-main" tabindex="-1">
  <div class="uk-container uk-section">
    <article <?php post_class(); ?>>
      <h1 class="uk-article-title"><?php the_title(); ?></h1>
      <div class="uk-article-content">
        <?php the_content(); ?>
      </div>
    </article>
  </div>

  <?php
  // -- Section: PAST EDITIONS CTA (after the content)
  get_template_part('includes/sections/past-editions-cta');
  ?>
</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>