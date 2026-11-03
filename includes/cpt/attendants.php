<?php
//-------------------------------------------------------------------------------------------
//  Creating ATTENDANTS CPT
//-------------------------------------------------------------------------------------------
if (!function_exists('ishf_set_cpt_attendants')):
  add_action('init', 'ishf_set_cpt_attendants');
  function ishf_set_cpt_attendants() {
    register_post_type('attendants',
      array(
        'labels' => array(
          'name' => __('Attendants', TEXT_DOMAIN),
          'singular_name' => __('Attendant', TEXT_DOMAIN),
          'menu_name' => __('Attendants', TEXT_DOMAIN),
          'parent_item_colon' => __('Parent attendant', TEXT_DOMAIN),
          'all_items' => __('All attendants', TEXT_DOMAIN),
          'view_item' => __('View attendants', TEXT_DOMAIN),
          'add_new_item' => __('Add new attendant', TEXT_DOMAIN),
          'add_new' => __('New attendant', TEXT_DOMAIN),
          'edit_item' => __('Edit attendant', TEXT_DOMAIN),
          'update_item' => __('Update attendant', TEXT_DOMAIN),
          'search_items' => __('Search attendant', TEXT_DOMAIN),
          'not_found' => __('Not Found', TEXT_DOMAIN),
          'not_found_in_trash' => __('Not found in Trash', TEXT_DOMAIN),
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
if (!function_exists('ishf_set_cpt_attendant_categories')):
  add_action('init', 'ishf_set_cpt_attendant_categories', 0);
  function ishf_set_cpt_attendant_categories() {
    register_taxonomy('attendant_categories',
      array('attendants'),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => __('Attendants Categories', TEXT_DOMAIN),
          'singular_name' => __('Attendant Category', TEXT_DOMAIN),
          'search_items' => __('Search Attendant Categories', TEXT_DOMAIN),
          'all_items' => __('All Attendant Categories', TEXT_DOMAIN),
          'parent_item' => __('Parent Attendant Category', TEXT_DOMAIN),
          'parent_item_colon' => __('Parent Attendant Category:', TEXT_DOMAIN),
          'edit_item' => __('Edit Attendant Category', TEXT_DOMAIN),
          'update_item' => __('Update Attendant Category', TEXT_DOMAIN),
          'add_new_item' => __('Add New Attendant Category', TEXT_DOMAIN),
          'new_item_name' => __('New Attendant Category', TEXT_DOMAIN),
          'menu_name' => __('Attendant Categories', TEXT_DOMAIN),
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
add_filter('manage_attendants_posts_columns', 'ishf_set_attendants_custom_columns');
function ishf_set_attendants_custom_columns($columns){
  $new_columns = array();
  foreach($columns as $key=>$value):
    if ($key == 'date'):
       $new_columns['order'] = __('Order', TEXT_DOMAIN);
    endif;
    $new_columns[$key]=$value;
  endforeach;
  return $new_columns;
}

//-------------------------------------------------------------------------------------------
//  Add the data to the custom columns on LIST (admin)
//-------------------------------------------------------------------------------------------
add_action('manage_attendants_posts_custom_column' , 'ishf_set_attendants_custom_columns_values', 10, 2);
function ishf_set_attendants_custom_columns_values($column, $post_id){
  switch ($column):
    case 'order':
      $terms = get_post_meta($post_id , 'ishf_cf_order', true);
      echo $terms;
      break;
  endswitch;
}
?>