<?php
//-------------------------------------------------------------------------------------------
//  Creating ATTENDANTS CPT
//-------------------------------------------------------------------------------------------
if (!function_exists('bs_set_cpt_attendants')):
  add_action('init', 'bs_set_cpt_attendants');
  function bs_set_cpt_attendants() {
    register_post_type('attendants',
      array(
        'labels' => array(
          'name' => __('Attendants', 'uikit-boostsolutions'),
          'singular_name' => __('Attendant', 'uikit-boostsolutions'),
          'menu_name' => __('Attendants', 'uikit-boostsolutions'),
          'parent_item_colon' => __('Parent attendant', 'uikit-boostsolutions'),
          'all_items' => __('All attendants', 'uikit-boostsolutions'),
          'view_item' => __('View attendants', 'uikit-boostsolutions'),
          'add_new_item' => __('Add new attendant', 'uikit-boostsolutions'),
          'add_new' => __('New attendant', 'uikit-boostsolutions'),
          'edit_item' => __('Edit attendant', 'uikit-boostsolutions'),
          'update_item' => __('Update attendant', 'uikit-boostsolutions'),
          'search_items' => __('Search attendant', 'uikit-boostsolutions'),
          'not_found' => __('Not Found', 'uikit-boostsolutions'),
          'not_found_in_trash' => __('Not found in Trash', 'uikit-boostsolutions'),
        ),
        'public' => true,
        'menu_position' => 30,
        'has_archive' => true,
        'show_in_nav_menus' => true,
        'menu_icon' => 'dashicons-groups',
        'rewrite' => array('slug' => 'attendants'),
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'taxonomies' => array('category','post_tag'),
      )
    );
  }
endif;

//-------------------------------------------------------------------------------------------
//  Creating ATTENDANTS CATEGORY (Custom Taxonomy)
//-------------------------------------------------------------------------------------------
if (!function_exists('bs_set_cpt_attendant_categories')):
  add_action('init', 'bs_set_cpt_attendant_categories', 0);
  function bs_set_cpt_attendant_categories() {
    register_taxonomy('attendant_categories',
      array('attendants'),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __('Attendants Categories', 'uikit-boostsolutions'),
          'singular_name' => __('Attendant Category', 'uikit-boostsolutions'),
          'search_items' => __('Search Attendant Categories', 'uikit-boostsolutions'),
          'all_items' => __('All Attendant Categories', 'uikit-boostsolutions'),
          'parent_item' => __('Parent Attendant Category', 'uikit-boostsolutions'),
          'parent_item_colon' => __('Parent Attendant Category:', 'uikit-boostsolutions'),
          'edit_item' => __('Edit Attendant Category', 'uikit-boostsolutions'),
          'update_item' => __('Update Attendant Category', 'uikit-boostsolutions'),
          'add_new_item' => __('Add New Attendant Category', 'uikit-boostsolutions'),
          'new_item_name' => __('New Attendant Category', 'uikit-boostsolutions'),
          'menu_name' => __('Attendant Categories', 'uikit-boostsolutions'),
        ),
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'query_var' => true,
        'rewrite' => array('slug' => 'attendant-category'),
      )
    );
  }
endif;



//-------------------------------------------------------------------------------------------
//  Add the custom columns on LIST (admin)
//-------------------------------------------------------------------------------------------
add_filter('manage_attendants_posts_columns', 'bs_set_attendants_custom_columns');
function bs_set_attendants_custom_columns($columns){
  $new_columns = array();
  foreach($columns as $key=>$value):
    if ($key == 'date'):
       $new_columns['order'] = __('Order', 'uikit-boostsolutions');
    endif;
    $new_columns[$key]=$value;
  endforeach;
  return $new_columns;
}

//-------------------------------------------------------------------------------------------
//  Add the data to the custom columns on LIST (admin)
//-------------------------------------------------------------------------------------------
add_action('manage_attendants_posts_custom_column' , 'bs_set_attendants_custom_columns_values', 10, 2);
function bs_set_attendants_custom_columns_values($column, $post_id){
  switch ($column):
    case 'order':
      $terms = get_post_meta($post_id , 'bs_cf_order', true);
      echo $terms;
      break;
  endswitch;
}
?>