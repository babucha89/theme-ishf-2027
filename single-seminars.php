<?php
// -----------------------------------------------------------------------------
//  File: SINGLE for SEMINARS
//  Edited: 3L, 12-FEV-2026
// -----------------------------------------------------------------------------


// Block[0]: Intro
// Block[1]: Video Slider
// Block[2]: Program / Agenda
// Block[3]: Program / Agenda (if day 2 exists)


// -- Initializations
global $featured_articles;
$featured_articles = array();
the_post();
array_push($featured_articles, get_the_ID());
uikit_cbs_set_post_views(get_the_ID());

$post_tags = get_the_tags($post->ID);
$categories = get_the_category( $post->ID );
$youtube_id_1 = get_post_meta(get_the_ID(), 'bs_cf_youtube_id_1', true);
$date_time_1 = get_post_meta(get_the_ID(), 'bs_cf_date_time_1', true);
$youtube_id_2 = get_post_meta(get_the_ID(), 'bs_cf_youtube_id_2', true);
$date_time_2 = get_post_meta(get_the_ID(), 'bs_cf_date_time_2', true);
$date_end_time_1 =get_post_meta(get_the_ID(), 'bs_cf_date_end_time_1', true);
$date_end_time_2 = get_post_meta(get_the_ID(), 'bs_cf_date_end_time_2', true);


$date_start_broadcast_1 = new DateTime($date_time_1);
$date_start_broadcast_1->sub(new DateInterval('PT10M'));
$date_end_broadcast_1 = new DateTime($date_end_time_1);

$date_start_broadcast_2 = new DateTime($date_time_2);
$date_start_broadcast_2->sub(new DateInterval('PT10M'));
$date_end_broadcast_2 = new DateTime($date_end_time_2);

$now = new DateTime('NOW');
// $now = new DateTime('2022-04-08 11:10:00');
?>

<?php
// -- Block: HEADER
get_header();
?>

<?php
// -- Section: HEADER
get_template_part('includes/sections/header','premio');
?>

<?php
// -- Content Starter (for sticky elements)
?>
<div id="content-start"></div>


<?php
// -- Get CUSTOM FIELDS
$date_time_1 = get_post_meta(get_the_ID(), 'bs_cf_date_time_1', true);
$date_visible_1 = strftime('%e de %B', strtotime($date_time_1));
$date_visible_1 = utf8_encode($date_visible_1);
$time_visible_1 = strftime('%Hh%M', strtotime($date_time_1));

$date_time_2 = get_post_meta(get_the_ID(), 'bs_cf_date_time_2', true);
$date_visible_2 = strftime('%e de %B', strtotime($date_time_2));
$date_visible_2 = utf8_encode($date_visible_2);
$time_visible_2 = strftime('%Hh%M', strtotime($date_time_2));

// -- Get GROUPS
$groups = array();
$blocks = parse_blocks(get_the_content());
foreach($blocks as $block):
  switch ($block['blockName']):
    case 'core/group':
      array_push($groups, $block['innerBlocks']);
      break;
  endswitch;
endforeach;
?>

<?php
// -- Section: SEMINAR INTRO
$shortcodes = array();
$title = null;
$content = null;
if(!empty($groups[0])):
  $blocks = $groups[0];
  foreach($blocks as $block):
    switch ($block['blockName']):
      case 'core/shortcode':
        $shortcode = render_block($block);
        array_push($shortcodes, $shortcode);
        break;
      case 'core/heading':
          $title = (empty($title)) ? wp_strip_all_tags(render_block($block), true) : $title;
      default:
        if ($title != wp_strip_all_tags(render_block($block), true)):
          $content .= render_block($block);
        endif;
        break;
    endswitch;
  endforeach;
$gallery = (sizeof($shortcodes) > 0) ? apply_filters('the_content', do_blocks($shortcodes[0])) : null;
$content = apply_filters('the_content', do_blocks($content));
?>
<section id="seminar-intro" class="uk-section">
  <div class="uk-container uk-container-small">
    <?php
    // -- Block: SECTION LABEL
    $section_name = get_the_title();
    $heading_size = 2;
    include(locate_template('includes/section-label.php', false, false));
    ?>

    <?php
    if (has_excerpt()):
    ?>
    <p class="text-featured"><?php echo get_the_excerpt(); ?></p>
    <?php
    endif;
    ?>
    <h2 class="title"><?php echo $title; ?></h2>
    <?php echo $content; ?>
  </div>
