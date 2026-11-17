<?php
// -----------------------------------------------------------------------------
//  Block: ORGANISED BY (header and footer of every page)
//  Logos of the organisers: the sponsors of the first sponsor type (highest
//  "Order"), ishf_get_organisers() in includes/cpt/sponsors.php. Logo, dark-mode
//  logo and website (URL field) are edited in Sponsors, per language with WPML.
//  Sizes are balanced by area like the Lisbon 2027 page (the "Logo size (%)"
//  field applies, "Logo width (px)" does not).
//  get_template_part('includes/organisers', null, array('context' => 'header'|'footer'));
//    - header: light / dark logo follow the site mode
//    - footer: dark background in both modes, so always the dark-mode logo
//  Renders nothing when there are no organisers.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 17-NOV-2026
// -----------------------------------------------------------------------------
if (!function_exists('ishf_get_organisers')) {
  return;
}
$ishf_org_ids = ishf_get_organisers();
if (empty($ishf_org_ids)) {
  return;
}
$ishf_org_context = (isset($args['context']) && $args['context'] === 'footer') ? 'footer' : 'header';
$ishf_org_box     = $ishf_org_context === 'footer' ? array(3000, 170, 52) : array(2600, 150, 46);
$ishf_org_label   = __('Organised by', TEXT_DOMAIN);
?>
<div class="organisers organisers--<?php echo esc_attr($ishf_org_context); ?>">
  <p class="organisers-label" id="organisers-label-<?php echo esc_attr($ishf_org_context); ?>"><?php echo esc_html($ishf_org_label); ?></p>
  <ul class="organisers-logos" aria-labelledby="organisers-label-<?php echo esc_attr($ishf_org_context); ?>">
    <?php foreach ($ishf_org_ids as $sid):
      $url  = get_post_meta($sid, 'ishf_cf_url', true);
      $logo = ishf_sponsor_logo_html($sid, 'medium', $ishf_org_box, array(
        'variant' => $ishf_org_context === 'footer' ? 'on-dark' : 'auto',
        'fixed'   => false,
      ));
    ?>
      <li>
        <?php if ($url): ?>
          <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer"><?php echo $logo; ?><span class="sr-only"> (<?php _e('opens in a new tab', TEXT_DOMAIN); ?>)</span></a>
        <?php else: ?>
          <?php echo $logo; ?>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
