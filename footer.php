<?php
// -----------------------------------------------------------------------------
//  File: ROOT FOOTER
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------
?>

  <?php if (function_exists('ishf_use_full_layout') && ishf_use_full_layout()): ?>
    <?php get_template_part('includes/footer-full'); ?>
  <?php else: ?>
  <!-- Main Site Footer (WCAG 2.1 AA) -->
  <footer class="ishf-footer-simple uk-section uk-section-xsmall uk-text-center" role="contentinfo">
    <div class="uk-container">
      <p class="uk-margin-remove footer-legal-text uk-text-small">
        © <?php echo date('Y'); ?> <?php echo esc_html(defined('SITE_SHORT_NAME') ? SITE_SHORT_NAME : SITE_NAME); ?>. <?php _e('All rights reserved.', TEXT_DOMAIN); ?>
        <span class="footer-legal-sep" aria-hidden="true">|</span>
        <span><?php echo sprintf(esc_html__('Developed by %s', TEXT_DOMAIN), 'Medialivre Boost Solutions'); ?></span>
      </p>
    </div>
  </footer>
  <?php endif; ?>

  <?php
  // -- Block: COOKIES DISCLAIMER (Bottom)
  if (!is_page(array('manutencao', 'under-maintenance')) && !is_page_template('page-under-maintenance.php')):
    if (defined('SITE_COOKIES_DISCLAIMER_POSITION') && (SITE_COOKIES_DISCLAIMER_POSITION === 'bottom')):
      get_template_part('includes/cookies-disclaimer');
    endif;
  endif;
  ?>

  <?php wp_footer(); ?>
</body>
</html>
