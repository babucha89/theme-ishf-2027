<?php
// -----------------------------------------------------------------------------
//  File: SINGLE (Single Article / News Post)
//  Layout: compact header -> ARTICLE HERO section (label, title, lead = excerpt side by side with the
//  'image-gallery' image; date = small detail under the image, right-aligned)
//  -> ARTICLE BODY section (narrow container, different background for contrast) -> post navigation.
//  Clippings (external URL) have no single page (redirected, see includes/post-meta-clipping.php); the
//  related-articles block lists other articles and clippings with the home news cards.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------

the_post();
if (function_exists('ishf_set_post_views')) {
  ishf_set_post_views(get_the_ID());
}

// Inner pages / master homepage use the full navigation-menu and footer (landing page, 404 and maintenance keep the minimal ones)
ishf_use_full_layout(true);
get_header();
get_template_part('includes/sections/header');

$ishf_clip     = ishf_get_clipping();
$ishf_label    = ishf_post_label();
$ishf_lead     = has_excerpt() ? get_the_excerpt() : '';
$ishf_date_iso   = $ishf_clip ? $ishf_clip['date_iso'] : get_the_date('c');
$ishf_date_label = $ishf_clip ? $ishf_clip['date_label'] : get_the_date();
$ishf_img_alt  = '';
if (has_post_thumbnail()) {
  $ishf_img_alt = trim((string) get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true));
}
?>

<!-- Main Content Landmark -->
<main id="content-start" role="main" class="site-main" tabindex="-1">
  <article <?php post_class('single-article'); ?>>

    <!-- Section 1: label, title, lead + image side by side (container + background A) -->
    <section class="article-hero" aria-labelledby="article-title">
      <div class="uk-container uk-container-large">
        <div class="article-hero-grid uk-grid-large uk-flex-middle" data-uk-grid>

          <header class="article-header uk-width-1-1 <?php echo has_post_thumbnail() ? 'uk-width-1-3@s' : ''; ?>">
            <p class="article-meta">
              <?php if ($ishf_label): ?><span class="uk-label ishf-label<?php echo ($ishf_clip || has_category(array('clipping', 'cobertura'))) ? ' ishf-label--clipping' : ''; ?>"><?php echo esc_html($ishf_label); ?></span><?php endif; ?>
            </p>
            <h1 id="article-title" class="article-title"><?php the_title(); ?></h1>
            <?php if ($ishf_lead): ?>
              <p class="article-lead uk-text-lead"><?php echo esc_html($ishf_lead); ?></p>
            <?php endif; ?>
            <?php if (!has_post_thumbnail()): ?>
              <p class="article-date"><time datetime="<?php echo esc_attr($ishf_date_iso); ?>"><?php echo esc_html($ishf_date_label); ?></time></p>
            <?php endif; ?>
          </header>

          <?php if (has_post_thumbnail()): ?>
            <figure class="article-featured-image uk-width-1-1 uk-width-expand@s">
              <?php the_post_thumbnail('image-gallery', array('alt' => $ishf_img_alt, 'loading' => 'eager', 'fetchpriority' => 'high')); ?>
              <figcaption class="article-image-meta">
                <?php $ishf_caption = get_the_post_thumbnail_caption(); if ($ishf_caption): ?>
                  <span class="article-image-caption uk-text-small"><?php echo esc_html($ishf_caption); ?></span>
                <?php endif; ?>
                <time class="article-date" datetime="<?php echo esc_attr($ishf_date_iso); ?>"><?php echo esc_html($ishf_date_label); ?></time>
              </figcaption>
            </figure>
          <?php endif; ?>

        </div>
      </div>
    </section>

    <!-- Section 2: article body (narrow reading container + background B) -->
    <section class="article-body" aria-label="<?php esc_attr_e('Article content', TEXT_DOMAIN); ?>">
      <div class="uk-container uk-container-small">

        <div class="uk-article-content">
          <?php the_content(); ?>
        </div>

        <?php $ishf_tags = get_the_tags(); if ($ishf_tags): ?>
          <ul class="article-tags" role="list">
            <?php foreach ($ishf_tags as $tag): ?><li class="news-card-tag"><?php echo esc_html($tag->name); ?></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>

      </div>
    </section>

    <!-- Section 3: related articles (content or clippings, same cards as the home news block) -->
    <?php get_template_part('includes/sections/related-articles'); ?>

    <nav class="article-footer" aria-label="<?php esc_attr_e('Article navigation', TEXT_DOMAIN); ?>">
      <div class="uk-container">
        <a class="uk-button uk-button-text article-back" href="<?php echo esc_url(home_url('/')); ?>">← <?php _e('Back to Home', TEXT_DOMAIN); ?></a>
      </div>
    </nav>

  </article>
</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>
