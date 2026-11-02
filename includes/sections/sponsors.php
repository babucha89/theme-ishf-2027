<?php
// -----------------------------------------------------------------------------
//  Section: SPONSORS (site-wide, before newsletter + footer)
//  Source: Custom Post Type "ishf_sponsor" (provided by the base template the team is merging; slug/fields to be aligned then),
//  grouped by "Sponsor tier" (e.g. Organised with / Supported by).
//  Renders nothing when there are no published sponsors.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
if (!post_type_exists('ishf_sponsor')) {
  return;
}

$ishf_sponsor_q = new WP_Query(array(
  'post_type'      => 'ishf_sponsor',
  'posts_per_page' => 60,
  'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
  'no_found_rows'  => true,
));
if (!$ishf_sponsor_q->have_posts()) {
  wp_reset_postdata();
  return;
}

// Group by first tier term (term order = admin order of creation); no tier => generic group
$groups = array();
while ($ishf_sponsor_q->have_posts()) {
  $ishf_sponsor_q->the_post();
  $terms = get_the_terms(get_the_ID(), 'ishf_sponsor_tier');
  $term  = (!empty($terms) && !is_wp_error($terms)) ? $terms[0] : null;
  $key   = $term ? $term->term_id : 0;
  if (!isset($groups[$key])) {
    $groups[$key] = array('label' => $term ? $term->name : __('Partners', TEXT_DOMAIN), 'items' => array());
  }
  $groups[$key]['items'][] = get_the_ID();
}
wp_reset_postdata();
ksort($groups);
?>
<section id="partners" class="ishf-partners" aria-label="<?php esc_attr_e('Partners and sponsors', TEXT_DOMAIN); ?>">
  <div class="uk-container">
    <?php foreach ($groups as $gi => $group): ?>
      <div class="partner-group">
        <h2 class="ishf-eyebrow"><?php echo esc_html($group['label']); ?></h2>
        <ul class="partner-logos" role="list">
          <?php foreach ($group['items'] as $sid):
            $name    = get_the_title($sid);
            $url     = get_post_meta($sid, '_ishf_sponsor_url', true);
            $light   = get_post_thumbnail_id($sid);
            $dark    = (int) get_post_meta($sid, '_ishf_sponsor_logo_dark_id', true);
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
