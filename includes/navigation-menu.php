<?php
// -----------------------------------------------------------------------------
//  Block: NAVIGATION MENU
//  Minimal navbar: icon-key + "ISHF 2027 | Lisbon" logo and Theme Toggle
//  Full variant (ishf_use_full_layout): + menu links, WPML language switch,
//  off-canvas menu on < 960px. Menus use the UIkit helpers/walkers of includes/uikit-menus.php. No Register button: the CTA lives in the CTA sections.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------
?>
<div id="navigation-top" class="uk-position-relative uk-position-z-index" data-uk-sticky="top: 100; animation: uk-animation-slide-top;">
  <?php if (SITE_USE_NAVSCROLLER): ?>
    <progress id="site-scroller" class="uk-progress uk-margin-remove" value="0" max="100" aria-hidden="true"></progress>
  <?php endif; ?>

  <?php $ishf_full = function_exists('ishf_use_full_layout') && ishf_use_full_layout(); ?>
  <div class="uk-container <?php echo $ishf_full ? '' : 'uk-container-xlarge'; ?>">
    <nav class="uk-navbar-container uk-navbar-transparent uk-flex-between uk-flex-middle" data-uk-navbar role="navigation" aria-label="<?php esc_attr_e('Main Navigation', TEXT_DOMAIN); ?>">
      
      <!-- Brand Logo: icon-key + "ISHF 2027 | Lisbon" -->
      <div class="uk-navbar-left">
        <a class="uk-navbar-item uk-logo nav-brand-link uk-flex uk-flex-middle" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(SITE_NAME); ?>">
          <img class="light-mode-img nav-key-icon" 
               src="<?php echo esc_url(BASE_ASSETS . 'icon-key-light.svg'); ?>" 
               alt="" 
               aria-hidden="true" />
          <img class="dark-mode-img nav-key-icon" 
               src="<?php echo esc_url(BASE_ASSETS . 'icon-key-dark.svg'); ?>" 
               alt="" 
               aria-hidden="true" />
          <span class="nav-brand-text">
            <strong>ISHF 2027</strong>
            <span class="nav-brand-sep" aria-hidden="true">|</span>
            <span class="nav-brand-city">Lisbon</span>
          </span>
        </a>
      </div>

      <?php if ($ishf_full): ?>
      <!-- Right: menu links + tools (>= 960px) / burger (< 960px) -->
      <div class="uk-navbar-right uk-flex uk-flex-middle">
        <?php uikit_top_navbar_right(); // location "Top Navbar / Right" (includes/uikit-menus.php) ?>
        <div class="nav-tools uk-visible@m uk-margin-medium-left">
          <?php get_template_part('includes/language-switcher'); ?>
          <span class="nav-tools-divider" aria-hidden="true"></span>
          <?php get_template_part('includes/theme-toggle'); ?>
        </div>
        <button class="nav-burger uk-hidden@m" type="button" data-uk-toggle="target: #nav-offcanvas" aria-controls="nav-offcanvas" aria-label="<?php esc_attr_e('Open menu', TEXT_DOMAIN); ?>">
          <span data-uk-icon="icon: menu" aria-hidden="true"></span>
          <span><?php _e('Menu', TEXT_DOMAIN); ?></span>
        </button>
      </div>
      <?php else: ?>
      <!-- Right: Theme Toggle (Dark / Light Mode) -->
      <div class="uk-navbar-right uk-flex uk-flex-middle">
        <div class="uk-navbar-item theme-toggle-container">
          <?php get_template_part('includes/theme-toggle'); ?>
        </div>
      </div>
      <?php endif; ?>

    </nav>
  </div>
</div>

<?php if ($ishf_full): ?>
<!-- Mobile menu (UIkit off-canvas: focus is trapped while open, Esc closes) -->
<div id="nav-offcanvas" data-uk-offcanvas="overlay: true; flip: true">
  <div class="uk-offcanvas-bar on-inverse" role="dialog" aria-label="<?php esc_attr_e('Main Navigation', TEXT_DOMAIN); ?>">
    <button class="uk-offcanvas-close" type="button" data-uk-close aria-label="<?php esc_attr_e('Close menu', TEXT_DOMAIN); ?>"></button>
    <?php uikit_offcanvas_right(); // same menu ("Top Navbar / Right"), off-canvas walker ?>
    <div class="nav-tools">
      <?php get_template_part('includes/language-switcher'); ?>
      <span class="nav-tools-divider" aria-hidden="true"></span>
      <?php get_template_part('includes/theme-toggle'); ?>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if (SITE_USE_NAVSCROLLER): ?>
<script type="text/javascript">
jQuery(function($){
  var page_height = $(document).height();
  var viewport_height = $(window).height();
  var total_height = page_height - viewport_height;
  $(window).on('scroll resize', function() {
    page_height = $(document).height();
    viewport_height = $(window).height();
    total_height = page_height - viewport_height;
    if (total_height > 0) {
      var height = $(window).scrollTop();
      var percent_scroll = Math.round(((height / total_height) * 100));
      $('#site-scroller').prop('value', percent_scroll);
    }
  });
});
</script>
<?php endif; ?>