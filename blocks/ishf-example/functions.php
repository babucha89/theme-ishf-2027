<?php
// -----------------------------------------------------------------------------
//  Block: ISHF EXAMPLE
//  Edited: 3L, 23-JUL-2026
// -----------------------------------------------------------------------------
//  TODO: Rename this file's functions from "ishf_example" to "ishf_yourblock".
//  This block is a template — duplicate the folder and customise as needed.
// -----------------------------------------------------------------------------


//--------------------------------------------
//  Function that renders the block
//--------------------------------------------
function render_ishf_example($attributes, $content)
{

  // -- Access block attributes
  $title = $attributes['title'] ?? '';
  $description = $attributes['description'] ?? '';
  $image_url = $attributes['imageUrl'] ?? '';
  $image_alt = $attributes['imageAlt'] ?? '';
  $is_featured = $attributes['isFeatured'] ?? false;

  // -- Build CSS classes
  $classes = 'ishf-example';
  if ($is_featured) {
    $classes .= ' ishf-example--featured';
  }

  // -- Generate HTML output
  $output = '<div class="' . esc_attr($classes) . '">';

  if (!empty($image_url)):
    $output .= '<div class="ishf-example__image">';
    $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" />';
    $output .= '</div>';
  endif;

  if (!empty($title)):
    $output .= '<h3 class="ishf-example__title">' . esc_html($title) . '</h3>';
  endif;

  if (!empty($description)):
    $output .= '<div class="ishf-example__description">' . wp_kses_post($description) . '</div>';
  endif;

  $output .= '</div>';

  return $output;
}


//--------------------------------------------
//  Function that registers the block
//--------------------------------------------
function register_ishf_example()
{
  register_block_type('ishf/example', array(
    'render_callback' => 'render_ishf_example',
    'attributes' => array(

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

      // -- Toggle / Boolean
      'isFeatured' => array(
        'type' => 'boolean',
        'default' => false,
      ),

    ),
  ));
}
add_action('init', 'register_ishf_example');
?>