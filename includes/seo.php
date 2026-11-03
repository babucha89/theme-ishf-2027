<?php
// -----------------------------------------------------------------------------
//  SEO / Search Console hygiene
//  Keeps helper pages out of Google: the "sections" page and its sub-pages
//  (hero, streaming, ...), utility templates and anything flagged by an editor.
//  - Visitors opening such a page are redirected (301) to the home page
//    (editors can still open and preview them).
//  - <meta name="robots" content="noindex, nofollow"> as a safety net.
//  - Left out of the Google sitemap (page-google-sitemap.php).
//  - The WordPress core sitemap (wp-sitemap.xml) is disabled: the theme serves
//    ONE sitemap, so Search Console never sees duplicates.
//  Opt-out per page: custom field `ishf_noindex` = 1.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------

if (!function_exists('ishf_is_section_page')):
  /** True for the "sections" page and any of its descendants (WPML translations included). */
  function ishf_is_section_page($post = null)
  {
    $post = get_post($post);
    if (!$post || $post->post_type !== 'page') {
      return false;
    }
    $uri = get_page_uri($post);
    // Translated slugs differ in other languages: also check the original-language page.
    $orig = (int) apply_filters('wpml_object_id', $post->ID, 'page', true, apply_filters('wpml_default_language', null));
    if ($orig && $orig !== (int) $post->ID) {
      $uri .= ' ' . get_page_uri($orig);
    }
    foreach (explode(' ', $uri) as $u) {
      if ($u === 'sections' || strpos($u, 'sections/') === 0) {
        return true;
      }
    }
    return false;
  }
endif;

if (!function_exists('ishf_is_noindex_page')):
  /** Pages that must never be indexed nor listed in the sitemap. */
  function ishf_is_noindex_page($post = null)
  {
    $post = get_post($post);
    if (!$post || $post->post_type !== 'page') {
      return false;
    }
    if (ishf_is_section_page($post)) {
      return true;
    }
    if (in_array($post->post_name, array('google-sitemap', 'manutencao', 'under-maintenance', 'ics-generator', 'widget', 'streaming'), true)) {
      return true;
    }
    $template = (string) get_page_template_slug($post);
    foreach (array('page-google-sitemap', 'page-ics-generator', 'page-under-maintenance', 'page-manutencao') as $t) {
      if ($template !== '' && strpos($template, $t) === 0) {
        return true;
      }
    }
    if (get_post_meta($post->ID, 'ishf_noindex', true)) {
      return true;
    }
    return (bool) apply_filters('ishf_is_noindex_page', false, $post);
  }
endif;

// -- Visitors never land on a helper page: back to the home page (editors preview freely)
add_action('template_redirect', function () {
  if (is_page() && !is_preview() && !current_user_can('edit_pages') && ishf_is_section_page(get_queried_object())) {
    wp_safe_redirect(home_url('/'), 301);
    exit;
  }
});

// -- Safety net for anything else flagged noindex (internal search results are already noindex in WordPress core)
add_filter('wp_robots', function ($robots) {
  if (is_page() && ishf_is_noindex_page(get_queried_object())) {
    $robots['noindex']  = true;
    $robots['nofollow'] = true;
    unset($robots['max-image-preview']);
  }
  return $robots;
});

// -- One sitemap only: the theme's /google-sitemap/ page
add_filter('wp_sitemaps_enabled', '__return_false');
