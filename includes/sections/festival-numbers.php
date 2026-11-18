<?php
// -----------------------------------------------------------------------------
//  Section: THE FESTIVAL IN NUMBERS (About page, before the Past Editions CTA)
//  Four big numbers on the festival's evolution, then a CTA to the Lisbon 2027
//  page (hidden until that page is published).
//  Texts and figures are edited per language in the About page's "Festival in
//  numbers" box (includes/about-page.php); empty fields use these defaults:
//    - Decade impact ......... 10 years, 2017 - 2027, six editions
//    - Attendee growth ....... 2,400+ in Dublin 2025 (Housing Europe report) vs
//                              1,300 in Amsterdam 2017 (architecture proposal)
//    - Event expansion ....... 350 events in Dublin 2025 vs 40 in Amsterdam 2017
//    - Global network reach .. 35 countries, six continents (Dublin 2025)
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 18-NOV-2026
// -----------------------------------------------------------------------------
$ishf_about_id = (int) get_the_ID();
$ishf_n       = function ($key) use ($ishf_about_id) {
  return function_exists('ishf_about_get') ? ishf_about_get($ishf_about_id, $key) : '';
};
$ishf_numbers = array();
for ($ishf_k = 1; $ishf_k <= 4; $ishf_k++) {
  $ishf_numbers[] = array(
    'label' => $ishf_n("number_{$ishf_k}_label"),
    'value' => $ishf_n("number_{$ishf_k}_value"),
    'unit'  => $ishf_n("number_{$ishf_k}_unit"),
    'text'  => $ishf_n("number_{$ishf_k}_text"),
  );
}
$ishf_numbers_cta = function_exists('ishf_get_template_page_url') ? ishf_get_template_page_url('page-lisbon-2027.php', 'lisbon-2027') : '';
?>
<section class="uk-section festival-numbers on-inverse" aria-labelledby="festival-numbers-title">
  <div class="uk-container">
    <div class="festival-numbers-head uk-grid-medium uk-flex-bottom uk-child-width-1-1 uk-child-width-1-2@m" data-uk-grid>
      <div>
        <p class="ishf-eyebrow festival-numbers-eyebrow"><?php echo esc_html($ishf_n('numbers_eyebrow')); ?></p>
        <h2 id="festival-numbers-title" class="festival-numbers-title"><?php echo esc_html($ishf_n('numbers_title')); ?></h2>
      </div>
      <p class="festival-numbers-intro"><?php echo esc_html($ishf_n('numbers_intro')); ?></p>
    </div>

    <ul class="festival-numbers-list uk-grid-medium uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-4@m" data-uk-grid>
      <?php foreach ($ishf_numbers as $number): ?>
        <li>
          <div class="festival-number">
            <p class="festival-number-label"><?php echo esc_html($number['label']); ?></p>
            <p class="festival-number-value"><strong><?php echo esc_html($number['value']); ?></strong> <span><?php echo esc_html($number['unit']); ?></span></p>
            <p class="festival-number-text"><?php echo esc_html($number['text']); ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="festival-numbers-foot">
      <p class="festival-numbers-source"><?php echo esc_html($ishf_n('numbers_source')); ?></p>
      <?php if ($ishf_numbers_cta !== ''): ?>
        <a class="uk-button uk-button-primary uk-border-pill festival-numbers-button" href="<?php echo esc_url($ishf_numbers_cta); ?>">
          <span><?php echo esc_html($ishf_n('numbers_button')); ?></span>
          <span data-uk-icon="icon: arrow-right; ratio: 0.9" aria-hidden="true"></span>
        </a>
      <?php endif; ?>
    </div>
  </div>
</section>
