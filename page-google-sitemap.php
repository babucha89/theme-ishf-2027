<?php
// -----------------------------------------------------------------------------
//  Template Name: Google Sitemap
//  Page: GOOGLE SITEMAP (XML)
//  Lists only URLs that should be indexed:
//   - home, published posts (NOT clippings: they redirect to third-party sites),
//     published pages (NOT the "sections" tree, utility templates, or pages with
//     the custom field ishf_noindex), categories with posts.
//   - Every active WPML language (each language lists its own URLs).
//   - <lastmod> is the real last modification date (Google ignores priority/changefreq).
//  Rules live in includes/seo.php.
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 03-NOV-2026
// -----------------------------------------------------------------------------

header('Content-Type: text/xml; charset=utf-8');
header('X-Robots-Tag: noindex'); // the sitemap itself must not be indexed

$ishf_map_url = function ($url) {
  if (defined('REDIRECT_SITE') && defined('BASE_SITE')) {
    $url = str_replace(BASE_SITE, REDIRECT_SITE, $url);
  }
  return esc_url($url);
};
$ishf_map_entry = function ($url, $lastmod = '') use ($ishf_map_url) {
  echo "<url>\n  <loc>" . $ishf_map_url($url) . "</loc>\n";
  if ($lastmod) {
    echo '  <lastmod>' . esc_html($lastmod) . "</lastmod>\n";
  }
  echo "</url>\n";
};

// Languages to list (WPML) or a single pass when WPML is not active
$ishf_langs = array(null);
if (has_filter('wpml_active_languages')) {
  $active = apply_filters('wpml_active_languages', null, array('skip_missing' => 0));
  if (is_array($active) && count($active) > 1) {
    $ishf_langs = array_keys($active);
  }
}
$ishf_current_lang = apply_filters('wpml_current_language', null);

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($ishf_langs as $ishf_lang) {
  if ($ishf_lang) {
    do_action('wpml_switch_language', $ishf_lang);
  }

  // -- Home
  $latest = get_posts(array('posts_per_page' => 1, 'post_status' => 'publish', 'orderby' => 'modified', 'order' => 'DESC', 'suppress_filters' => false));
  $ishf_map_entry(home_url('/'), $latest ? get_post_modified_time('c', true, $latest[0]) : '');

  // -- Posts (clippings redirect to third-party sites: never listed)
  $posts = get_posts(array('posts_per_page' => -1, 'post_status' => 'publish', 'suppress_filters' => false));
  foreach ($posts as $post) {
    if (function_exists('ishf_get_clipping') && ishf_get_clipping($post->ID)) {
      continue;
    }
    $ishf_map_entry(get_permalink($post->ID), get_post_modified_time('c', true, $post));
  }

  // -- Pages
  $pages = get_pages(array('post_status' => 'publish'));
  foreach ($pages as $page) {
    if (ishf_is_noindex_page($page) || (int) $page->ID === (int) get_option('page_on_front')) {
      continue;
    }
    $ishf_map_entry(get_permalink($page->ID), get_post_modified_time('c', true, $page));
  }

  // -- Categories (with at least one internal article)
  foreach (get_categories(array('hide_empty' => true)) as $category) {
    if ($category->count > 0 && !in_array($category->slug, array('uncategorized', 'sem-categoria'), true)) {
      $ishf_map_entry(get_category_link($category->term_id));
    }
  }
}

if ($ishf_current_lang) {
  do_action('wpml_switch_language', $ishf_current_lang);
}
echo '</urlset>';
