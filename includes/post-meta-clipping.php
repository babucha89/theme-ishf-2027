<?php
// -----------------------------------------------------------------------------
//  Block: CLIPPING / EXTERNAL LINK (post meta + helpers)
//  A "Clipping" (PT: "Cobertura") is a post that points to coverage published on a
//  third-party website. It is a normal post with an external URL:
//    - uikit_bs_external_url : external article URL (same key as the base template)
//    - ishf_external_date    : publication date at the source (Y-m-d, optional; falls back to the post date)
//    - ishf_external_lang    : language of the external content (pt/en/es/fr; default pt)
//  The publication it comes from is set with POST TAGS (e.g. "Público", "Expresso").
//  Cards show the "In Portuguese" note when the content language differs from the site language.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------

if (!function_exists('ishf_clipping_languages')):
  /** Content languages available in the meta box: code => label shown in the admin. */
  function ishf_clipping_languages()
  {
    return array(
      'pt' => __('Portuguese', TEXT_DOMAIN),
      'en' => __('English', TEXT_DOMAIN),
      'es' => __('Spanish', TEXT_DOMAIN),
      'fr' => __('French', TEXT_DOMAIN),
    );
  }
endif;

if (!function_exists('ishf_current_language')):
  /** Two-letter code of the language the visitor is browsing in (WPML aware). */
  function ishf_current_language()
  {
    $lang = apply_filters('wpml_current_language', null);
    if (empty($lang)) {
      $lang = substr(determine_locale(), 0, 2);
    }
    return strtolower(substr($lang, 0, 2));
  }
endif;

if (!function_exists('ishf_get_clipping')):
  /**
   * Clipping data for a post, or null when the post is a regular (internal) article.
   * Returns: url, lang, lang_note (empty when same language as the site), date_iso, date_label, sources (tag names).
   */
  function ishf_get_clipping($post_id = 0)
  {
    $post_id = $post_id ? (int) $post_id : get_the_ID();
    $url     = trim((string) get_post_meta($post_id, 'uikit_bs_external_url', true));
    if ($url === '') {
      return null;
    }

    $langs = ishf_clipping_languages();
    $lang  = (string) get_post_meta($post_id, 'ishf_external_lang', true);
    $lang  = isset($langs[$lang]) ? $lang : 'pt';

    $notes = array(
      'pt' => __('In Portuguese', TEXT_DOMAIN),
      'en' => __('In English', TEXT_DOMAIN),
      'es' => __('In Spanish', TEXT_DOMAIN),
      'fr' => __('In French', TEXT_DOMAIN),
    );

    $date = (string) get_post_meta($post_id, 'ishf_external_date', true);
    $ts   = $date ? strtotime($date) : false;
    $iso  = $ts ? gmdate('Y-m-d', $ts) : get_the_date('Y-m-d', $post_id);
    $label = $ts ? date_i18n(get_option('date_format'), $ts) : get_the_date('', $post_id);

    $tags    = get_the_tags($post_id);
    $sources = array();
    if ($tags && !is_wp_error($tags)) {
      foreach ($tags as $tag) {
        $sources[] = $tag->name;
      }
    }

    return array(
      'url'        => $url,
      'lang'       => $lang,
      'lang_note'  => ($lang !== ishf_current_language()) ? $notes[$lang] : '',
      'date_iso'   => $iso,
      'date_label' => $label,
      'sources'    => $sources,
    );
  }
endif;

if (!function_exists('ishf_post_label')):
  /** Eyebrow label of a post: "Clipping"/"Cobertura" for external or clipping-category posts, else its first category. */
  function ishf_post_label($post_id = 0)
  {
    $post_id = $post_id ? (int) $post_id : get_the_ID();
    if (ishf_get_clipping($post_id) || has_category(array('clipping', 'cobertura'), $post_id)) {
      return __('Clipping', TEXT_DOMAIN);
    }
    $cats = get_the_category($post_id);
    return !empty($cats) ? $cats[0]->name : '';
  }
endif;

// -- Meta registration (exposed in REST so the future app can read it)
add_action('init', function () {
  register_post_meta('post', 'uikit_bs_external_url', array('type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'esc_url_raw', 'auth_callback' => function () { return current_user_can('edit_posts'); }));
  register_post_meta('post', 'ishf_external_date', array('type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_text_field', 'auth_callback' => function () { return current_user_can('edit_posts'); }));
  register_post_meta('post', 'ishf_external_lang', array('type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_key', 'auth_callback' => function () { return current_user_can('edit_posts'); }));
});

// -- Meta box
add_action('add_meta_boxes', function () {
  add_meta_box('ishf_clipping', __('Clipping / External link', TEXT_DOMAIN), 'ishf_clipping_meta_box', 'post', 'side', 'high');
});

function ishf_clipping_meta_box($post)
{
  wp_nonce_field('ishf_clipping_save', 'ishf_clipping_nonce');
  $url  = get_post_meta($post->ID, 'uikit_bs_external_url', true);
  $date = get_post_meta($post->ID, 'ishf_external_date', true);
  $lang = get_post_meta($post->ID, 'ishf_external_lang', true) ?: 'pt';
  ?>
  <p class="description"><?php _e('Fill in the URL only for coverage published on a third-party website. The card then links to it (new tab). Add the publication name as a post tag.', TEXT_DOMAIN); ?></p>
  <p>
    <label for="ishf_external_url"><strong><?php _e('External URL', TEXT_DOMAIN); ?></strong></label>
    <input type="url" id="ishf_external_url" name="uikit_bs_external_url" class="widefat" value="<?php echo esc_attr($url); ?>" placeholder="https://">
  </p>
  <p>
    <label for="ishf_external_date"><strong><?php _e('Publication date at the source', TEXT_DOMAIN); ?></strong></label>
    <input type="date" id="ishf_external_date" name="ishf_external_date" class="widefat" value="<?php echo esc_attr($date); ?>">
  </p>
  <p>
    <label for="ishf_external_lang"><strong><?php _e('Language of the external content', TEXT_DOMAIN); ?></strong></label>
    <select id="ishf_external_lang" name="ishf_external_lang" class="widefat">
      <?php foreach (ishf_clipping_languages() as $code => $name): ?>
        <option value="<?php echo esc_attr($code); ?>" <?php selected($lang, $code); ?>><?php echo esc_html($name); ?></option>
      <?php endforeach; ?>
    </select>
  </p>
  <?php
}

add_action('save_post_post', function ($post_id) {
  if (!isset($_POST['ishf_clipping_nonce']) || !wp_verify_nonce($_POST['ishf_clipping_nonce'], 'ishf_clipping_save')) {
    return;
  }
  if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
    return;
  }
  $url = esc_url_raw(wp_unslash($_POST['uikit_bs_external_url'] ?? ''));
  if ($url === '') {
    delete_post_meta($post_id, 'uikit_bs_external_url');
  } else {
    update_post_meta($post_id, 'uikit_bs_external_url', $url);
  }
  $date = sanitize_text_field(wp_unslash($_POST['ishf_external_date'] ?? ''));
  $date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) ? $date : '';
  update_post_meta($post_id, 'ishf_external_date', $date);
  $lang = sanitize_key($_POST['ishf_external_lang'] ?? 'pt');
  update_post_meta($post_id, 'ishf_external_lang', array_key_exists($lang, ishf_clipping_languages()) ? $lang : 'pt');
});
