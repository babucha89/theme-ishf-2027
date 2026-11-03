<?php
//-------------------------------------------------------------------------------------------
//  Creating FAQS CPT
//-------------------------------------------------------------------------------------------
if (!function_exists('bs_set_cpt_faqs')):
  add_action('init', 'bs_set_cpt_faqs');
  function bs_set_cpt_faqs() {
    register_post_type('faqs',
      array(
        'labels' => array(
          'name' => __('FAQs', 'uikit-boostsolutions'),
          'singular_name' => __('FAQ', 'uikit-boostsolutions'),
          'menu_name' => __('FAQs', 'uikit-boostsolutions'),
          'parent_item_colon' => __('Parent FAQ', 'uikit-boostsolutions'),
          'all_items' => __('All FAQs', 'uikit-boostsolutions'),
          'view_item' => __('View FAQs', 'uikit-boostsolutions'),
          'add_new_item' => __('Add new FAQ', 'uikit-boostsolutions'),
          'add_new' => __('New FAQ', 'uikit-boostsolutions'),
          'edit_item' => __('Edit FAQ', 'uikit-boostsolutions'),
          'update_item' => __('Update FAQ', 'uikit-boostsolutions'),
          'search_items' => __('Search FAQ', 'uikit-boostsolutions'),
          'not_found' => __('Not Found', 'uikit-boostsolutions'),
          'not_found_in_trash' => __('Not found in Trash', 'uikit-boostsolutions'),
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
add_filter('manage_faqs_posts_columns', 'bs_set_faqs_custom_columns');
function bs_set_faqs_custom_columns($columns){
  $new_columns = array();
  foreach($columns as $key=>$value):
    if ($key == 'date'):
       $new_columns['order'] = __('Order', 'uikit-boostsolutions');
    endif;
    $new_columns[$key]=$value;
  endforeach;
  return $new_columns;
}

// Add the data to the custom columns on LIST (admin)
add_action('manage_faqs_posts_custom_column' , 'bs_set_faqs_custom_columns_values', 10, 2);
function bs_set_faqs_custom_columns_values($column, $post_id){
  switch ($column):
    case 'order':
      $terms = get_post_meta($post_id , 'bs_cf_order', true);
      echo $terms;
      break;
  endswitch;
}
?>