<?php
// -----------------------------------------------------------------------------
//  Section: DUAL CTAS (SIDE-BY-SIDE)
//  CTA 1: Call for Sessions (External Form Link)
//  CTA 2: Partnership Modal Trigger & Popup Contact Form
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

// Read form results from form-handlers.php
$partnership_result = isset($GLOBALS['partnership_result']) ? $GLOBALS['partnership_result'] : null;
$has_p_result = ($partnership_result !== null && ($partnership_result['context'] ?? '') === 'partnership');

$p_name     = '';
$p_email    = '';
$p_company  = '';
$p_position = '';
$p_phone    = '';
$p_message  = '';
$p_consent  = false;

if ($has_p_result && !empty($partnership_result['fields'])) {
  $f = $partnership_result['fields'];
  $p_name     = sanitize_text_field($f['partnership_name'] ?? '');
  $p_email    = sanitize_email($f['partnership_email'] ?? '');
  $p_company  = sanitize_text_field($f['partnership_company'] ?? '');
  $p_position = sanitize_text_field($f['partnership_position'] ?? '');
  $p_phone    = sanitize_text_field($f['partnership_phone'] ?? '');
  $p_message  = sanitize_textarea_field($f['partnership_message'] ?? '');
  $p_consent  = !empty($f['partnership_consent']);
}

