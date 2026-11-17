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

// Visible part of a logo image (without empty margins): array(x, y, w, h) in fractions of
// the image (0..1), or null. Transparent images: pixels that are not (almost) transparent;
// opaque images: pixels that differ from the corner colour. Cached per file in the
// attachment meta '_ishf_logo_bbox'.
if (!function_exists('ishf_logo_content_box')):
  function ishf_logo_content_box($attachment_id)
  {
    $file = get_attached_file($attachment_id);
    if (!$file || !is_readable($file) || !function_exists('imagecreatefromstring')) {
      return null;
    }
    $stamp  = filemtime($file) . ':' . filesize($file);
    $cached = get_post_meta($attachment_id, '_ishf_logo_bbox', true);
    if (is_array($cached) && isset($cached['stamp']) && $cached['stamp'] === $stamp) {
      return $cached['box'];
    }
    $box = null;
    $im  = @imagecreatefromstring((string) file_get_contents($file));
    if ($im) {
      $w = imagesx($im);
      $h = imagesy($im);
      $step = max(1, (int) floor(max($w, $h) / 600)); // sample at most ~600 px per side
      $corner = imagecolorsforindex($im, imagecolorat($im, 0, 0));
      $transparent = $corner['alpha'] > 100;
      $x0 = $w; $y0 = $h; $x1 = -1; $y1 = -1;
      for ($y = 0; $y < $h; $y += $step) {
        for ($x = 0; $x < $w; $x += $step) {
          $c = imagecolorsforindex($im, imagecolorat($im, $x, $y));
          $ink = $transparent
            ? $c['alpha'] < 110
            : (abs($c['red'] - $corner['red']) + abs($c['green'] - $corner['green']) + abs($c['blue'] - $corner['blue']) > 45 && $c['alpha'] < 110);
          if ($ink) {
            if ($x < $x0) $x0 = $x;
            if ($y < $y0) $y0 = $y;
            if ($x > $x1) $x1 = $x;
            if ($y > $y1) $y1 = $y;
          }
        }
      }
      imagedestroy($im);
      if ($x1 >= $x0 && $y1 >= $y0) {
        $x1 = min($w - 1, $x1 + $step - 1);
        $y1 = min($h - 1, $y1 + $step - 1);
        $box = array($x0 / $w, $y0 / $h, ($x1 - $x0 + 1) / $w, ($y1 - $y0 + 1) / $h);
      }
    }
    update_post_meta($attachment_id, '_ishf_logo_bbox', array('stamp' => $stamp, 'box' => $box));
    return $box;
  }
endif;

