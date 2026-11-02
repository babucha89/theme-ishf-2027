<?php
// -----------------------------------------------------------------------------
//  File: ROOT HEADER
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="copyright" content="© <?php echo date('Y'); ?>, <?php echo esc_attr(SITE_NAME); ?>" />
  <meta name="designer" content="Medialivre Boost Solutions, www.medialivreboostsolutions.pt" />

  <?php
  // Page Title (WCAG 2.1 AA Criterion 2.4.2 Page Titled / WAVE compliant)
  $site_short = defined('SITE_SHORT_NAME') ? SITE_SHORT_NAME : 'ISHF 2027';
  $site_full  = defined('SITE_NAME') ? SITE_NAME : 'International Social Housing Festival 2027';
  $claim      = __('Cities with Soul: It Begins with a Key', TEXT_DOMAIN);

  if (is_front_page() || is_home() || is_page('landing-page') || is_page_template('page-landing-page.php')) {
    $page_title = $site_short . ' | ' . $claim;
  } elseif (is_singular()) {
    $page_title = single_post_title('', false) . ' | ' . $site_short;
  } elseif (is_archive()) {
    $page_title = wp_strip_all_tags(get_the_archive_title()) . ' | ' . $site_short;
  } elseif (is_search()) {
    $page_title = sprintf(__('Search: %s', TEXT_DOMAIN), get_search_query()) . ' | ' . $site_short;
  } elseif (is_404()) {
    $page_title = __('Page Not Found', TEXT_DOMAIN) . ' | ' . $site_short;
  } else {
    $doc_title = wp_get_document_title();
    $page_title = !empty($doc_title) ? $doc_title : ($site_short . ' | ' . $claim);
  }
  ?>
  <title><?php echo esc_html($page_title); ?></title>

  <?php
  // -- Open Graph / Social Media Meta Tags
  global $fixed_og_image;
  if (function_exists('ishf_open_graph_tags')):
    ishf_open_graph_tags(!empty($fixed_og_image) ? $fixed_og_image : null);
  endif;
  ?>

  <?php
  // -- Favicons
  get_template_part('includes/favicons');
  ?>

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <!-- Accessibility: Skip Link (WCAG 2.1 AA) -->
  <a href="#content-start" class="skip-link">
    <?php _e('Skip to main content', TEXT_DOMAIN); ?>
  </a>

  <?php
  // -- Block: COOKIES DISCLAIMER (Top)
  if (!is_page(array('manutencao', 'under-maintenance')) && !is_page_template('page-under-maintenance.php')):
    if (defined('SITE_COOKIES_DISCLAIMER_POSITION') && (SITE_COOKIES_DISCLAIMER_POSITION === 'top')):
      get_template_part('includes/cookies-disclaimer');
    endif;
  endif;
  ?>
