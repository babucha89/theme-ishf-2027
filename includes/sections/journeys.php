<?php
// -----------------------------------------------------------------------------
//  Section: USER JOURNEYS (Home)
//  Two cards at a time:
//    1. Participate, by registration phase (ishf_get_register_phase(), functions.php):
//       - until 31 Dec 2026: Call for Sessions (external form, new tab)
//       - from 1 Jan 2027:  attendee registration (external form, new tab)
//    2. Partnerships: opens #modal-partnership (rendered by sections/ctas).
//  Texts: "Homepage: hero and journeys" box of the page 'sections/hero'
//  (includes/home-fields.php), per language; empty fields use the defaults.
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 17-NOV-2026
// -----------------------------------------------------------------------------
$ishf_phase = ishf_get_register_phase();
if ($ishf_phase === 'b2c') {
  $ishf_participate = array(
    'title'  => ishf_home_get('ishf_journeys_attend_title'),
    'button' => ishf_home_get('ishf_journeys_attend_button'),
    'url'    => ishf_get_register_url(),
    'style'  => 'navy',
    'art'    => 'icon-sun.svg',
    'class'  => 'uk-button uk-button-primary',
  );
} else {
  $ishf_participate = array(
    'title'  => ishf_home_get('ishf_journeys_participate_title'),
    'button' => ishf_home_get('ishf_journeys_participate_button'),
    'url'    => ishf_get_call_for_sessions_url(),
    'style'  => 'tejo',
    'art'    => 'icon-tree.svg',
    'class'  => 'uk-button btn-ishf-dark',
  );
}
?>
<section id="journeys" class="ishf-journeys" aria-labelledby="journeys-title">
  <div class="uk-container">
    <h2 id="journeys-title" class="ishf-eyebrow"><?php echo esc_html(ishf_home_get('ishf_journeys_title')); ?></h2>

    <ul class="journey-grid uk-grid uk-grid-medium uk-child-width-1-1 uk-child-width-1-2@m uk-grid-match" data-uk-grid>

      <li>
        <article class="journey-card journey-card--<?php echo esc_attr($ishf_participate['style']); ?>">
          <h3 class="journey-title"><?php echo esc_html($ishf_participate['title']); ?></h3>
          <img class="journey-art" src="<?php echo esc_url(BASE_ASSETS . $ishf_participate['art']); ?>" alt="" aria-hidden="true" />
          <a class="<?php echo esc_attr($ishf_participate['class']); ?>" href="<?php echo esc_url($ishf_participate['url']); ?>" target="_blank" rel="noopener noreferrer">
            <?php echo esc_html($ishf_participate['button']); ?><span class="uk-visually-hidden"> <?php _e('(opens in a new tab)', TEXT_DOMAIN); ?></span>
          </a>
        </article>
      </li>

      <li>
        <article class="journey-card journey-card--sun">
          <h3 class="journey-title"><?php echo esc_html(ishf_home_get('ishf_journeys_partner_title')); ?></h3>
          <img class="journey-art" src="<?php echo esc_url(BASE_ASSETS . 'icon-bridge.svg'); ?>" alt="" aria-hidden="true" />
          <button type="button" class="uk-button btn-ishf-dark" data-uk-toggle="target: #modal-partnership" aria-haspopup="dialog" aria-controls="modal-partnership">
            <?php echo esc_html(ishf_home_get('ishf_journeys_partner_button')); ?>
          </button>
        </article>
      </li>

    </ul>
  </div>
</section>
