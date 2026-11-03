<?php
// -----------------------------------------------------------------------------
//  File: SINGLE (Single Article / News Post)
//  Layout: compact header -> ARTICLE HERO section (label, title, lead = excerpt, image 'image-gallery')
//  -> ARTICLE BODY section (narrow container, different background for contrast) -> post navigation.
//  Clippings (external URL) get a "Read at the source" button, with the content-language note.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------

the_post();
if (function_exists('ishf_set_post_views')) {
  ishf_set_post_views(get_the_ID());
}

// Inner pages / master homepage use the full navigation-menu and footer (landing page, 404 and maintenance keep the minimal ones)
ishf_use_full_layout(true);
get_header();
get_template_part('includes/sections/header', null, array('compact' => true));

$ishf_clip     = ishf_get_clipping();
$ishf_label    = ishf_post_label();
$ishf_lead     = has_excerpt() ? get_the_excerpt() : '';
$ishf_img_alt  = '';
if (has_post_thumbnail()) {
  $ishf_img_alt = trim((string) get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true));
}
?>

<!-- Main Content Landmark -->
<main id="content-start" role="main" class="site-main" tabindex="-1">
  <article <?php post_class('single-article'); ?>>

    <!-- Section 1: label, title, lead, image (container + background A) -->
    <section class="article-hero" aria-labelledby="article-title">
      <div class="uk-container">
        <header class="article-header">
          <p class="article-meta">
            <?php if ($ishf_label): ?><span class="ishf-eyebrow"><?php echo esc_html($ishf_label); ?></span><?php endif; ?>
            <?php if ($ishf_clip): ?>
              <?php foreach ($ishf_clip['sources'] as $source): ?><span class="news-card-tag"><?php echo esc_html($source); ?></span><?php endforeach; ?>
              <?php if ($ishf_clip['lang_note']): ?><span class="news-card-tag news-card-tag--lang"><?php echo esc_html($ishf_clip['lang_note']); ?></span><?php endif; ?>
              <time datetime="<?php echo esc_attr($ishf_clip['date_iso']); ?>"><?php echo esc_html($ishf_clip['date_label']); ?></time>
            <?php else: ?>
              <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
            <?php endif; ?>
          </p>
          <h1 id="article-title" class="article-title"><?php the_title(); ?></h1>
          <?php if ($ishf_lead): ?>
            <p class="article-lead uk-text-lead"><?php echo esc_html($ishf_lead); ?></p>
          <?php endif; ?>
        </header>

        <?php if (has_post_thumbnail()): ?>
          <figure class="article-featured-image">
            <?php the_post_thumbnail('image-gallery', array('alt' => $ishf_img_alt, 'loading' => 'eager', 'fetchpriority' => 'high')); ?>
            <?php $ishf_caption = get_the_post_thumbnail_caption(); if ($ishf_caption): ?>
              <figcaption class="uk-text-small"><?php echo esc_html($ishf_caption); ?></figcaption>
            <?php endif; ?>
          </figure>
        <?php endif; ?>
      </div>
    </section>

    <!-- Section 2: article body (narrow reading container + background B) -->
    <section class="article-body" aria-label="<?php esc_attr_e('Article content', TEXT_DOMAIN); ?>">
      <div class="uk-container uk-container-small">

        <div class="uk-article-content">
          <?php the_content(); ?>
        </div>

        <?php if ($ishf_clip): ?>
          <p class="article-source-cta">
            <a class="uk-button uk-button-primary" href="<?php echo esc_url($ishf_clip['url']); ?>" target="_blank" rel="noopener noreferrer"<?php echo $ishf_clip['lang_note'] ? ' lang="' . esc_attr($ishf_clip['lang']) . '"' : ''; ?>>
              <?php _e('Read the full article at the source', TEXT_DOMAIN); ?>
              <?php if ($ishf_clip['lang_note']): ?>(<?php echo esc_html($ishf_clip['lang_note']); ?>)<?php endif; ?>
              <span class="uk-visually-hidden"> <?php _e('(opens in a new tab)', TEXT_DOMAIN); ?></span>
            </a>
          </p>
        <?php endif; ?>

        <?php $ishf_tags = !$ishf_clip ? get_the_tags() : false; if ($ishf_tags): ?>
          <ul class="article-tags" role="list">
            <?php foreach ($ishf_tags as $tag): ?><li class="news-card-tag"><?php echo esc_html($tag->name); ?></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>

      </div>
    </section>

    <!-- Section 3: post navigation -->
    <nav class="article-footer" aria-label="<?php esc_attr_e('Article navigation', TEXT_DOMAIN); ?>">
      <div class="uk-container uk-container-small uk-flex uk-flex-between uk-flex-middle uk-flex-wrap">
        <a class="uk-button uk-button-text" href="<?php echo esc_url(home_url('/')); ?>">← <?php _e('Back to Home', TEXT_DOMAIN); ?></a>
        <div>
          <?php previous_post_link('%link', '← ' . __('Previous', TEXT_DOMAIN)); ?>
          <span class="uk-margin-small-left uk-margin-small-right" aria-hidden="true">|</span>
          <?php next_post_link('%link', __('Next', TEXT_DOMAIN) . ' →'); ?>
        </div>
      </div>
    </nav>

  </article>
</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>
