<?php
//-------------------------------------------------------------------------------------------
//  Creating SEMINAR CPT
//-------------------------------------------------------------------------------------------
if (!function_exists('ishf_set_cpt_seminars')):
  add_action('init', 'ishf_set_cpt_seminars');
  function ishf_set_cpt_seminars() {
    register_post_type('seminars',
      array(
        'labels' => array(
          'name' => __('Seminars', TEXT_DOMAIN),
          'singular_name' => __('Seminar', TEXT_DOMAIN),
          'menu_name' => __('Seminars', TEXT_DOMAIN),
          'parent_item_colon' => __('Parent seminar', TEXT_DOMAIN),
          'all_items' => __('All seminars', TEXT_DOMAIN),
          'view_item' => __('View seminars', TEXT_DOMAIN),
          'add_new_item' => __('Add new seminar', TEXT_DOMAIN),
          'add_new' => __('New seminar', TEXT_DOMAIN),
          'edit_item' => __('Edit seminar', TEXT_DOMAIN),
          'update_item' => __('Update seminar', TEXT_DOMAIN),
          'search_items' => __('Search seminar', TEXT_DOMAIN),
          'not_found' => __('Not Found', TEXT_DOMAIN),
          'not_found_in_trash' => __('Not found in Trash', TEXT_DOMAIN),
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
add_filter('manage_seminars_posts_columns', 'ishf_set_seminars_custom_columns');
function ishf_set_seminars_custom_columns($columns){
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
add_action('manage_seminars_posts_custom_column' , 'ishf_set_seminars_custom_columns_values', 10, 2);
function ishf_set_seminars_custom_columns_values($column, $post_id){
  switch ($column):
    case 'order':
      $terms = get_post_meta($post_id , 'ishf_cf_order', true);
      echo $terms;
      break;
  endswitch;
}
?>