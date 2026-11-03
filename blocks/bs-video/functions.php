<?php
// -----------------------------------------------------------------------------
//  Block: BS VIDEO
//  Edited: 3L, 30-JUL-2026
// -----------------------------------------------------------------------------

//--------------------------------------------
//  Function that renders the block
//--------------------------------------------
function render_bs_video($attributes, $content)
{
  $platform = $attributes['platform'] ?? 'youtube';
  $id = $attributes['videoId'] ?? '';
  
  if (empty($id)) {
    return '';
  }

  $title = $attributes['title'] ?? '';
  $title = str_replace('{br}', '<br/>', $title);
  $title_size = $attributes['titleSize'] ?? '2';
  $title_position = $attributes['titlePosition'] ?? 'none';
  $no_margin = $attributes['noMargin'] ?? false;
  $is_shorts = $attributes['isShorts'] ?? false;
  $playlist_id = $attributes['playlistId'] ?? '';

  $html = '<div';
  if (!$no_margin):
    $html .= ' class="uk-margin-medium"';
  endif;
  $html .= '>';

  // -- Top Title
  if ((!empty($title)) && ($title_position == 'top')):
    $html .= '<h' . $title_size . '>' . $title . '</h' . $title_size . '>';
  endif;

  // -- Render Video
  if ($platform === 'brightcove') {
    // Brightcove
    $html .= '<iframe src="https://players.brightcove.net/6108484330001/kAtzSWivK_default/index.html?videoId=' . esc_attr($id) . '" allowfullscreen="" allow="encrypted-media" width="1280" height="720" data-uk-responsive loading="lazy" title="' . esc_attr($title) . '"></iframe>';
  } else {
    // YouTube
    $list = (!empty($playlist_id)) ? 'list=' . esc_attr($playlist_id) . '&' : '';
    
    if ($is_shorts) {
      // YouTube Shorts (Vertical)
      $html .= '<style>
        .vertical-video-wrapper {
          position: relative;
          width: 100%;
          max-width: 400px;
          margin: 0 auto;
          aspect-ratio: 9 / 16;
          background-color: #000;
          overflow: hidden;
        }
        .vertical-video-wrapper iframe {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
        }
      </style>';
      $html .= '<div class="vertical-video-wrapper">';
      $html .= '<iframe src="https://www.youtube.com/embed/' . esc_attr($id) . '?' . $list . 'showinfo=0" title="' . esc_attr($title) . '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>';
      $html .= '</div>';
    } else {
      // YouTube Standard
      $html .= '<iframe width="1280" height="720" src="https://www.youtube.com/embed/' . esc_attr($id) . '?' . $list . 'showinfo=0" frameborder="0" allowfullscreen data-uk-responsive loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="' . esc_attr($title) . '"></iframe>';
    }
  }

  // -- Bottom Title
  if ((!empty($title)) && ($title_position == 'bottom')):
    $html .= '<h' . $title_size . ' class="uk-margin-top">' . $title . '</h' . $title_size . '>';
  endif;

  $html .= '</div>';

  return $html;
}


//--------------------------------------------
//  Function that registers the block
//--------------------------------------------
function register_bs_video()
{
  register_block_type('uikit-boostsolutions/bs-video', array(
    'render_callback' => 'render_bs_video',
    'attributes' => array(
      'blockId' => array(
        'type' => 'string',
        'default' => '',
      ),
      'platform' => array(
        'type' => 'string',
        'default' => 'youtube',
      ),
      'videoId' => array(
        'type' => 'string',
        'default' => '',
      ),
      'isShorts' => array(
        'type' => 'boolean',
        'default' => false,
      ),
      'playlistId' => array(
        'type' => 'string',
        'default' => '',
      ),
      'title' => array(
        'type' => 'string',
        'default' => '',
      ),
      'titleSize' => array(
        'type' => 'string',
        'default' => '2',
      ),
      'titlePosition' => array(
        'type' => 'string',
        'default' => 'none',
      ),
      'noMargin' => array(
        'type' => 'boolean',
        'default' => false,
      ),
    ),
  ));
}
add_action('init', 'register_bs_video');
?>
