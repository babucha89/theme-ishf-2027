<?php
// -----------------------------------------------------------------------------
//  Block: CLIPPING / EXTERNAL LINK (post meta + helpers)
//  A "Clipping" (PT: "Cobertura") is a post that points to coverage published on a
//  third-party website. It is a normal post with these CUSTOM FIELDS (post editor > Custom Fields):
//    - ishf_external_url : external article URL (same key as the base template)
//    - ishf_external_lang    : language of the external content: pt, en, es or fr (default pt)
//    - ishf_external_date    : publication date at the source, e.g. 2027-03-15 (optional; falls back to the post date)
//    - ishf_external_source  : publication name, only used when the post has no publication tag (optional)
//  Clippings have NO single page: visiting one redirects to the external URL (previews excepted) and
//  they are left out of the XML sitemap.
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
    $url     = trim((string) get_post_meta($post_id, 'ishf_external_url', true));
    if ($url === '') {
      return null;
    }

    $langs = ishf_clipping_languages();
    $lang  = strtolower(substr(trim((string) get_post_meta($post_id, 'ishf_external_lang', true)), 0, 2));
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

    // Publication the article comes from: post tags (optionally limited to the slugs returned by the
    // 'ishf_publication_tag_slugs' filter, e.g. array('publico', 'expresso')); if there is none, the
    // free-text custom field ishf_external_source is used.
    $slugs   = (array) apply_filters('ishf_publication_tag_slugs', array());
    $tags    = get_the_tags($post_id);
    $sources = array();
    if ($tags && !is_wp_error($tags)) {
      foreach ($tags as $tag) {
        if (empty($slugs) || in_array($tag->slug, $slugs, true)) {
          $sources[] = $tag->name;
        }
      }
    }
    if (empty($sources)) {
      $fallback = trim((string) get_post_meta($post_id, 'ishf_external_source', true));
      if ($fallback !== '') {
        $sources[] = $fallback;
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
  register_post_meta('post', 'ishf_external_url', array('type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'esc_url_raw', 'auth_callback' => function () { return current_user_can('edit_posts'); }));
  register_post_meta('post', 'ishf_external_date', array('type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_text_field', 'auth_callback' => function () { return current_user_can('edit_posts'); }));
  register_post_meta('post', 'ishf_external_source', array('type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_text_field', 'auth_callback' => function () { return current_user_can('edit_posts'); }));
  register_post_meta('post', 'ishf_external_lang', array('type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_text_field', 'auth_callback' => function () { return current_user_can('edit_posts'); }));
});

// -- Back office: the fields live in the native "Custom Fields" panel of the post editor
//    (classic editor: Screen Options > Custom Fields; block editor: Options > Preferences > Panels > Custom fields).
//    Keys: ishf_external_url, ishf_external_lang, ishf_external_date (see the readme).
add_action('init', function () {
  add_post_type_support('post', 'custom-fields');
});

// -- Clippings have no single page: send visitors straight to the external article
add_action('template_redirect', function () {
  if (is_singular('post') && !is_preview()) {
    $clip = ishf_get_clipping(get_queried_object_id());
    if ($clip) {
      wp_redirect(esc_url_raw($clip['url']), 302);
      exit;
    }
  }
});

// -- ...and they are not listed in the WordPress XML sitemap
add_filter('wp_sitemaps_posts_query_args', function ($args, $post_type) {
  if ($post_type === 'post') {
    $args['meta_query'] = array(
      'relation' => 'OR',
      array('key' => 'ishf_external_url', 'compare' => 'NOT EXISTS'),
      array('key' => 'ishf_external_url', 'value' => '', 'compare' => '='),
    );
  }
  return $args;
}, 10, 2);
