<?php
// -----------------------------------------------------------------------------
//  Block: ISHF SEMINAR PROGRAM
//  Edited: 3L, 23-JUL-2026
// -----------------------------------------------------------------------------
//  A program block with repeatable sessions. Each session has a time slot,
//  a keynote title, and speaker names (autocomplete from CPT attendants).
//  Supports render modes: full | morning | afternoon.
// -----------------------------------------------------------------------------


//--------------------------------------------
//  REST API: Attendants autocomplete endpoint
//--------------------------------------------
add_action('rest_api_init', 'ishf_seminar_register_attendants_endpoint');
function ishf_seminar_register_attendants_endpoint()
{
  register_rest_route('ishf/v1', '/attendants', array(
    'methods' => 'GET',
    'callback' => 'ishf_seminar_get_attendants',
    'permission_callback' => function () {
      return current_user_can('edit_posts');
    },
    'args' => array(
      'search' => array(
        'required' => false,
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
      ),
    ),
  ));
}

function ishf_seminar_get_attendants($request)
{
  $search = $request->get_param('search');

  $args = array(
    'post_type' => 'attendants',
    'posts_per_page' => 50,
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
    'no_found_rows' => true,
  );
  if (!empty($search)) {
    $args['s'] = $search;
  }

  $query = new WP_Query($args);
  $results = array();
  foreach ($query->posts as $post) {
    $results[] = array(
      'id' => (int) $post->ID,
      'name' => get_the_title($post),
      'ishf_cf_function' => (string) get_post_meta($post->ID, 'ishf_cf_function', true),
      'ishf_cf_organization' => (string) get_post_meta($post->ID, 'ishf_cf_organization', true),
    );
  }
  wp_reset_postdata();

  return rest_ensure_response($results);
}


//--------------------------------------------
//  Helper: Render one speaker / moderator (face, name, function, organisation)
//--------------------------------------------
function ishf_seminar_format_speaker($speaker, $isModerator = false)
{
  $id = intval($speaker['id'] ?? 0);
  $name = trim((string) ($speaker['name'] ?? ''));
  $function = '';
  $organization = '';
  $permalink = '';
  $image_url = '';

  if ($id > 0) {
    // -- Linked attendant (CPT): current-language version when WPML is active
    $id = function_exists('ishf_translate_post') ? ishf_translate_post($id, 'attendants') : $id;
    if (get_post_status($id) === 'publish') {
      $name = get_the_title($id);
      $function = (string) get_post_meta($id, 'ishf_cf_function', true);
      $organization = (string) get_post_meta($id, 'ishf_cf_organization', true);
      $permalink = get_permalink($id);
      $image_url = (string) get_the_post_thumbnail_url($id, 'image-speaker-face');
      if ($image_url === '') {
        $gender = get_post_meta($id, 'ishf_cf_gender', true) === 'female' ? 'female' : 'male';
        $image_url = CHILD_BASE_IMAGES . 'attendant-placeholder-' . $gender . '.jpg';
      }
    } else {
      $id = 0;
    }
  }

  if ($id === 0) {
    // -- Manual entry: "Name, role, organisation" (first part is the name)
    $parts = array_map('trim', explode(',', $name, 2));
    $name = $parts[0];
    $function = $parts[1] ?? '';
  }

  if ($name === '') {
    return '';
  }

  $details = implode(', ', array_filter(array($function, $organization)));
  $initials = '';
  foreach (array_slice(preg_split('/\s+/', $name), 0, 2) as $word) {
    $initials .= mb_strtoupper(mb_substr($word, 0, 1));
  }

  $tag = $permalink !== '' ? 'a' : 'div';
  $out = '<' . $tag . ' class="ishf-speaker' . ($isModerator ? ' ishf-speaker--moderator' : '') . '"' . ($permalink !== '' ? ' href="' . esc_url($permalink) . '"' : '') . '>';
  $out .= '<span class="ishf-speaker__face">';
  if ($image_url !== '') {
    $out .= '<img src="' . esc_url($image_url) . '" alt="" loading="lazy" width="96" height="96" />';
  } else {
    $out .= '<span class="ishf-speaker__initials" aria-hidden="true">' . esc_html($initials) . '</span>';
  }
  $out .= '</span>';
  $out .= '<span class="ishf-speaker__text"><strong class="ishf-speaker__name">' . esc_html($name) . '</strong>';
  if ($details !== '') {
    $out .= '<span class="ishf-speaker__details">' . esc_html($details) . '</span>';
  }
  $out .= '</span></' . $tag . '>';
  return $out;
}

//--------------------------------------------
//  Helper: Render a single session HTML
//--------------------------------------------
function ishf_seminar_render_session($session) {
  $time = esc_html($session['time'] ?? '');
  $keynote = esc_html($session['keynote'] ?? '');
  $moderator = $session['moderator'] ?? array();
  $speakers = $session['speakers'] ?? array();

  $output = '<li class="ishf-session">';
  $output .= '<div class="ishf-session__time"><time>' . $time . '</time></div>';
  $output .= '<div class="ishf-session__body">';
  $output .= '<h3 class="ishf-session__title">' . $keynote . '</h3>';

  // -- Moderator
  if (!empty($moderator)):
    $output .= '<div class="ishf-session__group"><p class="ishf-session__label">' . esc_html__('Moderation', TEXT_DOMAIN) . '</p><div class="ishf-speakers">';
    foreach ($moderator as $mod):
      $output .= ishf_seminar_format_speaker($mod, true);
    endforeach;
    $output .= '</div></div>';
  endif;

  // -- Speakers
  if (!empty($speakers)):
    if (!isset($session['speakersAlphabetical']) || $session['speakersAlphabetical'] !== false) {
      usort($speakers, function ($a, $b) {
        return strcasecmp($a['name'] ?? '', $b['name'] ?? '');
      });
    }
    $output .= '<div class="ishf-session__group"><p class="ishf-session__label">' . esc_html__('Speakers', TEXT_DOMAIN) . '</p><div class="ishf-speakers">';
    foreach ($speakers as $speaker):
      $output .= ishf_seminar_format_speaker($speaker);
    endforeach;
    $output .= '</div></div>';
  endif;

  $output .= '</div></li>';

  return $output;
}

