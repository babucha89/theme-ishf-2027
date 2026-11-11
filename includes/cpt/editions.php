<?php
// -----------------------------------------------------------------------------
//  CPT: EDITIONS (past festivals: Amsterdam 2017, Lyon 2019, ...)
//  - Name  = title
//  - Logo  = featured image (optional dark-mode logo: field ishf_cf_logo_dark_id)
//  - Dates = ishf_cf_date_start / ishf_cf_date_end (yyyy-mm-dd), shown combined
//  - Venue = ishf_cf_venue + ishf_cf_address; map pin = ishf_cf_geolocation
//            ("lat, lng"), geocoded from the address with Mapbox on save
//  - Body  = post content
//  No single pages: every edition is shown in a modal on the Past Editions page
//  (page-past-editions.php, includes/sections/past-editions.php).
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 11-NOV-2026
// -----------------------------------------------------------------------------

if (!function_exists('ishf_set_cpt_editions')):
  add_action('init', 'ishf_set_cpt_editions');
  function ishf_set_cpt_editions()
  {
    register_post_type('editions', array(
      'labels' => array(
        'name'               => __('Past Editions', TEXT_DOMAIN),
        'singular_name'      => __('Edition', TEXT_DOMAIN),
        'menu_name'          => __('Past Editions', TEXT_DOMAIN),
        'all_items'          => __('All editions', TEXT_DOMAIN),
        'add_new_item'       => __('Add new edition', TEXT_DOMAIN),
        'add_new'            => __('New edition', TEXT_DOMAIN),
        'edit_item'          => __('Edit edition', TEXT_DOMAIN),
        'update_item'        => __('Update edition', TEXT_DOMAIN),
        'search_items'       => __('Search edition', TEXT_DOMAIN),
        'not_found'          => __('Not Found', TEXT_DOMAIN),
        'not_found_in_trash' => __('Not found in Trash', TEXT_DOMAIN),
        'featured_image'     => __('Edition logo', TEXT_DOMAIN),
        'set_featured_image' => __('Set edition logo', TEXT_DOMAIN),
        'remove_featured_image' => __('Remove edition logo', TEXT_DOMAIN),
        'use_featured_image' => __('Use as edition logo', TEXT_DOMAIN),
      ),
      'public'              => false, // content lives in the modals of the Past Editions page
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_rest'        => true,  // block editor
      'menu_position'       => 36,
      'menu_icon'           => 'dashicons-backup',
      'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields'),
      'has_archive'         => false,
      'rewrite'             => false,
    ));
  }
endif;


//------------------------------------------------------------------------------
//  Data helpers
//------------------------------------------------------------------------------

// All published editions, oldest first (editions without a start date go last)
if (!function_exists('ishf_get_editions')):
  function ishf_get_editions($order = 'ASC')
  {
    $posts = get_posts(array(
      'post_type'        => 'editions',
      'post_status'      => 'publish',
      'numberposts'      => -1,
      'suppress_filters' => false, // WPML: current language only
    ));
    usort($posts, function ($a, $b) use ($order) {
      $da = (string) get_post_meta($a->ID, 'ishf_cf_date_start', true);
      $db = (string) get_post_meta($b->ID, 'ishf_cf_date_start', true);
      if ($da === '' || $db === '') {
        return ($da === '') - ($db === '');
      }
      return strtoupper($order) === 'DESC' ? strcmp($db, $da) : strcmp($da, $db);
    });
    return $posts;
  }
endif;

