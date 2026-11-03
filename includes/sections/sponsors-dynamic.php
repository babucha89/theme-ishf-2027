<?php
// -----------------------------------------------------------------------------
//  Section: SPONSORS
//  Edited: 3L, 13-FEV-2026
// -----------------------------------------------------------------------------
?>

<?php
// -- Get SPONSOR CATEGORIES
$args_categories = array(
  'taxonomy' => 'sponsor_types',
  'hide_empty' => false,
  // -- Use to order by TAXONOMY ORDER field
  'meta_key' => 'ishf_cf_sponsor_types_order',
  'meta_type' => 'NUMERIC',
  'orderby' => 'meta_value_num',
  'order' => 'DESC',
);
$categories = get_categories($args_categories);

if (!empty($categories)):
?>
<section id="sponsors" class="uk-section uk-section-small uk-light">
  <div class="uk-container">
    <div data-uk-grid class="uk-grid-small uk-child-width-1-2 uk-child-width-1-4@m uk-child-width-1-5@l">
      <?php
      foreach($categories as $category):
        // -- Get SPONSORS
        $args_sponsors = array(
          'post_type' => 'sponsors',
          'posts_per_page' => -1,
          'post_status' => 'publish',
          'tax_query' => array(
            array (
              'taxonomy' => 'sponsor_types',
              'field' => 'slug',
              'terms' => $category->slug,
            )
          ),
          // -- Use to order by CPT ORDER field (then by NAME)
          'meta_key' => 'ishf_cf_order',
          'meta_type' => 'NUMERIC',
          'orderby' => [
            'meta_value_num' => 'DESC',
            'title' => 'ASC',
          ],
        );
        $sponsors = new WP_Query($args_sponsors);

        $sponsors_counter = 0;
        while ($sponsors->have_posts()):
          $sponsors->the_post();
          $title = get_the_title();
          $image_url = str_replace(BASE_SITE, REDIRECT_SITE, get_the_post_thumbnail_url(get_the_ID(),'full'));
          $image_url = (empty($image_url)) ? 'https://placehold.co/300x150?text='.rawurlencode($title) : $image_url;
          $url = get_post_meta(get_the_ID(), 'ishf_cf_url', true);
      ?>
      <div>
        <p class="text-smaller uk-margin-small-bottom uk-text-muted uk-text-uppercase"><?php if ($sponsors_counter === 0): echo esc_html($category->name); else: echo '&nbsp;'; endif; ?></p>
        <?php
        if (!empty($url)):
        ?>
        <a class="uk-inline" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
        <?php
        endif;
        ?>
        <img class="logo" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" />
        <?php
        if (!empty($url)):
        ?>
        </a>
        <?php
        endif;
        ?>
      </div>
      <?php
          $sponsors_counter++;
        endwhile;
      endforeach;
      ?>

    </div>
  </div>
</section>
<?php
endif;
?>