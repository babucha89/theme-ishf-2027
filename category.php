<?php
// -----------------------------------------------------------------------------
//  File: CATEGORY
//  Edited: 3L, 30-JAN-2023
// -----------------------------------------------------------------------------
?>

<?php
// -- Block: HEADER
get_header();
?>

<?php
// -- Section: HEADER
get_template_part('includes/sections/header');
?>

<!-- Main Content Landmark -->
<main id="content-start" role="main" class="site-main" tabindex="-1">
<?php
// -- Get ARTICLES
$query_params = array(
  'posts_per_page' => 20,
);

$articles = new WP_Query($query_params);
if ($articles->have_posts()):
?>
<section id="category-body" class="uk-section">
  <div class="uk-container uk-container-xlarge">
    <?php
    // -- Block: SECTION LABEL
    $section_name = single_cat_title('', false);
    ?>
    <span class="ishf-badge-pill"><?php _e('Category', TEXT_DOMAIN); ?></span>
    <h1 class="uk-h2 uk-margin-small-top"><?php echo esc_html($section_name); ?></h1>

    <div data-uk-grid data-uk-height-match class="loader-grid uk-margin-medium-top uk-child-width-1-1 uk-child-width-1-4@m uk-flex uk-flex-center">
      <?php
      while ($articles->have_posts()):
        $articles->the_post();
      ?>
      <div>
        <?php
        // -- Block: ARTICLE
        get_template_part('includes/article');
        ?>
      </div>
      <?php
      endwhile;
      ?>
    </div>

    <?php
    // -- Load More Button
    if ($articles->max_num_pages > 1):
      wp_enqueue_script('load-more-articles');
    ?>
    <div class="section-load-more uk-text-center uk-margin-medium-top">
      <div class="uk-container">
        <button type="button" class="load-more-articles uk-button uk-button-large uk-button-secondary uk-border-pill">
          <?php _e('Load More Articles', TEXT_DOMAIN); ?> <span data-uk-icon="chevron-down" aria-hidden="true"></span>
        </button>
      </div>
    </div>
    <script>
    var posts_load_more_articles = '<?php echo json_encode($query_params); ?>', current_page_load_more_articles = 1, max_page_load_more_articles = <?php echo $articles->max_num_pages; ?>;
    </script>
    <?php
    endif;
    ?>
  </div>
</section>
<?php
endif;
wp_reset_query();
?>

  <?php
  // -- Section: CONTACT FORM
  get_template_part('includes/sections/contact-form');
  ?>
</main>

<?php
// -- Block: FOOTER (Consolidated Site Footer & Scripts)
get_footer();
?>