</section>
<?php
endif;
?>


<?php
// -- Section: SEMINAR VIDEO SLIDER
$has_video_slider = true;
$youtubes = array();
$title = null;
$content = null;
if(!empty($groups[1])):
$blocks = $groups[1];
  foreach($blocks as $block):
    switch ($block['blockName']):
      case 'core/heading':
        $title = (empty($title)) ? wp_strip_all_tags(render_block($block)) : $title;
        break;
      case 'core/shortcode':
        $shortcode = wp_strip_all_tags(render_block($block));
        array_push($youtubes, $shortcode);
        break;
      default:
        $content .= render_block($block);
        break;
    endswitch;
  endforeach;

$content = apply_filters('the_content', do_blocks($content));

if(count($youtubes) > 0):
?>
<section id="seminar-video-slider" class="uk-section">
  <div class="uk-container uk-container-xlarge">
    <?php
    // -- Block: SECTION LABEL
    if (!empty($title)):
      $section_name = $title;
      $heading_size = 2;
      $text_align = 'center';
      include(locate_template('includes/section-label.php', false, false));
    endif;
    ?>

    <?php
    // -- Show content
    if (!empty($content)):
      echo $content;
    endif;
    ?>

    <div data-uk-slider="sets: true;" class="uk-margin-medium-top">
      <div class="uk-position-relative">
        <div class="uk-slider-container">
          <ul class="uk-slider-items uk-grid uk-grid-small uk-child-width-1-2@s uk-child-width-1-3@l">
            <?php
            foreach ($youtubes as $youtube):
              $youtube = do_shortcode(do_blocks($youtube));
            ?>
            <li>
              <?php echo $youtube; ?>
            </li>
            <?php
            endforeach;
            ?>
          </ul>
        </div>
        <div class="uk-visible@s">
          <a class="uk-position-center-left-out uk-slidenav-large uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
          <a class="uk-position-center-right-out uk-slidenav-large uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
        </div>
      </div>
      <div class="uk-hidden@s">
        <a class="" href="#" data-uk-slidenav-previous uk-slider-item="previous"></a>
        <a class="" href="#" uk-slidenav-next uk-slider-item="next"></a>
      </div>
      <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
    </div>

  </div>
</section>
<?php
endif;
endif;
?>

<?php
// -- Section: SEMINAR VIDEO
if ((!empty($youtube_id_1)) && (!empty($youtube_id_2)) && ($now >= $date_start_broadcast_1)):
  // -- Validating if IS STREAMING
  $is_streaming_1 = (($now >= $date_start_broadcast_1) && ($now < $date_end_broadcast_1)) ? true : false;
  $is_streaming_2 = (($now >= $date_start_broadcast_2) && ($now < $date_end_broadcast_2)) ? true : false;
?>

  <?php
  // -- STREAMING
  if (($is_streaming_1) || ($is_streaming_2)):
    $pretitle = 'Streaming';
    $title = 'Acompanhe em direto';
    $bodycopy = 'Assista à Conferência em direto e descubra mais sobre os temas em discussão.';
  ?>
  <section id="seminar-video" class="uk-section uk-light">
    <div class="uk-container uk-container-large">
      <div data-uk-grid>
        <div class="uk-width-1-1 uk-width-1-4@s">
          <h6 class="uk-margin-small-bottom uk-margin-remove-adjacent uk-text-uppercase"><?php echo $pretitle; ?></h6>
          <h1><?php echo $title; ?></h1>
          <p><?php echo $bodycopy; ?></p>
        </div>
        <div class="uk-width-1-1 uk-width-expand@s">
          <?php
          // -- Block: VIDEO YOUTUBE
          $youtube_id = $youtube_id_1;
          get_template_part('includes/video-youtube','embed');
          ?>
        </div>
      </div>
    </div>
  </section>
  <?php
  endif;
  ?>

  <?php
  // -- POS-STREAMING
  if ((!$has_video_slider) && ($now >= $date_end_broadcast_2)):
    $title = 'Reveja as Conferências';
    $bodycopy = 'Assista novamente aos eventos e relembre os momentos altos destas conversas.';
  ?>
  <section id="seminar-video" class="uk-section uk-light">
    <div class="uk-container uk-container-xlarge">
      <div class="uk-text-center">
        <h1><?php echo $title; ?></h1>
        <p><?php echo $bodycopy; ?></p>
      </div>
      <div data-uk-grid>
        <div class="uk-width-1-1 uk-width-1-2@m uk-text-left uk-text-right@s">
          <h4><?php echo $date_visible_1; ?></h4>
          <?php
          // -- Block: VIDEO YOUTUBE #1
          $youtube_id = $youtube_id_1;
          get_template_part('includes/video-youtube','embed');
          ?>
        </div>
        <div class="uk-width-1-1 uk-width-expand@s">
          <h4><?php echo $date_visible_2; ?></h4>
          <?php
          // -- Block: VIDEO YOUTUBE #2
          $youtube_id = $youtube_id_2;
          get_template_part('includes/video-youtube','embed');
          ?>
        </div>
      </div>
    </div>
  </section>
  <?php
  endif;
  ?>