$p_errors = ($has_p_result && !empty($partnership_result['field_errors'])) ? $partnership_result['field_errors'] : array();
?>
<section id="dual-ctas" class="uk-section uk-section-medium section-dual-ctas" aria-label="<?php esc_attr_e('Engagement Options', TEXT_DOMAIN); ?>">
  <div class="uk-container uk-container-large">
    
    <div data-uk-grid class="uk-grid-match uk-grid-large uk-child-width-1-1 uk-child-width-1-2@m">
      
      <?php
      // CTA 1: CALL FOR SESSIONS (External Form Link - Copy Exclusivo)
      ?>
      <div id="call-for-sessions" role="region" aria-labelledby="cta-title-sessions">
        <div class="cta-box cta-box-sessions uk-card uk-card-body uk-flex uk-flex-column uk-flex-between">
          <div>
            <div class="cta-header uk-margin-bottom">
              <span class="cta-badge cta-badge-sessions">
                <?php _e('Call for Sessions', TEXT_DOMAIN); ?>
              </span>
              <h2 id="cta-title-sessions" class="cta-title uk-margin-small-top uk-margin-small-bottom">
                <?php _e('Call for Sessions', TEXT_DOMAIN); ?>
              </h2>
              <p class="cta-intro uk-margin-remove-top">
                <?php _e('Help shape the programme of ISHF Lisbon 2027: submit your proposal for a session, debate or workshop and share your ideas on the future of social and affordable housing.', TEXT_DOMAIN); ?>
              </p>
            </div>
          </div>

          <div class="cta-footer uk-margin-top">
            <a href="<?php echo esc_url(ishf_get_call_for_sessions_url()); ?>"
               id="btn-call-for-sessions"
               class="uk-button uk-button-primary uk-border-pill uk-width-1-1 uk-flex uk-flex-center uk-flex-middle"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="<?php esc_attr_e('Submit your proposal for Call for Sessions (opens external form in new tab)', TEXT_DOMAIN); ?>">
              <span><?php _e('Submit Your Proposal', TEXT_DOMAIN); ?></span>
              <span data-uk-icon="icon: push; ratio: 0.9" class="uk-margin-small-left" aria-hidden="true"></span>
            </a>
            <p class="uk-visually-hidden uk-text-small form-help-text uk-text-center uk-margin-small-top uk-margin-remove-bottom">
              <?php _e('External application form opens in a new tab.', TEXT_DOMAIN); ?>
            </p>
          </div>
        </div>
      </div>


      <?php
      // CTA 2: PARTNERSHIP CTA CARD (Opens Modal on Click)
      ?>
      <div id="partnership" role="region" aria-labelledby="cta-title-partnership">
        <div class="cta-box cta-box-partnership uk-card uk-card-body uk-flex uk-flex-column uk-flex-between">
          <div>
            <div class="cta-header uk-margin-bottom">
              <span class="cta-badge cta-badge-partnership">
                <?php _e('Partnership', TEXT_DOMAIN); ?>
              </span>
              <h2 id="cta-title-partnership" class="cta-title uk-margin-small-top uk-margin-small-bottom">
                <?php _e('Partner with Us', TEXT_DOMAIN); ?>
              </h2>
              <p class="cta-intro uk-margin-remove-top">
                <?php _e('Join ISHF 2027 as an official partner. Connect your organization with key decision-makers and urban housing pioneers shaping the future of affordable living.', TEXT_DOMAIN); ?>
              </p>
            </div>
          </div>

          <div class="cta-footer uk-margin-top">
            <button type="button"
                    id="btn-open-partnership-modal"
                    class="uk-button uk-button-primary uk-border-pill uk-width-1-1 uk-flex uk-flex-center uk-flex-middle"
                    data-uk-toggle="target: #modal-partnership"
                    aria-haspopup="dialog"
                    aria-controls="modal-partnership">
              <span><?php _e('Become a Partner', TEXT_DOMAIN); ?></span>
              <span data-uk-icon="icon: mail; ratio: 0.9" class="uk-margin-small-left" aria-hidden="true"></span>
            </button>
            <p class="uk-visually-hidden uk-text-small form-help-text uk-text-center uk-margin-small-top uk-margin-remove-bottom">
              <?php _e('Opens partnership inquiry form in a popup window.', TEXT_DOMAIN); ?>
            </p>
          </div>
        </div>
      </div>

    </div>

  </div>

  <?php
  // ===========================================================================
  //  MODAL: PARTNERSHIP CONTACT FORM (UIkit Modal)
  // ===========================================================================
  ?>
  <div id="modal-partnership" class="uk-flex-top modal-ishf-partnership" data-uk-modal<?php echo $has_p_result ? ' data-auto-open="true"' : ''; ?>>
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical uk-border-rounded uk-box-shadow-xlarge uk-position-relative">
      <button class="uk-modal-close-default" type="button" data-uk-close aria-label="<?php esc_attr_e('Close modal', TEXT_DOMAIN); ?>"></button>
      
      <div class="modal-header uk-margin-bottom">
        <span class="cta-badge cta-badge-partnership">
          <?php _e('Partnership', TEXT_DOMAIN); ?>
        </span>
        <h2 class="uk-modal-title uk-margin-small-top uk-margin-remove-bottom">
          <?php _e('Partner with Us', TEXT_DOMAIN); ?>
        </h2>
        <p class="modal-subtitle uk-margin-small-top uk-margin-remove-bottom">
          <?php _e('Join ISHF 2027 as an official partner. Fill in the form below and our team will get in touch shortly.', TEXT_DOMAIN); ?>
        </p>
      </div>

      <?php if ($has_p_result): ?>
        <?php if ($partnership_result['success'] === true): ?>
          <div class="uk-alert-success uk-margin-bottom" data-uk-alert role="status" aria-live="polite">
            <a class="uk-alert-close" data-uk-close aria-label="<?php esc_attr_e('Close notification', TEXT_DOMAIN); ?>"></a>
            <div class="uk-flex uk-flex-middle">
              <span data-uk-icon="icon: check; ratio: 1.2" class="uk-margin-small-right"></span>
              <div>
                <strong><?php _e('Thank you for reaching out!', TEXT_DOMAIN); ?></strong>
                <p class="uk-margin-remove">
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

      <?php if (!$has_p_result || $partnership_result['success'] !== true): ?>
        <form id="form-partnership" class="ishf-form form-partnership" method="post" action="#partnership" aria-describedby="partnership-modal-help">
          <input type="hidden" name="partnership_form_submitted" value="1" />
          <input type="hidden" name="form_context" value="partnership" />
          <?php wp_nonce_field('partnership_form_action', 'partnership_nonce'); ?>

          <fieldset class="uk-fieldset">
            
            <div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@s" data-uk-grid>
              <div>
                <label class="uk-form-label" for="partnership-name">
                  <?php _e('Full Name *', TEXT_DOMAIN); ?>
                </label>
                <div class="uk-form-controls">
                  <input class="uk-input<?php echo isset($p_errors['partnership_name']) ? ' uk-form-danger' : ''; ?>"
                         type="text" name="partnership_name" id="partnership-name"
                         value="<?php echo esc_attr($p_name); ?>" placeholder="<?php esc_attr_e('Full name', TEXT_DOMAIN); ?>"
                         autocomplete="name" required aria-required="true"
                         <?php echo isset($p_errors['partnership_name']) ? ' aria-invalid="true"' : ''; ?> />
                  <?php if (isset($p_errors['partnership_name'])): ?>
                    <span class="uk-text-danger uk-text-small uk-margin-xsmall-top uk-display-block">
                      <?php echo esc_html($p_errors['partnership_name']); ?>
                    </span>
                  <?php endif; ?>
                </div>
              </div>

              <div>
                <label class="uk-form-label" for="partnership-email">
                  <?php _e('Corporate Email *', TEXT_DOMAIN); ?>
                </label>
                <div class="uk-form-controls">
                  <input class="uk-input<?php echo isset($p_errors['partnership_email']) ? ' uk-form-danger' : ''; ?>"
                         type="email" name="partnership_email" id="partnership-email"
                         value="<?php echo esc_attr($p_email); ?>" placeholder="<?php esc_attr_e('name@organization.com', TEXT_DOMAIN); ?>"
                         autocomplete="email" inputmode="email" required aria-required="true"
                         <?php echo isset($p_errors['partnership_email']) ? ' aria-invalid="true"' : ''; ?> />
                  <?php if (isset($p_errors['partnership_email'])): ?>
                    <span class="uk-text-danger uk-text-small uk-margin-xsmall-top uk-display-block">
                      <?php echo esc_html($p_errors['partnership_email']); ?>
                    </span>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@s uk-margin-small-top" data-uk-grid>
              <div>
                <label class="uk-form-label" for="partnership-company">
                  <?php _e('Company / Organization *', TEXT_DOMAIN); ?>
                </label>
                <div class="uk-form-controls">
                  <input class="uk-input<?php echo isset($p_errors['partnership_company']) ? ' uk-form-danger' : ''; ?>"
                         type="text" name="partnership_company" id="partnership-company"
                         value="<?php echo esc_attr($p_company); ?>" placeholder="<?php esc_attr_e('Organization name', TEXT_DOMAIN); ?>"
                         autocomplete="organization" required aria-required="true"
                         <?php echo isset($p_errors['partnership_company']) ? ' aria-invalid="true"' : ''; ?> />
                  <?php if (isset($p_errors['partnership_company'])): ?>
                    <span class="uk-text-danger uk-text-small uk-margin-xsmall-top uk-display-block">
                      <?php echo esc_html($p_errors['partnership_company']); ?>
                    </span>
                  <?php endif; ?>
                </div>
              </div>

              <div>
                <label class="uk-form-label" for="partnership-position">
                  <?php _e('Job Title / Role *', TEXT_DOMAIN); ?>
                </label>
                <div class="uk-form-controls">
                  <input class="uk-input<?php echo isset($p_errors['partnership_position']) ? ' uk-form-danger' : ''; ?>"
                         type="text" name="partnership_position" id="partnership-position"
                         value="<?php echo esc_attr($p_position); ?>" placeholder="<?php esc_attr_e('e.g. Director', TEXT_DOMAIN); ?>"
                         autocomplete="organization-title" required aria-required="true"
                         <?php echo isset($p_errors['partnership_position']) ? ' aria-invalid="true"' : ''; ?> />
                  <?php if (isset($p_errors['partnership_position'])): ?>
                    <span class="uk-text-danger uk-text-small uk-margin-xsmall-top uk-display-block">
                      <?php echo esc_html($p_errors['partnership_position']); ?>
                    </span>
                  <?php endif; ?>
                </div>
              </div>
            </div>

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

            <div class="uk-margin-small-top">
              <label class="uk-form-label" for="partnership-message">
                <?php _e('Message / Partnership Interests (Optional)', TEXT_DOMAIN); ?>
              </label>
              <div class="uk-form-controls">
                <textarea class="uk-textarea" name="partnership_message" id="partnership-message" rows="3"
                          placeholder="<?php esc_attr_e('Briefly describe your partnership interest...', TEXT_DOMAIN); ?>"><?php echo esc_textarea($p_message); ?></textarea>
              </div>
            </div>

            <div class="uk-margin-small-top uk-margin-small-bottom">
              <label class="newsletter-consent-label" for="partnership-consent">
                <input class="uk-checkbox" type="checkbox" name="partnership_consent" id="partnership-consent"
                       value="1" <?php checked($p_consent, true); ?> required aria-required="true" />
                <span class="newsletter-consent-text uk-text-small">
                  <?php echo esc_html(ishf_get_partnership_disclaimer()); ?>
                </span>
              </label>
              <?php if (isset($p_errors['partnership_consent'])): ?>
                <span class="uk-text-danger uk-text-small uk-margin-xsmall-top uk-display-block">
                  <?php echo esc_html($p_errors['partnership_consent']); ?>
                </span>
              <?php endif; ?>
            </div>

            <p id="partnership-modal-help" class="uk-text-small form-help-text uk-margin-remove-top uk-margin-small-bottom">
              <?php _e('All fields marked with * are required.', TEXT_DOMAIN); ?>
            </p>

            <div class="uk-margin-small-top">
              <button type="submit" id="btn-partnership-submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">
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

  <?php if ($has_p_result): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof UIkit !== 'undefined') {
        var modalEl = document.getElementById('modal-partnership');
        if (modalEl) {
          UIkit.modal(modalEl).show();
        }
      }
    });
  </script>
  <?php endif; ?>
</section>
