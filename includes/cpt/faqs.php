<?php
//-------------------------------------------------------------------------------------------
//  Creating FAQS CPT
//-------------------------------------------------------------------------------------------
if (!function_exists('ishf_set_cpt_faqs')):
  add_action('init', 'ishf_set_cpt_faqs');
  function ishf_set_cpt_faqs() {
    register_post_type('faqs',
      array(
        'labels' => array(
          'name' => __('FAQs', TEXT_DOMAIN),
          'singular_name' => __('FAQ', TEXT_DOMAIN),
          'menu_name' => __('FAQs', TEXT_DOMAIN),
          'parent_item_colon' => __('Parent FAQ', TEXT_DOMAIN),
          'all_items' => __('All FAQs', TEXT_DOMAIN),
          'view_item' => __('View FAQs', TEXT_DOMAIN),
          'add_new_item' => __('Add new FAQ', TEXT_DOMAIN),
          'add_new' => __('New FAQ', TEXT_DOMAIN),
          'edit_item' => __('Edit FAQ', TEXT_DOMAIN),
          'update_item' => __('Update FAQ', TEXT_DOMAIN),
          'search_items' => __('Search FAQ', TEXT_DOMAIN),
          'not_found' => __('Not Found', TEXT_DOMAIN),
          'not_found_in_trash' => __('Not found in Trash', TEXT_DOMAIN),
        ),
        'public' => true,
        'menu_position' => 30,
        'has_archive' => true,
        'show_in_nav_menus' => true,
        'menu_icon' => 'dashicons-editor-help',
        'rewrite' => array('slug' => 'faqs'),
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'custom-fields'),
        'taxonomies' => array('category'),
      )
    );
  }
endif;



// Add the custom columns on LIST (admin)
add_filter('manage_faqs_posts_columns', 'ishf_set_faqs_custom_columns');
function ishf_set_faqs_custom_columns($columns){
  $new_columns = array();
  foreach($columns as $key=>$value):
    if ($key == 'date'):
       $new_columns['order'] = __('Order', TEXT_DOMAIN);
    endif;
    $new_columns[$key]=$value;
  endforeach;
  return $new_columns;
}

// Add the data to the custom columns on LIST (admin)
add_action('manage_faqs_posts_custom_column' , 'ishf_set_faqs_custom_columns_values', 10, 2);
function ishf_set_faqs_custom_columns_values($column, $post_id){
  switch ($column):
    case 'order':
      $terms = get_post_meta($post_id , 'ishf_cf_order', true);
      echo $terms;
      break;
  endswitch;
}
?>