// Dates of an edition: array('label' => '9–11 June 2025', 'start' => 'Y-m-d', 'end' => 'Y-m-d', 'year' => '2025') or null
if (!function_exists('ishf_edition_dates')):
  function ishf_edition_dates($post_id)
  {
    $start = (string) get_post_meta($post_id, 'ishf_cf_date_start', true);
    $end   = (string) get_post_meta($post_id, 'ishf_cf_date_end', true);
    if ($start === '') {
      return null;
    }
    if ($end === '' || $end < $start) {
      $end = $start;
    }
    $tz = new DateTimeZone('UTC');
    $s  = DateTime::createFromFormat('!Y-m-d', $start, $tz);
    $e  = DateTime::createFromFormat('!Y-m-d', $end, $tz);
    if (!$s || !$e) {
      return null;
    }

    /* translators: PHP date formats used for edition dates. Full date, e.g. "9 June 2025" */
    $full = __('j F Y', TEXT_DOMAIN);
    /* translators: PHP date format: day and month, e.g. "28 June" (start of a range across two months) */
    $day_month = __('j F', TEXT_DOMAIN);
    $day = 'j';

    $sts = $s->getTimestamp();
    $ets = $e->getTimestamp();
    if ($start === $end) {
      $label = wp_date($full, $sts, $tz);
    } elseif ($s->format('Y-m') === $e->format('Y-m')) {
      $label = wp_date($day, $sts, $tz) . '–' . wp_date($full, $ets, $tz);
    } elseif ($s->format('Y') === $e->format('Y')) {
      $label = wp_date($day_month, $sts, $tz) . ' – ' . wp_date($full, $ets, $tz);
    } else {
      $label = wp_date($full, $sts, $tz) . ' – ' . wp_date($full, $ets, $tz);
    }

    return array('label' => $label, 'start' => $start, 'end' => $end, 'year' => $s->format('Y'));
  }
endif;

// Map pin of an edition: array('lat' => float, 'lng' => float) or null
if (!function_exists('ishf_edition_coordinates')):
  function ishf_edition_coordinates($post_id)
  {
    $raw = (string) get_post_meta($post_id, 'ishf_cf_geolocation', true);
    if (!preg_match('/^\s*(-?\d+(?:\.\d+)?)\s*[,;]\s*(-?\d+(?:\.\d+)?)\s*$/', $raw, $m)) {
      return null;
    }
    $lat = (float) $m[1];
    $lng = (float) $m[2];
    if (abs($lat) > 90 || abs($lng) > 180) {
      return null;
    }
    return array('lat' => $lat, 'lng' => $lng);
  }
endif;

// URL of the Past Editions page in the current language (template "Past Editions" or slug "past-editions"), or ''
if (!function_exists('ishf_get_past_editions_page_url')):
  function ishf_get_past_editions_page_url()
  {
    $pages = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'page-past-editions.php', 'number' => 1));
    $page  = !empty($pages) ? $pages[0] : get_page_by_path('past-editions');
    if (!$page || $page->post_status !== 'publish') {
      return '';
    }
    $page = function_exists('ishf_translate_post') ? ishf_translate_post($page, 'page') : $page;
    return get_permalink($page);
  }
endif;


//------------------------------------------------------------------------------
//  Mapbox: place the address on the map when the edition is saved
//  - Coordinates empty                       -> geocode the address
//  - Address changed and the pin was automatic -> geocode again
//  - Coordinates typed by hand are kept
//  Needs SITE_MAPBOX_TOKEN (functions.php). The token's URL restrictions are met
//  by sending the site URL as Referer.
//------------------------------------------------------------------------------
if (!function_exists('ishf_editions_geocode_on_save')):
  // 'save_post' (not 'save_post_editions', which fires earlier): runs after the custom fields are saved (priority 1)
  add_action('save_post', 'ishf_editions_geocode_on_save', 20, 2);
  function ishf_editions_geocode_on_save($post_id, $post)
  {
    if ($post->post_type !== 'editions') {
      return;
    }
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id)) {
      return;
    }
    $address = trim((string) get_post_meta($post_id, 'ishf_cf_address', true));
    $coords  = trim((string) get_post_meta($post_id, 'ishf_cf_geolocation', true));
    $last_address = (string) get_post_meta($post_id, '_ishf_geocoded_address', true);
    $last_coords  = (string) get_post_meta($post_id, '_ishf_geocoded_coords', true);

    if ($address === '') {
      return;
    }
    $needs = ($coords === '') || ($address !== $last_address && $coords === $last_coords);
    if (!$needs) {
      return;
    }
    if (!defined('SITE_MAPBOX_TOKEN') || SITE_MAPBOX_TOKEN === '') {
      set_transient('ishf_editions_notice_' . get_current_user_id(), 'no-token', 60);
      return;
    }

    $url = add_query_arg(array(
      'q'            => rawurlencode($address),
      'limit'        => 1,
      'access_token' => rawurlencode(SITE_MAPBOX_TOKEN),
    ), 'https://api.mapbox.com/search/geocode/v6/forward');

    $response = wp_remote_get($url, array('timeout' => 8, 'headers' => array('Referer' => home_url('/'))));
    $data     = is_wp_error($response) ? null : json_decode(wp_remote_retrieve_body($response), true);
    $point    = $data['features'][0]['geometry']['coordinates'] ?? null; // [lng, lat]

    if (!is_array($point) || count($point) < 2) {
      set_transient('ishf_editions_notice_' . get_current_user_id(), 'not-found', 60);
      return;
    }
    $value = round((float) $point[1], 6) . ', ' . round((float) $point[0], 6);
    update_post_meta($post_id, 'ishf_cf_geolocation', $value);
    update_post_meta($post_id, '_ishf_geocoded_address', $address);
    update_post_meta($post_id, '_ishf_geocoded_coords', $value);
  }
