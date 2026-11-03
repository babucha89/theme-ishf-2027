<?php
// -----------------------------------------------------------------------------
//  File: ARCHIVE (for FAQs CPT)
//  Edited: 3L, 21-FEV-2021
// -----------------------------------------------------------------------------
?>

<?php
// -- Block: HEADER
ishf_use_full_layout(true); // full navigation + footer (inner pages)
get_header();
?>

<?php
// -- Section: HEADER for PREMIO
get_template_part('includes/sections/header', null, array('compact' => true));
?>


<?php
// -- Content Starter (for sticky elements)
?>
<div id="content-start"></div>

<?php
// -- Get FAQS CPT
$args = array(
  'post_type' => 'faqs',
  'posts_per_page' => -1,
  // -- Use to order by CPT ORDER field (then by NAME)
  'meta_key' => 'ishf_cf_order',
  'meta_type' => 'NUMERIC',
  'orderby' => [
    'meta_value_num' => 'DESC',
    'post_date' => 'DESC',
  ],
);
$query_faqs = new WP_Query($args);

// -- Showing ALL items from FAQS
if ($query_faqs->have_posts()):
?>
<section id="faqs-<?php echo get_the_ID(); ?>" class="uk-section">
  <div class="uk-container uk-container-small">
    <h1><?php _e('Frequently Asked Questions', TEXT_DOMAIN); ?></h1>
    <ul data-uk-accordion="multiple: true" class="faq-list uk-margin-medium-top">
      <?php
      // -- Cicle FAQS and list them
      $faq_counter = 1;
      while ($query_faqs->have_posts()):
        $query_faqs->the_post();
        $title = get_the_title();
        $content = get_the_content();
        ?>
      <li>
        <a class="uk-accordion-title" href="#"><span class="faq-counter"><?php echo $faq_counter; ?></span> <?php echo $title; ?></a>
        <div class="uk-accordion-content">
          <?php echo $content; ?>
        </div>
      </li>      
      <?php
        $faq_counter++;
      endwhile;
      ?>
    </ul>
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

<?php
// -- Block: FOOTER
get_footer();
?>