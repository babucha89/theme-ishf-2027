<?php
// -----------------------------------------------------------------------------
//  File: SINGLE (Single Article / News Post)
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------

the_post();
if (function_exists('ishf_set_post_views')) {
  ishf_set_post_views(get_the_ID());
}

get_header();
get_template_part('includes/sections/header');
?>

<!-- Main Content Landmark -->
<main id="content-start" role="main" class="site-main uk-section" tabindex="-1">
  <div class="uk-container uk-container-small">
    <article <?php post_class('single-article'); ?>>
      
      <header class="article-header uk-margin-medium-bottom">
        <div class="uk-text-meta uk-margin-small-bottom">
          <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
          <?php if (has_category()): ?>
            <span class="uk-margin-small-left uk-margin-small-right">·</span>
            <span><?php the_category(', '); ?></span>
          <?php endif; ?>
        </div>
        <h1 class="uk-article-title uk-margin-remove-top"><?php the_title(); ?></h1>
      </header>

      <?php if (has_post_thumbnail()): ?>
        <div class="article-featured-image uk-margin-medium-bottom">
          <?php the_post_thumbnail('image-gallery', array('class' => 'uk-border-rounded', 'alt' => get_the_title())); ?>
        </div>
      <?php endif; ?>

      <div class="uk-article-content uk-margin-medium-bottom">
        <?php the_content(); ?>
      </div>

      <footer class="article-footer uk-margin-large-top uk-padding-small uk-background-muted uk-border-rounded uk-flex uk-flex-between uk-flex-middle">
        <div>
          <a class="uk-button uk-button-text" href="<?php echo esc_url(home_url('/')); ?>">
            ← <?php _e('Back to Home', TEXT_DOMAIN); ?>
          </a>
        </div>
        <div>
          <?php previous_post_link('%link', '← ' . __('Previous', TEXT_DOMAIN)); ?>
          <span class="uk-margin-small-left uk-margin-small-right">|</span>
          <?php next_post_link('%link', __('Next', TEXT_DOMAIN) . ' →'); ?>
        </div>
      </footer>

    </article>
  </div>
</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>
