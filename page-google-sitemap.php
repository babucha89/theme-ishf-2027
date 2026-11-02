<?php
// -----------------------------------------------------------------------------
//  Template Name: Google Sitemap
//  Page: GOOGLE SITEMAP (XML)
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

header("Content-Type: text/xml; charset=utf-8");

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";

$base_url = home_url('/');
if (defined('REDIRECT_SITE') && defined('BASE_SITE')) {
  $homelink = esc_url(str_replace(BASE_SITE, REDIRECT_SITE, $base_url));
} else {
  $homelink = esc_url($base_url);
}
?>
<url>
  <loc><?php echo $homelink; ?></loc>
  <lastmod><?php echo date('c'); ?></lastmod>
  <changefreq>daily</changefreq>
  <priority>1.00</priority>
</url>
<?php

// -----------------------------------------------------------------------------
//  Posts / Articles
// -----------------------------------------------------------------------------
$posts = get_posts(array(
  'posts_per_page' => -1,
  'post_status'    => 'publish',
));

foreach ($posts as $post) {
  $url = get_permalink($post->ID);
  if (defined('REDIRECT_SITE') && defined('BASE_SITE')) {
    $url = esc_url(str_replace(BASE_SITE, REDIRECT_SITE, $url));
  }
?>
<url>
  <loc><?php echo $url; ?></loc>
  <lastmod><?php echo get_the_modified_date('c', $post->ID); ?></lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.80</priority>
</url>
<?php
}

// -----------------------------------------------------------------------------
//  Pages
// -----------------------------------------------------------------------------
$pages = get_pages(array(
  'post_status' => 'publish',
));
$excluded_pages = array('google-sitemap', 'manutencao', 'under-maintenance');

foreach ($pages as $page) {
  if (!in_array($page->post_name, $excluded_pages)) {
    $url = get_permalink($page->ID);
    if (defined('REDIRECT_SITE') && defined('BASE_SITE')) {
      $url = esc_url(str_replace(BASE_SITE, REDIRECT_SITE, $url));
    }
?>
<url>
  <loc><?php echo $url; ?></loc>
  <lastmod><?php echo get_the_modified_date('c', $page->ID); ?></lastmod>
  <changefreq>monthly</changefreq>
  <priority>0.80</priority>
</url>
<?php
  }
}

// -----------------------------------------------------------------------------
//  Categories
// -----------------------------------------------------------------------------
$categories = get_categories(array('hide_empty' => true));
foreach ($categories as $category) {
  if ($category->count > 0 && $category->slug !== 'uncategorized') {
    $url = get_category_link($category->term_id);
    if (defined('REDIRECT_SITE') && defined('BASE_SITE')) {
      $url = esc_url(str_replace(BASE_SITE, REDIRECT_SITE, $url));
    }
?>
<url>
  <loc><?php echo $url; ?></loc>
  <lastmod><?php echo date('c'); ?></lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.60</priority>
</url>
<?php
  }
}

echo "</urlset>";
