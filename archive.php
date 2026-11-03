<?php
// -----------------------------------------------------------------------------
//  File: ARCHIVE
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

// Inner pages / master homepage use the full navigation-menu and footer (landing page, 404 and maintenance keep the minimal ones)
ishf_use_full_layout(true);
get_header();
get_template_part('includes/sections/header');
?>

<!-- Main Content Landmark (WCAG 2.1 AA) -->
<main id="content-start" role="main" class="site-main uk-section" tabindex="-1">
  <div class="uk-container uk-container-large">

    <div class="uk-margin-medium-bottom">
      <span class="ishf-badge-pill"><?php _e('Archive', TEXT_DOMAIN); ?></span>
      <h1 class="uk-h2 uk-margin-small-top"><?php the_archive_title(); ?></h1>
      <?php if (get_the_archive_description()): ?>
        <div class="uk-text-muted uk-margin-small-top">
          <?php the_archive_description(); ?>
        </div>
      <?php endif; ?>
    </div>

    <?php if (have_posts()): ?>
      <div data-uk-grid data-uk-height-match="target: > div > .uk-card" class="uk-grid-medium uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@m">
        <?php
        while (have_posts()):
          the_post();
        ?>
        <div>
          <?php get_template_part('includes/news-card'); ?>
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
        <h2 class="uk-h3"><?php _e('No Content Found', TEXT_DOMAIN); ?></h2>
        <p class="uk-text-muted"><?php _e('There are no publications available in this archive at the moment.', TEXT_DOMAIN); ?></p>
        <a class="uk-button uk-button-primary uk-border-pill uk-margin-small-top" href="<?php echo esc_url(home_url('/')); ?>">
          <?php _e('Back to Homepage', TEXT_DOMAIN); ?>
        </a>
      </div>
    <?php endif; ?>

  </div>
</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>
