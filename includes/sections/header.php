<?php
// -----------------------------------------------------------------------------
//  Section: HEADER
//  Clean header with the project logo and the claim side-by-side
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------
// Arg 'compact' => true renders a smaller version (single articles): smaller logo, tighter spacing.
$ishf_header_compact = !empty($args['compact']);
?>
<header class="ishf-simplified-header<?php echo $ishf_header_compact ? ' ishf-simplified-header--compact' : ''; ?> uk-position-relative uk-overflow-hidden" role="banner">
  <?php
  // -- Navigation Menu
  get_template_part('includes/navigation-menu');
  ?>

  <div class="uk-container uk-container-large header-content-wrapper">
    <div class="uk-grid uk-grid-large uk-flex-middle header-hero-grid" data-uk-grid>
      
      <!-- Project Logo (Adaptive Light / Dark) -->
      <div class="uk-width-1-1 uk-width-1-2@m header-logo-col uk-flex uk-flex-center uk-flex-right@m">
        <div class="header-logo-container">
          <img class="light-mode-img header-project-logo" 
               src="<?php echo esc_url(BASE_BRANDING . 'logo-project-horizontal-dark.svg'); ?>" 
               alt="<?php echo esc_attr(SITE_NAME); ?>" />
          <img class="dark-mode-img header-project-logo" 
               src="<?php echo esc_url(BASE_BRANDING . 'logo-project-horizontal-light.svg'); ?>" 
               alt="<?php echo esc_attr(SITE_NAME); ?>" />
        </div>
      </div>

      <!-- Claim -->
      <div class="uk-width-1-1 uk-width-1-2@m header-claim-col uk-flex uk-flex-center uk-flex-left@m">
        <div class="header-claim-inner">
          <p class="header-claim uk-margin-remove" role="heading" aria-level="2">
            <?php _e('Cities with Soul: It Begins with a Key', TEXT_DOMAIN); ?>
          </p>
        </div>
      </div>

    </div>
  </div>
</header>
