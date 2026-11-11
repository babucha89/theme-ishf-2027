<?php
// -----------------------------------------------------------------------------
//  Section: PAST EDITIONS CTA (About page, after the page content)
//  Links to the Past Editions page; every edition is a chip that opens its
//  modal directly (#edition-slug). Renders nothing until the Past Editions
//  page is published.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 11-NOV-2026
// -----------------------------------------------------------------------------
if (!function_exists('ishf_get_past_editions_page_url')) {
  return;
}
$ishf_editions_url = ishf_get_past_editions_page_url();
if ($ishf_editions_url === '') {
  return;
}
$ishf_editions = ishf_get_editions('ASC');
$ishf_first    = !empty($ishf_editions) ? ishf_edition_dates($ishf_editions[0]->ID) : null;
?>
<section class="uk-section uk-section-small section-editions-cta" aria-labelledby="editions-cta-title">
  <div class="uk-container">
    <div class="editions-cta">
      <div class="editions-cta-text">
        <?php if ($ishf_first): ?>
          <p class="editions-cta-eyebrow"><?php echo esc_html(sprintf(__('Since %s', TEXT_DOMAIN), $ishf_first['year'])); ?></p>
        <?php endif; ?>
        <h2 id="editions-cta-title" class="editions-cta-title"><?php _e('Past editions', TEXT_DOMAIN); ?></h2>
        <p class="editions-cta-intro"><?php _e('Every edition takes the festival to a new city. Look back at where ISHF has been and what each edition brought to the conversation on social and affordable housing.', TEXT_DOMAIN); ?></p>

        <?php if (!empty($ishf_editions)): ?>
          <ul class="editions-cta-chips" aria-label="<?php esc_attr_e('Past editions', TEXT_DOMAIN); ?>">
            <?php foreach ($ishf_editions as $edition): ?>
              <li><a href="<?php echo esc_url($ishf_editions_url . '#edition-' . $edition->post_name); ?>"><?php echo esc_html(get_the_title($edition)); ?></a></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
      <div class="editions-cta-action">
        <a class="uk-button uk-button-primary uk-border-pill" href="<?php echo esc_url($ishf_editions_url); ?>">
          <span><?php _e('Explore past editions', TEXT_DOMAIN); ?></span>
          <span data-uk-icon="icon: arrow-right; ratio: 0.9" aria-hidden="true"></span>
        </a>
      </div>
    </div>
  </div>
</section>
