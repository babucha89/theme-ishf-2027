<?php
// -----------------------------------------------------------------------------
//  Section: PROGRAMME TEASER (Home)
//  Always rendered, always the same panel size. Without published seminars it
//  says "Programme coming soon"; with seminars it lists the next sessions (start
//  and end) and a "Know more" button to the programme.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
?>
<section id="programme-teaser" class="ishf-programme-teaser" aria-label="<?php esc_attr_e('Programme', TEXT_DOMAIN); ?>">
  <div class="uk-container">
    <?php ishf_programme_panel('h2', array_slice(ishf_get_programme_sessions(), 0, 3), ishf_get_programme_url()); ?>
  </div>
</section>
