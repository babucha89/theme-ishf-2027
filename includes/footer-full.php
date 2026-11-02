<?php
// -----------------------------------------------------------------------------
//  Block: FOOTER (full variant)
//  Logo, menu columns, newsletter shortcut, legal line, WPML language switch and
//  theme toggle (Design System v2). Sponsor logos live in their own section.
//  Menus: Appearance > Menus > locations "Footer: Festival" / "Footer: About".
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
?>
<footer class="ishf-footer-full on-inverse" role="contentinfo">
  <div class="uk-container">
    <div data-uk-grid class="uk-grid-large">

      <div class="uk-width-1-1 uk-width-1-4@m">
        <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(SITE_NAME); ?>">
          <img class="footer-logo" src="<?php echo esc_url(BASE_BRANDING . 'logo-project-horizontal-light.svg'); ?>" alt="<?php echo esc_attr(SITE_NAME); ?>" />
        </a>
      </div>

      <div class="uk-width-1-2 uk-width-1-4@m">
        <h2 class="footer-heading"><?php _e('Festival', TEXT_DOMAIN); ?></h2>
        <?php
        wp_nav_menu(array('theme_location' => 'footer-festival', 'container' => false, 'menu_class' => 'footer-menu', 'depth' => 1, 'fallback_cb' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>'));
        ?>
      </div>

      <div class="uk-width-1-2 uk-width-1-4@m">
        <h2 class="footer-heading"><?php _e('About', TEXT_DOMAIN); ?></h2>
        <?php
        wp_nav_menu(array('theme_location' => 'footer-about', 'container' => false, 'menu_class' => 'footer-menu', 'depth' => 1, 'fallback_cb' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>'));
        ?>
      </div>

      <div class="uk-width-1-1 uk-width-1-4@m">
        <h2 class="footer-heading"><?php _e('Newsletter', TEXT_DOMAIN); ?></h2>
        <p class="uk-text-small"><?php _e('Join the ISHF 2027 community', TEXT_DOMAIN); ?></p>
        <a class="uk-button uk-button-primary" href="<?php echo esc_url(home_url('/#newsletter')); ?>"><?php _e('Subscribe', TEXT_DOMAIN); ?></a>
      </div>

    </div>

    <div class="footer-bottom">
      <p class="footer-legal-text">
        © <?php echo date('Y'); ?> <?php echo esc_html(defined('SITE_SHORT_NAME') ? SITE_SHORT_NAME : SITE_NAME); ?>. <?php _e('All rights reserved.', TEXT_DOMAIN); ?>
        <span class="footer-legal-sep" aria-hidden="true">|</span>
        <span><?php echo sprintf(esc_html__('Developed by %s', TEXT_DOMAIN), 'Medialivre Boost Solutions'); ?></span>
      </p>
      <div class="footer-tools">
        <?php get_template_part('includes/language-switcher'); ?>
        <span class="nav-tools-divider" aria-hidden="true"></span>
        <?php get_template_part('includes/theme-toggle'); ?>
      </div>
    </div>
  </div>
</footer>
