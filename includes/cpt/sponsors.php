<?php
//------------------------------------------------------------------------------
//  Creating SPONSORS CPT
//------------------------------------------------------------------------------
if (!function_exists('ishf_set_cpt_sponsors')):
  add_action('init', 'ishf_set_cpt_sponsors');
  function ishf_set_cpt_sponsors() {
    register_post_type('sponsors',
      array(
        'labels' => array(
          'name' => __('Sponsors', TEXT_DOMAIN),
          'singular_name' => __('Sponsor', TEXT_DOMAIN),
          'menu_name' => __('Sponsors', TEXT_DOMAIN),
          'parent_item_colon' => __('Parent sponsor', TEXT_DOMAIN),
          'all_items' => __('All sponsors', TEXT_DOMAIN),
          'view_item' => __('View sponsors', TEXT_DOMAIN),
          'add_new_item' => __('Add new sponsor', TEXT_DOMAIN),
          'add_new' => __('New sponsor', TEXT_DOMAIN),
          'edit_item' => __('Edit sponsor', TEXT_DOMAIN),
          'update_item' => __('Update sponsor', TEXT_DOMAIN),
          'search_items' => __('Search sponsor', TEXT_DOMAIN),
          'not_found' => __('Not Found', TEXT_DOMAIN),
          'not_found_in_trash' => __('Not found in Trash', TEXT_DOMAIN),
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

if (!function_exists('ishf_set_cpt_sponsor_types')):

  add_action('init', 'ishf_set_cpt_sponsor_types', 0);
  function ishf_set_cpt_sponsor_types() {
    register_taxonomy('sponsor_types',
      array('sponsors'),
      array(
        'hierarchical' => true,
        'labels' => array(
          'name' => _x('Sponsor Types', 'taxonomy general name', TEXT_DOMAIN),
          'singular_name' => _x('Sponsor Type', 'taxonomy singular name', TEXT_DOMAIN),
          'search_items' => __('Search Sponsor Types', TEXT_DOMAIN),
          'all_items' => __('All Sponsor Types', TEXT_DOMAIN),
          'parent_item' => __('Parent Sponsor Type', TEXT_DOMAIN),
          'parent_item_colon' => __('Parent Sponsor Type:', TEXT_DOMAIN),
          'edit_item' => __('Edit Sponsor Type', TEXT_DOMAIN),
          'update_item' => __('Update Sponsor Type', TEXT_DOMAIN),
          'add_new_item' => __('Add New Sponsor Type', TEXT_DOMAIN),
          'new_item_name' => __('New Sponsor Type', TEXT_DOMAIN),
          'menu_name' => __('Sponsor Types', TEXT_DOMAIN),
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
  add_action('sponsor_types_add_form_fields', 'ishf_cpt_sponsor_types_custom_fields_add');
  function ishf_cpt_sponsor_types_custom_fields_add($taxonomy){
    echo '<div class="form-field">';
    echo '<label for="ishf_cf_sponsor_types_order">'.__('Order', TEXT_DOMAIN).'</label>';
    echo '<input type="text" name="ishf_cf_sponsor_types_order" id="ishf_cf_sponsor_types_order" value="-1" />';
    echo '<p>'.__('Use to order elements from minor to major (defaults to -1)', TEXT_DOMAIN).'</p>';
    echo '</div>';
  }

  // -- Function that adds field on EDIT form
  //    Important: the HOOK has to start with the CPT Taxonomy declared name
  add_action('sponsor_types_edit_form_fields', 'ishf_cpt_sponsor_types_custom_fields_edit', 10, 2);
  function ishf_cpt_sponsor_types_custom_fields_edit($term, $taxonomy){
    // get meta data value
    $ishf_cf_sponsor_types_order = get_term_meta($term->term_id, 'ishf_cf_sponsor_types_order', true);

    echo '<tr class="form-field">';
    echo '<th><label for="ishf_cf_sponsor_types_order">'.__('Order', TEXT_DOMAIN).'</label></th>';
    echo '<td>';
    echo '<input name="ishf_cf_sponsor_types_order" id="ishf_cf_sponsor_types_order" type="text" value="'.esc_attr($ishf_cf_sponsor_types_order).'" />';
    echo '<p class="description">'.__('Use to order elements from minor to major (defaults to -1)', TEXT_DOMAIN).'</p>';
    echo '</td>';
    echo '</tr>';
  }

  // -- Function that adds field on EDIT form
  //    Important: the HOOK has to start with the CPT Taxonomy declared name
  add_action('created_sponsor_types', 'ishf_cpt_sponsor_types_custom_fields_save');
  add_action('edited_sponsor_types', 'ishf_cpt_sponsor_types_custom_fields_save');
  function ishf_cpt_sponsor_types_custom_fields_save($term_id) {
    update_term_meta(
      $term_id,
      'ishf_cf_sponsor_types_order',
      sanitize_text_field($_POST['ishf_cf_sponsor_types_order'])
    );
  }

endif;
?>