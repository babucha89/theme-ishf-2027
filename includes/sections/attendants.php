<?php
// -----------------------------------------------------------------------------
//  Section: ATTENDANTS
//  Edited: 3L, 03-MAI-2022
// -----------------------------------------------------------------------------


/* ---------
Notes:
- This block is design to use categories, please remove category logic if not needed
--------- */


// -- Initializations
global $attendant_type, $current_category;

$section_class = ($attendant_type == 'juri') ? 'section-mid-gray' : 'section-gray';

$args = array(
  'post_type' => 'attendants',
  'posts_per_page' => -1,
  // -- Use to order by CPT ORDER field (then by NAME)
  'meta_key' => 'bs_cf_order',
  'meta_type' => 'NUMERIC',
  'orderby' => [
    'meta_value_num' => 'DESC',
    'title' => 'ASC',
  ],
  // -- Meta query to filter only JURI members
  'meta_query' => array(
    array(
      'key' => 'bs_cf_is_'.$attendant_type,
      'value' => 'yes',
      'compare' => '='
    )
  ),
);
$original_args = $args;
$attendants = new WP_Query($args);
if ($attendants->have_posts()):
?>
<section id="<?php echo $attendant_type; ?>" class="<?php echo $section_class; ?> uk-section uk-light">
  <div class="uk-container uk-container-xlarge">
    <?php
    // -- Block: SECTION LABEL
    $section_name = ($attendant_type == 'juri') ? 'Júri' : 'Oradores';
    $heading_size = 2;
    $text_align = 'center';
    include(locate_template('includes/section-label.php', false, false));
    ?>

    <?php
    // -- If current CATEGORY is defined
    if (!empty($current_category)):
      // Add current category to $args
      $args = array_merge($args, array(
        'category__in' => $current_category->term_id,
      ));
    endif;
    $attendants = new WP_Query($args);
    ?>
    <div class="uk-position-relative uk-text-center uk-margin-medium-top" tabindex="-1" data-uk-slider>
      <div class="uk-slider-container">
        <ul class="uk-slider-items uk-grid uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-6@m">
          <?php
          while ($attendants->have_posts()):
            $attendants->the_post();
            $title = get_the_title();
            $image_url = str_replace(BASE_SITE, REDIRECT_SITE, get_the_post_thumbnail_url(get_the_ID(),'image-speaker-face'));
            if (empty($image_url)):
              $gender = get_post_meta(get_the_ID(), 'bs_cf_gender', true);
              $image_url = CHILD_BASE_IMAGES.'attendant-placeholder-'.$gender.'.jpg';
            endif;
            $attendant_function = array();
            $function = get_post_meta(get_the_ID(), 'bs_cf_function', true);
            if (!empty($function)) array_push($attendant_function, $function);
            $organization = get_post_meta(get_the_ID(), 'bs_cf_organization', true);
            if (!empty($organization)) array_push($attendant_function, $organization);

            $attendant_category = (empty($current_category) && !empty(get_the_category())) ? get_the_category()[0] : null;
          ?>
          <li>
            <a class="uk-link-reset" href="<?php echo str_replace(BASE_SITE, REDIRECT_SITE, get_permalink()); ?>">
              <div class="attendant-face uk-cover-container uk-border-circle">
                <img src="<?php echo $image_url; ?>" alt="<?php echo $title;?>" loading="lazy" data-uk-cover />
                <canvas width="400" height="400"></canvas>
              </div>
              <p class="text-name uk-margin-remove-bottom uk-margin-remove-adjacent"><?php echo $title;?></p>
              <?php
              if (sizeof($attendant_function) > 0):
              ?>
              <p class="text-function"><?php echo implode(', ', $attendant_function); ?></p>
              <?php
              endif;
              ?>
              <?php
              if (!empty($attendant_category)):
              ?>
              <p class="attendant-category uk-text-uppercase"><?php echo $attendant_category->name; ?></p>
              <?php
              endif;
              ?>
            </a>
          </li>
          <?php
          endwhile;
          ?>
        </ul>
      </div>
      <div class="uk-hidden@s uk-position-bottom-right">
        <a class="" href="#" data-uk-slidenav-previous data-uk-slider-item="previous"></a>
        <a class="" href="#" data-uk-slidenav-next data-uk-slider-item="next"></a>
      </div>
      <div class="uk-visible@s">
        <a class="uk-position-center-left-out uk-position-small" href="#" data-uk-slidenav-previous data-uk-slider-item="previous"></a>
        <a class="uk-position-center-right-out uk-position-small" href="#" data-uk-slidenav-next data-uk-slider-item="next"></a>
      </div>
    </div>
  </div>
</section>
<?php
endif;
wp_reset_query();
?>
