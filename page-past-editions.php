<?php
// -----------------------------------------------------------------------------
//  Template Name: Past Editions
//  Page: PAST EDITIONS (timeline built from the CPT "editions")
//  Used automatically by a page with the slug "past-editions" and selectable
//  for any other page (e.g. the Portuguese "edicoes-anteriores"). The page
//  content is shown as an introduction above the timeline.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 11-NOV-2026
// -----------------------------------------------------------------------------
the_post();
if (function_exists('ishf_set_post_views')) {
  ishf_set_post_views(get_the_ID());
}

// -- Maps in the modals (Mapbox GL JS is loaded by the script only when a modal with a map opens)
wp_enqueue_script('ishf-past-editions', BASE_JS . 'past-editions.js', array('uikit'), filemtime(__DIR__ . '/js/past-editions.js'), true);
wp_localize_script('ishf-past-editions', 'ishfEditions', array(
  'token'      => SITE_MAPBOX_TOKEN,
  'mapboxJs'   => 'https://api.mapbox.com/mapbox-gl-js/v' . CDN_MAPBOX_GL_VERSION . '/mapbox-gl.js',
  'mapboxCss'  => 'https://api.mapbox.com/mapbox-gl-js/v' . CDN_MAPBOX_GL_VERSION . '/mapbox-gl.css',
  'styleLight' => 'mapbox://styles/mapbox/light-v11',
  'styleDark'  => 'mapbox://styles/mapbox/dark-v11',
  'pinColor'   => '#CE4638',
));

ishf_use_full_layout(true);
get_header();
get_template_part('includes/sections/header');
?>
<main id="content-start" role="main" class="site-main" tabindex="-1">
  <div class="uk-container uk-section uk-section-small editions-intro">
    <h1 class="uk-article-title"><?php the_title(); ?></h1>
    <?php if (trim(get_the_content()) !== ''): ?>
      <div class="uk-article-content"><?php the_content(); ?></div>
    <?php endif; ?>
  </div>
  <?php get_template_part('includes/sections/past-editions'); ?>
</main>
<?php get_footer();
