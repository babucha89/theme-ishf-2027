<?php
// -----------------------------------------------------------------------------
//  Block: SOCIAL LINKS (LinkedIn, Instagram, Facebook)
//  URLs: constants SITE_SOCIAL_LINKEDIN / SITE_SOCIAL_INSTAGRAM / SITE_SOCIAL_FACEBOOK
//  (functions.php). A network without a URL is left out; nothing renders when
//  all three are empty. Used in the navbar tools (desktop), the off-canvas menu
//  (mobile) and the footer.
//  get_template_part('includes/social-links', null, array('class' => '...'));
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 17-NOV-2026
// -----------------------------------------------------------------------------
$ishf_social = array_filter(array(
  'linkedin'  => array('LinkedIn', defined('SITE_SOCIAL_LINKEDIN') ? SITE_SOCIAL_LINKEDIN : ''),
  'instagram' => array('Instagram', defined('SITE_SOCIAL_INSTAGRAM') ? SITE_SOCIAL_INSTAGRAM : ''),
  'facebook'  => array('Facebook', defined('SITE_SOCIAL_FACEBOOK') ? SITE_SOCIAL_FACEBOOK : ''),
), function ($network) {
  return !empty($network[1]);
});
if (empty($ishf_social)) {
  return;
}
$ishf_social_class = isset($args['class']) ? ' ' . $args['class'] : '';
?>
<ul class="social-links<?php echo esc_attr($ishf_social_class); ?>" aria-label="<?php esc_attr_e('ISHF 2027 on social media', TEXT_DOMAIN); ?>">
  <?php foreach ($ishf_social as $icon => $network): ?>
    <li>
      <a href="<?php echo esc_url($network[1]); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr(sprintf(__('%s (opens in a new tab)', TEXT_DOMAIN), $network[0])); ?>" data-uk-tooltip="title: <?php echo esc_attr($network[0]); ?>; pos: bottom">
        <span data-uk-icon="icon: <?php echo esc_attr($icon); ?>; ratio: 0.95" aria-hidden="true"></span>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
