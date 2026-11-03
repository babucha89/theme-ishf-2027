<?php
// -----------------------------------------------------------------------------
//  Block: COOKIES DISCLAIMER
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------
?>

<?php
// -- Validate if page exists, is published and cookies have not been accepted
$page_intro = get_page_by_path('cookie-policy', OBJECT, 'page') ?: get_page_by_path('politica-de-cookies', OBJECT, 'page');
if (!empty($page_intro)) { $page_intro = ishf_translate_post($page_intro); } // current-language version (WPML)

// Allow displaying if cookie has not been accepted yet and not inside widget iframe
// If page exists in WP database, ensure it is published; if page does not exist yet in DB, allow rendering with fallback URL
$page_is_valid   = empty($page_intro) || ($page_intro->post_status === 'publish');
$cookie_accepted = isset($_COOKIE['ISHF_2027_Cookie_Accept']);

if ($page_is_valid && !$cookie_accepted && !is_page('widget')):
  $cookie_policy_url = !empty($page_intro) ? get_permalink($page_intro) : (REDIRECT_SITE . '/cookie-policy/');
?>
<section id="cookies-disclaimer" class="uk-section uk-section-xsmall uk-light uk-position-relative uk-position-z-index" role="region" aria-label="<?php esc_attr_e('Cookies Disclaimer', TEXT_DOMAIN); ?>">
  <div class="uk-container uk-container-xlarge">
    <div data-uk-grid class="uk-grid-small uk-flex-middle">
      <div class="uk-width-1-1 uk-width-expand@s uk-flex uk-flex-middle">
        <p class="uk-text-small uk-margin-remove">
          <?php echo sprintf(
            __('This website uses cookies to improve the navigation. By navigating on the website you agree with its use. To know more please read our %1sCookies Policy%2s.', TEXT_DOMAIN),
            '<a href="' . esc_url($cookie_policy_url) . '">',
            '</a>'
          ); ?>
        </p>
      </div>
      <div class="uk-width-1-1 uk-width-auto@s uk-flex uk-flex-middle uk-margin-small-top uk-margin-remove-top@s">
        <button id="btnAcceptCookies" type="button" class="uk-button uk-button-small uk-button-primary uk-border-pill">
          <?php _e('I Accept', TEXT_DOMAIN); ?>
        </button>
      </div>
    </div>
  </div>
</section>
<?php
endif;
?>