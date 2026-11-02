<?php
// -----------------------------------------------------------------------------
//  Block: NAVIGATION MENU
//  Minimal navbar: icon-key + "ISHF 2027 | Lisbon" logo and Theme Toggle
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------
?>
<div id="navigation-top" class="uk-position-relative uk-position-z-index" data-uk-sticky="top: 100; animation: uk-animation-slide-top;">
  <?php if (SITE_USE_NAVSCROLLER): ?>
    <progress id="site-scroller" class="uk-progress uk-margin-remove" value="0" max="100" aria-hidden="true"></progress>
  <?php endif; ?>

  <div class="uk-container uk-container-xlarge">
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

      <!-- Right: Theme Toggle (Dark / Light Mode) -->
      <div class="uk-navbar-right uk-flex uk-flex-middle">
        <div class="uk-navbar-item theme-toggle-container">
          <?php get_template_part('includes/theme-toggle'); ?>
        </div>
      </div>

    </nav>
  </div>
</div>

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