<?php
endif;
?>

<?php
// -- Section: SEMINAR BODY (Group #3 + #4)
// Checks if there is a second date
if(!empty($date_time_2)):
?>
<section id="seminar-body" class="uk-section">
  <div class="uk-container">
    <?php
    $section_name = 'Programa';
    $heading_size = 2;
    $text_align = 'center';
    include(locate_template('includes/section-label.php', false, false));
    ?>

    <div data-uk-grid class="uk-margin-large-top uk-grid-divider uk-child-width-1-1 uk-child-width-1-2@s">
      <?php
      // -- Program #1: Contents
      $shortcodes = array();
      $title = null;
      $content = null;
      $blocks = $groups[2];
      foreach($blocks as $block):
        switch ($block['blockName']):
          case 'core/shortcode':
            $shortcode = render_block($block);
            array_push($shortcodes, $shortcode);
            break;
          default:
            $content .= render_block($block);
            break;
        endswitch;
      endforeach;
      $gallery = (sizeof($shortcodes) > 0) ? apply_filters('the_content', do_blocks($shortcodes[0])) : null;
      $content = apply_filters('the_content', do_blocks($content));
      ?>
      <div class="uk-text-left uk-text-right@s">
        <div>
          <span class="uk-label uk-label-primary"><?php echo $date_visible_1; ?></span>
          <span class="uk-label uk-label-primary"><?php echo $time_visible_1; ?></span>
        </div>
        <?php echo $content; ?>
        <?php
        // -- Generate ICS Event Button
        if ($now <= $date_start_broadcast_1):
          $date_start_1 = strftime('%Y-%m-%d %H:%M:%S', strtotime($date_time_1));
          $date_start_1 = utf8_encode($date_start_1);
          $date_end_1 = strftime('%Y-%m-%d %H:%M:%S', strtotime($date_end_time_1));
          $date_end_1 = utf8_encode($date_end_time_1);

          $ics_generator_url = '../../ics-generator/?'.esc_attr('ics_start='.$date_start_1.'&ics_end='.$date_end_1.'&ics_name='. get_the_title() .'&ics_location='.str_replace(CHILD_BASE_SITE, REDIRECT_SITE, get_permalink()).'&ics_alert=15');
        ?>
        <div class="uk-margin-large-top">
          <a class="uk-button uk-button-large uk-button-danger uk-margin-auto" href="<?php echo $ics_generator_url; ?>" target="_blank" rel="noopener"><span data-uk-icon="calendar"></span> Reservar na Agenda</a>
        </div>
        <?php
        endif;
        ?>
      </div>

      <?php
      // -- Program #2: Contents
      $shortcodes = array();
      $title = null;
      $content = null;
      $blocks = $groups[3];
      foreach($blocks as $block):
        switch ($block['blockName']):
          case 'core/shortcode':
            $shortcode = render_block($block);
            array_push($shortcodes, $shortcode);
            break;
          default:
            $content .= render_block($block);
            break;
        endswitch;
      endforeach;
      $gallery = (sizeof($shortcodes) > 0) ? apply_filters('the_content', do_blocks($shortcodes[0])) : null;
      $content = apply_filters('the_content', do_blocks($content));
      ?>
      <div>
        <div>
          <span class="uk-label uk-label-primary"><?php echo $date_visible_2; ?></span>
          <span class="uk-label uk-label-primary"><?php echo $time_visible_2; ?></span>
        </div>
        <?php echo $content; ?>
        <?php
        // -- Generate ICS Event Button
        if ($now <= $date_start_broadcast_2):
          $date_start_2 = strftime('%Y-%m-%d %H:%M:%S', strtotime($date_time_2));
          $date_start_2 = utf8_encode($date_start_2);
          $date_end_2 = strftime('%Y-%m-%d %H:%M:%S', strtotime($date_end_time_2));
          $date_end_2 = utf8_encode($date_end_time_2);

          $ics_generator_url = '../../ics-generator/?'.esc_attr('ics_start='.$date_start_1.'&ics_end='.$date_end_1.'&ics_name='. get_the_title() .'&ics_location='.str_replace(CHILD_BASE_SITE, REDIRECT_SITE, get_permalink()).'&ics_alert=15');
        ?>
        <div class="uk-margin-large-top">
          <a class="uk-button uk-button-large uk-button-danger uk-margin-auto" href="<?php echo $ics_generator_url; ?>" target="_blank" rel="noopener"><span data-uk-icon="calendar"></span> Reservar na Agenda</a>
        </div>
        <?php
        endif;
        ?>
      </div>
    </div>
  </div>
