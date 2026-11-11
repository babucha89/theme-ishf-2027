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
 * Finds every block called $name in a block tree (also inside groups/columns).
 *
 * @param  array  $blocks parse_blocks() output
 * @param  string $name   e.g. 'ishf/seminar-program'
 * @return array
 */
function ishf_programme_find_blocks(array $blocks, $name)
{
  $found = array();
  foreach ($blocks as $block) {
    if (($block['blockName'] ?? '') === $name) {
      $found[] = $block;
    }
    if (!empty($block['innerBlocks'])) {
      $found = array_merge($found, ishf_programme_find_blocks($block['innerBlocks'], $name));
    }
  }
  return $found;
}

/**
 * Attendants taking part in a seminar, from BOTH places they can be set:
 * the "Associated Attendants" field and the speakers / moderators of the
 * ISHF Seminar Program block.
 *
 * Returns array(
 *   'people' => array(attendant_id => name),
 *   'panels' => array(attendant_id => array(array('time', 'title', 'role'), ...)),
 * ) with IDs already in the current language (WPML).
 *
 * @param  int $post_id Seminar ID
 * @return array
 */
function ishf_programme_seminar_people($post_id)
{
  $people = array();
  $panels = array();
  $tr = function ($id) {
    return function_exists('ishf_translate_post') ? (int) ishf_translate_post((int) $id, 'attendants') : (int) $id;
  };
  $add = function ($id) use (&$people, $tr) {
    $id = $tr($id);
    if ($id > 0 && get_post_status($id) === 'publish') {
      $people[$id] = get_the_title($id);
      return $id;
    }
    return 0;
  };

  // -- 1) "Associated Attendants" field
  foreach (array_filter(array_map('intval', explode(',', (string) get_post_meta($post_id, 'ishf_cf_attendants_related', true)))) as $id) {
    $add($id);
  }

  // -- 2) ISHF Seminar Program block(s)
  $post = get_post($post_id);
  if ($post && has_block('ishf/seminar-program', $post)) {
    foreach (ishf_programme_find_blocks(parse_blocks($post->post_content), 'ishf/seminar-program') as $block) {
      foreach ((array) ($block['attrs']['sessions'] ?? array()) as $session) {
        foreach (array('speakers' => 'speaker', 'moderator' => 'moderator') as $key => $role) {
          foreach ((array) ($session[$key] ?? array()) as $person) {
            $id = $add((int) ($person['id'] ?? 0));
            if ($id) {
              $panels[$id][] = array(
                'time'  => (string) ($session['time'] ?? ''),
                'title' => (string) ($session['keynote'] ?? ''),
                'role'  => $role,
              );
            }
          }
        }
      }
    }
  }

  asort($people);
  return array('people' => $people, 'panels' => $panels);
}

/**
 * Sessions (seminars) an attendant takes part in, grouped by seminar.
 * Each entry: id, title, url, slots (list of sessions with a start), panels
 * (time/title/role of the attendant inside the ISHF Seminar Program block).
 *
 * @param  int $attendant_id Attendant ID (current language)
 * @return array
 */
