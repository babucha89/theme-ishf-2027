<?php
//-------------------------------------------------------------------------------------------
//  Creating QUOTATIONS CPT
//-------------------------------------------------------------------------------------------
if (!function_exists('bs_set_cpt_quotations')):
  add_action('init', 'bs_set_cpt_quotations');
  function bs_set_cpt_quotations() {
    register_post_type('quotations',
      array(
        'labels' => array(
          'name' => __('Quotations', 'uikit-boostsolutions'),
          'singular_name' => __('Quotation', 'uikit-boostsolutions'),
          'menu_name' => __('Quotations', 'uikit-boostsolutions'),
          'parent_item_colon' => __('Parent quotation', 'uikit-boostsolutions'),
          'all_items' => __('All quotations', 'uikit-boostsolutions'),
          'view_item' => __('View quotations', 'uikit-boostsolutions'),
          'add_new_item' => __('Add new quotation', 'uikit-boostsolutions'),
          'add_new' => __('New quotation', 'uikit-boostsolutions'),
          'edit_item' => __('Edit quotation', 'uikit-boostsolutions'),
          'update_item' => __('Update quotation', 'uikit-boostsolutions'),
          'search_items' => __('Search quotation', 'uikit-boostsolutions'),
          'not_found' => __('Not Found', 'uikit-boostsolutions'),
          'not_found_in_trash' => __('Not found in Trash', 'uikit-boostsolutions'),
        ),
        'public' => true,
        'menu_position' => 30,
        'has_archive' => true,
        'show_in_nav_menus' => true,
        'menu_icon' => 'dashicons-format-quote',
        'rewrite' => array('slug' => 'quotations'),
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
      )
    );
  }
endif;
?>