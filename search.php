<?php
// -----------------------------------------------------------------------------
//  File: SEARCH RESULTS
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------

// Inner pages / master homepage use the full navigation-menu and footer (landing page, 404 and maintenance keep the minimal ones)
ishf_use_full_layout(true);
get_header();
get_template_part('includes/sections/header');
?>

<!-- Main Content Landmark -->
<main id="content-start" role="main" class="site-main uk-section" tabindex="-1">
  <div class="uk-container uk-container-large">
    
    <div class="uk-margin-medium-bottom">
      <span class="ishf-badge-pill"><?php _e('Search', TEXT_DOMAIN); ?></span>
      <h1 class="uk-h2 uk-margin-small-top">
        <?php printf(__('Search Results for: %s', TEXT_DOMAIN), '<span class="uk-text-primary">' . esc_html(get_search_query()) . '</span>'); ?>
      </h1>
    </div>

    <?php if (have_posts()): ?>
      <div data-uk-grid class="uk-grid-medium uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@m">
        <?php
        while (have_posts()):
          the_post();
        ?>
        <div>
          <article class="search-result-card uk-card uk-card-default uk-card-body">
            <span class="uk-text-meta"><?php echo get_the_date(); ?></span>
            <h2 class="uk-h4 uk-margin-small-top uk-margin-small-bottom">
              <a class="uk-link-reset" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <p class="uk-text-small uk-margin-remove"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
            <a class="uk-button uk-button-text uk-margin-small-top" href="<?php the_permalink(); ?>">
              <?php _e('Read More', TEXT_DOMAIN); ?> →
            </a>
          </article>
        </div>
        <?php endwhile; ?>
      </div>

      <?php the_posts_pagination(array(
        'prev_text' => '← ' . __('Previous', TEXT_DOMAIN),
        'next_text' => __('Next', TEXT_DOMAIN) . ' →',
        'class'     => 'uk-pagination uk-flex-center uk-margin-large-top',
      )); ?>

    <?php else: ?>
      <div class="uk-card uk-card-default uk-card-body uk-text-center uk-margin-large-top">
        <h2 class="uk-h3"><?php _e('Nothing Found', TEXT_DOMAIN); ?></h2>
        <p class="uk-text-muted"><?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', TEXT_DOMAIN); ?></p>
        <div class="uk-margin-medium-top uk-width-1-2@m uk-margin-auto">
          <?php get_search_form(); ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>
