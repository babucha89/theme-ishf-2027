<?php
// -----------------------------------------------------------------------------
//  ABOUT PAGE: "The festival in numbers" fields (back office)
//  A "Festival in numbers" box in the editor of the About page (slug "about"
//  or "about-…", template "About", or a WPML translation of it) with every
//  text of includes/sections/festival-numbers.php: heading, the four big
//  numbers (label, number, unit, text), sources line and button text.
//  - Meta keys: ishf_about_* (listed in ishf_about_fields()).
//  - Translatable per language with WPML (wpml-config.xml, action "translate"),
//    so each language can also use its own number separators (2,400+ / 2400+).
//  - An empty field shows its default (placeholder in the box), taken from
//    languages/*.po in the current language, so the section is never blank.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 18-NOV-2026
// -----------------------------------------------------------------------------

// Field definitions: key => array(label, type, default, description). Groups are the box's sub-headings.
if (!function_exists('ishf_about_fields')):
  function ishf_about_fields()
  {
    $number_help = __('Number: the big figure (e.g. 2,400+). Unit: the word after it.', TEXT_DOMAIN);
    return array(
      __('Heading', TEXT_DOMAIN) => array(
        'numbers_eyebrow' => array(__('Eyebrow', TEXT_DOMAIN), 'text', __('2017 – 2027', TEXT_DOMAIN), ''),
        'numbers_title'   => array(__('Title', TEXT_DOMAIN), 'text', __('The festival in numbers', TEXT_DOMAIN), ''),
        'numbers_intro'   => array(__('Introduction', TEXT_DOMAIN), 'textarea', __('In ten years, ISHF has grown from a Dutch premiere into the global meeting point for social, public and cooperative housing.', TEXT_DOMAIN), ''),
      ),
      __('Number 1', TEXT_DOMAIN) => array(
        'number_1_label' => array(__('Label', TEXT_DOMAIN), 'text', __('Decade impact', TEXT_DOMAIN), ''),
        'number_1_value' => array(__('Number', TEXT_DOMAIN), 'text', __('10', TEXT_DOMAIN), $number_help),
        'number_1_unit'  => array(__('Unit', TEXT_DOMAIN), 'text', __('years', TEXT_DOMAIN), ''),
        'number_1_text'  => array(__('Text', TEXT_DOMAIN), 'textarea', __('From Amsterdam 2017 to Lisbon 2027: six editions in six European cities.', TEXT_DOMAIN), ''),
      ),
      __('Number 2', TEXT_DOMAIN) => array(
        'number_2_label' => array(__('Label', TEXT_DOMAIN), 'text', __('Attendee growth', TEXT_DOMAIN), ''),
        'number_2_value' => array(__('Number', TEXT_DOMAIN), 'text', __('2,400+', TEXT_DOMAIN), $number_help),
        'number_2_unit'  => array(__('Unit', TEXT_DOMAIN), 'text', __('participants', TEXT_DOMAIN), ''),
        'number_2_text'  => array(__('Text', TEXT_DOMAIN), 'textarea', __('In Dublin 2025, almost twice the 1,300 visitors of the first edition.', TEXT_DOMAIN), ''),
      ),
      __('Number 3', TEXT_DOMAIN) => array(
        'number_3_label' => array(__('Label', TEXT_DOMAIN), 'text', __('Event expansion', TEXT_DOMAIN), ''),
        'number_3_value' => array(__('Number', TEXT_DOMAIN), 'text', __('350', TEXT_DOMAIN), $number_help),
        'number_3_unit'  => array(__('Unit', TEXT_DOMAIN), 'text', __('events', TEXT_DOMAIN), ''),
        'number_3_text'  => array(__('Text', TEXT_DOMAIN), 'textarea', __('In Dublin 2025, up from 40 events in Amsterdam 2017.', TEXT_DOMAIN), ''),
      ),
      __('Number 4', TEXT_DOMAIN) => array(
        'number_4_label' => array(__('Label', TEXT_DOMAIN), 'text', __('Global network reach', TEXT_DOMAIN), ''),
        'number_4_value' => array(__('Number', TEXT_DOMAIN), 'text', __('35', TEXT_DOMAIN), $number_help),
        'number_4_unit'  => array(__('Unit', TEXT_DOMAIN), 'text', __('countries', TEXT_DOMAIN), ''),
        'number_4_text'  => array(__('Text', TEXT_DOMAIN), 'textarea', __('Participants from six continents met in Dublin 2025.', TEXT_DOMAIN), ''),
      ),
      __('Footer', TEXT_DOMAIN) => array(
        'numbers_source' => array(__('Sources', TEXT_DOMAIN), 'text', __('Sources: festival reports by Housing Europe and the host organisations.', TEXT_DOMAIN), ''),
        'numbers_button' => array(__('Button text', TEXT_DOMAIN), 'text', __('Join us in Lisbon 2027', TEXT_DOMAIN), __('The button links to the Lisbon 2027 page and is hidden until that page is published.', TEXT_DOMAIN)),
      ),
    );
  }
