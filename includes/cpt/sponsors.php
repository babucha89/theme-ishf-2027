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
    echo '<p>'.__('Higher numbers are shown first (defaults to -1).', TEXT_DOMAIN).'</p>';
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
    echo '<p class="description">'.__('Higher numbers are shown first (defaults to -1).', TEXT_DOMAIN).'</p>';
    echo '</td>';
    echo '</tr>';
  }

  // -- Function that adds field on EDIT form
  //    Important: the HOOK has to start with the CPT Taxonomy declared name
  add_action('created_sponsor_types', 'ishf_cpt_sponsor_types_custom_fields_save');
  add_action('edited_sponsor_types', 'ishf_cpt_sponsor_types_custom_fields_save');
  function ishf_cpt_sponsor_types_custom_fields_save($term_id) {
    // Only when the field was submitted (terms created by code or by WPML keep their order)
    if (!isset($_POST['ishf_cf_sponsor_types_order'])) {
      return;
    }
    update_term_meta(
      $term_id,
      'ishf_cf_sponsor_types_order',
      sanitize_text_field(wp_unslash($_POST['ishf_cf_sponsor_types_order']))
    );
  }

endif;


//------------------------------------------------------------------------------
//  Sponsors grouped by type, for the site-wide sponsors section and the
//  Lisbon 2027 page. Returns a list of groups:
//    array('label' => 'Organização', 'slug' => 'organizacao', 'order' => 40, 'items' => array(post IDs))
//  - Groups: term field ishf_cf_sponsor_types_order, highest first, then name.
//  - Inside a group: custom field ishf_cf_order, highest first, then title.
//    Sponsors without an order value count as -1 (they go last).
//  - A sponsor with several types is listed under the first one.
//------------------------------------------------------------------------------
if (!function_exists('ishf_get_sponsor_groups')):
  function ishf_get_sponsor_groups()
  {
    $posts = get_posts(array(
      'post_type'        => 'sponsors',
      'post_status'      => 'publish',
      'numberposts'      => 100,
      'suppress_filters' => false, // WPML: current language only
    ));
    if (empty($posts)) {
      return array();
    }
    $order_of = function ($id) {
      $v = get_post_meta($id, 'ishf_cf_order', true);
      return is_numeric($v) ? (int) $v : -1;
    };
    usort($posts, function ($a, $b) use ($order_of) {
      return array($order_of($b->ID), $a->post_title) <=> array($order_of($a->ID), $b->post_title);
    });

    $groups = array();
    foreach ($posts as $p) {
      $terms = get_the_terms($p->ID, 'sponsor_types');
      $term  = (!empty($terms) && !is_wp_error($terms)) ? $terms[0] : null;
      $key   = $term ? $term->term_id : 0;
      if (!isset($groups[$key])) {
        $order = $term ? get_term_meta($term->term_id, 'ishf_cf_sponsor_types_order', true) : '';
        $groups[$key] = array(
          'label' => $term ? $term->name : __('Partners', TEXT_DOMAIN),
          'slug'  => $term ? $term->slug : 'partners',
          'order' => is_numeric($order) ? (int) $order : -1,
          'items' => array(),
        );
      }
      $groups[$key]['items'][] = $p->ID;
    }
    uasort($groups, function ($a, $b) {
      return array($b['order'], $a['label']) <=> array($a['order'], $b['label']);
    });
    return array_values($groups);
  }
endif;

// One sponsor logo (featured image + optional dark-mode logo), or its name when there is no logo
if (!function_exists('ishf_sponsor_logo_html')):
  function ishf_sponsor_logo_html($sponsor_id, $size = 'medium')
  {
    $name  = get_the_title($sponsor_id);
    $light = get_post_thumbnail_id($sponsor_id);
    $dark  = (int) get_post_meta($sponsor_id, 'ishf_cf_logo_dark_id', true);
    $att   = array('alt' => $name, 'loading' => 'lazy', 'class' => '');
    if ($light && $dark) {
      return wp_get_attachment_image($light, $size, false, array_merge($att, array('class' => 'light-mode-img')))
        . wp_get_attachment_image($dark, $size, false, array_merge($att, array('class' => 'dark-mode-img')));
    }
    if ($light) {
      return wp_get_attachment_image($light, $size, false, $att);
    }
    return '<span class="partner-name">' . esc_html($name) . '</span>';
  }
endif;
