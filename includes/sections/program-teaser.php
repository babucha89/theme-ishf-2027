<?php
// -----------------------------------------------------------------------------
//  Section: PROGRAMME TEASER (Home)
//  Always rendered, always the same panel size. Without published seminars it
//  says "Programme coming soon"; with seminars it shows one card per day (time, title and a
//  "Know more" button to that session) and, below, a button to the full programme
//  (the seminars archive, archive-seminars.php).
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
?>
<section id="programme-teaser" class="ishf-programme-teaser" aria-label="<?php esc_attr_e('Programme', TEXT_DOMAIN); ?>">
  <div class="uk-container">
    <?php
    $ishf_teaser_sessions = ishf_programme_teaser_sessions(ishf_get_programme_sessions(), 3);
    $ishf_full_programme  = get_post_type_archive_link('seminars') ?: ishf_get_programme_url();
    ishf_programme_panel('h2', $ishf_teaser_sessions, $ishf_full_programme);
    ?>
    <?php if (!empty($ishf_teaser_sessions)): ?>
      <div class="programme-teaser-more uk-text-center">
        <a class="uk-button uk-button-default" href="<?php echo esc_url($ishf_full_programme); ?>">
          <span><?php _e('See the full programme', TEXT_DOMAIN); ?></span>
          <span data-uk-icon="icon: arrow-right; ratio: 0.9" aria-hidden="true"></span>
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>
