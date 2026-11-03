<?php
// -----------------------------------------------------------------------------
//  File: SINGLE for ATTENDANTS
//  Edited: 3L, 03-MAR-2025
// -----------------------------------------------------------------------------

// -- Initializations
global $featured_articles;
$featured_articles = array();
the_post();
array_push($featured_articles, get_the_ID());
uikit_cbs_set_post_views(get_the_ID());
?>

<?php
// -- Block: HEADER
get_header();
?>

<?php
// -- Section: HEADER
get_template_part('includes/sections/header');
?>

<?php
// -- Content Starter (for sticky elements)
?>
<div id="content-start"></div>


<?php
// -- Section: SPONSORS for HEADER
get_template_part('includes/sections/sponsors','header');
?>

<section id="attendant" class="section-soft-gray uk-section">
  <div class="uk-container uk-container-xlarge">
    <div data-uk-grid>
      <?php
      // -- Block: ASIDE BAR
      ?>
      <aside class="uk-width-1-1 uk-width-1-3@s uk-text-right">
        <?php
        // -- Get the picture
        $image_url = str_replace(BASE_SITE, REDIRECT_SITE, get_the_post_thumbnail_url(get_the_ID(),'image-speaker-face'));
        if (empty($image_url)):
          $gender = get_post_meta(get_the_ID(), 'bs_cf_gender', true);
          $image_url = CHILD_BASE_IMAGES.'attendant-placeholder-'.$gender.'.jpg';
        endif;
        ?>
        <img class="speaker-photo uk-border-rounded" src="<?php echo $image_url; ?>" alt="<?php echo the_title(); ?>" loading="lazy">
      </aside>
      <?php
      // -- Block: TEXTS
      ?>
      <div class="uk-width-1-1 uk-width-3-5@s">
        <div class="content">
          <span class="separator"></span>
          <h2 class="uk-heading-small uk-margin-remove-top"><?php the_title(); ?></h2>

          <?php
          // -- Function and Organization
          $attendant_function = array();
          $function = get_post_meta(get_the_ID(), 'bs_cf_function', true);
          if (!empty($function)) array_push($attendant_function, $function);
          $organization = get_post_meta(get_the_ID(), 'bs_cf_organization', true);
          if (!empty($organization)) array_push($attendant_function, $organization);

          if (sizeof($attendant_function) > 0):
          ?>
          <h4><?php echo implode(', ', $attendant_function); ?></h4>
          <?php
          endif;
          ?>

          <?php
          if (has_excerpt()):
          ?>
          <p class="uk-text-lead uk-margin-medium-bottom"><?php echo get_the_excerpt(); ?></p>
          <?php
          endif;
          ?>
          <?php the_content(); ?>
          <span class="separator bottom"></span>
        </div>
        <?php
        // -- Link back to HOMEPAGE
        // $url_return = str_replace(BASE_SITE, REDIRECT_SITE, get_permalink(get_page_by_path('conferencia')));
        $url_return = REDIRECT_SITE;
        if (!empty($url_return)):
        ?>
        <a class="uk-button uk-button-large uk-button-default uk-margin-large-top" href="<?php echo $url_return; ?>#conference-attendants"><span data-uk-icon="chevron-left"></span> <?php _e('Back to Homepage',TEXT_DOMAIN); ?></a>
        <?php
        endif;
        ?>
      </div>
    </div>
  </div>
</section>

<?php
// -- Section: SPONSORS for FOOTER
get_template_part('includes/sections/sponsors','footer');
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
