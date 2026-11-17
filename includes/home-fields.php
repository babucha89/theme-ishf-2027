<?php
// -----------------------------------------------------------------------------
//  HOMEPAGE TEXTS (back office): hero and user journeys
//  A "Homepage: hero and journeys" box in the editor of the page "sections/hero"
//  (a child page "hero" of a page "sections"; not listed in the sitemap) and of
//  its WPML translations. Every text of includes/sections/hero.php and
//  includes/sections/journeys.php is edited there, per language.
//  - Meta keys: ishf_hero_* and ishf_journeys_* (ishf_home_fields()); the older
//    keys ishf_hero_eyebrow / _date / _cta_primary / _cta_secondary are kept.
//  - The hero photo is the page's featured image.
//  - An empty field shows its default (placeholder in the box) from languages/*.po.
//  - Registration phases (ishf_get_register_phase(), functions.php): until
//    31 Dec 2026 the "participate" journey and the hero's main button lead to the
//    Call for Sessions form; from 1 Jan 2027 to the attendee registration form.
//    Each phase has its own texts.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 17-NOV-2026
// -----------------------------------------------------------------------------

// Field definitions: meta key => array(label, type, default, description), by group
if (!function_exists('ishf_home_fields')):
  function ishf_home_fields()
  {
    $until = __('until 31 Dec 2026', TEXT_DOMAIN);
    $from  = __('from 1 Jan 2027', TEXT_DOMAIN);
    return array(
      __('Hero', TEXT_DOMAIN) => array(
        'ishf_hero_eyebrow' => array(__('Eyebrow', TEXT_DOMAIN), 'text', __('International Social Housing Festival', TEXT_DOMAIN), ''),
        'ishf_hero_title'   => array(__('Title', TEXT_DOMAIN), 'text', __('Cities with Soul: It Begins with a Key', TEXT_DOMAIN), __('The only H1 of the homepage.', TEXT_DOMAIN)),
        'ishf_hero_date'    => array(__('Dates and place', TEXT_DOMAIN), 'text', __('7 – 9 June 2027 · Lisbon, Portugal', TEXT_DOMAIN), ''),
        'ishf_hero_lead'    => array(__('Text', TEXT_DOMAIN), 'textarea', __('The international meeting point for people, ideas and solutions to build more inclusive, sustainable and resilient cities.', TEXT_DOMAIN), __('The photo is this page\'s featured image.', TEXT_DOMAIN)),
        'ishf_hero_cta_primary'      => array(sprintf(__('Main button (%s)', TEXT_DOMAIN), $until), 'text', __('Submit Your Proposal', TEXT_DOMAIN), __('Leads to the Call for Sessions form.', TEXT_DOMAIN)),
        'ishf_hero_cta_primary_2027' => array(sprintf(__('Main button (%s)', TEXT_DOMAIN), $from), 'text', __('Get Your Ticket', TEXT_DOMAIN), __('Leads to the attendee registration form.', TEXT_DOMAIN)),
        'ishf_hero_cta_primary_url'  => array(__('Main button link (optional)', TEXT_DOMAIN), 'url', '', __('Leave empty to follow the dates above.', TEXT_DOMAIN)),
        'ishf_hero_cta_secondary'     => array(__('Second button', TEXT_DOMAIN), 'text', __('Become a Partner', TEXT_DOMAIN), ''),
        'ishf_hero_cta_secondary_url' => array(__('Second button link (optional)', TEXT_DOMAIN), 'url', '', __('Leave empty to open the partnership form.', TEXT_DOMAIN)),
      ),
      __('Journeys', TEXT_DOMAIN) => array(
        'ishf_journeys_title' => array(__('Section title', TEXT_DOMAIN), 'text', __('Choose your journey', TEXT_DOMAIN), ''),
        'ishf_journeys_participate_title'  => array(sprintf(__('Participate: title (%s)', TEXT_DOMAIN), $until), 'text', __('Contribute to the Programme', TEXT_DOMAIN), __('Call for Sessions.', TEXT_DOMAIN)),
        'ishf_journeys_participate_button' => array(sprintf(__('Participate: button (%s)', TEXT_DOMAIN), $until), 'text', __('Submit Your Proposal', TEXT_DOMAIN), ''),
        'ishf_journeys_attend_title'       => array(sprintf(__('Participate: title (%s)', TEXT_DOMAIN), $from), 'text', __('Attend the Festival', TEXT_DOMAIN), __('Attendee registration.', TEXT_DOMAIN)),
        'ishf_journeys_attend_button'      => array(sprintf(__('Participate: button (%s)', TEXT_DOMAIN), $from), 'text', __('Get Your Ticket', TEXT_DOMAIN), ''),
        'ishf_journeys_partner_title'  => array(__('Partnerships: title', TEXT_DOMAIN), 'text', __('Become a Partner', TEXT_DOMAIN), __('Opens the partnership form.', TEXT_DOMAIN)),
        'ishf_journeys_partner_button' => array(__('Partnerships: button', TEXT_DOMAIN), 'text', __('Become a Partner', TEXT_DOMAIN), ''),
      ),
    );
  }
