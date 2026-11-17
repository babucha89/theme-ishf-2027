<?php
// -----------------------------------------------------------------------------
//  Section: ORGANISERS AND PARTNERS (Lisbon 2027 page)
//  Sponsors CPT, one row per sponsor type (Organização, Alto Patrocínio,
//  Parceiros, Apoio, ...): type name on the left, its logos on the right.
//  Order: ishf_get_sponsor_groups() (includes/cpt/sponsors.php): type "Order"
//  field, highest first; inside a type, sponsor "Order" field, highest first.
//  Logo tiles: white in both modes (brand logos stay legible); with a dark-mode
//  logo the tile turns dark in dark mode. Link = custom field ishf_cf_url.
//  Renders nothing when there are no published sponsors.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 17-NOV-2026
// -----------------------------------------------------------------------------
if (!function_exists('ishf_get_sponsor_groups')) {
  return;
}
$partner_groups = ishf_get_sponsor_groups();
if (empty($partner_groups)) {
  return;
}
?>
<section id="organisers" class="uk-section lisbon-partners" aria-labelledby="organisers-title">
  <div class="uk-container">
    <p class="ishf-eyebrow"><?php _e('Who makes it happen', TEXT_DOMAIN); ?></p>
    <h2 id="organisers-title" class="lisbon-section-title"><?php _e('Organisers and partners', TEXT_DOMAIN); ?></h2>

    <div class="lisbon-partner-rows">
      <?php foreach ($partner_groups as $gi => $group):
        $label_id = 'partners-' . sanitize_title($group['slug']) . '-' . $gi; ?>
        <div class="lisbon-partner-row">
          <h3 id="<?php echo esc_attr($label_id); ?>" class="lisbon-partner-label"><?php echo esc_html($group['label']); ?></h3>
          <ul class="lisbon-partner-logos" aria-labelledby="<?php echo esc_attr($label_id); ?>">
            <?php foreach ($group['items'] as $sid):
              $url      = get_post_meta($sid, 'ishf_cf_url', true);
              $has_dark = (bool) get_post_meta($sid, 'ishf_cf_logo_dark_id', true) && has_post_thumbnail($sid);
              $tag      = $url ? 'a' : 'span';
              $attrs    = $url ? ' href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer"' : '';
            ?>
              <li>
                <<?php echo $tag . $attrs; ?> class="lisbon-partner-tile<?php echo $has_dark ? ' has-dark-logo' : ''; ?>">
                  <?php echo ishf_sponsor_logo_html($sid); ?>
                  <?php if ($url): ?><span class="sr-only"> (<?php _e('opens in a new tab', TEXT_DOMAIN); ?>)</span><?php endif; ?>
                </<?php echo $tag; ?>>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
