<?php
// -----------------------------------------------------------------------------
//  Section: ATTENDANT SESSIONS (single attendant page)
//  Lists the seminars the attendant takes part in (as speaker or moderator,
//  set in the ISHF Seminar Program block or in "Associated Attendants"), with
//  the day / time and the panel inside each seminar, linking to the session.
//  Renders nothing when the attendant is not in any published seminar.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$ishf_att_sessions = ishf_get_attendant_sessions(get_the_ID());
if (empty($ishf_att_sessions)) {
  return;
}
$ishf_att_tz = new DateTimeZone('Europe/Lisbon');
?>
<section id="attendant-sessions" class="attendant-sessions" aria-labelledby="attendant-sessions-title">
  <h3 id="attendant-sessions-title" class="attendant-sessions-title"><?php esc_html_e('Sessions', TEXT_DOMAIN); ?></h3>
  <ul class="attendant-sessions-list">
    <?php foreach ($ishf_att_sessions as $seminar): ?>
      <li class="attendant-session">
        <a class="attendant-session-link" href="<?php echo esc_url($seminar['url']); ?>">
          <span class="attendant-session-when">
            <?php if (!empty($seminar['slots'])): ?>
              <?php foreach ($seminar['slots'] as $slot): ?>
                <time datetime="<?php echo esc_attr($slot['start']->format('c')); ?>"><?php echo esc_html(ishf_programme_when_label($slot)); ?></time>
              <?php endforeach; ?>
            <?php else: ?>
              <?php esc_html_e('Date to be announced', TEXT_DOMAIN); ?>
            <?php endif; ?>
          </span>
          <strong class="attendant-session-title"><?php echo esc_html($seminar['title']); ?></strong>
          <?php foreach ($seminar['panels'] as $panel): ?>
            <span class="attendant-session-panel">
              <?php
              $line = trim(($panel['time'] !== '' ? $panel['time'] . ' - ' : '') . $panel['title'], ' -');
              echo esc_html($line);
              if ($panel['role'] === 'moderator') {
                echo ' (' . esc_html__('Moderator', TEXT_DOMAIN) . ')';
              }
              ?>
            </span>
          <?php endforeach; ?>
          <span class="attendant-session-cta"><?php esc_html_e('View session', TEXT_DOMAIN); ?> <span data-uk-icon="icon: chevron-right; ratio: .8" aria-hidden="true"></span></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</section>