endif;

// Notice after saving when the address could not be placed on the map
if (!function_exists('ishf_editions_geocode_notice')):
  add_action('admin_notices', 'ishf_editions_geocode_notice');
  function ishf_editions_geocode_notice()
  {
    $key  = 'ishf_editions_notice_' . get_current_user_id();
    $code = get_transient($key);
    if (!$code) {
      return;
    }
    delete_transient($key);
    $message = $code === 'no-token'
      ? __('The map pin was not placed: the Mapbox token (SITE_MAPBOX_TOKEN) is not set. Type the coordinates by hand or add the token.', TEXT_DOMAIN)
      : __('The address could not be found on the map. Check the address or type the coordinates by hand.', TEXT_DOMAIN);
    echo '<div class="notice notice-warning is-dismissible"><p>' . esc_html($message) . '</p></div>';
  }
endif;


//------------------------------------------------------------------------------
//  Admin list: logo, dates and venue columns; sorted by start date
//------------------------------------------------------------------------------
add_filter('manage_editions_posts_columns', function ($columns) {
  $new = array();
  foreach ($columns as $key => $label) {
    if ($key === 'title') {
      $new['logo'] = __('Logo', TEXT_DOMAIN);
    }
    if ($key === 'date') {
      $new['edition_dates'] = __('Dates', TEXT_DOMAIN);
      $new['edition_venue'] = __('Venue', TEXT_DOMAIN);
    }
    $new[$key] = $label;
  }
  return $new;
});

add_action('manage_editions_posts_custom_column', function ($column, $post_id) {
  switch ($column) {
    case 'logo':
      echo get_the_post_thumbnail($post_id, array(60, 60), array('style' => 'width:auto; max-width:60px; height:auto; max-height:60px;'));
      break;
    case 'edition_dates':
      $dates = ishf_edition_dates($post_id);
      echo $dates ? esc_html($dates['label']) : '—';
      break;
    case 'edition_venue':
      $venue = (string) get_post_meta($post_id, 'ishf_cf_venue', true);
      echo $venue !== '' ? esc_html($venue) : '—';
      echo ishf_edition_coordinates($post_id) ? ' <span class="dashicons dashicons-location" title="' . esc_attr__('On the map', TEXT_DOMAIN) . '"></span>' : '';
      break;
  }
}, 10, 2);

add_action('pre_get_posts', function ($query) {
  if (is_admin() && $query->is_main_query() && $query->get('post_type') === 'editions' && !$query->get('orderby')) {
    // Named clause so editions without a start date still show (at the end)
    $query->set('meta_query', array(
      'relation'   => 'OR',
      'date_start' => array('key' => 'ishf_cf_date_start', 'compare' => 'EXISTS'),
      array('key' => 'ishf_cf_date_start', 'compare' => 'NOT EXISTS'),
    ));
    $query->set('orderby', array('date_start' => 'DESC', 'title' => 'ASC'));
  }
});
