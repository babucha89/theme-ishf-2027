<?php
// -----------------------------------------------------------------------------
//  Section: PARTNERSHIP CTA & CONTACT FORM
//  Bilingual copy and structured contact form following parent template logic
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

$partnership_result = isset($GLOBALS['partnership_result']) ? $GLOBALS['partnership_result'] : null;
$has_result = ($partnership_result !== null && ($partnership_result['context'] ?? '') === 'partnership');

$p_name     = '';
$p_email    = '';
$p_company  = '';
$p_position = '';
$p_phone    = '';
$p_message  = '';
$p_consent  = false;

if ($has_result && !empty($partnership_result['fields'])) {
  $f = $partnership_result['fields'];
  $p_name     = sanitize_text_field($f['partnership_name'] ?? '');
  $p_email    = sanitize_email($f['partnership_email'] ?? '');
  $p_company  = sanitize_text_field($f['partnership_company'] ?? '');
  $p_position = sanitize_text_field($f['partnership_position'] ?? '');
  $p_phone    = sanitize_text_field($f['partnership_phone'] ?? '');
  $p_message  = sanitize_textarea_field($f['partnership_message'] ?? '');
  $p_consent  = !empty($f['partnership_consent']);
}
?>
<section id="partnership" class="uk-section uk-section-large section-partnership" aria-labelledby="partnership-title">
  <div class="uk-container uk-container-large">
    <div data-uk-grid class="uk-grid-large uk-flex-between uk-flex-middle">
      
      <!-- Left Column: Copy & Partnership Benefits -->
      <div class="uk-width-1-1 uk-width-1-2@l">
        <div class="partnership-content">
          <span class="ishf-badge-pill">
            <?php _e('Collaboration & Sponsorship', TEXT_DOMAIN); ?>
          </span>

          <h2 id="partnership-title" class="uk-h2 uk-text-bold section-title uk-margin-small-top">
            <?php _e('Become an Official Partner of ISHF 2027', TEXT_DOMAIN); ?>
          </h2>

          <p class="section-lead uk-margin-bottom">
            <?php _e('Position your organization at the center of the European conversation on affordable and sustainable living. As a festival partner, you will connect with international policymakers, municipal leaders, housing federations, and urban innovators.', TEXT_DOMAIN); ?>
          </p>

          <div class="partnership-benefits uk-margin-medium-bottom">
            <div class="benefit-item uk-flex uk-flex-top uk-margin-small-bottom">
              <span data-uk-icon="icon: check; ratio: 1.1" class="benefit-icon uk-margin-small-right"></span>
              <div>
                <strong><?php _e('Global Visibility:', TEXT_DOMAIN); ?></strong>
                <span><?php _e('Showcase your brand and vision before 2,000+ delegates and hundreds of participating organizations from over 40 countries.', TEXT_DOMAIN); ?></span>
              </div>
            </div>

            <div class="benefit-item uk-flex uk-flex-top uk-margin-small-bottom">
              <span data-uk-icon="icon: check; ratio: 1.1" class="benefit-icon uk-margin-small-right"></span>
              <div>
                <strong><?php _e('Thought Leadership:', TEXT_DOMAIN); ?></strong>
                <span><?php _e('Co-host dedicated thematic workshops, present innovative projects, and contribute to policy recommendations.', TEXT_DOMAIN); ?></span>
              </div>
            </div>

            <div class="benefit-item uk-flex uk-flex-top uk-margin-small-bottom">
              <span data-uk-icon="icon: check; ratio: 1.1" class="benefit-icon uk-margin-small-right"></span>
              <div>
                <strong><?php _e('Strategic Networking:', TEXT_DOMAIN); ?></strong>
                <span><?php _e('Engage in exclusive B2B and institutional sessions, bilateral meetings, and site visit roundtables across Lisbon.', TEXT_DOMAIN); ?></span>
              </div>
            </div>
          </div>

          <div class="partnership-contact-direct uk-text-small">
            <p class="uk-margin-remove">
              <?php _e('Have specific sponsorship requirements?', TEXT_DOMAIN); ?>
              <br class="uk-hidden@s" />
              <?php _e('Contact our team directly at', TEXT_DOMAIN); ?>
              <a href="mailto:<?php echo esc_attr(defined('SITE_CONTACT_EMAIL') ? SITE_CONTACT_EMAIL : 'boostactivate@medialivre.pt'); ?>" class="uk-text-bold">
                <?php echo esc_html(defined('SITE_CONTACT_EMAIL') ? SITE_CONTACT_EMAIL : 'boostactivate@medialivre.pt'); ?>
              </a>
            </p>
          </div>
        </div>
      </div>

      <!-- Right Column: Structured Contact Form -->
      <div class="uk-width-1-1 uk-width-1-2@l">
        <div class="form-card uk-card uk-card-default uk-card-body uk-box-shadow-medium">
          
          <h3 class="uk-h3 uk-text-bold uk-margin-small-bottom">
            <?php _e('Request Partnership Package', TEXT_DOMAIN); ?>
          </h3>
          <p class="uk-text-muted uk-text-small uk-margin-bottom">
            <?php _e('Fill in your details below and our partnerships secretariat will get in touch with you promptly.', TEXT_DOMAIN); ?>
          </p>

          <!-- Form Feedback Alerts -->
          <?php if ($has_result): ?>
            <?php if ($partnership_result['success'] === true): ?>
              <div class="uk-alert-success uk-margin-bottom" data-uk-alert role="status" aria-live="polite">
                <a class="uk-alert-close" data-uk-close aria-label="<?php esc_attr_e('Close notification', TEXT_DOMAIN); ?>"></a>
                <div class="uk-flex uk-flex-middle">
                  <span data-uk-icon="icon: check; ratio: 1.3" class="uk-margin-small-right"></span>
                  <div>
                    <strong><?php _e('Thank you for your interest!', TEXT_DOMAIN); ?></strong>
                    <p class="uk-margin-remove-bottom uk-margin-xsmall-top">
                      <?php _e('Your partnership inquiry has been received. Our team will contact you shortly.', TEXT_DOMAIN); ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php elseif ($partnership_result['success'] === false): ?>
              <div class="uk-alert-warning uk-margin-bottom" data-uk-alert role="alert" aria-live="assertive">
                <a class="uk-alert-close" data-uk-close aria-label="<?php esc_attr_e('Close notification', TEXT_DOMAIN); ?>"></a>
                <p class="uk-margin-remove">
                  <?php echo esc_html($partnership_result['error']); ?>
                </p>
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <?php if (!$has_result || $partnership_result['success'] !== true): ?>
            <form id="form-partnership" class="ishf-form form-partnership" method="post" action="#partnership" aria-describedby="partnership-form-help">
              <input type="hidden" name="partnership_form_submitted" value="1" />
              <input type="hidden" name="form_context" value="partnership" />
              <input type="hidden" name="g-recaptcha-response" value="" />
              <?php wp_nonce_field('partnership_form_action', 'partnership_nonce'); ?>

              <fieldset class="uk-fieldset">
                
                <!-- Name & Email Grid -->
                <div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@s" data-uk-grid>
                  <div>
                    <label class="uk-form-label" for="partnership-name">
                      <?php _e('Full Name *', TEXT_DOMAIN); ?>
                    </label>
                    <div class="uk-form-controls">
                      <input class="uk-input" type="text" name="partnership_name" id="partnership-name"
                             value="<?php echo esc_attr($p_name); ?>" placeholder="<?php esc_attr_e('e.g. Maria Silva', TEXT_DOMAIN); ?>"
                             autocomplete="name" required aria-required="true" />
                    </div>
                  </div>

                  <div>
                    <label class="uk-form-label" for="partnership-email">
                      <?php _e('Corporate Email *', TEXT_DOMAIN); ?>
                    </label>
                    <div class="uk-form-controls">
                      <input class="uk-input" type="email" name="partnership_email" id="partnership-email"
                             value="<?php echo esc_attr($p_email); ?>" placeholder="<?php esc_attr_e('e.g. maria@company.com', TEXT_DOMAIN); ?>"
                             autocomplete="email" inputmode="email" required aria-required="true" />
                    </div>
                  </div>
                </div>

                <!-- Company & Position Grid -->
                <div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@s uk-margin-small-top" data-uk-grid>
                  <div>
                    <label class="uk-form-label" for="partnership-company">
                      <?php _e('Company / Organization *', TEXT_DOMAIN); ?>
                    </label>
                    <div class="uk-form-controls">
                      <input class="uk-input" type="text" name="partnership_company" id="partnership-company"
                             value="<?php echo esc_attr($p_company); ?>" placeholder="<?php esc_attr_e('e.g. Housing Europe / Lisbon Municipality', TEXT_DOMAIN); ?>"
                             autocomplete="organization" required aria-required="true" />
                    </div>
                  </div>

                  <div>
                    <label class="uk-form-label" for="partnership-position">
                      <?php _e('Job Title / Role *', TEXT_DOMAIN); ?>
                    </label>
                    <div class="uk-form-controls">
                      <input class="uk-input" type="text" name="partnership_position" id="partnership-position"
                             value="<?php echo esc_attr($p_position); ?>" placeholder="<?php esc_attr_e('e.g. Director of Sustainability', TEXT_DOMAIN); ?>"
                             autocomplete="organization-title" required aria-required="true" />
                    </div>
                  </div>
                </div>

                <!-- Phone -->
                <div class="uk-margin-small-top">
                  <label class="uk-form-label" for="partnership-phone">
                    <?php _e('Phone Number (Optional)', TEXT_DOMAIN); ?>
                  </label>
                  <div class="uk-form-controls">
                    <input class="uk-input" type="tel" name="partnership_phone" id="partnership-phone"
                           value="<?php echo esc_attr($p_phone); ?>" placeholder="<?php esc_attr_e('+351 ...', TEXT_DOMAIN); ?>"
                           autocomplete="tel" inputmode="tel" />
                  </div>
                </div>

                <!-- Message / Partnership Interest -->
                <div class="uk-margin-small-top">
                  <label class="uk-form-label" for="partnership-message">
                    <?php _e('Message / Partnership Interests (Optional)', TEXT_DOMAIN); ?>
                  </label>
                  <div class="uk-form-controls">
                    <textarea class="uk-textarea" name="partnership_message" id="partnership-message" rows="3"
                              placeholder="<?php esc_attr_e('Tell us about your organization or preferred areas of collaboration...', TEXT_DOMAIN); ?>"><?php echo esc_textarea($p_message); ?></textarea>
                  </div>
                </div>

                <!-- Help Text -->
                <p id="partnership-form-help" class="uk-text-small form-help-text uk-margin-small-top uk-margin-small-bottom">
                  <?php _e('All fields marked with * are required.', TEXT_DOMAIN); ?>
                </p>

                <!-- RGPD Consent -->
                <div class="uk-margin-small-bottom">
                  <label class="newsletter-consent-label" for="partnership-consent">
                    <input class="uk-checkbox" type="checkbox" name="partnership_consent" id="partnership-consent"
                           value="1" <?php checked($p_consent, true); ?> required aria-required="true" />
                    <span class="newsletter-consent-text uk-text-small">
                      <?php echo esc_html(ishf_get_partnership_disclaimer()); ?>
                    </span>
                  </label>
                </div>

                <!-- Submit Button -->
                <div class="uk-margin-top">
                  <button type="submit" id="btn-partnership-submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1" data-sending="<?php echo esc_attr__('Sending...', TEXT_DOMAIN); ?>">
                    <span class="uk-margin-small-right" data-uk-icon="icon: check"></span>
                    <?php _e('Send Partnership Request', TEXT_DOMAIN); ?>
                  </button>
                </div>

              </fieldset>
            </form>
          <?php else: ?>
            <div class="uk-margin-top uk-text-center">
              <a href="#partnership" onclick="window.location.reload();" class="uk-button uk-button-default uk-border-pill">
                <?php _e('Send Another Message', TEXT_DOMAIN); ?>
              </a>
            </div>
          <?php endif; ?>

        </div>
      </div>

    </div>
  </div>
</section>
