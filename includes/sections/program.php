<?php
// -----------------------------------------------------------------------------
//  Section: PROGRAM / AGENDA
//  Festival Schedule & Key Streams (7–9 June 2027 Lisbon)
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
?>
<section id="program" class="uk-section uk-section-large section-program" aria-labelledby="program-title">
  <div class="uk-container uk-container-large">
    
    <div class="uk-text-center uk-margin-large-bottom">
      <span class="ishf-badge-pill">
        <?php _e('Festival Agenda', TEXT_DOMAIN); ?>
      </span>
      <h2 id="program-title" class="uk-h2 uk-text-bold section-title uk-margin-small-top">
        <?php _e('Three Days of Exchange, Innovation & Urban Action', TEXT_DOMAIN); ?>
      </h2>
      <p class="section-lead uk-margin-auto uk-width-2-3@m">
        <?php _e('Explore high-level international plenaries, practical workshops, site tours of innovative Lisbon housing projects, and policy assemblies.', TEXT_DOMAIN); ?>
      </p>

      <!-- Program Day Switcher (UIkit Subnav) -->
      <ul class="uk-subnav uk-subnav-pill uk-flex-center uk-margin-medium-top program-days-nav" data-uk-switcher="animation: uk-animation-fade">
        <li class="uk-active"><a href="#"><?php _e('Day 1 · 7 Jun', TEXT_DOMAIN); ?></a></li>
        <li><a href="#"><?php _e('Day 2 · 8 Jun', TEXT_DOMAIN); ?></a></li>
        <li><a href="#"><?php _e('Day 3 · 9 Jun', TEXT_DOMAIN); ?></a></li>
      </ul>
    </div>

    <!-- Day Panels -->
    <ul class="uk-switcher uk-margin">
      
      <!-- Day 1 Panel -->
      <li>
        <div class="program-timeline">
          <div class="timeline-item uk-card uk-card-default uk-card-body uk-margin-small-bottom">
            <div class="uk-grid-small uk-flex-middle" data-uk-grid>
              <div class="uk-width-auto@m">
                <span class="timeline-time uk-text-bold uk-text-primary">09:00 – 10:30</span>
              </div>
              <div class="uk-width-expand@m">
                <span class="uk-label uk-label-warning"><?php _e('Plenary', TEXT_DOMAIN); ?></span>
                <h3 class="uk-h4 uk-margin-small-top uk-margin-remove-bottom">
                  <?php _e('Opening Ceremony: Cities with Soul & Right to Housing', TEXT_DOMAIN); ?>
                </h3>
                <p class="uk-text-muted uk-text-small uk-margin-remove-top">
                  <?php _e('Keynotes by European, Portuguese, and Lisbon leadership on social housing governance.', TEXT_DOMAIN); ?>
                </p>
              </div>
            </div>
          </div>

          <div class="timeline-item uk-card uk-card-default uk-card-body uk-margin-small-bottom">
            <div class="uk-grid-small uk-flex-middle" data-uk-grid>
              <div class="uk-width-auto@m">
                <span class="timeline-time uk-text-bold uk-text-primary">11:00 – 13:00</span>
              </div>
              <div class="uk-width-expand@m">
                <span class="uk-label uk-label-success"><?php _e('Policy Debate', TEXT_DOMAIN); ?></span>
                <h3 class="uk-h4 uk-margin-small-top uk-margin-remove-bottom">
                  <?php _e('European Financing Models for Affordable Living', TEXT_DOMAIN); ?>
                </h3>
                <p class="uk-text-muted uk-text-small uk-margin-remove-top">
                  <?php _e('Unlocking public investment, EIB facilities, and cooperative housing funds.', TEXT_DOMAIN); ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </li>

      <!-- Day 2 Panel -->
      <li>
        <div class="program-timeline">
          <div class="timeline-item uk-card uk-card-default uk-card-body uk-margin-small-bottom">
            <div class="uk-grid-small uk-flex-middle" data-uk-grid>
              <div class="uk-width-auto@m">
                <span class="timeline-time uk-text-bold uk-text-primary">09:30 – 13:00</span>
              </div>
              <div class="uk-width-expand@m">
                <span class="uk-label"><?php _e('Site Visits', TEXT_DOMAIN); ?></span>
                <h3 class="uk-h4 uk-margin-small-top uk-margin-remove-bottom">
                  <?php _e('Lisbon Housing Tour: Regeneration, Co-ops & Heritage', TEXT_DOMAIN); ?>
                </h3>
                <p class="uk-text-muted uk-text-small uk-margin-remove-top">
                  <?php _e('Guided field visits across innovative municipal and community housing projects in Greater Lisbon.', TEXT_DOMAIN); ?>
                </p>
              </div>
            </div>
          </div>

          <div class="timeline-item uk-card uk-card-default uk-card-body uk-margin-small-bottom">
            <div class="uk-grid-small uk-flex-middle" data-uk-grid>
              <div class="uk-width-auto@m">
                <span class="timeline-time uk-text-bold uk-text-primary">14:30 – 17:00</span>
              </div>
              <div class="uk-width-expand@m">
                <span class="uk-label uk-label-danger"><?php _e('Workshops', TEXT_DOMAIN); ?></span>
                <h3 class="uk-h4 uk-margin-small-top uk-margin-remove-bottom">
                  <?php _e('Climate Resilience & Energy Communities in Social Housing', TEXT_DOMAIN); ?>
                </h3>
                <p class="uk-text-muted uk-text-small uk-margin-remove-top">
                  <?php _e('Circular renovation, zero-emission neighborhoods, and combating energy poverty.', TEXT_DOMAIN); ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </li>

      <!-- Day 3 Panel -->
      <li>
        <div class="program-timeline">
          <div class="timeline-item uk-card uk-card-default uk-card-body uk-margin-small-bottom">
            <div class="uk-grid-small uk-flex-middle" data-uk-grid>
              <div class="uk-width-auto@m">
                <span class="timeline-time uk-text-bold uk-text-primary">10:00 – 12:30</span>
              </div>
              <div class="uk-width-expand@m">
                <span class="uk-label uk-label-warning"><?php _e('Mayors Assembly', TEXT_DOMAIN); ?></span>
                <h3 class="uk-h4 uk-margin-small-top uk-margin-remove-bottom">
                  <?php _e('International Mayors & Urban Leaders Roundtable', TEXT_DOMAIN); ?>
                </h3>
                <p class="uk-text-muted uk-text-small uk-margin-remove-top">
                  <?php _e('Mayors commit to the Lisbon Declaration for Sustainable Public Housing.', TEXT_DOMAIN); ?>
                </p>
              </div>
            </div>
          </div>

          <div class="timeline-item uk-card uk-card-default uk-card-body uk-margin-small-bottom">
            <div class="uk-grid-small uk-flex-middle" data-uk-grid>
              <div class="uk-width-auto@m">
                <span class="timeline-time uk-text-bold uk-text-primary">14:30 – 16:30</span>
              </div>
              <div class="uk-width-expand@m">
                <span class="uk-label uk-label-success"><?php _e('Closing Plenary', TEXT_DOMAIN); ?></span>
                <h3 class="uk-h4 uk-margin-small-top uk-margin-remove-bottom">
                  <?php _e('Handover of the ISHF Key to the 2029 Host City', TEXT_DOMAIN); ?>
                </h3>
                <p class="uk-text-muted uk-text-small uk-margin-remove-top">
                  <?php _e('Celebration of festival achievements, artistic performances, and future outlook.', TEXT_DOMAIN); ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </li>

    </ul>

  </div>
</section>
