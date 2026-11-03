<?php
// -----------------------------------------------------------------------------
//  Block: ISHF INTRO
//  Edited: 3L, 23-JUL-2026
// -----------------------------------------------------------------------------

//--------------------------------------------
//  Function that renders the block
//--------------------------------------------
function render_ishf_intro($attributes, $content)
{

  // -- Access block attributes
  $title = $attributes['title'] ?? '';
  $description = $attributes['description'] ?? '';
  $image_url = $attributes['imageUrl'] ?? '';
  $image_alt = $attributes['imageAlt'] ?? '';
  $layout = $attributes['layout'] ?? 'no-image';

  // -- Build CSS classes
  $classes = 'ishf-intro uk-margin-large-bottom';


  // -- Generate HTML output
  $output = '<div class="uk-margin-large-bottom">';

  if ($layout === 'no-image') {
    if (!empty($title)):
      $output .= '<p class="uk-h2 uk-text-bold uk-margin-remove-bottom">' . esc_html($title) . '</p>';
    endif;
    if (!empty($description)):
      $output .= '<div class="uk-text-default uk-margin-small-top">' . wp_kses_post($description) . '</div>';
    endif;
  } else {
    // image-left or image-right
    $output .= '<div data-uk-grid class="uk-grid-large uk-flex-middle">';
    
    // Image column (1/3)
    $image_col = '<div class="uk-width-1-3@m' . ($layout === 'image-right' ? ' uk-flex-last@m' : '') . '">';
    if (!empty($image_url)):
      $image_col .= '<img class="uk-border-rounded" src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" />';
    endif;
    $image_col .= '</div>';
    
    // Text column (2/3)
    $text_col = '<div class="uk-width-2-3@m">';
    if (!empty($title)):
      $text_col .= '<p class="uk-h2 uk-text-bold uk-margin-remove-bottom">' . esc_html($title) . '</p>';
    endif;
    if (!empty($description)):
      $text_col .= '<div class="uk-text-default uk-margin-small-top">' . wp_kses_post($description) . '</div>';
    endif;
    $text_col .= '</div>';

    // Output columns
    $output .= $image_col . $text_col;
    $output .= '</div>';
  }

  $output .= '</div>';

  return $output;
}


//--------------------------------------------
//  Function that registers the block
//--------------------------------------------
function register_ishf_intro()
{
  register_block_type('ishf/intro', array(
    'render_callback' => 'render_ishf_intro',
    'attributes' => array(
      
      // -- Block ID (for API identification)
      'blockId' => array(
        'type' => 'string',
        'default' => '',
      ),

      // -- Layout
      'layout' => array(
        'type' => 'string',
        'default' => 'no-image',
      ),

      // -- Text field (single line)
      'title' => array(
        'type' => 'string',
        'default' => '',
      ),

      // -- Textarea field (multi-line)
      'description' => array(
        'type' => 'string',
        'default' => '',
      ),

      // -- Media / Image URL
      'imageUrl' => array(
        'type' => 'string',
        'default' => '',
      ),

      // -- Media / Image Alt text
      'imageAlt' => array(
        'type' => 'string',
        'default' => '',
      ),

    ),
  ));
}
add_action('init', 'register_ishf_intro');
?>