<?php
// -----------------------------------------------------------------------------
//  Section: THE FESTIVAL IN NUMBERS (About page, before the Past Editions CTA)
//  Four big numbers on the festival's evolution, then a CTA to the Lisbon 2027
//  page (hidden until that page is published).
//  Figures (texts in languages/*.po; numbers are translatable so each language
//  can use its own separators):
//    - Decade impact ......... 10 years, 2017 - 2027, six editions
//    - Attendee growth ....... 2,400+ in Dublin 2025 (Housing Europe report) vs
//                              1,300 in Amsterdam 2017 (architecture proposal)
//    - Event expansion ....... 350 events in Dublin 2025 vs 40 in Amsterdam 2017
//    - Global network reach .. 35 countries, six continents (Dublin 2025)
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 17-NOV-2026
// -----------------------------------------------------------------------------
$ishf_numbers = array(
  array(
    'label' => __('Decade impact', TEXT_DOMAIN),
    'value' => __('10', TEXT_DOMAIN),
    'unit'  => __('years', TEXT_DOMAIN),
    'text'  => __('From Amsterdam 2017 to Lisbon 2027: six editions in six European cities.', TEXT_DOMAIN),
  ),
  array(
    'label' => __('Attendee growth', TEXT_DOMAIN),
    'value' => __('2,400+', TEXT_DOMAIN),
    'unit'  => __('participants', TEXT_DOMAIN),
    'text'  => __('In Dublin 2025, almost twice the 1,300 visitors of the first edition.', TEXT_DOMAIN),
  ),
  array(
    'label' => __('Event expansion', TEXT_DOMAIN),
    'value' => __('350', TEXT_DOMAIN),
    'unit'  => __('events', TEXT_DOMAIN),
    'text'  => __('In Dublin 2025, up from 40 events in Amsterdam 2017.', TEXT_DOMAIN),
  ),
  array(
    'label' => __('Global network reach', TEXT_DOMAIN),
    'value' => __('35', TEXT_DOMAIN),
    'unit'  => __('countries', TEXT_DOMAIN),
    'text'  => __('Participants from six continents met in Dublin 2025.', TEXT_DOMAIN),
  ),
);
$ishf_numbers_cta = function_exists('ishf_get_template_page_url') ? ishf_get_template_page_url('page-lisbon-2027.php', 'lisbon-2027') : '';
?>
<section class="uk-section festival-numbers on-inverse" aria-labelledby="festival-numbers-title">
  <div class="uk-container">
    <div class="festival-numbers-head">
      <div>
        <p class="ishf-eyebrow festival-numbers-eyebrow"><?php _e('2017 – 2027', TEXT_DOMAIN); ?></p>
        <h2 id="festival-numbers-title" class="festival-numbers-title"><?php _e('The festival in numbers', TEXT_DOMAIN); ?></h2>
      </div>
      <p class="festival-numbers-intro"><?php _e('In ten years, ISHF has grown from a Dutch premiere into the global meeting point for social, public and cooperative housing.', TEXT_DOMAIN); ?></p>
    </div>

    <ul class="festival-numbers-list">
      <?php foreach ($ishf_numbers as $number): ?>
        <li class="festival-number">
          <p class="festival-number-label"><?php echo esc_html($number['label']); ?></p>
          <p class="festival-number-value"><strong><?php echo esc_html($number['value']); ?></strong> <span><?php echo esc_html($number['unit']); ?></span></p>
          <p class="festival-number-text"><?php echo esc_html($number['text']); ?></p>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="festival-numbers-foot">
      <p class="festival-numbers-source"><?php _e('Sources: festival reports by Housing Europe and the host organisations.', TEXT_DOMAIN); ?></p>
      <?php if ($ishf_numbers_cta !== ''): ?>
        <a class="uk-button uk-button-primary uk-border-pill festival-numbers-button" href="<?php echo esc_url($ishf_numbers_cta); ?>">
          <span><?php _e('Join us in Lisbon 2027', TEXT_DOMAIN); ?></span>
          <span data-uk-icon="icon: arrow-right; ratio: 0.9" aria-hidden="true"></span>
        </a>
      <?php endif; ?>
    </div>
  </div>
</section>
