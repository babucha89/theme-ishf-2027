<?php
// -----------------------------------------------------------------------------
//  Section: ABOUT THE FESTIVAL
//  Pillars and vision of ISHF 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------
?>
<section id="about" class="uk-section uk-section-large section-about" aria-labelledby="about-title">
  <div class="uk-container uk-container-large">
    
    <div class="uk-text-center uk-margin-large-bottom">
      <span class="ishf-badge-pill">
        <?php _e('The 2027 Lisbon Edition', TEXT_DOMAIN); ?>
      </span>
      <h2 id="about-title" class="uk-h2 uk-text-bold section-title uk-margin-small-top">
        <?php _e('A Global Gathering for Social & Sustainable Living', TEXT_DOMAIN); ?>
      </h2>
      <p class="section-lead uk-margin-auto uk-width-2-3@m">
        <?php _e('The International Social Housing Festival (ISHF) is the premier global event dedicated to affordable, public, and cooperative housing. After Amsterdam, Lyon, Helsinki, and Barcelona, Lisbon will host the next biennial edition in June 2027.', TEXT_DOMAIN); ?>
      </p>
    </div>

    <!-- 4 Pillars Grid -->
    <div data-uk-grid class="uk-grid-match uk-grid-medium uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-4@l">
      
      <!-- Pillar 1 -->
      <div>
        <div class="pillar-card uk-card uk-card-default uk-card-body">
          <div class="pillar-icon-box" style="background-color: rgba(2, 50, 86, 0.08);">
            <img src="<?php echo esc_url(BASE_ASSETS . 'icon-building.svg'); ?>" alt="" class="pillar-icon" aria-hidden="true" />
          </div>
          <h3 class="uk-h4 uk-margin-small-top uk-margin-small-bottom">
            <?php _e('Affordable Housing', TEXT_DOMAIN); ?>
          </h3>
          <p class="uk-text-small uk-margin-remove">
            <?php _e('Showcasing best practices in public and non-profit housing delivery, long-term financing, and cooperative tenure models.', TEXT_DOMAIN); ?>
          </p>
        </div>
      </div>

      <!-- Pillar 2 -->
      <div>
        <div class="pillar-card uk-card uk-card-default uk-card-body">
          <div class="pillar-icon-box" style="background-color: rgba(74, 141, 128, 0.12);">
            <img src="<?php echo esc_url(BASE_ASSETS . 'icon-tree.svg'); ?>" alt="" class="pillar-icon" aria-hidden="true" />
          </div>
          <h3 class="uk-h4 uk-margin-small-top uk-margin-small-bottom">
            <?php _e('Green Transition', TEXT_DOMAIN); ?>
          </h3>
          <p class="uk-text-small uk-margin-remove">
            <?php _e('Decarbonizing neighborhoods through circular renovation, renewable energy communities, and climate-resilient architecture.', TEXT_DOMAIN); ?>
          </p>
        </div>
      </div>

      <!-- Pillar 3 -->
      <div>
        <div class="pillar-card uk-card uk-card-default uk-card-body">
          <div class="pillar-icon-box" style="background-color: rgba(206, 70, 56, 0.1);">
            <img src="<?php echo esc_url(BASE_ASSETS . 'icon-bridge.svg'); ?>" alt="" class="pillar-icon" aria-hidden="true" />
          </div>
          <h3 class="uk-h4 uk-margin-small-top uk-margin-small-bottom">
            <?php _e('Social Inclusion', TEXT_DOMAIN); ?>
          </h3>
          <p class="uk-text-small uk-margin-remove">
            <?php _e('Connecting communities, championing tenant democracy, combating spatial segregation, and fostering inclusive cities for all generations.', TEXT_DOMAIN); ?>
          </p>
        </div>
      </div>

      <!-- Pillar 4 -->
      <div>
        <div class="pillar-card uk-card uk-card-default uk-card-body">
          <div class="pillar-icon-box" style="background-color: rgba(141, 190, 204, 0.2);">
            <img src="<?php echo esc_url(BASE_ASSETS . 'icon-wave.svg'); ?>" alt="" class="pillar-icon" style="max-height: 28px;" aria-hidden="true" />
          </div>
          <h3 class="uk-h4 uk-margin-small-top uk-margin-small-bottom">
            <?php _e('Lisbon & Beyond', TEXT_DOMAIN); ?>
          </h3>
          <p class="uk-text-small uk-margin-remove">
            <?php _e('Immerse in field visits, site tours, and international debates hosted in historic and revitalized venues across the Portuguese capital.', TEXT_DOMAIN); ?>
          </p>
        </div>
      </div>

    </div>

  </div>
</section>
