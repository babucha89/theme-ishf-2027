<?php
//----------------------------------------------------------------------------------
//  Block: GOOGLE TAG MANAGER (No Script)
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//----------------------------------------------------------------------------------

if (defined('SITE_GTM_ID') && !empty(SITE_GTM_ID) && (($_SERVER['SERVER_NAME'] ?? '') !== 'localhost')):
?>
  <!-- Google Tag Manager (noscript) -->
  <noscript aria-hidden="true"><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr(SITE_GTM_ID); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
<?php
endif;
?>
