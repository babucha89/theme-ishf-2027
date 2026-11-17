<?php
// -----------------------------------------------------------------------------
//  Section: ORGANISERS AND PARTNERS (Lisbon 2027 page)
//  Sponsors CPT, one row per sponsor type (Organização, Alto Patrocínio,
//  Parceiros, Apoio, ...): type name on the left, its logos on the right.
//  Order: ishf_get_sponsor_groups() (includes/cpt/sponsors.php): type "Order"
//  field, highest first; inside a type, sponsor "Order" field, highest first.
//  Logos without boxes, balanced by area (ishf_sponsor_logo_html(): same visual
//  weight from each logo's proportions; first row larger; per-sponsor "Logo size
//  (%)" to fine-tune). Dark mode uses the sponsor's dark-mode logo; a logo without
//  one gets a light backing so it stays legible. Link = custom field ishf_cf_url.
//  Renders nothing when there are no published sponsors.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 17-NOV-2026
// -----------------------------------------------------------------------------
if (!function_exists('ishf_get_sponsor_groups')) {
  return;
}
$partner_groups = ishf_get_sponsor_groups();
// Texts from the "Lisbon 2027 page" box (page-lisbon-2027.php passes them)
$partner_eyebrow = isset($args['eyebrow']) ? $args['eyebrow'] : __('Who makes it happen', TEXT_DOMAIN);
$partner_title   = isset($args['title']) ? $args['title'] : __('Organisers and partners', TEXT_DOMAIN);
$partner_intro   = isset($args['intro']) ? trim((string) $args['intro']) : '';
if (empty($partner_groups)) {
  return;
}
?>
<section id="organisers" class="uk-section lisbon-partners" aria-labelledby="organisers-title">
  <div class="uk-container">
    <?php if ($partner_eyebrow !== ''): ?><p class="ishf-eyebrow"><?php echo esc_html($partner_eyebrow); ?></p><?php endif; ?>
    <h2 id="organisers-title" class="lisbon-section-title"><?php echo esc_html($partner_title); ?></h2>
    <?php if ($partner_intro !== ''): ?><div class="lisbon-partners-intro uk-width-2xlarge"><?php echo wpautop(wp_kses_post($partner_intro)); ?></div><?php endif; ?>

    <div class="lisbon-partner-rows">
      <?php foreach ($partner_groups as $gi => $group):
        $label_id = 'partners-' . sanitize_title($group['slug']) . '-' . $gi; ?>
        <div class="lisbon-partner-row">
          <div class="uk-grid-medium uk-flex-middle" data-uk-grid>
          <h3 id="<?php echo esc_attr($label_id); ?>" class="lisbon-partner-label uk-width-1-1 uk-width-1-5@m"><?php echo esc_html($group['label']); ?></h3>
          <div class="uk-width-1-1 uk-width-expand@m">
          <ul class="lisbon-partner-logos uk-grid-medium uk-flex-middle" data-uk-grid aria-labelledby="<?php echo esc_attr($label_id); ?>">
            <?php foreach ($group['items'] as $sid):
              $url      = get_post_meta($sid, 'ishf_cf_url', true);
              $has_dark = (bool) get_post_meta($sid, 'ishf_cf_logo_dark_id', true) && has_post_thumbnail($sid);
              $tag      = $url ? 'a' : 'span';
              $attrs    = $url ? ' href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer"' : '';
            ?>
              <li class="uk-width-auto"><?php // every size (automatic, Logo size %, Logo width px) is kept on phones; only wider than the screen shrinks ?>
                <<?php echo $tag . $attrs; ?> class="lisbon-partner-tile<?php echo $has_dark ? ' has-dark-logo' : ''; ?>">
                  <?php echo ishf_sponsor_logo_html($sid, 'medium', $gi === 0 ? array(7200, 220, 96) : array(4400, 180, 76)); ?>
                  <?php if ($url): ?><span class="sr-only"> (<?php _e('opens in a new tab', TEXT_DOMAIN); ?>)</span><?php endif; ?>
                </<?php echo $tag; ?>>
              </li>
            <?php endforeach; ?>
          </ul>
          </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
