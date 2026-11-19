<?php
// -----------------------------------------------------------------------------
//  [gallery] SHORTCODE: UIkit grid + lightbox
//  Replaces WordPress's default gallery markup everywhere ([gallery] in any
//  content, and the "Photo gallery" field of the editions):
//  - square-ish thumbnails in a UIkit grid (2 columns on mobile, up to the
//    shortcode's "columns", default 3, from 640px)
//  - each photo opens full size in a UIkit lightbox (arrows, swipe, captions)
//  - alt text from the Media Library; caption shown under the large photo
//  Shortcode attributes used: ids, columns, size, include, exclude, orderby, order, id.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 19-NOV-2026
// -----------------------------------------------------------------------------

if (!function_exists('ishf_gallery_shortcode')):
  add_filter('post_gallery', 'ishf_gallery_shortcode', 10, 3);
  function ishf_gallery_shortcode($output, $attr, $instance)
  {
    if ($output !== '' || is_feed()) {
      return $output;
    }
    $post = get_post();
    $atts = shortcode_atts(array(
      'order'   => 'ASC',
      'orderby' => 'menu_order ID',
      'id'      => $post ? $post->ID : 0,
      'columns' => 3,
      'size'    => 'medium_large',
      'ids'     => '',
      'include' => '',
      'exclude' => '',
    ), (array) $attr, 'gallery');

    $ids = $atts['ids'] !== '' ? $atts['ids'] : $atts['include'];
    if ($ids !== '') {
      $ids = array_filter(array_map('absint', explode(',', $ids)));
    } else {
      $children = get_children(array(
        'post_parent'    => (int) $atts['id'],
        'post_status'    => 'inherit',
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'order'          => $atts['order'],
        'orderby'        => $atts['orderby'],
        'exclude'        => $atts['exclude'],
        'fields'         => 'ids',
      ));
      $ids = array_values((array) $children);
    }
    $ids = array_values(array_filter($ids, 'wp_attachment_is_image'));
    if (!$ids) {
      return '';
    }

    $columns = max(1, min(6, (int) $atts['columns']));
    $widths  = array(1 => 'uk-child-width-1-1', 2 => 'uk-child-width-1-2', 3 => 'uk-child-width-1-2 uk-child-width-1-3@s', 4 => 'uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m', 5 => 'uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-5@m', 6 => 'uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-6@m');
    $size    = in_array($atts['size'], get_intermediate_image_sizes(), true) ? $atts['size'] : 'medium_large';

    $html = '<div class="ishf-gallery uk-grid-small ' . esc_attr($widths[$columns]) . '" data-uk-grid data-uk-lightbox="animation: slide">';
    foreach ($ids as $id) {
      $full    = wp_get_attachment_image_url($id, 'large');
      $alt     = trim((string) get_post_meta($id, '_wp_attachment_image_alt', true));
      $caption = trim((string) wp_get_attachment_caption($id));
      $label   = $alt !== '' ? $alt : ($caption !== '' ? $caption : get_the_title($id));
      $html .= '<div>';
      $html .= '<a class="ishf-gallery-item" href="' . esc_url($full) . '"' . ($caption !== '' ? ' data-caption="' . esc_attr($caption) . '"' : '') . ' data-alt="' . esc_attr($label) . '" aria-label="' . esc_attr(sprintf(__('Open photo: %s', TEXT_DOMAIN), $label)) . '">';
      $html .= wp_get_attachment_image($id, $size, false, array('alt' => $alt, 'loading' => 'lazy', 'class' => 'ishf-gallery-img'));
      $html .= '</a>';
      $html .= '</div>';
    }
    $html .= '</div>';
    return $html;
  }
endif;
