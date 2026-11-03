<?php
// -----------------------------------------------------------------------------
//  Section: ATTENDANTS (slider of speakers / jury)
//  Edited: 3L, 03-NOV-2026
// -----------------------------------------------------------------------------
//  Globals (set before get_template_part):
//    $attendant_type   - 'speakers' (default) or 'juri'. Filters CPT attendants
//                        by the custom field ishf_cf_is_{type} = yes.
//    $current_category - optional WP_Term; limits the list to that category.
// -----------------------------------------------------------------------------

// -- Initializations
global $attendant_type, $current_category;

$attendant_type = !empty($attendant_type) ? sanitize_key($attendant_type) : 'speakers';

$args = array(
  'post_type' => 'attendants',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  // -- Meta query: filter by attendant type; ORDER clause is optional so
  //    attendants without an order value are still listed (as 0)
  'meta_query' => array(
    'relation' => 'AND',
    array(
      'key' => 'ishf_cf_is_' . $attendant_type,
      'value' => 'yes',
      'compare' => '=',
    ),
    'order_clause' => array(
      'relation' => 'OR',
      'order_exists' => array(
        'key' => 'ishf_cf_order',
        'type' => 'NUMERIC',
        'compare' => 'EXISTS',
      ),
      'order_missing' => array(
        'key' => 'ishf_cf_order',
        'compare' => 'NOT EXISTS',
      ),
    ),
  ),
  // -- Order by CPT ORDER field (high first), then by NAME
  'orderby' => array(
    'order_exists' => 'DESC',
    'title' => 'ASC',
  ),
);

// -- If current CATEGORY is defined
if (!empty($current_category) && !empty($current_category->term_id)):
  $args['category__in'] = array((int) $current_category->term_id);
endif;

$attendants = new WP_Query($args);
if ($attendants->have_posts()):
?>
<section id="<?php echo esc_attr($attendant_type); ?>" class="ishf-attendants uk-section">
  <div class="uk-container uk-container-xlarge">
    <?php
    // -- Block: SECTION LABEL
    $section_name = ($attendant_type == 'juri') ? __('Jury', TEXT_DOMAIN) : __('Speakers', TEXT_DOMAIN);
    $heading_size = 2;
    $text_align = 'center';
    $section_label_template = locate_template('includes/section-label.php', false, false);
    if ($section_label_template) {
      include($section_label_template);
    } else {
      echo '<h2 class="uk-text-center">' . esc_html($section_name) . '</h2>';
    }
    ?>

    <div class="uk-position-relative uk-text-center uk-margin-medium-top" tabindex="-1" data-uk-slider>
      <div class="uk-slider-container">
        <ul class="uk-slider-items uk-grid uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-6@m">
          <?php
          while ($attendants->have_posts()):
            $attendants->the_post();
            $title = get_the_title();
            $image_url = str_replace(BASE_SITE, REDIRECT_SITE, (string) get_the_post_thumbnail_url(get_the_ID(), 'image-speaker-face'));
            if (empty($image_url)):
              $gender = get_post_meta(get_the_ID(), 'ishf_cf_gender', true);
              $gender = ($gender === 'female') ? 'female' : 'male';
              $image_url = CHILD_BASE_IMAGES . 'attendant-placeholder-' . $gender . '.jpg';
            endif;
            $attendant_function = array();
            $function = get_post_meta(get_the_ID(), 'ishf_cf_function', true);
            if (!empty($function)) $attendant_function[] = $function;
            $organization = get_post_meta(get_the_ID(), 'ishf_cf_organization', true);
            if (!empty($organization)) $attendant_function[] = $organization;

            $post_categories = get_the_category();
            $attendant_category = (empty($current_category) && !empty($post_categories)) ? $post_categories[0] : null;
          ?>
          <li>
            <a class="uk-link-reset" href="<?php echo esc_url(str_replace(BASE_SITE, REDIRECT_SITE, get_permalink())); ?>">
              <div class="attendant-face uk-cover-container uk-border-circle">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" data-uk-cover />
                <canvas width="400" height="400"></canvas>
              </div>
              <p class="text-name uk-margin-remove-bottom uk-margin-remove-adjacent"><?php echo esc_html($title); ?></p>
              <?php if (!empty($attendant_function)): ?>
              <p class="text-function"><?php echo esc_html(implode(', ', $attendant_function)); ?></p>
              <?php endif; ?>
              <?php if (!empty($attendant_category)): ?>
              <p class="attendant-category uk-text-uppercase"><?php echo esc_html($attendant_category->name); ?></p>
              <?php endif; ?>
            </a>
          </li>
          <?php
          endwhile;
          ?>
        </ul>
      </div>
      <div class="uk-hidden@s uk-position-bottom-right">
        <a href="#" data-uk-slidenav-previous data-uk-slider-item="previous" aria-label="<?php esc_attr_e('Previous', TEXT_DOMAIN); ?>"></a>
        <a href="#" data-uk-slidenav-next data-uk-slider-item="next" aria-label="<?php esc_attr_e('Next', TEXT_DOMAIN); ?>"></a>
      </div>
      <div class="uk-visible@s">
        <a class="uk-position-center-left-out uk-position-small" href="#" data-uk-slidenav-previous data-uk-slider-item="previous" aria-label="<?php esc_attr_e('Previous', TEXT_DOMAIN); ?>"></a>
        <a class="uk-position-center-right-out uk-position-small" href="#" data-uk-slidenav-next data-uk-slider-item="next" aria-label="<?php esc_attr_e('Next', TEXT_DOMAIN); ?>"></a>
      </div>
    </div>
  </div>
</section>
<?php
endif;
wp_reset_postdata();
