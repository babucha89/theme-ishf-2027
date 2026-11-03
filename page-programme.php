<?php
// -----------------------------------------------------------------------------
//  Template Name: Programme
//  Page: PROGRAMME (full agenda built from the CPT "seminars")
//  Used automatically by a page with the slug "programme" and selectable for
//  any other page (e.g. the Portuguese "programa"). The page content is shown
//  as an optional introduction above the agenda.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
the_post();
if (function_exists('ishf_set_post_views')) {
  ishf_set_post_views(get_the_ID());
}
ishf_use_full_layout(true);
get_header();
get_template_part('includes/sections/header');
?>
<main id="content-start" role="main" class="site-main" tabindex="-1">
  <div class="uk-container uk-section uk-section-small">
    <h1 class="uk-article-title"><?php the_title(); ?></h1>
    <?php if (trim(get_the_content()) !== ''): ?>
      <div class="uk-article-content uk-margin-medium-bottom"><?php the_content(); ?></div>
    <?php endif; ?>
  </div>
  <?php get_template_part('includes/sections/program'); ?>
</main>
<?php get_footer();
