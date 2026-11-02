<?php
// -----------------------------------------------------------------------------
//  Custom Post Type: SPONSOR (+ taxonomy "Sponsor tier")
//  Managed in WP admin, rendered by includes/sections/sponsors.php on every page
//  that uses the full layout (before the newsletter block and the footer).
//  - Title            : sponsor name (also the logo alt text)
//  - Featured image   : logo for LIGHT mode (use the dark-coloured logo version)
//  - Dark-mode logo   : optional logo for DARK mode (use the light-coloured version)
//  - Website URL      : external link (opens in a new tab)
//  - Order            : "Order" field in Page Attributes (lower first)
//  - Tier             : "Organised with", "Supported by", ... (taxonomy, free to edit)
//  Translatable with WPML (WPML > Settings > Post Types Translation).
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------

add_action('init', function () {
  register_post_type('ishf_sponsor', array(
    'labels' => array(
      'name'               => __('Sponsors', TEXT_DOMAIN),
      'singular_name'      => __('Sponsor', TEXT_DOMAIN),
      'add_new_item'       => __('Add new sponsor', TEXT_DOMAIN),
      'edit_item'          => __('Edit sponsor', TEXT_DOMAIN),
      'all_items'          => __('All sponsors', TEXT_DOMAIN),
      'featured_image'     => __('Logo (light mode)', TEXT_DOMAIN),
      'set_featured_image' => __('Set logo (light mode)', TEXT_DOMAIN),
    ),
    'public'              => false,
    'publicly_queryable'  => false,
    'exclude_from_search' => true,
    'show_ui'             => true,
    'show_in_rest'        => true,
    'show_in_nav_menus'   => false,
    'has_archive'         => false,
    'rewrite'             => false,
    'menu_position'       => 21,
    'menu_icon'           => 'dashicons-awards',
    'supports'            => array('title', 'thumbnail', 'page-attributes'),
  ));

  register_taxonomy('ishf_sponsor_tier', 'ishf_sponsor', array(
    'labels'             => array('name' => __('Sponsor tiers', TEXT_DOMAIN), 'singular_name' => __('Sponsor tier', TEXT_DOMAIN)),
    'public'             => false,
    'show_ui'            => true,
    'show_in_rest'       => true,
    'show_admin_column'  => true,
    'hierarchical'       => true,
    'rewrite'            => false,
  ));
});

add_action('add_meta_boxes', function () {
  add_meta_box('ishf_sponsor_details', __('Sponsor details', TEXT_DOMAIN), 'ishf_sponsor_meta_box', 'ishf_sponsor', 'normal', 'high');
});

function ishf_sponsor_meta_box($post)
{
  wp_nonce_field('ishf_sponsor_save', 'ishf_sponsor_nonce');
  $url     = get_post_meta($post->ID, '_ishf_sponsor_url', true);
  $dark_id = (int) get_post_meta($post->ID, '_ishf_sponsor_logo_dark_id', true);
  $dark    = $dark_id ? wp_get_attachment_image_url($dark_id, 'medium') : '';
  ?>
  <p>
    <label for="ishf_sponsor_url"><strong><?php _e('Website URL', TEXT_DOMAIN); ?></strong></label><br>
    <input type="url" id="ishf_sponsor_url" name="ishf_sponsor_url" class="widefat" value="<?php echo esc_attr($url); ?>" placeholder="https://">
  </p>
  <p><strong><?php _e('Logo for dark mode (optional)', TEXT_DOMAIN); ?></strong><br>
    <span class="description"><?php _e('Light-coloured version of the logo. If empty, the light-mode logo is used in both modes.', TEXT_DOMAIN); ?></span></p>
  <p>
    <input type="hidden" id="ishf_sponsor_logo_dark_id" name="ishf_sponsor_logo_dark_id" value="<?php echo esc_attr($dark_id); ?>">
    <img id="ishf_sponsor_logo_dark_preview" src="<?php echo esc_url($dark); ?>" alt="" style="max-width:240px;background:#0A3558;padding:8px;<?php echo $dark ? '' : 'display:none;'; ?>"><br>
    <button type="button" class="button" id="ishf_sponsor_logo_dark_pick"><?php _e('Choose image', TEXT_DOMAIN); ?></button>
    <button type="button" class="button-link-delete" id="ishf_sponsor_logo_dark_clear"><?php _e('Remove', TEXT_DOMAIN); ?></button>
  </p>
  <script>
  jQuery(function ($) {
    var frame;
    $('#ishf_sponsor_logo_dark_pick').on('click', function (e) {
      e.preventDefault();
      frame = frame || wp.media({ title: '<?php echo esc_js(__('Dark-mode logo', TEXT_DOMAIN)); ?>', multiple: false, library: { type: 'image' } });
      frame.off('select').on('select', function () {
        var a = frame.state().get('selection').first().toJSON();
        $('#ishf_sponsor_logo_dark_id').val(a.id);
        $('#ishf_sponsor_logo_dark_preview').attr('src', a.url).show();
      });
      frame.open();
    });
    $('#ishf_sponsor_logo_dark_clear').on('click', function () {
      $('#ishf_sponsor_logo_dark_id').val('');
      $('#ishf_sponsor_logo_dark_preview').hide();
    });
  });
  </script>
  <?php
}

add_action('admin_enqueue_scripts', function ($hook) {
  $screen = get_current_screen();
  if ($screen && $screen->post_type === 'ishf_sponsor') {
    wp_enqueue_media();
  }
});

add_action('save_post_ishf_sponsor', function ($post_id) {
  if (!isset($_POST['ishf_sponsor_nonce']) || !wp_verify_nonce($_POST['ishf_sponsor_nonce'], 'ishf_sponsor_save')) {
    return;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }
  update_post_meta($post_id, '_ishf_sponsor_url', esc_url_raw(wp_unslash($_POST['ishf_sponsor_url'] ?? '')));
  update_post_meta($post_id, '_ishf_sponsor_logo_dark_id', absint($_POST['ishf_sponsor_logo_dark_id'] ?? 0));
});
