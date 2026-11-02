<?php
// -----------------------------------------------------------------------------
//  File: 404 (Page Not Found)
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

// -- Block: HEADER (Head & Body Opening, Skip-link)
get_header();
?>

<?php
// -- Block: NAVIGATION MENU (Sticky Navbar, Key Icon, Logo, Theme Toggle)
get_template_part('includes/navigation-menu');
?>

<main id="content-start" role="main" class="site-main error-page-main uk-section uk-flex uk-flex-middle" tabindex="-1" data-uk-height-viewport="expand: true" data-uk-scrollspy="target: .animate-me; cls: uk-animation-slide-bottom-medium; delay: 150">
  <div class="uk-container uk-container-small uk-text-center">

    <div class="animate-me maintenance-logo-container uk-margin-medium-bottom">
      <img class="light-mode-img maintenance-project-logo" src="<?php echo esc_url(BASE_BRANDING . 'logo-project-horizontal-dark.svg'); ?>" alt="<?php echo esc_attr(SITE_NAME); ?>" />
      <img class="dark-mode-img maintenance-project-logo" src="<?php echo esc_url(BASE_BRANDING . 'logo-project-horizontal-light.svg'); ?>" alt="<?php echo esc_attr(SITE_NAME); ?>" />
    </div>

    <div class="animate-me uk-margin-small-bottom">
      <div class="error-number" aria-hidden="true">404</div>
    </div>

    <h1 class="animate-me error-title uk-margin-remove-top uk-margin-small-bottom">
      <?php _e('Page Not Found', TEXT_DOMAIN); ?>
    </h1>

    <p class="animate-me error-lead uk-margin-remove-top uk-margin-medium-bottom">
      <?php _e('The page you are looking for does not exist, has been removed or is temporarily unavailable.', TEXT_DOMAIN); ?>
    </p>

    <div class="animate-me uk-margin-medium-top">
      <a class="uk-button uk-button-primary uk-border-pill" href="<?php echo esc_url(home_url('/')); ?>">
        <span data-uk-icon="arrow-left" aria-hidden="true"></span>
        <?php _e('Back to Homepage', TEXT_DOMAIN); ?>
      </a>
    </div>

  </div>
</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>
