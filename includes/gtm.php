<?php
//----------------------------------------------------------------------------------
//  Block: GOOGLE TAG MANAGER
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//----------------------------------------------------------------------------------

if (defined('SITE_GTM_ID') && !empty(SITE_GTM_ID) && (($_SERVER['SERVER_NAME'] ?? '') !== 'localhost')):
?>
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); })(window,document,'script','dataLayer','<?php echo esc_js(SITE_GTM_ID); ?>');</script>
  <!-- End Google Tag Manager -->
<?php
endif;
?>
