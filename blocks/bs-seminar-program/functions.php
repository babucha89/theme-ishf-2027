<?php
// -----------------------------------------------------------------------------
//  Block: BS SEMINAR PROGRAM
//  Edited: 3L, 23-JUL-2026
// -----------------------------------------------------------------------------
//  A program block with repeatable sessions. Each session has a time slot,
//  a keynote title, and speaker names (autocomplete from CPT attendants).
//  Supports render modes: full | morning | afternoon.
// -----------------------------------------------------------------------------


//--------------------------------------------
//  REST API: Attendants autocomplete endpoint
//--------------------------------------------
add_action('rest_api_init', 'bs_seminar_register_attendants_endpoint');
function bs_seminar_register_attendants_endpoint()
{
  register_rest_route('bs/v1', '/attendants', array(
    'methods' => 'GET',
    'callback' => 'bs_seminar_get_attendants',
    'permission_callback' => function () {
      return current_user_can('edit_posts');
    },
    'args' => array(
      'search' => array(
        'required' => false,
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
      ),
      'post_id' => array(
        'required' => false,
        'type' => 'integer',
        'sanitize_callback' => 'absint',
      ),
    ),
  ));
}

function bs_seminar_get_attendants($request)
{
  $search = $request->get_param('search');
  $post_id = $request->get_param('post_id');

  $args = array(
    'post_type' => 'attendants',
    'posts_per_page' => 20,
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
  );

  // -- Filter attendants by the event's related attendants (bs_cf_attendants_related)
  if (!empty($post_id)) {
    $related = get_post_meta($post_id, 'bs_cf_attendants_related', true);
    if (!empty($related)) {
      $related_ids = array_map('intval', array_map('trim', explode(',', $related)));
      $args['post__in'] = $related_ids;
    } else {
      // DEBUG: Return diagnostic info instead of empty array
      return rest_ensure_response(array(
        '_debug' => array(
          'post_id' => $post_id,
          'post_type' => get_post_type($post_id),
          'meta_raw' => $related,
          'all_meta_keys' => array_keys(get_post_meta($post_id)),
        ),
      ));
    }
  }

  if (!empty($search)) {
    $args['s'] = $search;
  }

  $query = new WP_Query($args);
  $results = array();

  if ($query->have_posts()):
    while ($query->have_posts()):
      $query->the_post();
      $function = get_post_meta(get_the_ID(), 'bs_cf_function', true);
      $organization = get_post_meta(get_the_ID(), 'bs_cf_organization', true);

      $results[] = array(
        'id' => get_the_ID(),
        'name' => get_the_title(),
        'bs_cf_function' => !empty($function) ? $function : '',
        'bs_cf_organization' => !empty($organization) ? $organization : '',
      );
    endwhile;
  endif;
  wp_reset_postdata();

  return rest_ensure_response($results);
}


//--------------------------------------------
//  Helper: Format a speaker/moderator for frontend
//--------------------------------------------
function bs_seminar_format_speaker($speaker, $isModerator = false)
{
  $name = esc_html($speaker['name'] ?? '');
  $id = intval($speaker['id'] ?? 0);

  if ($id > 0) {
    // Linked speaker: Bold Name, Function, Organization
    $func = get_post_meta($id, 'bs_cf_function', true);
    $org = get_post_meta($id, 'bs_cf_organization', true);

    $details = array();
    if (!empty($func))
      $details[] = $func;
    if (!empty($org))
      $details[] = $org;
    $details_str = !empty($details) ? ', ' . implode(', ', $details) : '';

    $permalink = get_permalink($id);
    if ($isModerator) {
      return '<a href="' . esc_url($permalink) . '" class="">' . $name . '</a>' . $details_str;
    } else {
      return '<a href="' . esc_url($permalink) . '" class=""><strong>' . $name . '</strong></a>' . $details_str;
    }
  } else {
    // Manual speaker: bold before first comma
    $comma_idx = strpos($name, ',');
    if ($comma_idx !== false) {
      $bold_part = substr($name, 0, $comma_idx);
      $rest_part = substr($name, $comma_idx);
      if ($isModerator) {
        return $name;
      } else {
        return '<strong>' . $bold_part . '</strong>' . $rest_part;
      }
    } else {
      return '<strong>' . $name . '</strong>';
    }
  }
}

//--------------------------------------------
//  Helper: Render a single session HTML
//--------------------------------------------
function bs_seminar_render_session($session) {
  $time = esc_html($session['time'] ?? '');
  $keynote = esc_html($session['keynote'] ?? '');
  $moderator = $session['moderator'] ?? array();
  $speakers = $session['speakers'] ?? array();

  $output = '<li>';
  $output .= '<div data-uk-grid class="uk-grid-small uk-flex-top">';
  $output .= '  <div class="uk-width-auto"><p class="uk-text-bold uk-h4" data-time>' . $time . ' |</p></div>';
  $output .= '  <div class="uk-width-expand">';
  $output .= '    <p class="uk-text-bold uk-margin-remove-bottom uk-h4" data-keynote>' . $keynote . '</p>';

  // -- Speakers
  if (!empty($speakers)):
    $output .= '<div class="uk-text-small uk-margin-small-top">';
    if (!isset($session['speakersAlphabetical']) || $session['speakersAlphabetical'] !== false) {
      usort($speakers, function ($a, $b) {
        return strcasecmp($a['name'] ?? '', $b['name'] ?? '');
      });
    }
    foreach ($speakers as $index => $speaker):
      $output .= '<div class="uk-margin-small-bottom" data-speaker>' . bs_seminar_format_speaker($speaker) . '</div>';
    endforeach;
    $output .= '</div>';
  endif;

  // -- Moderator
  if (!empty($moderator)):
    $output .= '<div class="uk-text-small uk-margin-small-top">';
    $output .= '<div class="uk-margin-small-bottom"><strong>' . __('Moderação', TEXT_DOMAIN) . '</strong>: ';
    foreach ($moderator as $index => $mod):
      $output .= bs_seminar_format_speaker($mod, true);
    endforeach;
    $output .= '</div>';
  endif;

  $output .= '  </div>';
  $output .= '</div>';
  $output .= '</li>';

  return $output;
}

//--------------------------------------------
//  Function that renders the block
//--------------------------------------------
function render_bs_seminar_program($attributes, $content)
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

    $output = '<ul class="uk-list uk-margin-medium-bottom">';
    foreach ($sessions_to_render as $session) {
      $output .= bs_seminar_render_session($session);
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
    $output .= '<ul class="uk-list uk-margin-medium-bottom">';
    foreach ($grouped_sessions[$parts[0]['id']] as $session) {
      $output .= bs_seminar_render_session($session);
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
    $output .= '<ul class="uk-list uk-margin-medium-bottom">';
    foreach ($grouped_sessions[$part['id']] as $session) {
      $output .= bs_seminar_render_session($session);
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
function register_bs_seminar_program()
{
  register_block_type('uikit-boostsolutions/bs-seminar-program', array(
    'render_callback' => 'render_bs_seminar_program',
    'attributes' => array(

      // -- Block ID (for API identification)
      'blockId' => array(
        'type' => 'string',
        'default' => '',
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
add_action('init', 'register_bs_seminar_program');

