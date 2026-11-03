<?php
//----------------------------------------------------------------------------------
//  Block: VIDEO YOUTUBE (MODAL)
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Reference: base template includes/video-youtube-modal.php
//  Edited: BB, 30-OUT-2026
//----------------------------------------------------------------------------------

// -- Initializations
global $youtube_id, $modal_id, $auto_fire, $youtube_title;

// Support both global variables (base-template standard) and $args array
$yt_id        = !empty($args['youtube_id']) ? $args['youtube_id'] : $youtube_id;
$yt_modal_id  = !empty($args['modal_id']) ? $args['modal_id'] : (!empty($modal_id) ? $modal_id : 'modal-youtube-video');
$yt_auto_fire = isset($args['auto_fire']) ? (bool) $args['auto_fire'] : (bool) $auto_fire;
$yt_title     = !empty($args['youtube_title']) ? $args['youtube_title'] : $youtube_title;

// Fallback title if not set (WCAG 2.1 AA iframe title requirement)
$yt_title = isset($yt_title) && !empty($yt_title) ? esc_attr($yt_title) : esc_attr__('YouTube Video', TEXT_DOMAIN);

if (!empty($yt_id)):
  // -- Validate if AUTO FIRE is set
  if ($yt_auto_fire):
  ?>
  <script>
  jQuery(document).ready(function($) {
    if (typeof UIkit !== 'undefined') {
      UIkit.modal('#<?php echo esc_js($yt_modal_id); ?>').show();
    }
  });
  </script>
  <?php
  endif;

  // -- Modal: Video Youtube
  ?>
  <div id="<?php echo esc_attr($yt_modal_id); ?>" class="uk-flex-top uk-modal-container modal-ishf-video" data-uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical uk-border-rounded uk-box-shadow-xlarge uk-position-relative">
      <button class="uk-modal-close-default" type="button" data-uk-close aria-label="<?php esc_attr_e('Close video modal', TEXT_DOMAIN); ?>"></button>
      <div data-uk-grid>
        <div class="uk-width-1-1">
          <div class="streaming-video-wrapper uk-border-rounded uk-overflow-hidden">
            <iframe width="1280" height="720" src="https://www.youtube.com/embed/<?php echo esc_attr($yt_id); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen data-uk-responsive data-uk-video loading="lazy" title="<?php echo $yt_title; ?>" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
endif;

// -- Reset
$youtube_id = null;
$modal_id = null;
$auto_fire = null;
$youtube_title = null;
?>

