<?php
// -----------------------------------------------------------------------------
//  Section: SPONSORS (site-wide, before newsletter + footer)
//  Source: CPT "sponsors" (includes/cpt/sponsors.php), grouped by taxonomy "sponsor_types"
//  (group order: term field ishf_cf_sponsor_types_order, highest first; logo order inside a
//  group: custom field ishf_cf_order, highest first, then title).
//  Logo = featured image; link = custom field ishf_cf_url. Optional dark-mode logo: attachment ID
//  in the custom field ishf_cf_logo_dark_id.
//  Renders nothing when there are no published sponsors.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
if (!post_type_exists('sponsors')) {
  return;
}

$ishf_sponsor_q = new WP_Query(array(
  'post_type'      => 'sponsors',
  'post_status'    => 'publish',
  'posts_per_page' => 60,
  'meta_key'       => 'ishf_cf_order',
  'orderby'        => array('meta_value_num' => 'DESC', 'title' => 'ASC'),
  'no_found_rows'  => true,
));
if (!$ishf_sponsor_q->have_posts()) {
  // Sponsors without an "order" value have no meta row: fall back to a plain title order
  $ishf_sponsor_q = new WP_Query(array('post_type' => 'sponsors', 'post_status' => 'publish', 'posts_per_page' => 60, 'orderby' => 'title', 'order' => 'ASC', 'no_found_rows' => true));
}
if (!$ishf_sponsor_q->have_posts()) {
  wp_reset_postdata();
  return;
}

// Group by first sponsor type; groups sorted by the type's order field (highest first)
$groups = array();
while ($ishf_sponsor_q->have_posts()) {
  $ishf_sponsor_q->the_post();
  $terms = get_the_terms(get_the_ID(), 'sponsor_types');
  $term  = (!empty($terms) && !is_wp_error($terms)) ? $terms[0] : null;
  $key   = $term ? $term->term_id : 0;
  if (!isset($groups[$key])) {
    $groups[$key] = array(
      'label' => $term ? $term->name : __('Partners', TEXT_DOMAIN),
      'order' => $term ? (int) get_term_meta($term->term_id, 'ishf_cf_sponsor_types_order', true) : -1,
      'items' => array(),
    );
  }
  $groups[$key]['items'][] = get_the_ID();
}
wp_reset_postdata();
uasort($groups, function ($a, $b) { return $b['order'] <=> $a['order']; });
?>
<section id="partners" class="ishf-partners" aria-label="<?php esc_attr_e('Partners and sponsors', TEXT_DOMAIN); ?>">
  <div class="uk-container">
    <?php foreach ($groups as $gi => $group): ?>
      <div class="partner-group">
        <h2 class="ishf-eyebrow"><?php echo esc_html($group['label']); ?></h2>
        <ul class="partner-logos" role="list">
          <?php foreach ($group['items'] as $sid):
            $name    = get_the_title($sid);
            $url     = get_post_meta($sid, 'ishf_cf_url', true);
            $light   = get_post_thumbnail_id($sid);
            $dark    = (int) get_post_meta($sid, 'ishf_cf_logo_dark_id', true);
            $img_att = array('alt' => $name, 'loading' => 'lazy', 'class' => '');
            ?>
            <li>
              <?php if ($url): ?><a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer"><?php endif; ?>
              <?php if ($light && $dark): ?>
                <?php echo wp_get_attachment_image($light, 'medium', false, array_merge($img_att, array('class' => 'light-mode-img'))); ?>
                <?php echo wp_get_attachment_image($dark, 'medium', false, array_merge($img_att, array('class' => 'dark-mode-img'))); ?>
              <?php elseif ($light): ?>
                <?php echo wp_get_attachment_image($light, 'medium', false, $img_att); ?>
              <?php else: ?>
                <span class="partner-name"><?php echo esc_html($name); ?></span>
              <?php endif; ?>
              <?php if ($url): ?><span class="uk-visually-hidden"> <?php _e('(opens in a new tab)', TEXT_DOMAIN); ?></span></a><?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
</section>
