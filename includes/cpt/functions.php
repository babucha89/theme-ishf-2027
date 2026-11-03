<?php
// -----------------------------------------------------------------------------
//  File: FUNCTIONS for CPT
//  Edited: 3L, 12-FEV-2025
// -----------------------------------------------------------------------------

// Note: use $child_customFields on CHILD THEME to declare specific VARS


// TODO: add support for SELECT / DROPDOWN type, change declaration for RADIO (using unique ARRAY) and format code to remove extra spaces

// array(
//   'name'          => 'action_scale',
//   'title'         => __('Action Scale'),
//   'description'   => __('Select the action scale for this project'),
//   'type'          => 'select',
//   'options'       => array(
//     array('label' => __('National'), 'value' => 'national'),
//     array('label' => __('Regional'), 'value' => 'regional'),
//     array('label' => __('Local'), 'value' => 'local'),
//   ),
//   'scope'         => array('project'),
//   'capability'    => 'edit_posts',
// ),



$theme_customFields = array(
  array(
    'name'          => 'author',
    'title'         => __('Author'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('quotations'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),


  // -- EVENTS fields
  array(
    'name'          => 'is_featured',
    'title'         => __('Is event featured?'),
    'description'   => __('Check if event is featured (can restrict how it is shown)'),
    'type'          => 'checkbox',
    'scope'         => array('events'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'id_event',
    'title'         => __('ID of Event'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('events'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'dates',
    'title'         => __('Date(s) of Event'),
    'description'   => __('The date or interval for the event. This text is displayed as inserted.'),
    'type'          => 'text',
    'scope'         => array('events'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'date_start',
    'title'         => __('Date Start'),
    'description'   => __('The date for the start of the event. Use format yyyy-mm-dd hh:mm:ss.'),
    'type'          => 'text',
    'scope'         => array('events'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'date_end',
    'title'         => __('Date End'),
    'description'   => __('The date for the end of the event. Use format yyyy-mm-dd hh:mm:ss.'),
    'type'          => 'text',
    'scope'         => array('events'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'address',
    'title'         => __('Address'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('events'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'geolocation',
    'title'         => __('Geolocation'),
    'description'   => __('Use coordinates from Google Maps (separated by ",")'),
    'type'          => 'text',
    'scope'         => array('events'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'extra_information',
    'title'         => __('Extra Information'),
    'description'   => __('Insert additional information, useful for the event.'),
    'type'          => 'textarea',
    'scope'         => array('events'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'original_image_url',
    'title'         => __('Original Image URL'),
    'description'   => __('The original image URL for the event, possibly from the import process.'),
    'type'          => 'text',
    'scope'         => array('events'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),

  array(
    'name'          => 'hide_title',
    'title'         => __('Hide the Title?'),
    'description'   => null,
    'type'          => 'checkbox',
    'scope'         => array('header_slides','banners'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'url',
    'title'         => __('Remote URL'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('events','header_slides','banners','partnerships','podcasts','sponsors','videocasts'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'external_link',
    'title'         => __('Open in New Window?'),
    'description'   => null,
    'type'          => 'checkbox',
    'scope'         => array('events','header_slides','banners','partnerships','podcasts','sponsors','videocasts'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),

  // -- SEMINARS and TALKS fields
  array(
    'name'          => 'attendants_related',
    'title'         => __('Associated Attendants'),
    'description'   => __('Select the speakers associated with this event.'),
    'type'          => 'posts_checkboxes',
    'post_type_target' => 'attendants',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'is_provisional',
    'title'         => __('Is event provisional?'),
    'description'   => __('Check if event is provisional (can restrict how it is shown)'),
    'type'          => 'checkbox',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'date_time_1',
    'title'         => __('Date / Time #1'),
    'description'   => __('Schedule the date and time (yyyy-mm-dd hh:mm:ss) for start'),
    'type'          => 'text',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'date_time_2',
    'title'         => __('Date / Time #2'),
    'description'   => __('Schedule the date and time (yyyy-mm-dd hh:mm:ss) for start'),
    'type'          => 'text',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'date_end_time_1',
    'title'         => __('Date / Time End #1'),
    'description'   => __('Schedule the date and time (yyyy-mm-dd hh:mm:ss) for end'),
    'type'          => 'text',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'date_end_time_2',
    'title'         => __('Date / Time End #2'),
    'description'   => __('Schedule the date and time (yyyy-mm-dd hh:mm:ss) for end'),
    'type'          => 'text',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),

  // TODO: adjust field name from "youtube" to "video"
  array(
    'name'          => 'youtube_id_1',
    'title'         => __('Video ID #1'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'streaming_server_1',
    'title'         => __('Streaming Server #1'),
    'description'   => null,
    'labels'        => array('Youtube', 'Brightcove'),
    'values'        => array('youtube','brightcove'),
    'type'          => 'radio',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),

  // TODO: adjust field name from "youtube" to "video"
  array(
    'name'          => 'youtube_id_2',
    'title'         => __('Video ID #2'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'streaming_server_2',
    'title'         => __('Streaming Server #2'),
    'description'   => null,
    'labels'        => array('Youtube', 'Brightcove'),
    'values'        => array('youtube','brightcove'),
    'type'          => 'radio',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'event_local',
    'title'         => __('Event Location'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'sponsor_name',
    'title'         => __('Sponsor Name'),
    'description'   => __('Insert a sponsor name for this event'),
    'type'          => 'text',
    'scope'         => array('seminars','talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'sponsor_url',
    'title'         => __('Sponsor URL'),
    'description'   => __('The sponsor URL (needs to have a sponsor name declared also)'),
    'type'          => 'text',
    'scope'         => array('seminars', 'talks'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  

  // TODO: Possible future addings: ICS files


  // -- CO-AUTHOR fields
  array(
    'name'          => 'profile_url',
    'title'         => __('Profile URL'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('coauthors'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'linkedin_url',
    'title'         => __('LinkedIn URL'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('coauthors'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),

  // -- ATTENDANTS fields
  array(
    'name'          => 'organization',
    'title'         => __('Organization'),
    'description'   => __('Name of the Association or Company'),
    'type'          => 'text',
    'scope'         => array('attendants','coauthors'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'function',
    'title'         => __('Function'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('attendants','coauthors'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'is_juri',
    'title'         => __('Is attendant a Juri?'),
    'description'   => __('Check if this attendant is a member of the juri'),
    'type'          => 'checkbox',
    'scope'         => array('attendants'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'is_speaker',
    'title'         => __('Is attendant a Speaker?'),
    'description'   => __('Check if this attendant is a speaker'),
    'type'          => 'checkbox',
    'scope'         => array('attendants'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'gender',
    'title'         => __('Gender'),
    'description'   => null,
    'labels'        => array('F', 'M'),
    'values'        => array('female','male'),
    'type'          => 'radio',
    'scope'         => array('attendants','coauthors'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'use_in_seminars',
    'title'         => __('Include in Seminars?'),
    'description'   => __('Check if this attendant is to be included in seminars'),
    'type'          => 'checkbox',
    'scope'         => array('attendants'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'use_in_talks',
    'title'         => __('Include in Talks?'),
    'description'   => __('Check if this attendant is to be included in talks'),
    'type'          => 'checkbox',
    'scope'         => array('attendants'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),

  array(
    'name'          => 'order',
    'title'         => __('Order'),
    'description'   => __('Use to order elements from minor to major (defaults to -1)'),
    'type'          => 'number',
    'scope'         => array('attendants','coauthors','events','faqs','header_slides','seminars','sponsors','talks','winners'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),


  // -- VIDEOCASTS and PODCASTS fields
  array(
    'name'          => 'video_id',
    'title'         => __('Video ID'),
    'description'   => __('Declare the ID for video (please define Youtube or Brightcove)'),
    'type'          => 'text',
    'scope'         => array('videocasts','winners'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'audio_id',
    'title'         => __('Audio ID'),
    'description'   => __('Declare the Brightcove ID for audio file'),
    'type'          => 'text',
    'scope'         => array('podcasts'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'streaming_server',
    'title'         => __('Streaming Server'),
    'description'   => null,
    'labels'        => array('Youtube', 'Brightcove'),
    'values'        => array('youtube','brightcove'),
    'type'          => 'radio',
    'scope'         => array('videocasts','winners'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'spotify_podcasts_url',
    'title'         => __('Spotify Podcasts URL'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('videocasts','podcasts'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'google_podcasts_url',
    'title'         => __('Google Podcasts URL'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('videocasts','podcasts'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'apple_podcasts_url',
    'title'         => __('Apple Podcasts URL'),
    'description'   => null,
    'type'          => 'text',
    'scope'         => array('videocasts','podcasts'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'season_number',
    'title'         => __('Season Number'),
    'description'   => null,
    'type'          => 'number',
    'scope'         => array('videocasts','podcasts'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
  array(
    'name'          => 'episode_number',
    'title'         => __('Episode Number'),
    'description'   => null,
    'type'          => 'number',
    'scope'         => array('videocasts','podcasts'),
    'capability'    => 'edit_posts',
    'origin'        => 'uikit-boostsolutions',
  ),
);

if (!class_exists('cbsCustomFields')):

  class cbsCustomFields {
      /**
      * @var  string  $prefix  The prefix for storing custom fields in the postmeta table
      */
      var $prefix = 'bs_cf_';

      /**
      * @var  array  $postTypes  An array of public custom post types, plus the standard "post" and "page" - add the custom types you want to include here
      */
      var $postTypes = array(
        'attendants',
        'banners',
        'coauthors',
        'events',
        'faqs',
        'header_slides',
        'partnerships',
        'podcasts',
        'projects',
        'quotations',
        'seminars',
        'sponsors',
        'talks',
        'videocasts',
        'winners',
      );

      /**
      * @var  array  $customFields  Defines the custom fields available
      */
      var $customFields = array(
        /*
        array(
          "name"          => "short-text",
          "title"         => "A short bit of text",
          "description"   => "",
          "type"          => "text",
          "scope"         => array('post', 'page', 'slides'),
          "capability"    => "edit_posts"
        ),
        */
      );

      /**
      * PHP 5 Constructor
      */
      function __construct() {
        global $child_postTypes,$theme_customFields,$child_customFields;

        // -- Adding CPT from CHILD THEME
        if (!empty($child_postTypes)) $this->postTypes = array_merge($child_postTypes,$this->postTypes);

        // -- Adding CUSTOM FIELDS from several sources
        
        //  Combine items from $child_customFields and $theme_customFields (if name is equal, merge scope arrays else keep both)
        if (!empty($child_customFields)):
          foreach ($theme_customFields as $key => $value):
            foreach ($child_customFields as $key2 => $value2):
              if ($value['name'] === $value2['name']):
                $theme_customFields[$key]['scope'] = array_merge($value['scope'], $value2['scope']);
                unset($child_customFields[$key2]);
              endif;
            endforeach;
          endforeach;
        endif;
        // Adding items to THIS customFields
        if (!empty($theme_customFields)) $this->customFields = array_merge($this->customFields,$theme_customFields);
        if (!empty($child_customFields)) $this->customFields = array_merge($this->customFields,$child_customFields);

        add_action('admin_menu', array(&$this, 'createCustomFields'));
        add_action('save_post', array(&$this, 'saveCustomFields'), 1, 2);
        // Comment this line out if you want to keep default custom fields meta box
        add_action('do_meta_boxes', array(&$this, 'removeDefaultCustomFields'), 10, 3);
      }
      /**
      * Remove the default Custom Fields meta box
      */
      function removeDefaultCustomFields($type, $context, $post){
        foreach(array('normal', 'advanced', 'side') as $context):
          foreach($this->postTypes as $postType):
            remove_meta_box('postcustom', $postType, $context);
          endforeach;
        endforeach;
      }
      /**
      * Create the new Custom Fields meta box
      */
      function createCustomFields(){
        if (function_exists('add_meta_box')):
          foreach ($this->postTypes as $postType):
            add_meta_box('my-custom-fields', __('Extra Parameters','uikit-boostsolutions'), array(&$this, 'displayCustomFields'), $postType, 'normal', 'high');
          endforeach;
        endif;
      }
      /**
      * Display the new Custom Fields meta box
      */
      function displayCustomFields(){
        global $post;
        ?>
        <div class="form-wrap">
          <?php
          wp_nonce_field('my-custom-fields', 'my-custom-fields_wpnonce', false, true);
          foreach($this->customFields as $customField):
              // Check scope
              $scope = $customField['scope'];
              $output = false;
              foreach($scope as $scopeItem):
                switch($scopeItem):
                  default:
                    if ($post->post_type == $scopeItem) $output = true;
                    break;
                endswitch;
                if ($output) break;
              endforeach;

              // Check capability
              if (!current_user_can($customField['capability'], $post->ID)) $output = false;
              
              // Output if allowed
              if ($output) {
              ?>
              <div class="form-field form-required">
                <?php
                switch ($customField['type']):
                  case "posts_checkboxes":
                    // A list of checkboxes populated by a specific CPT
                    $custom_text_domain = (!empty($customField['origin'])) ? $customField['origin'] : TEXT_DOMAIN;
                    echo '<label><b>'.__($customField['title'],$custom_text_domain).'</b></label><br>';
                    
                    // Fetch target posts
                    $target_pt = isset($customField['post_type_target']) ? $customField['post_type_target'] : 'post';
                    
                    $query_args = array('post_type' => $target_pt, 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC');
                    
                    // Filter attendants by their "use_in_talks" or "use_in_seminars" checkboxes
                    if ($target_pt === 'attendants') {
                        if ($post->post_type === 'talks') {
                            $query_args['meta_query'] = array(
                                array(
                                    'key' => $this->prefix . 'use_in_talks',
                                    'value' => 'yes',
                                    'compare' => '='
                                )
                            );
                        } elseif ($post->post_type === 'seminars') {
                            $query_args['meta_query'] = array(
                                array(
                                    'key' => $this->prefix . 'use_in_seminars',
                                    'value' => 'yes',
                                    'compare' => '='
                                )
                            );
                        }
                    }
                    
                    $target_posts = get_posts($query_args);
                    
                    // Get current saved values (comma separated)
                    $saved_val = get_post_meta($post->ID, $this->prefix.$customField['name'], true);
                    $saved_array = array();
                    if (!empty($saved_val)) {
                        $saved_array = explode(',', $saved_val);
                    }
                    
                    echo '<div style="max-height: 200px; overflow-y: auto; padding: 10px; border: 1px solid #ddd; background: #fff; margin-top: 5px;">';
                    foreach ($target_posts as $t_post) {
                        $checked = in_array($t_post->ID, $saved_array) ? 'checked="checked"' : '';
                        $org = get_post_meta($t_post->ID, $this->prefix . 'organization', true);
                        $label_text = $t_post->post_title;
                        if (!empty($org)) {
                            $label_text .= ', ' . $org;
                        }
                        echo '<label style="display:block; margin-bottom: 5px; font-weight:normal;">';
                        echo '<input type="checkbox" name="'.$this->prefix.$customField['name'].'[]" value="'.$t_post->ID.'" '.$checked.' style="width: auto;" /> ';
                        echo esc_html($label_text);
                        echo '</label>';
                    }
                    echo '</div>';
                    break;

                  case "checkbox":
                    // Checkbox
                    $custom_text_domain = (!empty($customField['origin'])) ? $customField['origin'] : TEXT_DOMAIN;
                    echo '<label for="'.$this->prefix.$customField['name'].'" style="display:inline;"><b>'.__($customField['title'],$custom_text_domain).'</b></label>&nbsp;&nbsp;';
                    echo '<input type="checkbox" name="'.$this->prefix.$customField['name'].'" id="'.$this->prefix.$customField['name'].'" value="yes"';
                    if (get_post_meta($post->ID, $this->prefix.$customField['name'], true ) == "yes")
                        echo ' checked="checked"';
                    echo '" style="width: auto;" />';
                    break;
                  
                  case "radio":
                    // Radio
                    $custom_text_domain = (!empty($customField['origin'])) ? $customField['origin'] : TEXT_DOMAIN;
                    echo '<label><b>'.__($customField['title'],$custom_text_domain).'</b>&nbsp;&nbsp;';
                    $i = 0;
                    foreach ($customField['values'] as $value):
                      echo '<input type="radio" name="'.$this->prefix.$customField['name'].'" id="'.$value.'" value="'.$value.'"';
                      if (get_post_meta($post->ID, $this->prefix.$customField['name'], true) == $value)
                          echo ' checked="checked"';
                      echo ' style="width: auto;" />';
                      echo '<label for="'.$this->prefix.$customField['name'].'" style="display:inline;">'.$customField['labels'][$i].'</label>&nbsp;&nbsp;';
                      $i++;
                    endforeach;
                    echo '</label>';
                    break;
                  
                  case "textarea":
                  case "wysiwyg":
                    // Text area
                    $custom_text_domain = (!empty($customField['origin'])) ? $customField['origin'] : TEXT_DOMAIN;
                    echo '<label for="'.$this->prefix.$customField['name'] .'"><b>'.__($customField['title'],$custom_text_domain).'</b></label>';
                    echo '<textarea name="'.$this->prefix.$customField['name'].'" id="'.$this->prefix.$customField['name'].'" columns="30" rows="3">'.htmlspecialchars(get_post_meta($post->ID, $this->prefix.$customField['name'], true)).'</textarea>';
                    // WYSIWYG
                    if ($customField['type'] == "wysiwyg"):
                    ?>
                    <script type="text/javascript">
                    jQuery(document).ready(function(){
                      jQuery("<?php echo $this->prefix.$customField['name']; ?>").addClass("mceEditor");
                      if (typeof(tinyMCE) == "object" &amp;&amp; typeof(tinyMCE.execCommand) == "function") {
                        tinyMCE.execCommand("mceAddControl", false, "<?php echo $this->prefix.$customField['name']; ?>");
                      }
                    });
                  </script>
                    <?php
                    endif;
                    break;
                  
                  case "number":
                    // Number
                    $custom_text_domain = (!empty($customField['origin'])) ? $customField['origin'] : TEXT_DOMAIN;
                    echo '<label for="' . $this->prefix . $customField['name'] .'"><b>'.__($customField['title'],$custom_text_domain).'</b></label>';
                    $value = htmlspecialchars(get_post_meta($post->ID, $this->prefix.$customField['name'], true));
                    if (empty($value)) $value = '-1';
                    echo '<input type="text" width="100%" name="' . $this->prefix.$customField['name'].'" id="'.$this->prefix.$customField['name'].'" value="'.$value.'" />';
                    break;

                  case "dropdown":
                    // Select or Dropdown
                    $custom_text_domain = (!empty($customField['origin'])) ? $customField['origin'] : TEXT_DOMAIN;
                    echo '<label for="'.$this->prefix.$customField['name'].'"><b>'.__($customField['title'],$custom_text_domain).'</b></label>';
                    echo '<select name="'.$this->prefix.$customField['name'].'" id="'.$this->prefix.$customField['name'].'" style="width: 100%;">';
                    $i = 0;
                    foreach ($customField['options'] as $option):
                      echo '<option value="'.$option['value'].'"';
                      if (get_post_meta($post->ID, $this->prefix.$customField['name'], true) == $option['value'])
                          echo ' selected="selected"';
                      echo '>'.__($option['label'],TEXT_DOMAIN).'</option>';
                      $i++;
                    endforeach;
                    echo '</select>';
                    break;

                  case "image":
                    // Image from Multimedia
                    $custom_text_domain = (!empty($customField['origin'])) ? $customField['origin'] : TEXT_DOMAIN;
                    echo '<label for="'.$this->prefix.$customField['name'].'"><b>'.__($customField['title'],$custom_text_domain).'</b></label>';
                    $image_id = get_post_meta($post->ID, $this->prefix.$customField['name'], true);
                    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
                    
                    echo '<div class="image-field">';
                    echo '<input type="hidden" name="'.$this->prefix.$customField['name'].'" id="'.$this->prefix.$customField['name'].'" value="'.$image_id.'" />';
                    echo '<img src="'.$image_url.'" style="max-width:25%; display:block; margin-bottom:10px;" />';
                    echo '<button type="button" class="button upload-image-button" id="upload-image-button-'.$this->prefix.$customField['name'].'">'.__('Select Image', $custom_text_domain).'</button>&nbsp;';
                    echo '<button type="button" class="button remove-image-button" id="remove-image-button-'.$this->prefix.$customField['name'].'" style="'.($image_url ? '' : 'display:none;').'">'.__('Remove Image', $custom_text_domain).'</button>';
                    echo '</div>';
                    ?>
                    <script type="text/javascript">
                    jQuery(document).ready(function($){
                      
                      $('#upload-image-button-<?php echo $this->prefix.$customField['name'];?>').on('click', function(e){
                        e.preventDefault();
                        var frame;
                        var $button = $(this);
                        console.log ($button);
                        var $input = $button.siblings('input[type="hidden"]');
                        var $image = $button.siblings('img');
                        var $remove = $button.siblings('.remove-image-button');
                        if (frame) {
                          frame.off('select');
                          frame = null;
                        }
                        frame = wp.media({
                          title: '<?php _e("Select or Upload Image", $custom_text_domain); ?>',
                          button: {
                            text: '<?php _e("Use this image", $custom_text_domain); ?>'
                          },
                          multiple: false
                        });
                        frame.on('select', function(){
                          var attachment = frame.state().get('selection').first().toJSON();
                          $input.val(attachment.id);
                          $image.attr('src', attachment.url).show();
                          $remove.show();
                          frame.close(); // Force close the frame after selection
                        });
                        frame.open();
                      });

                      $('#remove-image-button-<?php echo $this->prefix.$customField['name'];?>').on('click', function(){
                        var $button = $(this);
                        var $input = $button.siblings('input[type="hidden"]');
                        var $image = $button.siblings('img');
                        $input.val('');
                        $image.hide();
                        $button.hide();
                      });
                    });
                    </script>
                    <?php
                    break;
  
    
                  default:
                    // Plain text field
                    $custom_text_domain = (!empty($customField['origin'])) ? $customField['origin'] : TEXT_DOMAIN;
                    echo '<label for="'.$this->prefix.$customField['name'].'"><b>'.__($customField['title'],$custom_text_domain).'</b></label>';
                    echo '<input type="text" width="100%" name="'.$this->prefix.$customField['name'].'" id="'.$this->prefix.$customField['name'].'" value="'.htmlspecialchars(get_post_meta($post->ID, $this->prefix.$customField['name'], true)).'" />';
                    break;
                    
                endswitch;
                ?>
                
                <?php
                if ($customField['description']):
                  $custom_text_domain = (!empty($customField['origin'])) ? $customField['origin'] : TEXT_DOMAIN;
                  echo '<p>'.__($customField['description'], $custom_text_domain).'</p>';
                endif;
                ?>
              </div>
              <?php
              }
          endforeach;
          ?>
        </div>
        <?php
      }

      /**
      * Save the new Custom Fields values
      */
      function saveCustomFields( $post_id, $post ) {
        global $theme_customFields,$child_customFields;
        // if (!empty($theme_customFields)) $this->customFields = array_merge($this->customFields,$theme_customFields);
        // if (!empty($child_customFields)) $this->customFields = array_merge($this->customFields,$child_customFields);

        if ( !isset( $_POST[ 'my-custom-fields_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'my-custom-fields_wpnonce' ], 'my-custom-fields' ) )
          return;
        if ( !current_user_can( 'edit_post', $post_id ) )
          return;
        if ( ! in_array( $post->post_type, $this->postTypes ) )
          return;
        foreach ( $this->customFields as $customField ) {
          if ( current_user_can( $customField['capability'], $post_id ) ) {
            $value = isset($_POST[$this->prefix.$customField['name']]) ? $_POST[$this->prefix.$customField['name']] : null;
            
            // Handle array values from checkboxes
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            // Numeric fields, check for empty values
            if ($customField['type'] == "number"):
              if (empty($value)) $value = '-1';
            endif;

            if (isset($value) && trim((string)$value) !== ''):
              // Auto-paragraphs for any WYSIWYG
              if ($customField['type'] == "wysiwyg") $value = wpautop($value);
              update_post_meta( $post_id, $this->prefix . $customField['name'], $value );
            else:
              delete_post_meta( $post_id, $this->prefix . $customField['name'] );
            endif;
          }
        }
      }

  } // End Class

endif;

// Instantiate the class
if (class_exists('cbsCustomFields')) $cbsCustomFields_var = new cbsCustomFields();
?>