//--------------------------------------------
//  Function that renders the block
//--------------------------------------------
function render_ishf_seminar_program($attributes, $content)
{
  $sessions = $attributes['sessions'] ?? array();
  $render_mode = $attributes['renderMode'] ?? 'full';
  $parts = $attributes['parts'] ?? array(
    array('id' => 'part-1', 'label' => 'Manhã', 'limit' => '13:00'),
    array('id' => 'part-2', 'label' => 'Tarde', 'limit' => '24:00')
  );

  if (empty($sessions)) {
    return '';
  }

  // If not 'split', render as normal seminar
  if ($render_mode !== 'split') {
    $sessions_to_render = array();
    foreach ($sessions as $session) {
      if ($render_mode === 'morning') {
        $hour = intval(explode(':', $session['time'] ?? '00:00')[0]);
        if ($hour < 13) $sessions_to_render[] = $session;
      } elseif ($render_mode === 'afternoon') {
        $hour = intval(explode(':', $session['time'] ?? '00:00')[0]);
        if ($hour >= 13) $sessions_to_render[] = $session;
      } else {
        $sessions_to_render[] = $session;
      }
    }

    $output = '<ul class="ishf-sessions">';
    foreach ($sessions_to_render as $session) {
      $output .= ishf_seminar_render_session($session);
    }
    $output .= '</ul>';
    return $output;
  }

  // -- 'split' logic: Group sessions by part
  // Ensure there's at least one part
  if (empty($parts)) {
    $parts = array(array('id' => 'default', 'label' => 'Program', 'limit' => '24:00'));
  }

  $grouped_sessions = array();
  foreach ($parts as $part) {
    $grouped_sessions[$part['id']] = array();
  }

  foreach ($sessions as $session) {
    $assigned_part_id = null;

    if (!empty($session['partId'])) {
      // Manual assignment
      $assigned_part_id = $session['partId'];
    } else {
      // Auto assignment by time
      $session_time = $session['time'] ?? '00:00';
      $session_hour = floatval(str_replace(':', '.', $session_time));

      foreach ($parts as $part) {
        $limit_hour = floatval(str_replace(':', '.', $part['limit'] ?? '24:00'));
        if ($session_hour < $limit_hour) {
          $assigned_part_id = $part['id'];
          break;
        }
      }
    }

    // Fallback if part not found
    if (empty($assigned_part_id) || !isset($grouped_sessions[$assigned_part_id])) {
      $assigned_part_id = $parts[0]['id'];
    }

    $grouped_sessions[$assigned_part_id][] = $session;
  }

  $output = '';

  // If only 1 part, render simple list
  if (count($parts) <= 1) {
    $output .= '<ul class="ishf-sessions">';
    foreach ($grouped_sessions[$parts[0]['id']] as $session) {
      $output .= ishf_seminar_render_session($session);
    }
    $output .= '</ul>';
    return $output;
  }

  // Render tabs for multiple parts
  $output .= '<ul class="uk-subnav uk-subnav-pill" uk-switcher>';
  foreach ($parts as $part) {
    $output .= '<li><a href="#" style="border-radius: 50px; padding: 5px 20px;">' . esc_html($part['label']) . '</a></li>';
  }
  $output .= '</ul>';

  $output .= '<ul class="uk-switcher uk-margin">';
  foreach ($parts as $part) {
    $output .= '<li>';
    $output .= '<ul class="ishf-sessions">';
    foreach ($grouped_sessions[$part['id']] as $session) {
      $output .= ishf_seminar_render_session($session);
    }
    $output .= '</ul>';
    $output .= '</li>';
  }
  $output .= '</ul>';

  return $output;
}


//--------------------------------------------
//  Function that registers the block
//--------------------------------------------
function register_ishf_seminar_program()
{
  register_block_type('ishf/seminar-program', array(
    'render_callback' => 'render_ishf_seminar_program',
    'attributes' => array(

      // -- Block ID (for API identification)
      'blockId' => array(
        'type' => 'string',
        'default' => '',
      ),

      // -- Render mode: full | split (tabs)
      'renderMode' => array(
        'type' => 'string',
        'default' => 'full',
      ),

      // -- Array of parts
      'parts' => array(
        'type' => 'array',
        'default' => array(
          array('id' => 'part-1', 'label' => 'Manhã', 'limit' => '13:00'),
          array('id' => 'part-2', 'label' => 'Tarde', 'limit' => '24:00')
        ),
        'items' => array(
          'type' => 'object',
        ),
      ),

      // -- Array of session objects
      'sessions' => array(
        'type' => 'array',
        'default' => array(),
        'items' => array(
          'type' => 'object',
        ),
      ),

    ),
  ));
}
add_action('init', 'register_ishf_seminar_program');

