<?php
// -----------------------------------------------------------------------------
//  Section: NEWSLETTER (site-wide, rendered by footer.php before the footer)
//  Figma: Home / Newsletter. Yellow band: heading + Subscribe button + skyline art.
//  The button (and the footer 'Subscribe' button) opens #modal-newsletter, which holds
//  the subscription form. The modal re-opens by itself after a submit so the
//  success/error message is visible. Handler: includes/form-handlers.php.
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

$newsletter_result = isset($GLOBALS['newsletter_landing_result']) ? $GLOBALS['newsletter_landing_result'] : null;
$has_nl_result = ($newsletter_result !== null && ($newsletter_result['context'] ?? '') === 'newsletter');

$nl_name    = '';
$nl_email   = '';
$nl_consent = false;

if ($has_nl_result && !empty($newsletter_result['fields'])) {
  $f = $newsletter_result['fields'];
  $nl_name    = sanitize_text_field($f['newsletter_name'] ?? '');
  $nl_email   = sanitize_email($f['newsletter_email'] ?? '');
  $nl_consent = !empty($f['newsletter_consent']);
}

$nl_errors = ($has_nl_result && !empty($newsletter_result['field_errors'])) ? $newsletter_result['field_errors'] : array();
?>
?>
<section id="newsletter" class="ishf-newsletter" aria-labelledby="newsletter-title">
  <div class="uk-container">
    <div class="newsletter-band">
      <div class="newsletter-band-copy">
        <h2 id="newsletter-title" class="newsletter-band-title"><?php _e('Join the ISHF 2027 community', TEXT_DOMAIN); ?></h2>
        <button type="button" class="uk-button btn-ishf-dark" data-uk-toggle="target: #modal-newsletter" aria-haspopup="dialog" aria-controls="modal-newsletter">
          <?php _e('Subscribe', TEXT_DOMAIN); ?>
        </button>
      </div>
      <img class="newsletter-band-art" src="<?php echo esc_url(BASE_ASSETS . 'skyline-colors.svg'); ?>" alt="" aria-hidden="true" />
    </div>
  </div>
</section>