</section>
<?php
// if it's only one day
else:
?>

<section id="seminar-body" class="uk-section section-light-gray">
  <div class="uk-container">

    <div data-uk-grid class="uk-grid-divider uk-grid uk-grid-large">
      <div id="program-title" class="uk-width-1-1 uk-width-1-3@s">
        <div uk-sticky="end: !#program-title; offset: 80">
        <?php
        $section_name = 'Programa';
        $heading_size = 2;
        $text_align = 'right@s';
        include(locate_template('includes/section-label.php', false, false));
        ?>

        <div class="uk-text-left uk-text-right@s uk-margin-top">
          <span class="uk-label uk-label-primary"><?php echo $date_visible_1; ?></span>
          <span class="uk-label uk-label-primary"><?php echo $time_visible_1; ?></span>
        </div>
        <?php
        // -- Generate ICS Event Button
        if ($now <= $date_start_broadcast_1):
          $date_start_1 = strftime('%Y-%m-%d %H:%M:%S', strtotime($date_time_1));
          $date_start_1 = utf8_encode($date_start_1);
          $date_end_1 = strftime('%Y-%m-%d %H:%M:%S', strtotime($date_end_time_1));
          $date_end_1 = utf8_encode($date_end_time_1);

          $ics_generator_url = '../../ics-generator/?'.esc_attr('ics_start='.$date_start_1.'&ics_end='.$date_end_1.'&ics_name='. get_the_title() .'&ics_location='.str_replace(CHILD_BASE_SITE, REDIRECT_SITE, get_permalink()).'&ics_alert=15');
        ?>
        <div class="uk-margin-small-top uk-text-right@s">
          <a class="uk-button uk-button-danger uk-margin-auto" href="<?php echo $ics_generator_url; ?>" target="_blank" rel="noopener"><span data-uk-icon="calendar"></span> Reservar na Agenda</a>
        </div>
        <?php
        endif;
        ?>
      </div>
    </div>

      <?php
      // -- Program #1: Contents
      $shortcodes = array();
      $title = null;
      $content = null;
      $blocks = $groups[2];
      foreach($blocks as $block):
        switch ($block['blockName']):
          case 'core/shortcode':
            $shortcode = render_block($block);
            array_push($shortcodes, $shortcode);
            break;
          default:
            $content .= render_block($block);
            break;
        endswitch;
      endforeach;
      $gallery = (sizeof($shortcodes) > 0) ? apply_filters('the_content', do_blocks($shortcodes[0])) : null;
      $content = apply_filters('the_content', do_blocks($content));
      ?>
      <div class="uk-width-1-1 uk-width-2-3@s uk-text-left@s programa">
        <?php echo $content; ?>
    </div>
    </div>
    </div>
</section>

<?php
endif;
?>

<?php
!empty($post_tags) ? $tag = $post_tags[0]->slug : null;
$attendant_categories = $categories;
if( $tag != 'edicoes-anteriores'):
// -- Section: ATTENDANTS
get_template_part('includes/sections/attendants','seminars');
?>

<?php
// -- Section: SPONSORS
get_template_part('includes/sections/sponsors');
endif;
?>

<?php
// -- Section: CONTACT FORM
get_template_part('includes/sections/contact-form');
?>

<?php
// -- Section: FOOTER
get_template_part('includes/sections/footer');
?>

<?php
// -- Block: FOOTER
get_footer();
?>