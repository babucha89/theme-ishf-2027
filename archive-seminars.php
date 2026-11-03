<?php
// -----------------------------------------------------------------------------
//  Archive: SEMINARS (/seminars/)
//  Renders the same programme as the Programme page, so the URL works even
//  before a "Programme" page exists.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
ishf_use_full_layout(true);
get_header();
get_template_part('includes/sections/header');
?>
<main id="content-start" role="main" class="site-main" tabindex="-1">
  <div class="uk-container uk-section uk-section-small">
    <h1 class="uk-article-title"><?php esc_html_e('Programme', TEXT_DOMAIN); ?></h1>
  </div>
  <?php get_template_part('includes/sections/program'); ?>
</main>
<?php get_footer();