// One sponsor logo (featured image + optional dark-mode logo), or its name when there is no logo.
// $box = array(area, max width, max height) in px: every logo gets the same visual
// weight (width x height = area) from its proportions, within the max box, so wide
// and tall logos look balanced. The sponsor's "Logo size (%)" field (ishf_cf_logo_scale,
// default 100) scales it by hand; above 100 it may also exceed the box by the same
// factor (e.g. a wide lockup with small text). "Logo width (px)" (ishf_cf_logo_width)
// overrides all of this with an exact width of the visible logo: the empty margins of
// the file are trimmed (ishf_logo_content_box()). Without $box the CSS decides the size.
// $opts: 'variant' => 'auto' (light / dark logo follow the site mode) or 'on-dark' (dark
// background in both modes: dark-mode logo only); 'fixed' => false ignores "Logo width (px)".
if (!function_exists('ishf_sponsor_logo_html')):
  function ishf_sponsor_logo_html($sponsor_id, $size = 'medium', $box = null, $opts = array())
  {
    $opts  = wp_parse_args($opts, array('variant' => 'auto', 'fixed' => true));
    $name  = get_the_title($sponsor_id);
    $light = get_post_thumbnail_id($sponsor_id);
    $dark  = (int) get_post_meta($sponsor_id, 'ishf_cf_logo_dark_id', true);
    $scale = get_post_meta($sponsor_id, 'ishf_cf_logo_scale', true);
    $scale = is_numeric($scale) ? max(50, min(300, (int) $scale)) / 100 : 1;
    $fixed = $opts['fixed'] ? (int) get_post_meta($sponsor_id, 'ishf_cf_logo_width', true) : 0; // exact width in px (0 = automatic)

    $img = function ($attachment_id, $class) use ($name, $size, $box, $scale, $fixed) {
      $att = array('alt' => $name, 'loading' => 'lazy', 'class' => $class);
      if ($fixed > 0) {
        // "Logo width (px)" wins: the visible logo (empty margins of the file trimmed) is this wide
        $src = wp_get_attachment_image_src($attachment_id, $size);
        $bb  = ishf_logo_content_box($attachment_id);
        if ($src && $src[1] > 0 && $src[2] > 0 && $bb && ($bb[2] < 0.98 || $bb[3] < 0.98)) {
          $content_ratio = ($src[1] * $bb[2]) / ($src[2] * $bb[3]);
          $att['class'] = '';
          $att['style'] = sprintf('position:absolute;max-width:none;max-height:none;width:%.3f%%;height:auto;left:%.3f%%;top:%.3f%%;', 100 / $bb[2], -100 * $bb[0] / $bb[2], -100 * $bb[1] / $bb[3]);
          return '<span class="sponsor-logo-trim' . ($class ? ' ' . esc_attr($class) : '') . '" style="width:' . $fixed . 'px;aspect-ratio:' . round($content_ratio, 4) . ';">'
            . wp_get_attachment_image($attachment_id, $size, false, $att) . '</span>';
        }
        $att['style'] = 'width:' . $fixed . 'px;height:auto;max-height:none;';
      } elseif (is_array($box) && count($box) === 3) {
        list($area, $max_w, $max_h) = $box;
        $src = wp_get_attachment_image_src($attachment_id, $size);
        $w   = $src ? (int) $src[1] : 0;
        $h   = $src ? (int) $src[2] : 0;
        if ($w > 0 && $h > 0) {
          $ratio  = $w / $h;
          $width  = sqrt($area * $ratio) * $scale;     // same area for every logo
          $grow   = max(1, $scale);                         // "Logo size" above 100% also enlarges the box
          $width  = min($width, $max_w * $grow, $max_h * $grow * $ratio); // stay inside the box
          $att['style'] = 'width:' . round($width) . 'px;height:auto;';
        } else {
          $att['style'] = 'width:auto;height:' . round(sqrt($area) * $scale) . 'px;'; // e.g. SVG without sizes
        }
      }
      return wp_get_attachment_image($attachment_id, $size, false, $att);
    };

    // On a dark background in both modes (footer): the dark-mode logo, or the normal logo on a light backing
    if ($opts['variant'] === 'on-dark') {
      if ($dark) {
        return $img($dark, '');
      }
      return $light ? '<span class="logo-light-backing">' . $img($light, '') . '</span>' : '<span class="partner-name">' . esc_html($name) . '</span>';
    }
    if ($light && $dark) {
      return $img($light, 'light-mode-img') . $img($dark, 'dark-mode-img');
    }
    if ($light) {
      return $img($light, '');
    }
    return '<span class="partner-name">' . esc_html($name) . '</span>';
  }
endif;


//------------------------------------------------------------------------------
//  Organisers: the sponsors of the first sponsor type (highest "Order", e.g.
//  "Organisation"), shown in the header and footer of every page
//  (includes/organisers.php). Another type can be chosen by slug:
//  add_filter('ishf_organisers_sponsor_type', fn() => 'organisation');
//------------------------------------------------------------------------------
if (!function_exists('ishf_get_organisers')):
  function ishf_get_organisers()
  {
    $groups = ishf_get_sponsor_groups();
    if (empty($groups)) {
      return array();
    }
    $slug = (string) apply_filters('ishf_organisers_sponsor_type', '');
    if ($slug !== '') {
      foreach ($groups as $group) {
        if ($group['slug'] === $slug) {
          return $group['items'];
        }
      }
      return array();
    }
    return $groups[0]['items'];
  }
endif;
