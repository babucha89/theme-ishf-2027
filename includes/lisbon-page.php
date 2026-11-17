<?php
// -----------------------------------------------------------------------------
//  LISBON 2027 PAGE: content fields (back office)
//  A "Lisbon 2027 page" box in the editor of the Lisbon 2027 page (slug
//  "lisbon-2027", template "Lisbon 2027", or a WPML translation of it) with
//  every text of page-lisbon-2027.php that is not the title, excerpt or body:
//  header facts, section titles and the three venue cards.
//  - Meta keys: ishf_lisbon_* (listed in ishf_lisbon_fields()).
//  - Translatable per language with WPML (wpml-config.xml, action "translate").
//  - An empty field shows its default (placeholder in the box), taken from
//    languages/*.po in the current language, so the page is never blank.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 17-NOV-2026
// -----------------------------------------------------------------------------

// Field definitions: key => array(label, type, default, description). Groups are the box's sub-headings.
if (!function_exists('ishf_lisbon_fields')):
  function ishf_lisbon_fields()
  {
    return array(
      __('Header', TEXT_DOMAIN) => array(
        'eyebrow'  => array(__('Eyebrow', TEXT_DOMAIN), 'text', __('Current edition · ISHF 2027', TEXT_DOMAIN), __('Small text above the title. The title is the page title; the lead under the facts is the page Excerpt.', TEXT_DOMAIN)),
        'dates'    => array(__('Dates', TEXT_DOMAIN), 'text', __('7 – 9 June 2027', TEXT_DOMAIN), ''),
        'location' => array(__('Location', TEXT_DOMAIN), 'text', __('Centro de Congressos de Lisboa · Lisbon, Portugal', TEXT_DOMAIN), ''),
      ),
      __('Organisers and partners', TEXT_DOMAIN) => array(
        'org_eyebrow' => array(__('Eyebrow', TEXT_DOMAIN), 'text', __('Who makes it happen', TEXT_DOMAIN), ''),
        'org_title'   => array(__('Title', TEXT_DOMAIN), 'text', __('Organisers and partners', TEXT_DOMAIN), __('The logos come from Sponsors, one row per Sponsor Type (Order field: higher numbers first).', TEXT_DOMAIN)),
        'org_intro'   => array(__('Introduction (optional)', TEXT_DOMAIN), 'textarea', '', __('Shown under the title. Leave empty for none.', TEXT_DOMAIN)),
      ),
      __('Venue & practical info', TEXT_DOMAIN) => array(
        'venue_eyebrow' => array(__('Eyebrow', TEXT_DOMAIN), 'text', __('Plan your visit', TEXT_DOMAIN), ''),
        'venue_title'   => array(__('Title', TEXT_DOMAIN), 'text', __('Venue & practical info', TEXT_DOMAIN), ''),
      ),
      __('Card 1: primary venue', TEXT_DOMAIN) => array(
        'venue_card_title' => array(__('Card title', TEXT_DOMAIN), 'text', __('Primary venue', TEXT_DOMAIN), ''),
        'venue_name'       => array(__('Venue name', TEXT_DOMAIN), 'text', __('Centro de Congressos de Lisboa', TEXT_DOMAIN), ''),
        'venue_address'    => array(__('Address', TEXT_DOMAIN), 'text', __('Praça das Indústrias, 1300-307 Lisboa, Portugal', TEXT_DOMAIN), ''),
        'venue_text'       => array(__('Text', TEXT_DOMAIN), 'textarea', __('The festival takes place at the Lisbon Congress Centre, by the river Tagus. Its Pavilhão do Rio is the central exhibition area: 3,800 m² with room for 2,000 people.', TEXT_DOMAIN), ''),
        'venue_map_url'    => array(__('Map link (optional)', TEXT_DOMAIN), 'url', '', __('Leave empty to search the venue name and address on Google Maps.', TEXT_DOMAIN)),
      ),
      __('Card 2: transport & participant support', TEXT_DOMAIN) => array(
        'transport_title' => array(__('Card title', TEXT_DOMAIN), 'text', __('Transport & participant support', TEXT_DOMAIN), ''),
        'transport_text'  => array(__('Text', TEXT_DOMAIN), 'textarea', __('Lisbon\'s buses, trams, metro and trains connect the city centre and the airport with the venue. Travel tips, transport passes for participants and the help desk opening hours will be published here before the festival.', TEXT_DOMAIN), ''),
      ),
      __('Card 3: venue accessibility & transport model', TEXT_DOMAIN) => array(
        'access_title' => array(__('Card title', TEXT_DOMAIN), 'text', __('Venue accessibility & transport model', TEXT_DOMAIN), ''),
        'access_text'  => array(__('Text', TEXT_DOMAIN), 'textarea', __('We encourage everyone to reach the festival by public transport, on foot or by bike. Information on step-free routes, accessible facilities and assistance on request will be published here; if you have specific needs, contact the organisation in advance.', TEXT_DOMAIN), ''),
      ),
    );
  }
