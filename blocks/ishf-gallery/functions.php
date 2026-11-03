<?php
// -----------------------------------------------------------------------------
//  Block: ISHF GALLERY
//  Edited: 3L, 30-JUL-2026
// -----------------------------------------------------------------------------

//--------------------------------------------
//  Function that renders the block
//--------------------------------------------
function render_ishf_gallery($attributes, $content)
{
  $images = $attributes['images'] ?? array();

  if (empty($images)) {
    return '';
  }

  $output = '<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" data-uk-slideshow>' . "\n";
  $output .= '<ul class="uk-slideshow-items">' . "\n";

  foreach ($images as $image):
    $id = $image['id'] ?? 0;
    $url = $image['url'] ?? '';
    $alt = $image['alt'] ?? '';
    $caption = $image['caption'] ?? '';

    // If we have an attachment ID, try to get the WordPress image
    if (!empty($id)) {
      $attachment = get_post($id);
      $wp_url = wp_get_attachment_image_src($id, 'image-vertical');
      if (!empty($wp_url[0])) {
        $url = str_replace(BASE_SITE, REDIRECT_SITE, $wp_url[0]);
      }
      if (empty($alt) && !empty($attachment)) {
        $alt = $attachment->post_excerpt ?? '';
      }
      if (empty($caption) && !empty($attachment)) {
        $caption = $attachment->post_excerpt ?? '';
      }
    }

    if (!empty($url)):
      $output .= '<li>' . "\n";
      $output .= '<img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '" data-uk-cover />' . "\n";
      if (!empty($caption)):
        $output .= '<div class="caption uk-position-bottom-right">';
        $output .= '<p class="uk-text-small uk-margin-remove">' . esc_html($caption) . '</p>';
        $output .= '</div>';
      endif;
      $output .= '</li>' . "\n";
    endif;
  endforeach;

  $output .= '</ul>' . "\n";
  $output .= '<a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>' . "\n";
  $output .= '<a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slideshow-item="next"></a>' . "\n";
  $output .= '</div>' . "\n";

  return $output;
}


//--------------------------------------------
//  Function that registers the block
//--------------------------------------------
function register_ishf_gallery()
{
  register_block_type('ishf/gallery', array(
    'render_callback' => 'render_ishf_gallery',
    'attributes' => array(

      // -- Block ID (for API identification)
      'blockId' => array(
        'type' => 'string',
        'default' => '',
      ),

      // -- Array of image objects { id, url, alt, caption }
      'images' => array(
        'type' => 'array',
        'default' => array(),
        'items' => array(
          'type' => 'object',
        ),
      ),

    ),
  ));
}
add_action('init', 'register_ishf_gallery');
?>