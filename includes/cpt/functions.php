<?php
// -----------------------------------------------------------------------------
//  File: CUSTOM FIELDS for the CPT (meta box "Extra Parameters")
//  Values are stored in the postmeta table as "ishf_cf_" + field name
//  (e.g. ishf_cf_order, ishf_cf_url, ishf_cf_date_start).
//  The meta box is added to every post type named in a field "scope".
//
//  Field types: text (default), number, date, checkbox, radio, image,
//  posts_checkboxes (list of posts of 'post_type_target', saved comma-separated).
//
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 11-NOV-2026
// -----------------------------------------------------------------------------

// Field definitions (a function, so the labels are translated after 'init')
function ishf_get_custom_fields()
{
  return array(

    // -- ATTENDANTS
    array(
      'name'        => 'organization',
      'title'       => __('Organization', TEXT_DOMAIN),
      'description' => __('Name of the Association or Company', TEXT_DOMAIN),
      'type'        => 'text',
      'scope'       => array('attendants'),
    ),
    array(
      'name'        => 'function',
      'title'       => __('Function', TEXT_DOMAIN),
      'description' => null,
      'type'        => 'text',
      'scope'       => array('attendants'),
    ),
    array(
      'name'        => 'gender',
      'title'       => __('Gender', TEXT_DOMAIN),
      'description' => null,
      'type'        => 'radio',
      'labels'      => array('F', 'M'),
      'values'      => array('female', 'male'),
      'scope'       => array('attendants'),
    ),

    // -- SEMINARS
    array(
      'name'             => 'attendants_related',
      'title'            => __('Associated Attendants', TEXT_DOMAIN),
      'description'      => __('Select the speakers associated with this event.', TEXT_DOMAIN),
      'type'             => 'posts_checkboxes',
      'post_type_target' => 'attendants',
      'scope'            => array('seminars'),
    ),
    array(
      'name'        => 'is_provisional',
      'title'       => __('Is event provisional?', TEXT_DOMAIN),
      'description' => __('Check if event is provisional (can restrict how it is shown)', TEXT_DOMAIN),
      'type'        => 'checkbox',
      'scope'       => array('seminars'),
    ),
    array(
      'name'        => 'date_time_1',
      'title'       => __('Date / Time #1', TEXT_DOMAIN),
      'description' => __('Schedule the date and time (yyyy-mm-dd hh:mm:ss) for start', TEXT_DOMAIN),
      'type'        => 'text',
      'scope'       => array('seminars'),
    ),
    array(
      'name'        => 'date_time_2',
      'title'       => __('Date / Time #2', TEXT_DOMAIN),
      'description' => __('Schedule the date and time (yyyy-mm-dd hh:mm:ss) for start', TEXT_DOMAIN),
      'type'        => 'text',
      'scope'       => array('seminars'),
    ),
    array(
      'name'        => 'date_end_time_1',
      'title'       => __('Date / Time End #1', TEXT_DOMAIN),
      'description' => __('Schedule the date and time (yyyy-mm-dd hh:mm:ss) for end', TEXT_DOMAIN),
      'type'        => 'text',
      'scope'       => array('seminars'),
    ),
    array(
      'name'        => 'date_end_time_2',
      'title'       => __('Date / Time End #2', TEXT_DOMAIN),
      'description' => __('Schedule the date and time (yyyy-mm-dd hh:mm:ss) for end', TEXT_DOMAIN),
      'type'        => 'text',
      'scope'       => array('seminars'),
    ),
    array(
      'name'        => 'youtube_id_1',
      'title'       => __('Video ID #1', TEXT_DOMAIN),
      'description' => null,
      'type'        => 'text',
      'scope'       => array('seminars'),
    ),
    array(
      'name'        => 'youtube_id_2',
      'title'       => __('Video ID #2', TEXT_DOMAIN),
      'description' => null,
      'type'        => 'text',
      'scope'       => array('seminars'),
    ),
    array(
      'name'        => 'event_local',
      'title'       => __('Event Location', TEXT_DOMAIN),
      'description' => null,
      'type'        => 'text',
      'scope'       => array('seminars'),
    ),

    // -- SPONSORS (logo = featured image)
    array(
      'name'        => 'url',
      'title'       => __('Remote URL', TEXT_DOMAIN),
      'description' => null,
      'type'        => 'text',
      'scope'       => array('sponsors'),
    ),

    // -- EDITIONS (past festivals; logo = featured image)
    array(
      'name'        => 'date_start',
      'title'       => __('Date Start', TEXT_DOMAIN),
      'description' => __('First day of the festival.', TEXT_DOMAIN),
      'type'        => 'date',
      'scope'       => array('editions'),
    ),
    array(
      'name'        => 'date_end',
      'title'       => __('Date End', TEXT_DOMAIN),
      'description' => __('Last day of the festival (leave empty for a one-day event).', TEXT_DOMAIN),
      'type'        => 'date',
      'scope'       => array('editions'),
    ),
    array(
      'name'        => 'venue',
      'title'       => __('Venue', TEXT_DOMAIN),
      'description' => __('Name of the venue, e.g. "Centro de Congressos de Lisboa".', TEXT_DOMAIN),
      'type'        => 'text',
      'scope'       => array('editions'),
    ),
    array(
      'name'        => 'address',
      'title'       => __('Address', TEXT_DOMAIN),
      'description' => __('Street, postcode and city. The map is placed from this address.', TEXT_DOMAIN),
      'type'        => 'text',
      'scope'       => array('editions'),
    ),
    array(
      'name'        => 'geolocation',
      'title'       => __('Map coordinates', TEXT_DOMAIN),
      'description' => __('Latitude, longitude (e.g. 38.6966, -9.2032). Filled in automatically from the address when you save; type your own to move the pin.', TEXT_DOMAIN),
      'type'        => 'text',
      'scope'       => array('editions'),
    ),

    // -- SHARED
    array(
      'name'        => 'logo_dark_id',
      'title'       => __('Logo for dark mode', TEXT_DOMAIN),
      'description' => __('Optional. Light version of the logo, shown when the site is in dark mode.', TEXT_DOMAIN),
      'type'        => 'image',
      'scope'       => array('sponsors', 'editions'),
    ),
    array(
      'name'        => 'order',
      'title'       => __('Order', TEXT_DOMAIN),
      'description' => __('Use to order elements from minor to major (defaults to -1)', TEXT_DOMAIN),
      'type'        => 'number',
      'scope'       => array('attendants', 'faqs', 'seminars', 'sponsors'),
    ),
  );
}


