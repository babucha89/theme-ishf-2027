<?php
//-------------------------------------------------------------------------------------------
//  Creating SEMINAR CPT
//-------------------------------------------------------------------------------------------
if (!function_exists('bs_set_cpt_seminars')):
  add_action('init', 'bs_set_cpt_seminars');
  function bs_set_cpt_seminars() {
    register_post_type('seminars',
      array(
        'labels' => array(
          'name' => __('Seminars', 'uikit-boostsolutions'),
          'singular_name' => __('Seminar', 'uikit-boostsolutions'),
          'menu_name' => __('Seminars', 'uikit-boostsolutions'),
          'parent_item_colon' => __('Parent seminar', 'uikit-boostsolutions'),
          'all_items' => __('All seminars', 'uikit-boostsolutions'),
          'view_item' => __('View seminars', 'uikit-boostsolutions'),
          'add_new_item' => __('Add new seminar', 'uikit-boostsolutions'),
          'add_new' => __('New seminar', 'uikit-boostsolutions'),
          'edit_item' => __('Edit seminar', 'uikit-boostsolutions'),
          'update_item' => __('Update seminar', 'uikit-boostsolutions'),
          'search_items' => __('Search seminar', 'uikit-boostsolutions'),
          'not_found' => __('Not Found', 'uikit-boostsolutions'),
          'not_found_in_trash' => __('Not found in Trash', 'uikit-boostsolutions'),
        ),
        'public' => true,
        'menu_position' => 30,
        'has_archive' => true,
        'show_in_nav_menus' => true,
        'menu_icon' => 'dashicons-megaphone',
        'rewrite' => array('slug' => 'seminars'),
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
        'taxonomies' => array('category','post_tag'),
      )
    );
  }
endif;


// Add the custom columns on LIST (admin)
add_filter('manage_seminars_posts_columns', 'bs_set_seminars_custom_columns');
function bs_set_seminars_custom_columns($columns){
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
add_action('manage_seminars_posts_custom_column' , 'bs_set_seminars_custom_columns_values', 10, 2);
function bs_set_seminars_custom_columns_values($column, $post_id){
  switch ($column):
    case 'order':
      $terms = get_post_meta($post_id , 'bs_cf_order', true);
      echo $terms;
      break;
  endswitch;
}
?>