<?php
//------------------------------------------------------------------------------
//  Creating SPONSORS CPT
//------------------------------------------------------------------------------
if (!function_exists('bs_set_cpt_sponsors')):
  add_action('init', 'bs_set_cpt_sponsors');
  function bs_set_cpt_sponsors() {
    register_post_type('sponsors',
      array(
        'labels' => array(
          'name' => __('Sponsors', 'uikit-boostsolutions'),
          'singular_name' => __('Sponsor', 'uikit-boostsolutions'),
          'menu_name' => __('Sponsors', 'uikit-boostsolutions'),
          'parent_item_colon' => __('Parent sponsor', 'uikit-boostsolutions'),
          'all_items' => __('All sponsors', 'uikit-boostsolutions'),
          'view_item' => __('View sponsors', 'uikit-boostsolutions'),
          'add_new_item' => __('Add new sponsor', 'uikit-boostsolutions'),
          'add_new' => __('New sponsor', 'uikit-boostsolutions'),
          'edit_item' => __('Edit sponsor', 'uikit-boostsolutions'),
          'update_item' => __('Update sponsor', 'uikit-boostsolutions'),
          'search_items' => __('Search sponsor', 'uikit-boostsolutions'),
          'not_found' => __('Not Found', 'uikit-boostsolutions'),
          'not_found_in_trash' => __('Not found in Trash', 'uikit-boostsolutions'),
        ),
        'public' => true,
        'menu_position' => 35,
        'has_archive' => true,
        'show_in_nav_menus' => true,
        'menu_icon' => 'dashicons-superhero-alt',
        'rewrite' => array('slug' => 'sponsors'),
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'taxonomies' => array('sponsor_types'),
      )
    );
  }
endif;

if (!function_exists('bs_set_cpt_sponsor_types')):

  add_action('init', 'bs_set_cpt_sponsor_types', 0);
  function bs_set_cpt_sponsor_types() {
    register_taxonomy('sponsor_types',
      array('sponsors'),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => _x('Sponsor Types', 'taxonomy general name', 'uikit-boostsolutions'),
          'singular_name' => _x('Sponsor Type', 'taxonomy singular name', 'uikit-boostsolutions'),
          'search_items' => __('Search Sponsor Types', 'uikit-boostsolutions'),
          'all_items' => __('All Sponsor Types', 'uikit-boostsolutions'),
          'parent_item' => __('Parent Sponsor Type', 'uikit-boostsolutions'),
          'parent_item_colon' => __('Parent Sponsor Type:', 'uikit-boostsolutions'),
          'edit_item' => __('Edit Sponsor Type', 'uikit-boostsolutions'),
          'update_item' => __('Update Sponsor Type', 'uikit-boostsolutions'),
          'add_new_item' => __('Add New Sponsor Type', 'uikit-boostsolutions'),
          'new_item_name' => __('New Sponsor Type', 'uikit-boostsolutions'),
          'menu_name' => __('Sponsor Types', 'uikit-boostsolutions'),
        ),
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'type'),
      )
    );
  }

  // -- Function that adds field on ADD form 
  //    Important: the HOOK has to start with the CPT Taxonomy declared name
  add_action('sponsor_types_add_form_fields', 'bs_cpt_sponsor_types_custom_fields_add');
  function bs_cpt_sponsor_types_custom_fields_add($taxonomy){
    echo '<div class="form-field">';
    echo '<label for="bs_cf_sponsor_types_order">'.__('Order', 'uikit-boostsolutions').'</label>';
    echo '<input type="text" name="bs_cf_sponsor_types_order" id="bs_cf_sponsor_types_order" value="-1" />';
    echo '<p>'.__('Use to order elements from minor to major (defaults to -1)', 'uikit-boostsolutions').'</p>';
    echo '</div>';
  }

  // -- Function that adds field on EDIT form
  //    Important: the HOOK has to start with the CPT Taxonomy declared name
  add_action('sponsor_types_edit_form_fields', 'bs_cpt_sponsor_types_custom_fields_edit', 10, 2);
  function bs_cpt_sponsor_types_custom_fields_edit($term, $taxonomy){
    // get meta data value
    $bs_cf_sponsor_types_order = get_term_meta($term->term_id, 'bs_cf_sponsor_types_order', true);

    echo '<tr class="form-field">';
    echo '<th><label for="bs_cf_sponsor_types_order">'.__('Order', 'uikit-boostsolutions').'</label></th>';
    echo '<td>';
    echo '<input name="bs_cf_sponsor_types_order" id="bs_cf_sponsor_types_order" type="text" value="'.esc_attr($bs_cf_sponsor_types_order).'" />';
    echo '<p class="description">'.__('Use to order elements from minor to major (defaults to -1)', 'uikit-boostsolutions').'</p>';
    echo '</td>';
    echo '</tr>';
  }

  // -- Function that adds field on EDIT form
  //    Important: the HOOK has to start with the CPT Taxonomy declared name
  add_action('created_sponsor_types', 'bs_cpt_sponsor_types_custom_fields_save');
  add_action('edited_sponsor_types', 'bs_cpt_sponsor_types_custom_fields_save');
  function bs_cpt_sponsor_types_custom_fields_save($term_id) {
    update_term_meta(
      $term_id,
      'bs_cf_sponsor_types_order',
      sanitize_text_field($_POST['bs_cf_sponsor_types_order'])
    );
  }

endif;
?>