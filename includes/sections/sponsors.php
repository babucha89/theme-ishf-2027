<?php
// -----------------------------------------------------------------------------
//  Section: SPONSORS (site-wide, before newsletter + footer)
//  Source: CPT "sponsors" (includes/cpt/sponsors.php), grouped by taxonomy "sponsor_types"
//  (group order: term field ishf_cf_sponsor_types_order, highest first; logo order inside a
//  group: custom field ishf_cf_order, highest first, then title).
//  Logo = featured image; link = custom field ishf_cf_url. Optional dark-mode logo: attachment ID
//  in the custom field ishf_cf_logo_dark_id.
//  Renders nothing when there are no published sponsors, or on pages that show the
//  sponsors themselves (ishf_show_footer_sponsors(false), e.g. Lisbon 2027).
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
if (!function_exists('ishf_get_sponsor_groups') || !ishf_show_footer_sponsors()) {
  return;
}
$groups = ishf_get_sponsor_groups(); // includes/cpt/sponsors.php
if (empty($groups)) {
  return;
}
?>
<section id="partners" class="ishf-partners" aria-label="<?php esc_attr_e('Partners and sponsors', TEXT_DOMAIN); ?>">
  <div class="uk-container">
    <?php foreach ($groups as $gi => $group): ?>
      <div class="partner-group">
        <h2 class="ishf-eyebrow"><?php echo esc_html($group['label']); ?></h2>
        <ul class="partner-logos" role="list">
          <?php foreach ($group['items'] as $sid):
            $url = get_post_meta($sid, 'ishf_cf_url', true); ?>
            <li>
              <?php if ($url): ?><a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer"><?php endif; ?>
              <?php echo ishf_sponsor_logo_html($sid); ?>
              <?php if ($url): ?><span class="uk-visually-hidden"> <?php _e('(opens in a new tab)', TEXT_DOMAIN); ?></span></a><?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
</section>
