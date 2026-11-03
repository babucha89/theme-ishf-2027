<?php
// -----------------------------------------------------------------------------
//  File: FUNCTIONS
//  Edited: 3L, 18-NOV-2024
// -----------------------------------------------------------------------------


//--------------------------------------------
//  Function that renders the block
//--------------------------------------------
function render_ishf_quotation($attributes, $content) {
  // Access block attributes
  $quotation = $attributes['quotation'] ?? '';
  $author = $attributes['author'] ?? '';
  $function = $attributes['function'] ?? '';
  $entity = $attributes['entity'] ?? '';

  $meta = array();
  if (!empty($author)) $meta[] = $author;
  if (!empty($function)) $meta[] = $function;
  if (!empty($entity)) $meta[] = $entity;
  
  // Generate HTML output
  $output  = '<figure class="ishf-quotation">';
  $output .= '<blockquote><p>'.$quotation.'</p></blockquote>';
  if (!empty($meta)):
    $output .= '<figcaption><cite>- '.implode(', ', $meta).'</cite></figcaption>';
  endif;
  $output .= '</figure>';
  
  return $output;
}

//--------------------------------------------
//  Function that registers the block
//--------------------------------------------
function register_ishf_quotation() {
  register_block_type('ishf/quotation', array(
    'render_callback' => 'render_ishf_quotation',
    'attributes' => array(
      'blockId' => array(
        'type' => 'string',
        'default' => '',
      ),
      'quotation' => array(
        'type' => 'string',
        'default' => '',
      ),
      'author' => array(
        'type' => 'string',
        'default' => '',
      ),
      'function' => array(
        'type' => 'string',
        'default' => '',
      ),
      'entity' => array(
        'type' => 'string',
        'default' => '',
      ),
    ),
  ));
}
add_action('init', 'register_ishf_quotation');
?>