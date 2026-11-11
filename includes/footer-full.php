<?php
// -----------------------------------------------------------------------------
//  Block: FOOTER (full variant)
//  Logo, menu columns, newsletter shortcut and legal line (Design System v2).
//  The language switch lives in the navbar next to the theme toggle. Sponsor logos live in their own section.
//  Menus: Appearance > Menus > locations "Footer: Festival" / "Footer: About".
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
?>
<footer class="ishf-footer-full on-inverse" role="contentinfo">
  <div class="uk-container uk-container-large">
    <div data-uk-grid class="uk-grid-large">

      <div class="uk-width-1-1 uk-width-1-4@m">
        <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(SITE_NAME); ?>">
          <img class="footer-logo" src="<?php echo esc_url(BASE_BRANDING . 'logo-project-horizontal-light.svg'); ?>" alt="<?php echo esc_attr(SITE_NAME); ?>" />
        </a>
      </div>

      <div class="uk-width-1-2 uk-width-1-4@m">
        <h2 class="footer-heading"><?php _e('Festival', TEXT_DOMAIN); ?></h2>
        <?php uikit_footer_subnav_left(); // location "Footer Subnav / Left" ?>
      </div>

      <div class="uk-width-1-2 uk-width-1-4@m">
        <h2 class="footer-heading"><?php _e('About', TEXT_DOMAIN); ?></h2>
        <?php uikit_footer_subnav_center(); // location "Footer Subnav / Center" ?>
      </div>

      <div class="uk-width-1-1 uk-width-1-4@m">
        <h2 class="footer-heading"><?php _e('Newsletter', TEXT_DOMAIN); ?></h2>
        <a href="#modal-newsletter" class="footer-newsletter-link uk-link-reset uk-flex uk-flex-middle uk-flex-center uk-flex-right@s uk-margin-bottom" data-uk-toggle="target: #modal-newsletter" aria-haspopup="dialog" aria-controls="modal-newsletter" aria-label="<?php esc_attr_e('Join the ISHF 2027 community', TEXT_DOMAIN); ?>">
          <span class="footer-newsletter-text"><?php _e('Join the ISHF 2027 community', TEXT_DOMAIN); ?></span>
        </a>
      </div>

    </div>

    <div class="footer-bottom">
      <p class="footer-legal-text">
        © <?php echo date('Y'); ?> <?php echo esc_html(defined('SITE_NAME') ? SITE_NAME : 'International Social Housing Festival'); ?>. <?php _e('All rights reserved.', TEXT_DOMAIN); ?>
        <span class="footer-legal-sep" aria-hidden="true">|</span>
        <span><?php echo sprintf(esc_html__('Developed by %s', TEXT_DOMAIN), 'Medialivre Boost Solutions'); ?></span>
      </p>
    </div>
  </div>
</footer>