<div id="modal-newsletter" class="uk-flex-top modal-ishf-partnership modal-ishf-newsletter" data-uk-modal aria-labelledby="modal-newsletter-title">
  <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical uk-border-rounded uk-box-shadow-xlarge uk-position-relative">
    <button class="uk-modal-close-default" type="button" data-uk-close aria-label="<?php esc_attr_e('Close modal', TEXT_DOMAIN); ?>"></button>

    <div class="modal-header uk-margin-bottom">
      <span class="cta-badge cta-badge-newsletter"><?php _e('Newsletter', TEXT_DOMAIN); ?></span>
      <h2 id="modal-newsletter-title" class="uk-modal-title uk-margin-small-top uk-margin-remove-bottom"><?php _e('Stay Informed', TEXT_DOMAIN); ?></h2>
      <p class="modal-subtitle uk-margin-small-top uk-margin-remove-bottom">
        <?php _e('Subscribe to our newsletter to receive the latest updates, announcements, calls for proposals, and festival news directly in your inbox.', TEXT_DOMAIN); ?>
      </p>
    </div>

    <div class="cta-box-newsletter newsletter-modal-body">
    <!-- Alerts -->
    <?php if ($has_nl_result): ?>
      <?php if ($newsletter_result['success'] === true): ?>
        <div class="uk-alert-success uk-margin-bottom" data-uk-alert role="status" aria-live="polite">
          <a class="uk-alert-close" data-uk-close aria-label="<?php esc_attr_e('Close notification', TEXT_DOMAIN); ?>"></a>
          <div class="uk-flex uk-flex-middle">
            <span data-uk-icon="icon: check; ratio: 1.2" class="uk-margin-small-right"></span>
            <div>
              <strong><?php _e('Thank you for subscribing!', TEXT_DOMAIN); ?></strong>
              <p class="uk-margin-remove">
                <?php _e('You are now registered to receive all the latest news from ISHF 2027 Lisbon.', TEXT_DOMAIN); ?>
              </p>
            </div>
          </div>
        </div>
      <?php elseif ($newsletter_result['success'] === false): ?>
        <div class="uk-alert-warning uk-margin-bottom" data-uk-alert role="alert" aria-live="assertive">
          <a class="uk-alert-close" data-uk-close aria-label="<?php esc_attr_e('Close notification', TEXT_DOMAIN); ?>"></a>
          <p class="uk-margin-remove">
            <?php echo esc_html($newsletter_result['error']); ?>
          </p>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (!$has_nl_result || $newsletter_result['success'] !== true): ?>
      <form id="form-newsletter-landing" class="ishf-form form-newsletter-landing" method="post" action="#newsletter" aria-describedby="nl-help">
        <input type="hidden" name="newsletter_landing_submitted" value="1" />
        <input type="hidden" name="form_context" value="newsletter" />
        <?php wp_nonce_field('newsletter_landing_action', 'newsletter_landing_nonce'); ?>

        <fieldset class="uk-fieldset">
          
          <!-- Name -->
          <div class="uk-margin-small-bottom">
            <label class="uk-form-label" for="newsletter-name">
              <?php _e('Your Name *', TEXT_DOMAIN); ?>
            </label>
            <div class="uk-form-controls">
              <input class="uk-input<?php echo isset($nl_errors['newsletter_name']) ? ' uk-form-danger' : ''; ?>"
                     type="text" name="newsletter_name" id="newsletter-name"
                     value="<?php echo esc_attr($nl_name); ?>" placeholder="<?php esc_attr_e('Enter your full name', TEXT_DOMAIN); ?>"
                     autocomplete="name" required aria-required="true"
                     <?php echo isset($nl_errors['newsletter_name']) ? ' aria-invalid="true"' : ''; ?> />
              <?php if (isset($nl_errors['newsletter_name'])): ?>
                <span class="uk-text-danger uk-text-small uk-margin-xsmall-top uk-display-block">
                  <?php echo esc_html($nl_errors['newsletter_name']); ?>
                </span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Email -->
          <div class="uk-margin-small-bottom">
            <label class="uk-form-label" for="newsletter-email">
              <?php _e('Email Address *', TEXT_DOMAIN); ?>
            </label>
            <div class="uk-form-controls">
              <input class="uk-input<?php echo isset($nl_errors['newsletter_email']) ? ' uk-form-danger' : ''; ?>"
                     type="email" name="newsletter_email" id="newsletter-email"
                     value="<?php echo esc_attr($nl_email); ?>" placeholder="<?php esc_attr_e('your.email@domain.com', TEXT_DOMAIN); ?>"
                     autocomplete="email" inputmode="email" required aria-required="true"
                     <?php echo isset($nl_errors['newsletter_email']) ? ' aria-invalid="true"' : ''; ?> />
              <?php if (isset($nl_errors['newsletter_email'])): ?>
                <span class="uk-text-danger uk-text-small uk-margin-xsmall-top uk-display-block">
                  <?php echo esc_html($nl_errors['newsletter_email']); ?>
                </span>
              <?php endif; ?>
            </div>
          </div>

          <!-- RGPD Consent Checkbox -->
          <div class="uk-margin-small-top uk-margin-small-bottom">
            <label class="newsletter-consent-label" for="newsletter-consent">
              <input class="uk-checkbox" type="checkbox" name="newsletter_consent" id="newsletter-consent"
                     value="1" <?php checked($nl_consent, true); ?> required aria-required="true" />
              <span class="newsletter-consent-text uk-text-small">
                <?php echo esc_html(ishf_get_newsletter_disclaimer()); ?>
              </span>
            </label>
            <?php if (isset($nl_errors['newsletter_consent'])): ?>
              <span class="uk-text-danger uk-text-small uk-margin-xsmall-top uk-display-block">
                <?php echo esc_html($nl_errors['newsletter_consent']); ?>
              </span>
            <?php endif; ?>
          </div>

          <p id="nl-help" class="uk-text-small form-help-text uk-margin-remove-top uk-margin-small-bottom">
            <?php _e('All fields marked with * are required.', TEXT_DOMAIN); ?>
          </p>

          <!-- Submit Button -->
          <div class="uk-margin-small-top">
            <button type="submit" id="btn-newsletter-landing-submit" class="uk-button uk-button-secondary uk-border-pill uk-width-1-1">
              <?php _e('Subscribe to Newsletter', TEXT_DOMAIN); ?>
            </button>
          </div>

        </fieldset>
      </form>
    <?php endif; ?>
    </div>
  </div>
</div>

<?php if ($has_nl_result): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof UIkit !== 'undefined') {
      var el = document.getElementById('modal-newsletter');
      if (el) { UIkit.modal(el).show(); }
    }
  });
</script>
<?php endif; ?>
