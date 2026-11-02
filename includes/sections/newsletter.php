<?php
// -----------------------------------------------------------------------------
//  Section: NEWSLETTER band (site-wide, rendered by footer.php before the footer)
//  Figma: Home / Newsletter. Yellow band: heading + Subscribe button + footer-1.svg skyline strip as background art behind the content.
//  The button opens #modal-newsletter, which is hard-coded in the footer
//  (footer.php -> includes/newsletter-modal.php), so it exists on every page.
// -----------------------------------------------------------------------------
?>
<section id="newsletter" class="ishf-newsletter" aria-labelledby="newsletter-title">
  <!-- Decorative background art: sits behind the content, inside the same yellow background -->
  <img class="newsletter-band-art" src="<?php echo esc_url(BASE_ASSETS . 'footer-1.svg'); ?>" alt="" aria-hidden="true" width="1600" height="148" loading="lazy" />
  <div class="uk-container">
    <div class="newsletter-band">
      <div class="newsletter-band-copy">
        <h2 id="newsletter-title" class="newsletter-band-title"><?php _e('Join the ISHF 2027 community', TEXT_DOMAIN); ?></h2>
        <button type="button" class="uk-button btn-ishf-dark" data-uk-toggle="target: #modal-newsletter" aria-haspopup="dialog" aria-controls="modal-newsletter">
          <?php _e('Subscribe', TEXT_DOMAIN); ?>
        </button>
      </div>
    </div>
  </div>
</section>
