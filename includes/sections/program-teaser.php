<?php
// -----------------------------------------------------------------------------
//  Section: PROGRAMME TEASER (Home)
//  Always rendered. With published seminars it shows the first sessions and a
//  link to the full programme; without any it shows "Programme coming soon".
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$ishf_sessions  = ishf_get_programme_sessions();
$ishf_teaser    = array_slice($ishf_sessions, 0, 3);
$ishf_prog_url  = ishf_get_programme_url();
?>
<section id="programme-teaser" class="ishf-programme-teaser" aria-labelledby="programme-teaser-title">
  <div class="uk-container">
    <div class="uk-flex uk-flex-between uk-flex-bottom uk-flex-wrap">
      <h2 id="programme-teaser-title" class="uk-h3 uk-margin-remove"><?php esc_html_e('Programme', TEXT_DOMAIN); ?></h2>
      <?php if (!empty($ishf_teaser)): ?>
        <a class="uk-button uk-button-text" href="<?php echo esc_url($ishf_prog_url); ?>"><?php esc_html_e('Full programme', TEXT_DOMAIN); ?></a>
      <?php endif; ?>
    </div>

    <?php if (empty($ishf_teaser)): ?>
      <?php ishf_programme_coming_soon('h3'); ?>
    <?php else: ?>
      <ul class="programme-teaser-grid uk-grid uk-grid-medium uk-child-width-1-1 uk-child-width-1-3@m uk-grid-match" data-uk-grid>
        <?php foreach ($ishf_teaser as $s): ?>
          <li>
            <article class="programme-teaser-card">
              <p class="programme-teaser-when">
                <?php if ($s['start']): ?>
                  <?php echo esc_html(wp_date('D j M', $s['start']->getTimestamp(), new DateTimeZone('Europe/Lisbon'))); ?> &middot;
                  <time datetime="<?php echo esc_attr($s['start']->format('c')); ?>"><?php echo esc_html(ishf_programme_time_label($s)); ?></time>
                <?php else: ?>
                  <?php esc_html_e('Date to be announced', TEXT_DOMAIN); ?>
                <?php endif; ?>
              </p>
              <h3 class="programme-teaser-title"><a href="<?php echo esc_url($s['url']); ?>"><?php echo esc_html($s['title']); ?></a></h3>
              <?php if ($s['location'] !== ''): ?>
                <p class="programme-teaser-where"><span data-uk-icon="icon: location; ratio: .9" aria-hidden="true"></span> <?php echo esc_html($s['location']); ?></p>
              <?php endif; ?>
            </article>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="uk-margin-medium-top"><a class="uk-button uk-button-primary" href="<?php echo esc_url($ishf_prog_url); ?>"><?php esc_html_e('View the full programme', TEXT_DOMAIN); ?></a></p>
    <?php endif; ?>
  </div>
</section>
