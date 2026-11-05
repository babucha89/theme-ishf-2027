<?php
// -----------------------------------------------------------------------------
//  Block: LANGUAGE SWITCH (WPML)
//  Renders EN / PT pills (flag + 2-letter code, even when WPML uses pt-pt / en-us) from WPML's active languages. Prints nothing when WPML
//  is not active, so the theme works before the plugin is installed.
//  WCAG: lang + hreflang on each link, aria-current on the active language,
//  visible text ("EN") is contained in the accessible name.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$ishf_languages = apply_filters('wpml_active_languages', null, array('skip_missing' => 0, 'orderby' => 'code', 'order' => 'desc'));
if (empty($ishf_languages) || !is_array($ishf_languages)) {
  return;
}
?>
<nav class="lang-switch-nav" aria-label="<?php esc_attr_e('Language', TEXT_DOMAIN); ?>">
  <ul class="lang-switch">
    <?php foreach ($ishf_languages as $ishf_lang): ?>
      <?php
      $code   = strtolower($ishf_lang['language_code']);
      $active = !empty($ishf_lang['active']);
      $name   = !empty($ishf_lang['native_name']) ? $ishf_lang['native_name'] : strtoupper($code);
      ?>
      <li>
        <a href="<?php echo esc_url($ishf_lang['url']); ?>"
           lang="<?php echo esc_attr($code); ?>"
           hreflang="<?php echo esc_attr($code); ?>"
           <?php echo $active ? 'aria-current="true"' : ''; ?>>
          <?php if (!empty($ishf_lang['country_flag_url'])): ?>
            <img class="lang-flag" src="<?php echo esc_url($ishf_lang['country_flag_url']); ?>" width="18" height="12" alt="" aria-hidden="true" loading="lazy" />
          <?php endif; ?>
          <span class="lang-code"><?php echo esc_html(strtoupper(substr($code, 0, 2))); ?></span><span class="sr-only"> – <?php echo esc_html($name); ?></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</nav>
