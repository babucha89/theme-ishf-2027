<?php
// -----------------------------------------------------------------------------
//  Section: USER JOURNEYS (Home)
//  Figma: Home / User journeys. Three cards: Contribute (external Call for
//  Sessions form, new tab), Become a Partner (opens #modal-partnership, rendered
//  by sections/ctas with show_boxes=false), Attend (external registration).
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
?>
<?php $ishf_cta = ishf_get_register_cta(); ?>
<section id="journeys" class="ishf-journeys" aria-labelledby="journeys-title">
  <div class="uk-container">
    <h2 id="journeys-title" class="ishf-eyebrow"><?php _e('Choose your journey', TEXT_DOMAIN); ?></h2>

    <ul class="journey-grid uk-grid uk-grid-medium uk-child-width-1-1 uk-child-width-1-3@m uk-grid-match" data-uk-grid>

      <li>
        <article class="journey-card journey-card--tejo">
          <h3 class="journey-title"><?php _e('Contribute to the Programme', TEXT_DOMAIN); ?></h3>
          <img class="journey-art journey-art--tree" src="<?php echo esc_url(BASE_ASSETS . 'icon-tree.svg'); ?>" alt="" aria-hidden="true" />
          <a class="uk-button btn-ishf-dark" href="<?php echo esc_url(ishf_get_call_for_sessions_url()); ?>" target="_blank" rel="noopener noreferrer">
            <?php _e('Submit Your Proposal', TEXT_DOMAIN); ?><span class="uk-visually-hidden"> <?php _e('(opens in a new tab)', TEXT_DOMAIN); ?></span>
          </a>
        </article>
      </li>

      <li>
        <article class="journey-card journey-card--sun">
          <h3 class="journey-title"><?php _e('Become a Partner', TEXT_DOMAIN); ?></h3>
          <img class="journey-art journey-art--bridge" src="<?php echo esc_url(BASE_ASSETS . 'icon-bridge.svg'); ?>" alt="" aria-hidden="true" />
          <button type="button" class="uk-button btn-ishf-dark" data-uk-toggle="target: #modal-partnership" aria-haspopup="dialog" aria-controls="modal-partnership">
            <?php _e('Become a Partner', TEXT_DOMAIN); ?>
          </button>
        </article>
      </li>

      <li>
        <article class="journey-card journey-card--navy">
          <h3 class="journey-title"><?php _e('Attend the Festival', TEXT_DOMAIN); ?></h3>
          <img class="journey-art journey-art--sun" src="<?php echo esc_url(BASE_ASSETS . 'icon-sun.svg'); ?>" alt="" aria-hidden="true" />
          <a class="uk-button uk-button-primary" href="<?php echo esc_url($ishf_cta['url']); ?>" target="_blank" rel="noopener noreferrer">
            <?php echo esc_html($ishf_cta['label']); ?><span class="uk-visually-hidden"> <?php _e('(opens in a new tab)', TEXT_DOMAIN); ?></span>
          </a>
        </article>
      </li>

    </ul>
  </div>
</section>
