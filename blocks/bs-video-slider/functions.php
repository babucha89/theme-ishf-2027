<?php
// -----------------------------------------------------------------------------
//  Block: BS VIDEO SLIDER
//  Edited: 3L, 30-JUL-2026
// -----------------------------------------------------------------------------

//--------------------------------------------
//  Function that renders the block
//--------------------------------------------
function render_bs_video_slider($attributes, $content, $block)
{
  $title = $attributes['title'] ?? '';

  ob_start();
  ?>
  <section id="videoclips" class="uk-section">
    <div class="uk-container uk-container-large">
      <?php
      if (!empty($title)) {
        // -- Try to use the section-label template if it exists
        $section_name = $title;
        $text_align = "left";
        $template = locate_template('includes/section-label.php', false, false);
        if ($template) {
          include($template);
        } else {
          echo '<h2 class="uk-margin-bottom">' . esc_html($title) . '</h2>';
        }
      }
      ?>

      <div data-uk-slider>
        <div class="uk-position-relative">
          <div class="uk-slider-container">
            <ul class="uk-slider-items uk-child-width-1-1 uk-child-width-1-3@s uk-grid">
              <?php
              // Render inner blocks (bs-video blocks) wrapped in <li>
              if (!empty($block->inner_blocks)) {
                foreach ($block->inner_blocks as $inner_block) {
                  echo '<li>';
                  echo $inner_block->render();
                  echo '</li>';
                }
              } else {
                // Fallback if $block is not available (older WP versions)
                // We just output the content, though it won't be wrapped in <li>
                // This shouldn't happen in modern WP.
                if (empty($block->inner_blocks) && !empty(trim($content))) {
                   // A crude fallback: assume each bs-video outputs a top level <div>, we wrap them.
                   // It's safer to just echo $content and rely on UIkit grid if possible, 
                   // but UIkit slider *requires* <li>. So we rely on $block->inner_blocks.
                }
              }
              ?>
            </ul>
          </div>
          <div class="uk-hidden@s">
            <a class="uk-position-center-left uk-position-small" href uk-slidenav-previous uk-slider-item="previous"></a>
            <a class="uk-position-center-right uk-position-small" href uk-slidenav-next uk-slider-item="next"></a>
          </div>
          <div class="uk-visible@s">
            <a class="uk-position-center-left-out uk-position-small" href uk-slidenav-previous uk-slider-item="previous"></a>
            <a class="uk-position-center-right-out uk-position-small" href uk-slidenav-next uk-slider-item="next"></a>
          </div>
        </div>
        <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
      </div>
    </div>
  </section>
  <?php
  return ob_get_clean();
}


//--------------------------------------------
//  Function that registers the block
//--------------------------------------------
function register_bs_video_slider()
{
  register_block_type('uikit-boostsolutions/bs-video-slider', array(
    'render_callback' => 'render_bs_video_slider',
    'attributes' => array(
      'blockId' => array(
        'type' => 'string',
        'default' => '',
      ),
      'title' => array(
        'type' => 'string',
        'default' => 'Videoclips',
      ),
    ),
  ));
}
add_action('init', 'register_bs_video_slider');
?>
