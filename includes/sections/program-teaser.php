<?php
// -----------------------------------------------------------------------------
//  Section: PROGRAMME TEASER (Home)
//  Always rendered, always the same panel size. Without published seminars it
//  says "Programme coming soon"; with seminars it shows one card per day (time, title and a
//  "Know more" button to that session).
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
?>
<section id="programme-teaser" class="ishf-programme-teaser" aria-label="<?php esc_attr_e('Programme', TEXT_DOMAIN); ?>">
  <div class="uk-container">
    <?php ishf_programme_panel('h2', ishf_programme_teaser_sessions(ishf_get_programme_sessions(), 3), ishf_get_programme_url()); ?>
  </div>
</section>
