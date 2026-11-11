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
ishf_set_post_views(get_the_ID());
?>

<?php
// -- Block: HEADER
ishf_use_full_layout(true); // full navigation + footer (inner pages)
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
          $gender = get_post_meta(get_the_ID(), 'ishf_cf_gender', true);
          $image_url = BASE_IMAGES.'attendant-placeholder-'.$gender.'.jpg';
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
          $function = get_post_meta(get_the_ID(), 'ishf_cf_function', true);
          if (!empty($function)) array_push($attendant_function, $function);
          $organization = get_post_meta(get_the_ID(), 'ishf_cf_organization', true);
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
        <?php get_template_part('includes/sections/attendant-sessions'); ?>
        <?php
        // -- Link back to the PREVIOUS page (falls back to the Programme when opened directly)
        $url_return = ishf_get_programme_url();
        ?>
        <a class="uk-button uk-button-large uk-button-default uk-margin-large-top" href="<?php echo esc_url($url_return); ?>" data-ishf-back><span data-uk-icon="chevron-left"></span> <?php _e('Back', TEXT_DOMAIN); ?></a>
        <script>
        (function () {
          var link = document.querySelector('[data-ishf-back]');
          if (!link) { return; }
          link.addEventListener('click', function (e) {
            // Only go "back" when the visitor really came from this site
            var sameSite = false;
            try { sameSite = document.referrer !== '' && new URL(document.referrer).host === location.host; } catch (err) {}
            if (sameSite && history.length > 1) {
              e.preventDefault();
              history.back();
            }
          });
        })();
        </script>
      </div>
    </div>
  </div>
</section>

<?php
// -- Block: FOOTER
get_footer();
?>