endif;

// Value of a field for a page: saved value, or the default in the current language
if (!function_exists('ishf_lisbon_get')):
  function ishf_lisbon_get($post_id, $key)
  {
    $value = trim((string) get_post_meta($post_id, 'ishf_lisbon_' . $key, true));
    if ($value !== '') {
      return $value;
    }
    foreach (ishf_lisbon_fields() as $fields) {
      if (isset($fields[$key])) {
        return $fields[$key][2];
      }
    }
    return '';
  }
endif;

// Is this page the Lisbon 2027 page (or a translation of it)?
if (!function_exists('ishf_is_lisbon_page')):
  function ishf_is_lisbon_page($post)
  {
    $post = get_post($post);
    if (!$post || $post->post_type !== 'page') {
      return false;
    }
    if (get_page_template_slug($post) === 'page-lisbon-2027.php' || $post->post_name === 'lisbon-2027') {
      return true;
    }
    // WPML: the original of this translation is the Lisbon 2027 page
    $default_lang = apply_filters('wpml_default_language', null);
    if ($default_lang) {
      $original = (int) apply_filters('wpml_object_id', $post->ID, 'page', false, $default_lang);
      if ($original && $original !== (int) $post->ID) {
        $orig = get_post($original);
        return $orig && ($orig->post_name === 'lisbon-2027' || get_page_template_slug($orig) === 'page-lisbon-2027.php');
      }
    }
    return false;
  }
endif;


//------------------------------------------------------------------------------
//  Meta box
//------------------------------------------------------------------------------
add_action('add_meta_boxes_page', function ($post) {
  if (ishf_is_lisbon_page($post)) {
    add_meta_box('ishf-lisbon-page', __('Lisbon 2027 page', TEXT_DOMAIN), 'ishf_lisbon_render_box', 'page', 'normal', 'high');
  }
});

if (!function_exists('ishf_lisbon_render_box')):
  function ishf_lisbon_render_box($post)
  {
    wp_nonce_field('ishf-lisbon-page', 'ishf-lisbon-page_wpnonce');
    echo '<p>' . esc_html__('Texts of this page in this language. An empty field shows the text in grey.', TEXT_DOMAIN) . '</p>';
    foreach (ishf_lisbon_fields() as $group => $fields) {
      echo '<h3 style="margin:24px 0 8px; padding-top:16px; border-top:1px solid #dcdcde;">' . esc_html($group) . '</h3>';
      foreach ($fields as $key => $f) {
        list($label, $type, $default, $help) = $f;
        $name  = 'ishf_lisbon_' . $key;
        $value = (string) get_post_meta($post->ID, $name, true);
        echo '<p style="margin:0 0 14px;"><label for="' . esc_attr($name) . '" style="display:block; font-weight:600; margin-bottom:4px;">' . esc_html($label) . '</label>';
        if ($type === 'textarea') {
          echo '<textarea class="widefat" rows="4" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" placeholder="' . esc_attr($default) . '">' . esc_textarea($value) . '</textarea>';
        } else {
          echo '<input class="widefat" type="' . ($type === 'url' ? 'url' : 'text') . '" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($default) . '" />';
        }
        if ($help !== '') {
          echo '<span class="description" style="display:block; margin-top:4px;">' . esc_html($help) . '</span>';
        }
        if ($type === 'textarea') {
          echo '<span class="description" style="display:block; margin-top:4px;">' . esc_html__('Simple HTML (links, bold) allowed; a blank line starts a new paragraph.', TEXT_DOMAIN) . '</span>';
        }
        echo '</p>';
      }
    }
  }
endif;

add_action('save_post_page', function ($post_id) {
  if (!isset($_POST['ishf-lisbon-page_wpnonce']) || !wp_verify_nonce($_POST['ishf-lisbon-page_wpnonce'], 'ishf-lisbon-page')) {
    return;
  }
  if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || !current_user_can('edit_page', $post_id)) {
    return;
  }
  foreach (ishf_lisbon_fields() as $fields) {
    foreach ($fields as $key => $f) {
      $name = 'ishf_lisbon_' . $key;
      if (!isset($_POST[$name])) {
        continue;
      }
      $raw = wp_unslash($_POST[$name]);
      switch ($f[1]) {
        case 'textarea':
          $value = trim(wp_kses_post($raw));
          break;
        case 'url':
          $value = esc_url_raw(trim($raw));
          break;
        default:
          $value = sanitize_text_field($raw);
      }
      if ($value !== '') {
        update_post_meta($post_id, $name, $value);
      } else {
        delete_post_meta($post_id, $name);
      }
    }
  }
});