if (!class_exists('ISHF_Custom_Fields')):

  class ISHF_Custom_Fields
  {
    /** @var string Prefix of the meta keys */
    const PREFIX = 'ishf_cf_';

    /** @var array Field definitions */
    private $fields = array();

    /** @var array Post types that get the meta box (every post type found in a field scope) */
    private $post_types = array();

    public function __construct()
    {
      add_action('init', array($this, 'load_fields'), 20);
      add_action('add_meta_boxes', array($this, 'add_meta_box'));
      add_action('save_post', array($this, 'save'), 1, 2);
      add_action('do_meta_boxes', array($this, 'remove_default_meta_box'), 10, 3);
      add_action('admin_enqueue_scripts', array($this, 'enqueue_media'));
    }

    public function load_fields()
    {
      foreach (ishf_get_custom_fields() as $field) {
        $this->fields[] = wp_parse_args($field, array('description' => null, 'type' => 'text', 'scope' => array(), 'capability' => 'edit_posts'));
        $this->post_types = array_merge($this->post_types, (array) $field['scope']);
      }
      $this->post_types = array_values(array_unique($this->post_types));
    }

    /** Hide WordPress's own "Custom Fields" box: the values are edited here */
    public function remove_default_meta_box($type, $context, $post)
    {
      foreach (array('normal', 'advanced', 'side') as $box_context) {
        foreach ($this->post_types as $post_type) {
          remove_meta_box('postcustom', $post_type, $box_context);
        }
      }
    }

    public function add_meta_box()
    {
      foreach ($this->post_types as $post_type) {
        add_meta_box('ishf-custom-fields', __('Extra Parameters', TEXT_DOMAIN), array($this, 'render'), $post_type, 'normal', 'high');
      }
    }

    /** The image field opens the media library */
    public function enqueue_media($hook)
    {
      if (!in_array($hook, array('post.php', 'post-new.php'), true)) {
        return;
      }
      $screen = get_current_screen();
      if ($screen && in_array($screen->post_type, $this->post_types, true)) {
        wp_enqueue_media();
      }
    }

    private function fields_for($post_type)
    {
      return array_filter($this->fields, function ($field) use ($post_type) {
        return in_array($post_type, $field['scope'], true);
      });
    }

    public function render($post)
    {
      wp_nonce_field('ishf-custom-fields', 'ishf-custom-fields_wpnonce');
      echo '<div class="form-wrap">';
      foreach ($this->fields_for($post->post_type) as $field) {
        if (!current_user_can($field['capability'], $post->ID)) {
          continue;
        }
        $key   = self::PREFIX . $field['name'];
        $value = get_post_meta($post->ID, $key, true);
        $title = esc_html($field['title']);

        echo '<div class="form-field">';
        switch ($field['type']) {
          case 'posts_checkboxes':
            $saved   = $value !== '' ? array_map('intval', explode(',', $value)) : array();
            $targets = get_posts(array('post_type' => $field['post_type_target'], 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC'));
            echo '<label><b>' . $title . '</b></label>';
            echo '<div style="max-height:200px; overflow-y:auto; padding:10px; border:1px solid #ddd; background:#fff; margin-top:5px;">';
            foreach ($targets as $target) {
              $org   = get_post_meta($target->ID, self::PREFIX . 'organization', true);
              $label = $target->post_title . ($org ? ', ' . $org : '');
              echo '<label style="display:block; margin-bottom:5px; font-weight:normal;">';
              echo '<input type="checkbox" name="' . esc_attr($key) . '[]" value="' . (int) $target->ID . '"' . checked(in_array($target->ID, $saved, true), true, false) . ' style="width:auto;" /> ';
              echo esc_html($label) . '</label>';
            }
            echo '</div>';
            break;

          case 'checkbox':
            echo '<label for="' . esc_attr($key) . '" style="display:inline;"><b>' . $title . '</b></label>&nbsp;&nbsp;';
            echo '<input type="checkbox" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" value="yes"' . checked($value, 'yes', false) . ' style="width:auto;" />';
            break;

          case 'radio':
            echo '<fieldset><legend><b>' . $title . '</b></legend>';
            foreach ($field['values'] as $i => $option) {
              $id = $key . '_' . $option;
              echo '<input type="radio" name="' . esc_attr($key) . '" id="' . esc_attr($id) . '" value="' . esc_attr($option) . '"' . checked($value, $option, false) . ' style="width:auto;" />';
              echo '<label for="' . esc_attr($id) . '" style="display:inline;">' . esc_html($field['labels'][$i]) . '</label>&nbsp;&nbsp;';
            }
            echo '</fieldset>';
            break;

          case 'number':
            echo '<label for="' . esc_attr($key) . '"><b>' . $title . '</b></label>';
            echo '<input type="number" step="1" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" value="' . esc_attr($value === '' ? '-1' : $value) . '" />';
            break;

          case 'date':
            echo '<label for="' . esc_attr($key) . '"><b>' . $title . '</b></label>';
            echo '<input type="date" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" value="' . esc_attr($value) . '" style="width:auto;" />';
            break;

          case 'image':
            $image_url = $value ? wp_get_attachment_image_url((int) $value, 'medium') : '';
            echo '<label><b>' . $title . '</b></label>';
            echo '<div class="ishf-image-field">';
            echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
            echo '<img src="' . esc_url($image_url) . '" alt="" style="max-width:200px; max-height:120px; margin:8px 0; padding:8px; background:#555;' . ($image_url ? '' : ' display:none;') . '" />';
            echo '<p style="margin:0;"><button type="button" class="button ishf-image-select">' . esc_html__('Select Image', TEXT_DOMAIN) . '</button> ';
            echo '<button type="button" class="button ishf-image-remove"' . ($image_url ? '' : ' style="display:none;"') . '>' . esc_html__('Remove Image', TEXT_DOMAIN) . '</button></p>';
            echo '</div>';
            break;

          default:
            echo '<label for="' . esc_attr($key) . '"><b>' . $title . '</b></label>';
            echo '<input type="text" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
            break;
        }
        if (!empty($field['description'])) {
          echo '<p>' . esc_html($field['description']) . '</p>';
        }
        echo '</div>';
      }
      echo '</div>';
      $this->image_field_script();
    }

    /** One script for every image field in the box */
    private function image_field_script()
    {
      ?>
      <script>
      jQuery(function ($) {
        $('.ishf-image-field').each(function () {
          var $box = $(this), $input = $box.find('input[type="hidden"]'), $img = $box.find('img'), $remove = $box.find('.ishf-image-remove');
          $box.find('.ishf-image-select').on('click', function (e) {
            e.preventDefault();
            var frame = wp.media({ title: <?php echo wp_json_encode(__('Select or Upload Image', TEXT_DOMAIN)); ?>, button: { text: <?php echo wp_json_encode(__('Use this image', TEXT_DOMAIN)); ?> }, library: { type: 'image' }, multiple: false });
            frame.on('select', function () {
              var file = frame.state().get('selection').first().toJSON();
              $input.val(file.id);
              $img.attr('src', (file.sizes && file.sizes.medium ? file.sizes.medium.url : file.url)).show();
              $remove.show();
            });
            frame.open();
          });
          $remove.on('click', function (e) {
            e.preventDefault();
            $input.val('');
            $img.hide();
            $remove.hide();
          });
        });
      });
      </script>
      <?php
    }

    public function save($post_id, $post)
    {
      if (!isset($_POST['ishf-custom-fields_wpnonce']) || !wp_verify_nonce($_POST['ishf-custom-fields_wpnonce'], 'ishf-custom-fields')) {
        return;
      }
      if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || !current_user_can('edit_post', $post_id)) {
        return;
      }
      foreach ($this->fields_for($post->post_type) as $field) {
        if (!current_user_can($field['capability'], $post_id)) {
          continue;
        }
        $key   = self::PREFIX . $field['name'];
        $value = isset($_POST[$key]) ? wp_unslash($_POST[$key]) : '';

        switch ($field['type']) {
          case 'posts_checkboxes':
            $value = is_array($value) ? implode(',', array_filter(array_map('intval', $value))) : '';
            break;
          case 'number':
            $value = is_numeric($value) ? (string) (int) $value : '-1';
            break;
          case 'image':
            $value = absint($value) ? (string) absint($value) : '';
            break;
          case 'date':
            $value = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value) ? $value : '';
            break;
          case 'checkbox':
            $value = $value === 'yes' ? 'yes' : '';
            break;
          default:
            $value = sanitize_text_field((string) $value);
            break;
        }

        if ($value !== '') {
          update_post_meta($post_id, $key, $value);
        } else {
          delete_post_meta($post_id, $key);
        }
      }
    }
  }

  new ISHF_Custom_Fields();

endif;
