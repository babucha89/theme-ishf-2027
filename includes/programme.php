<?php
// -----------------------------------------------------------------------------
//  Programme helpers (data layer for the Programme page and the Home teaser)
//  Source of truth: CPT "seminars". A seminar can have up to two sessions
//  (Date / Time #1 and #2); each becomes one entry in the programme.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Parses a "Y-m-d H:i[:s]" custom-field value as Europe/Lisbon time.
 *
 * @param  mixed $value
 * @return DateTimeImmutable|null
 */
function ishf_programme_parse_datetime($value)
{
  $value = is_string($value) ? trim($value) : '';
  if ($value === '') {
    return null;
  }
  try {
    return new DateTimeImmutable($value, new DateTimeZone('Europe/Lisbon'));
  } catch (Exception $e) {
    return null;
  }
}

/**
 * URL of the Programme page in the current language.
 * Looks for a page with slug "programme" (or "programa"); falls back to the
 * seminars archive, which renders the same programme.
 *
 * @return string
 */
function ishf_get_programme_url()
{
  foreach (array('programme', 'programa') as $slug) {
    $page = get_page_by_path($slug);
    if ($page) {
      $page = function_exists('ishf_translate_post') ? ishf_translate_post($page) : $page;
      return get_permalink($page);
    }
  }
  $archive = get_post_type_archive_link('seminars');
  return $archive ? $archive : home_url('/');
}

/**
 * URL of the .ics download for one programme session.
 *
 * @param  array $session Entry from ishf_get_programme_sessions()
 * @return string
 */
function ishf_programme_ics_url(array $session)
{
  $page = get_page_by_path('ics-generator');
  if (!$page || empty($session['start'])) {
    return '';
  }
  $page = function_exists('ishf_translate_post') ? ishf_translate_post($page) : $page;
  return add_query_arg(array(
    'ics_start'    => $session['start']->format('Y-m-d H:i:s'),
    'ics_end'      => $session['end'] ? $session['end']->format('Y-m-d H:i:s') : '',
    'ics_name'     => $session['title'],
    'ics_location' => $session['location'],
    'ics_url'      => $session['url'],
    'ics_alert'    => 15,
  ), get_permalink($page));
}

/**
 * All published seminars as programme sessions, sorted by start time.
 *
 * Each entry: id, title, url, excerpt, start, end (DateTimeImmutable|null),
 * day (Y-m-d or 'tba'), location, themes (slug => name), speakers (id => name),
 * provisional (bool).
 *
 * @return array
 */
function ishf_get_programme_sessions()
{
  static $cache = array();
  $lang = function_exists('ishf_current_language') ? ishf_current_language() : get_locale();
  if (isset($cache[$lang])) {
    return $cache[$lang];
  }

  $query = new WP_Query(array(
    'post_type'           => 'seminars',
    'post_status'         => 'publish',
    'posts_per_page'      => -1,
    'no_found_rows'       => true,
    'ignore_sticky_posts' => true,
  ));

  $sessions = array();
  foreach ($query->posts as $post) {
    $id       = (int) $post->ID;
    $location = (string) get_post_meta($id, 'ishf_cf_event_local', true);

    $themes = array();
    foreach (get_the_category($id) as $cat) {
      if ($cat->slug !== 'uncategorized' && $cat->slug !== 'sem-categoria') {
        $themes[$cat->slug] = $cat->name;
      }
    }

    $speakers = array();
    $related  = (string) get_post_meta($id, 'ishf_cf_attendants_related', true);
    foreach (array_filter(array_map('intval', explode(',', $related))) as $att_id) {
      $att_id = function_exists('ishf_translate_post') ? ishf_translate_post($att_id, 'attendants') : $att_id;
      if (get_post_status($att_id) === 'publish') {
        $speakers[$att_id] = get_the_title($att_id);
      }
    }

    $slots = array(
      array(get_post_meta($id, 'ishf_cf_date_time_1', true), get_post_meta($id, 'ishf_cf_date_end_time_1', true)),
      array(get_post_meta($id, 'ishf_cf_date_time_2', true), get_post_meta($id, 'ishf_cf_date_end_time_2', true)),
    );
    $has_slot = false;
    foreach ($slots as $i => $slot) {
      $start = ishf_programme_parse_datetime($slot[0]);
      if (!$start && ($i > 0 || $has_slot)) {
        continue; // second slot only counts when it has a date
      }
      $has_slot = $has_slot || (bool) $start;
      $sessions[] = array(
        'id'          => $id,
        'title'       => get_the_title($id),
        'url'         => get_permalink($id),
        'excerpt'     => has_excerpt($id) ? get_the_excerpt($id) : '',
        'start'       => $start,
        'end'         => ishf_programme_parse_datetime($slot[1]),
        'day'         => $start ? $start->format('Y-m-d') : 'tba',
        'location'    => $location,
        'themes'      => $themes,
        'speakers'    => $speakers,
        'provisional' => (bool) get_post_meta($id, 'ishf_cf_is_provisional', true),
        'order'       => (int) get_post_meta($id, 'ishf_cf_order', true),
      );
      if (!$start) {
        break; // undated seminar: one "to be announced" entry
      }
    }
  }
  wp_reset_postdata();

  usort($sessions, function ($a, $b) {
    if ($a['day'] === 'tba' || $b['day'] === 'tba') {
      return ($a['day'] === 'tba') <=> ($b['day'] === 'tba');
    }
    return ($a['start'] <=> $b['start']) ?: ($b['order'] <=> $a['order']) ?: strcasecmp($a['title'], $b['title']);
  });

  return $cache[$lang] = $sessions;
}

/**
 * "10:30 - 12:00" (or just the start time).
 *
 * @param  array $session
 * @return string
 */
function ishf_programme_time_label(array $session)
{
  if (empty($session['start'])) {
    return '';
  }
  $label = $session['start']->format('H:i');
  if (!empty($session['end']) && $session['end']->format('Y-m-d') === $session['start']->format('Y-m-d')) {
    $label .= ' - ' . $session['end']->format('H:i');
  }
  return $label;
}

/**
 * "Programme coming soon" panel, shared by the Programme page and the Home
 * block. Shown whenever no seminar is published yet.
 *
 * @param  string $heading_tag h2 or h3
 * @return void
 */
function ishf_programme_coming_soon($heading_tag = 'h3')
{
  $heading_tag = in_array($heading_tag, array('h2', 'h3'), true) ? $heading_tag : 'h3';
  ?>
  <div class="programme-soon">
    <span class="programme-soon-icon" data-uk-icon="icon: calendar; ratio: 2" aria-hidden="true"></span>
    <<?php echo $heading_tag; ?> class="programme-soon-title"><?php esc_html_e('Programme coming soon', TEXT_DOMAIN); ?></<?php echo $heading_tag; ?>>
    <p class="programme-soon-text">
      <?php esc_html_e('We are building three days of sessions, workshops and site visits for 7‑9 June 2027 in Lisbon. The full programme will be published here as soon as it is confirmed.', TEXT_DOMAIN); ?>
    </p>
    <button type="button" class="uk-button uk-button-primary" data-uk-toggle="target: #modal-newsletter" aria-haspopup="dialog" aria-controls="modal-newsletter">
      <?php esc_html_e('Get notified when it is published', TEXT_DOMAIN); ?>
    </button>
  </div>
  <?php
}
