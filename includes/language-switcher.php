<?php
// -----------------------------------------------------------------------------
//  Block: LANGUAGE SWITCH (WPML)
//  Order follows WPML > Settings > Language Switchers > Order of languages.
//  Renders EN / PT pills (2-letter code) from WPML's active languages; lang / hreflang use the
//  language's locale from WPML (en-GB, pt-PT), or the code when WPML gives none. Prints nothing when WPML
//  is not active, so the theme works before the plugin is installed.
//  WCAG: lang + hreflang on each link, aria-current on the active language,
//  visible text ("EN") is contained in the accessible name.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$ishf_languages = apply_filters('wpml_active_languages', null, array('skip_missing' => 0, 'orderby' => 'custom'));
if (empty($ishf_languages) || !is_array($ishf_languages)) {
  return;
}
?>
<nav class="lang-switch-nav" aria-label="<?php esc_attr_e('Language', TEXT_DOMAIN); ?>">
  <ul class="lang-switch">
    <?php foreach ($ishf_languages as $ishf_lang): ?>
      <?php
      $code   = strtolower($ishf_lang['language_code']);
      $tag    = !empty($ishf_lang['default_locale']) ? str_replace('_', '-', $ishf_lang['default_locale']) : $code; // en-GB, pt-PT
      $active = !empty($ishf_lang['active']);
      $name   = !empty($ishf_lang['native_name']) ? $ishf_lang['native_name'] : strtoupper($code);
      ?>
      <li>
        <a href="<?php echo esc_url($ishf_lang['url']); ?>"
           lang="<?php echo esc_attr($tag); ?>"
           hreflang="<?php echo esc_attr($tag); ?>"
           <?php echo $active ? 'aria-current="true"' : ''; ?>>
          <span class="lang-code"><?php echo esc_html(strtoupper(substr($code, 0, 2))); ?></span><span class="sr-only"> – <?php echo esc_html($name); ?></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</nav>