endif;

// The page that holds the homepage texts, in the current language (published), or null
if (!function_exists('ishf_home_content_page')):
  function ishf_home_content_page()
  {
    static $page = false;
    if ($page === false) {
      $page = get_page_by_path('sections/hero');
      $page = $page ? ishf_translate_post($page) : null;
      if ($page && $page->post_status !== 'publish') {
        $page = null;
      }
    }
    return $page;
  }
endif;

// A homepage text: saved value, else (title / lead) the page title / excerpt, else the default
if (!function_exists('ishf_home_get')):
  function ishf_home_get($key)
  {
    $page  = ishf_home_content_page();
    $value = $page ? trim((string) get_post_meta($page->ID, $key, true)) : '';
    if ($value === '' && $page && $key === 'ishf_hero_title') {
      $value = trim($page->post_title);
      $value = in_array(strtolower($value), array('hero', ''), true) ? '' : $value; // the page is often just called "Hero"
    }
    if ($value === '' && $page && $key === 'ishf_hero_lead') {
      $value = trim($page->post_excerpt);
    }
    if ($value !== '') {
      return $value;
    }
    foreach (ishf_home_fields() as $fields) {
      if (isset($fields[$key])) {
        return $fields[$key][2];
      }
    }
    return '';
  }
endif;

// Is this the "sections/hero" page (or a translation of it)?
if (!function_exists('ishf_is_home_content_page')):
  function ishf_is_home_content_page($post)
  {
    $post = get_post($post);
    if (!$post || $post->post_type !== 'page') {
      return false;
    }
    $hero = get_page_by_path('sections/hero');
    if (!$hero) {
      return false;
    }
    if ((int) $hero->ID === (int) $post->ID) {
      return true;
    }
    $default_lang = apply_filters('wpml_default_language', null);
    if ($default_lang) {
      $original = (int) apply_filters('wpml_object_id', $post->ID, 'page', false, $default_lang);
      return $original === (int) $hero->ID;
    }
    return false;
  }
endif;


//------------------------------------------------------------------------------
//  Meta box
//------------------------------------------------------------------------------
add_action('add_meta_boxes_page', function ($post) {
  if (ishf_is_home_content_page($post)) {
    add_meta_box('ishf-home-texts', __('Homepage: hero and journeys', TEXT_DOMAIN), 'ishf_home_render_box', 'page', 'normal', 'high');
  }
});

if (!function_exists('ishf_home_render_box')):
  function ishf_home_render_box($post)
  {
    wp_nonce_field('ishf-home-texts', 'ishf-home-texts_wpnonce');
    echo '<p>' . esc_html__('Texts of the homepage in this language. An empty field shows the text in grey.', TEXT_DOMAIN) . '</p>';
    foreach (ishf_home_fields() as $group => $fields) {
      echo '<h3 style="margin:24px 0 8px; padding-top:16px; border-top:1px solid #dcdcde;">' . esc_html($group) . '</h3>';
      foreach ($fields as $key => $f) {
        list($label, $type, $default, $help) = $f;
        $value = (string) get_post_meta($post->ID, $key, true);
        echo '<p style="margin:0 0 14px;"><label for="' . esc_attr($key) . '" style="display:block; font-weight:600; margin-bottom:4px;">' . esc_html($label) . '</label>';
        if ($type === 'textarea') {
          echo '<textarea class="widefat" rows="3" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" placeholder="' . esc_attr($default) . '">' . esc_textarea($value) . '</textarea>';
        } else {
          echo '<input class="widefat" type="' . ($type === 'url' ? 'url' : 'text') . '" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($default) . '" />';
        }
        if ($help !== '') {
          echo '<span class="description" style="display:block; margin-top:4px;">' . esc_html($help) . '</span>';
        }
        echo '</p>';
      }
    }
  }
endif;

add_action('save_post_page', function ($post_id) {
  if (!isset($_POST['ishf-home-texts_wpnonce']) || !wp_verify_nonce($_POST['ishf-home-texts_wpnonce'], 'ishf-home-texts')) {
    return;
  }
  if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || !current_user_can('edit_page', $post_id)) {
    return;
  }
  foreach (ishf_home_fields() as $fields) {
    foreach ($fields as $key => $f) {
      if (!isset($_POST[$key])) {
        continue;
      }
      $raw = wp_unslash($_POST[$key]);
      $value = $f[1] === 'url' ? esc_url_raw(trim($raw)) : ($f[1] === 'textarea' ? sanitize_textarea_field($raw) : sanitize_text_field($raw));
      if ($value !== '') {
        update_post_meta($post_id, $key, $value);
      } else {
        delete_post_meta($post_id, $key);
      }
    }
  }
});
