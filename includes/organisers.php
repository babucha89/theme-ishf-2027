<?php
// -----------------------------------------------------------------------------
//  Block: ORGANISED BY (header and footer of every page)
//  Logos of the organisers: the sponsors of the first sponsor type (highest
//  "Order"), ishf_get_organisers() in includes/cpt/sponsors.php. Logo, dark-mode
//  logo and website (URL field) are edited in Sponsors, per language with WPML.
//  Sizes are balanced by area like the Lisbon 2027 page (the "Logo size (%)"
//  field applies, "Logo width (px)" does not).
//  get_template_part('includes/organisers', null, array('context' => 'header'|'offcanvas'|'footer'));
//    - header ...... light / dark logo follow the site mode (homepage hero, landing page)
//    - band ........ inner pages' header band, right column, larger logos
//    - offcanvas ... mobile / tablet menu (< 960px): one logo per line, larger;
//                    dark panel in both modes, so the dark-mode logo
//    - footer ...... dark background in both modes, so the dark-mode logo;
//                    'all_groups' => true (homepage) adds a row per other sponsor
//                    type (Alto Patrocínio, Parceiros, Apoio…) below "Organised by"
//  Every logo has a tooltip with the sponsor's name (ishf_sponsor_tooltip_attr()).
//  Renders nothing when there are no organisers.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 19-NOV-2026
// -----------------------------------------------------------------------------
if (!function_exists('ishf_get_organisers')) {
  return;
}
$ishf_org_ids = ishf_get_organisers();
if (empty($ishf_org_ids)) {
  return;
}
$ishf_org_context = (isset($args['context']) && in_array($args['context'], array('footer', 'offcanvas', 'band'), true)) ? $args['context'] : 'header';
$ishf_org_boxes   = array('header' => array(4000, 190, 60), 'band' => array(4200, 200, 64), 'offcanvas' => array(5000, 220, 64), 'footer' => array(4400, 210, 64));
$ishf_org_box     = $ishf_org_boxes[$ishf_org_context];
// Rows: "Organised by" + organisers; with all_groups (footer of the homepage) one more row per sponsor type
$ishf_org_sets = array(array('label' => __('Organised by', TEXT_DOMAIN), 'ids' => $ishf_org_ids));
if (!empty($args['all_groups']) && function_exists('ishf_get_sponsor_groups')) {
  foreach (ishf_get_sponsor_groups() as $ishf_group) {
    if ($ishf_group['items'] !== $ishf_org_ids && !empty($ishf_group['items'])) {
      $ishf_org_sets[] = array('label' => $ishf_group['label'], 'ids' => $ishf_group['items']);
    }
  }
}
$ishf_tip_pos = $ishf_org_context === 'offcanvas' ? 'right' : ($ishf_org_context === 'footer' ? 'top' : 'bottom');
?>
<?php if (count($ishf_org_sets) > 1): ?><div class="organisers-stack organisers-stack--<?php echo esc_attr($ishf_org_context); ?>"><?php endif; ?>
<?php foreach ($ishf_org_sets as $ishf_set):
  $ishf_org_label_id = wp_unique_id('organisers-label-'); // unique: several blocks per page (header / menu / footer)
?>
<div class="organisers organisers--<?php echo esc_attr($ishf_org_context); ?>">
  <p class="organisers-label" id="<?php echo esc_attr($ishf_org_label_id); ?>"><?php echo esc_html($ishf_set['label']); ?></p>
  <ul class="organisers-logos" aria-labelledby="<?php echo esc_attr($ishf_org_label_id); ?>">
    <?php foreach ($ishf_set['ids'] as $sid):
      $url  = get_post_meta($sid, 'ishf_cf_url', true);
      $tip  = ishf_sponsor_tooltip_attr($sid, $ishf_tip_pos);
      $logo = ishf_sponsor_logo_html($sid, 'medium', $ishf_org_box, array(
        'variant' => in_array($ishf_org_context, array('header', 'band'), true) ? 'auto' : 'on-dark',
        'fixed'   => false,
      ));
    ?>
      <li<?php echo $url ? '' : $tip; ?>>
        <?php if ($url): ?>
          <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer"<?php echo $tip; ?>><?php echo $logo; ?><span class="sr-only"> (<?php _e('opens in a new tab', TEXT_DOMAIN); ?>)</span></a>
        <?php else: ?>
          <?php echo $logo; ?>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endforeach; ?>
<?php if (count($ishf_org_sets) > 1): ?></div><?php endif; ?>