endif;

// Value of a field for a page: saved value, or the default in the current language
if (!function_exists('ishf_about_get')):
  function ishf_about_get($post_id, $key)
  {
    $value = $post_id ? trim((string) get_post_meta($post_id, 'ishf_about_' . $key, true)) : '';
    if ($value !== '') {
      return $value;
    }
    foreach (ishf_about_fields() as $fields) {
      if (isset($fields[$key])) {
        return $fields[$key][2];
      }
    }
    return '';
  }
endif;

// Is this page the About page (or a translation of it)?
if (!function_exists('ishf_is_about_page')):
  function ishf_is_about_page($post)
  {
    $is_about = function ($p) {
      $slug = urldecode($p->post_name);
      return get_page_template_slug($p) === 'page-about.php' || $slug === 'about' || strpos($slug, 'about-') === 0;
    };
    $post = get_post($post);
    if (!$post || $post->post_type !== 'page') {
      return false;
    }
    if ($is_about($post)) {
      return true;
    }
    // WPML: the original of this translation is the About page
    $default_lang = apply_filters('wpml_default_language', null);
    if ($default_lang) {
      $original = (int) apply_filters('wpml_object_id', $post->ID, 'page', false, $default_lang);
      if ($original && $original !== (int) $post->ID) {
        $orig = get_post($original);
        return $orig && $is_about($orig);
      }
    }
    return false;
  }
endif;


//------------------------------------------------------------------------------
//  Meta box
//------------------------------------------------------------------------------
add_action('add_meta_boxes_page', function ($post) {
  if (ishf_is_about_page($post)) {
    add_meta_box('ishf-about-page', __('Festival in numbers', TEXT_DOMAIN), 'ishf_about_render_box', 'page', 'normal', 'high');
  }
});

if (!function_exists('ishf_about_render_box')):
  function ishf_about_render_box($post)
  {
    wp_nonce_field('ishf-about-page', 'ishf-about-page_wpnonce');
    echo '<p>' . esc_html__('Texts of the "festival in numbers" section in this language. An empty field shows the text in grey.', TEXT_DOMAIN) . '</p>';
    foreach (ishf_about_fields() as $group => $fields) {
      echo '<h3 style="margin:24px 0 8px; padding-top:16px; border-top:1px solid #dcdcde;">' . esc_html($group) . '</h3>';
      foreach ($fields as $key => $f) {
        list($label, $type, $default, $help) = $f;
        $name  = 'ishf_about_' . $key;
        $value = (string) get_post_meta($post->ID, $name, true);
        echo '<p style="margin:0 0 14px;"><label for="' . esc_attr($name) . '" style="display:block; font-weight:600; margin-bottom:4px;">' . esc_html($label) . '</label>';
        if ($type === 'textarea') {
          echo '<textarea class="widefat" rows="3" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" placeholder="' . esc_attr($default) . '">' . esc_textarea($value) . '</textarea>';
        } else {
          echo '<input class="widefat" type="text" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($default) . '" />';
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
  if (!isset($_POST['ishf-about-page_wpnonce']) || !wp_verify_nonce($_POST['ishf-about-page_wpnonce'], 'ishf-about-page')) {
    return;
  }
  if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || !current_user_can('edit_page', $post_id)) {
    return;
  }
  foreach (ishf_about_fields() as $fields) {
    foreach ($fields as $key => $f) {
      $name = 'ishf_about_' . $key;
      if (!isset($_POST[$name])) {
        continue;
      }
      $raw   = wp_unslash($_POST[$name]);
      $value = $f[1] === 'textarea' ? sanitize_textarea_field($raw) : sanitize_text_field($raw);
      if ($value !== '') {
        update_post_meta($post_id, $name, $value);
      } else {
        delete_post_meta($post_id, $name);
      }
    }
  }
});
