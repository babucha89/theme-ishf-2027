<?php
// -----------------------------------------------------------------------------
//  Page: UNDER MAINTENANCE (Slug: under-maintenance)
//  File: page-under-maintenance.php
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

the_post();
if (function_exists('ishf_set_post_views')) {
  ishf_set_post_views(get_the_ID());
}

$content = get_the_content();

$fixed_og_image = BASE_OG_IMAGES . 'og-image-homepage.jpg';
?>

<?php
// -- Block: HEADER (Head & Body Opening, Skip-link)
get_header();
?>

<?php
// -- Block: NAVIGATION MENU (Sticky Navbar, Key Icon, Logo, Theme Toggle)
get_template_part('includes/navigation-menu');
?>

<main id="content-start" role="main" class="site-main maintenance-main uk-section uk-flex uk-flex-middle" tabindex="-1" data-uk-height-viewport="expand: true" data-uk-scrollspy="target: .animate-me; cls: uk-animation-slide-bottom-medium; delay: 150">
  <div class="uk-container uk-container-small uk-text-center">
    
    <div class="animate-me maintenance-logo-container uk-margin-medium-bottom">
      <img class="light-mode-img maintenance-project-logo" src="<?php echo esc_url(BASE_BRANDING . 'logo-project-horizontal-dark.svg'); ?>" alt="<?php echo esc_attr(SITE_NAME); ?>" />
      <img class="dark-mode-img maintenance-project-logo" src="<?php echo esc_url(BASE_BRANDING . 'logo-project-horizontal-light.svg'); ?>" alt="<?php echo esc_attr(SITE_NAME); ?>" />
    </div>

    <div class="animate-me uk-margin-medium-bottom">
      <span data-uk-icon="icon: settings; ratio: 2.5" class="maintenance-cog" aria-hidden="true"></span>
    </div>
    
    <h1 class="animate-me uk-margin-remove-top uk-margin-medium-bottom uk-heading-small maintenance-title">
      <?php _e('Under Maintenance', TEXT_DOMAIN); ?>
    </h1>
    
    <div class="animate-me uk-text-lead maintenance-lead">
      <?php if (!empty($content)): ?>
        <?php echo $content; ?>
      <?php else: ?>
        <p><?php _e('We are preparing something special for the International Social Housing Festival 2027. We will be back online soon.', TEXT_DOMAIN); ?></p>
      <?php endif; ?>
    </div>

  </div>
</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>