<?php
// -----------------------------------------------------------------------------
//  Section: NEWS & ARTICLES (Home)
//  Figma: Home / News. Three latest posts rendered with includes/news-card.php
//  (internal articles and external clippings).
//  WPML filters the query by the current language automatically.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$ishf_news = new WP_Query(array(
  'post_type'           => 'post',
  'posts_per_page'      => 3,
  'ignore_sticky_posts' => true,
  'no_found_rows'       => true,
));
if (!$ishf_news->have_posts()) {
  wp_reset_postdata();
  return;
}
$ishf_posts_page = ishf_translate_post((int) get_option('page_for_posts')); // current-language version (WPML)
?>
<section id="news" class="ishf-news" aria-labelledby="news-title">
  <div class="uk-container">
    <div class="uk-flex uk-flex-between uk-flex-bottom uk-flex-wrap">
      <h2 id="news-title" class="uk-h3 uk-margin-remove"><?php _e('News & articles', TEXT_DOMAIN); ?></h2>
      <?php if ($ishf_posts_page): ?>
        <a class="uk-button uk-button-text" href="<?php echo esc_url(get_permalink($ishf_posts_page)); ?>"><?php _e('All news', TEXT_DOMAIN); ?></a>
      <?php endif; ?>
    </div>

    <ul class="news-grid uk-grid uk-grid-medium uk-child-width-1-1 uk-child-width-1-3@m uk-grid-match" data-uk-grid>
      <?php while ($ishf_news->have_posts()): $ishf_news->the_post(); ?>
        <li><?php get_template_part('includes/news-card'); ?></li>
      <?php endwhile; wp_reset_postdata(); ?>
    </ul>
  </div>
</section>
