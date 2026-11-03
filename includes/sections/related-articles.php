<?php
// -----------------------------------------------------------------------------
//  Section: RELATED ARTICLES (single.php)
//  Up to 3 other posts, articles or clippings, same cards as the home news block
//  (includes/news-card.php). Same category first; completed with the latest posts.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$ishf_current = get_the_ID();
$ishf_ids     = array();

$ishf_cats = wp_get_post_categories($ishf_current);
if (!empty($ishf_cats)) {
  $ishf_ids = get_posts(array(
    'post_type'           => 'post',
    'posts_per_page'      => 3,
    'post__not_in'        => array($ishf_current),
    'category__in'        => $ishf_cats,
    'ignore_sticky_posts' => true,
    'fields'              => 'ids',
    'no_found_rows'       => true,
  ));
}
if (count($ishf_ids) < 3) {
  $ishf_more = get_posts(array(
    'post_type'           => 'post',
    'posts_per_page'      => 3 - count($ishf_ids),
    'post__not_in'        => array_merge(array($ishf_current), $ishf_ids),
    'ignore_sticky_posts' => true,
    'fields'              => 'ids',
    'no_found_rows'       => true,
  ));
  $ishf_ids = array_merge($ishf_ids, $ishf_more);
}
if (empty($ishf_ids)) {
  return;
}

$ishf_related = new WP_Query(array('post_type' => 'post', 'post__in' => $ishf_ids, 'orderby' => 'post__in', 'posts_per_page' => 3, 'no_found_rows' => true));
?>
<section id="related-articles" class="ishf-news article-related" aria-labelledby="related-title">
  <div class="uk-container">
    <h2 id="related-title" class="uk-h3 uk-margin-remove"><?php _e('Related articles', TEXT_DOMAIN); ?></h2>
    <ul class="news-grid uk-grid uk-grid-medium uk-child-width-1-1 uk-child-width-1-3@m uk-grid-match" data-uk-grid>
      <?php while ($ishf_related->have_posts()): $ishf_related->the_post(); ?>
        <li><?php get_template_part('includes/news-card'); ?></li>
      <?php endwhile; wp_reset_postdata(); ?>
    </ul>
  </div>
</section>