function ishf_get_attendant_sessions($attendant_id)
{
  $attendant_id = (int) $attendant_id;
  $out = array();
  foreach (ishf_get_programme_sessions() as $s) {
    if (!isset($s['speakers'][$attendant_id])) {
      continue;
    }
    if (!isset($out[$s['id']])) {
      $out[$s['id']] = array(
        'id'     => $s['id'],
        'title'  => $s['title'],
        'url'    => $s['url'],
        'slots'  => array(),
        'panels' => isset($s['panels'][$attendant_id]) ? $s['panels'][$attendant_id] : array(),
        'first'  => $s['start'],
      );
    }
    if ($s['start']) {
      $out[$s['id']]['slots'][] = $s;
    }
  }
  return array_values($out);
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

    $people   = ishf_programme_seminar_people($id);
    $speakers = $people['people'];
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
        'panels'      => $people['panels'],
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
 * "10:30 - 12:00". When the end falls on another day the end date is added
 * ("10:30 - 8 Jun 12:00"). Only the start time when there is no end.
 *
 * @param  array $session
 * @return string
 */
function ishf_programme_time_label(array $session)
{
  if (empty($session['start'])) {
    return '';
  }
  $tz    = new DateTimeZone('Europe/Lisbon');
  $label = $session['start']->format('H:i');
  if (!empty($session['end'])) {
    $label .= ' - ';
    if ($session['end']->format('Y-m-d') !== $session['start']->format('Y-m-d')) {
      $label .= wp_date('j M', $session['end']->getTimestamp(), $tz) . ' ';
    }
    $label .= $session['end']->format('H:i');
  }
  return $label;
}

/**
 * "Mon 7 Jun, 09:30 - 11:00" (day + start and end time).
 *
 * @param  array $session
 * @return string
 */
function ishf_programme_when_label(array $session)
{
  if (empty($session['start'])) {
    return __('Date to be announced', TEXT_DOMAIN);
  }
  $day = wp_date('D j M', $session['start']->getTimestamp(), new DateTimeZone('Europe/Lisbon'));
  return $day . ', ' . ishf_programme_time_label($session);
}

/**
 * Sessions shown in the Home teaser: the first session of each day (up to $limit
 * days, chronological), each with its day number ("day_number" = position of the
 * day in the whole programme). Undated sessions are only used when nothing is dated.
 *
 * @param  array $sessions Output of ishf_get_programme_sessions() (already sorted)
 * @param  int   $limit
 * @return array
 */
function ishf_programme_teaser_sessions(array $sessions, $limit = 3)
{
  $days = array();
  foreach ($sessions as $s) {
    if ($s['day'] !== 'tba' && !isset($days[$s['day']])) {
      $days[$s['day']] = $s;
    }
  }
  if (!$days) {
    return array_slice($sessions, 0, (int) $limit);
  }
  $out = array();
  $n   = 0;
  foreach ($days as $s) {
    $n++;
    $s['day_number'] = $n;
    $out[]           = $s;
  }
  return array_slice($out, 0, (int) $limit);
}

/**
 * Home panel of the programme. Same size and layout in both states:
 *  - no seminar published: "Programme coming soon" + newsletter button, dashed border;
 *  - seminars published: one card per day (time, title, "Know more" to the session).
 *
 * @param  string $heading_tag h2 or h3
 * @param  array  $sessions    Sessions to list (empty = coming soon)
 * @param  string $url         Programme URL (live state)
 * @return void
 */
function ishf_programme_panel($heading_tag = 'h3', array $sessions = array(), $url = '')
{
  $heading_tag = in_array($heading_tag, array('h2', 'h3'), true) ? $heading_tag : 'h3';
  $live        = !empty($sessions);
  ?>
  <?php if ($live): ?>
  <div class="programme-live">
    <p class="programme-live-eyebrow"><?php esc_html_e('Programme', TEXT_DOMAIN); ?></p>
    <<?php echo $heading_tag; ?> class="programme-live-title"><?php esc_html_e('Full Event', TEXT_DOMAIN); ?></<?php echo $heading_tag; ?>>
    <ul class="programme-live-grid">
      <?php foreach ($sessions as $s): ?>
        <li class="programme-live-card">
          <h3 class="programme-live-session"><?php echo esc_html($s['title']); ?></h3>
          <?php if ($s['start']):
            $tz        = new DateTimeZone('Europe/Lisbon');
            $weekday   = wp_date('D', $s['start']->getTimestamp(), $tz);
            $day_label = !empty($s['day_number'])
              /* translators: 1: day number, 2: weekday abbreviation, e.g. "Day 1 - Mon" */
              ? sprintf(__('Day %1$d - %2$s', TEXT_DOMAIN), (int) $s['day_number'], $weekday)
              : $weekday;
            ?>
            <p class="programme-live-day"><?php echo esc_html($day_label); ?></p>
            <p class="programme-live-time"><?php echo esc_html(ishf_programme_time_label($s)); ?></p>
          <?php else: ?>
            <p class="programme-live-day"><?php esc_html_e('Date to be announced', TEXT_DOMAIN); ?></p>
          <?php endif; ?>
          <a class="uk-button uk-button-primary" href="<?php echo esc_url(!empty($s['url']) ? $s['url'] : $url); ?>">
            <?php esc_html_e('Know more', TEXT_DOMAIN); ?>
            <span class="uk-hidden-visually"> – <?php echo esc_html($s['title']); ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php else: ?>
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
  <?php endif; ?>
  <?php
}

/** Backwards-compatible alias (empty state only). */
function ishf_programme_coming_soon($heading_tag = 'h3')
{
  ishf_programme_panel($heading_tag);
}
