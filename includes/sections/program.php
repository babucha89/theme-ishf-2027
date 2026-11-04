<?php
// -----------------------------------------------------------------------------
//  Section: PROGRAMME (full agenda)
//  Native list built from the CPT "seminars": grouped by day, with filters by
//  room, theme and speaker (client-side, no external widget). Without
//  JavaScript every session stays visible. Shows a "coming soon" panel while
//  no seminar is published.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$sessions = ishf_get_programme_sessions();
?>
<section id="programme" class="ishf-programme" aria-labelledby="programme-title">
  <div class="uk-container">
    <h2 id="programme-title" class="uk-visually-hidden"><?php esc_html_e('Programme', TEXT_DOMAIN); ?></h2>

    <?php if (empty($sessions)): ?>

      <?php ishf_programme_coming_soon('h3'); ?>

    <?php else:
      // -- Group by day and collect filter options
      $days     = array();
      $themes   = array();
      $speakers = array();
      foreach ($sessions as $s) {
        $days[$s['day']][] = $s;
        $themes   += $s['themes'];
        $speakers += $s['speakers'];
      }
      asort($themes);
      asort($speakers);
      $has_filters = !empty($themes) || !empty($speakers);
    ?>

      <div class="programme-toolbar">
      <?php if (count($days) > 1): ?>
      <nav class="programme-days" aria-label="<?php esc_attr_e('Programme days', TEXT_DOMAIN); ?>">
        <ul class="uk-subnav uk-subnav-pill uk-margin-remove-bottom">
          <?php foreach ($days as $day_key => $day_sessions): ?>
            <li><a href="#day-<?php echo esc_attr($day_key); ?>"><?php
              echo $day_key === 'tba'
                ? esc_html__('To be announced', TEXT_DOMAIN)
                : esc_html(wp_date('D j M', $day_sessions[0]['start']->getTimestamp(), new DateTimeZone('Europe/Lisbon')));
            ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>
      <?php endif; ?>

      <?php if ($has_filters): ?>
      <form class="programme-filters" id="programme-filters" aria-label="<?php esc_attr_e('Filter the programme', TEXT_DOMAIN); ?>" onsubmit="return false;" hidden>
        <p id="programme-count" class="programme-count" role="status" aria-live="polite"
           data-one="<?php echo esc_attr__('1 session', TEXT_DOMAIN); ?>"
           data-many="<?php echo esc_attr__('%d sessions', TEXT_DOMAIN); ?>"
           data-none="<?php echo esc_attr__('No sessions match these filters.', TEXT_DOMAIN); ?>"></p>
        <?php if (!empty($themes)): ?>
          <label class="programme-filter">
            <span class="uk-visually-hidden"><?php esc_html_e('Theme', TEXT_DOMAIN); ?></span>
            <select class="uk-select" data-filter="theme">
              <option value=""><?php esc_html_e('All themes', TEXT_DOMAIN); ?></option>
              <?php foreach ($themes as $slug => $name): ?>
                <option value="<?php echo esc_attr($slug); ?>"><?php echo esc_html($name); ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        <?php endif; ?>
        <?php if (!empty($speakers)): ?>
          <label class="programme-filter">
            <span class="uk-visually-hidden"><?php esc_html_e('Speaker', TEXT_DOMAIN); ?></span>
            <select class="uk-select" data-filter="speaker">
              <option value=""><?php esc_html_e('All speakers', TEXT_DOMAIN); ?></option>
              <?php foreach ($speakers as $sid => $name): ?>
                <option value="<?php echo (int) $sid; ?>"><?php echo esc_html($name); ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        <?php endif; ?>
        <button type="button" class="uk-button uk-button-text programme-reset" data-programme-reset><?php esc_html_e('Clear filters', TEXT_DOMAIN); ?></button>
      </form>
      <?php endif; ?>
      </div>

      <div id="programme-list">
      <?php foreach ($days as $day_key => $day_sessions): ?>
        <section id="day-<?php echo esc_attr($day_key); ?>" class="programme-day" data-day aria-labelledby="heading-<?php echo esc_attr($day_key); ?>">
          <h3 id="heading-<?php echo esc_attr($day_key); ?>" class="programme-day-title">
            <?php
            echo $day_key === 'tba'
              ? esc_html__('Date to be announced', TEXT_DOMAIN)
              : esc_html(wp_date('l, j F Y', $day_sessions[0]['start']->getTimestamp(), new DateTimeZone('Europe/Lisbon')));
            ?>
          </h3>
          <ol class="programme-items">
            <?php foreach ($day_sessions as $s):
              $ics = ishf_programme_ics_url($s);
            ?>
              <li class="programme-item"
                  data-themes="<?php echo esc_attr(implode(' ', array_keys($s['themes']))); ?>"
                  data-speakers="<?php echo esc_attr(implode(' ', array_keys($s['speakers']))); ?>">
                <div class="programme-time">
                  <?php if ($s['start']): ?>
                    <time datetime="<?php echo esc_attr($s['start']->format('c')); ?>"><?php echo esc_html(ishf_programme_time_label($s)); ?></time>
                  <?php else: ?>
                    <span><?php esc_html_e('TBA', TEXT_DOMAIN); ?></span>
                  <?php endif; ?>
                </div>
                <div class="programme-body">
                  <?php if (!empty($s['themes']) || $s['provisional']): ?>
                    <div class="programme-tags">
                      <?php foreach ($s['themes'] as $name): ?>
                        <span class="uk-label"><?php echo esc_html($name); ?></span>
                      <?php endforeach; ?>
                      <?php if ($s['provisional']): ?>
                        <span class="uk-label uk-label-warning"><?php esc_html_e('Provisional', TEXT_DOMAIN); ?></span>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                  <h4 class="programme-title"><a href="<?php echo esc_url($s['url']); ?>"><?php echo esc_html($s['title']); ?></a></h4>
                  <?php if ($s['excerpt'] !== ''): ?>
                    <p class="programme-excerpt"><?php echo esc_html($s['excerpt']); ?></p>
                  <?php endif; ?>
                  <ul class="programme-meta">
                    <?php if ($s['location'] !== ''): ?>
                      <li><span data-uk-icon="icon: location; ratio: .9" aria-hidden="true"></span> <span class="uk-visually-hidden"><?php esc_html_e('Room:', TEXT_DOMAIN); ?></span> <?php echo esc_html($s['location']); ?></li>
                    <?php endif; ?>
                    <?php if (!empty($s['speakers'])): ?>
                      <li><span data-uk-icon="icon: user; ratio: .9" aria-hidden="true"></span> <span class="uk-visually-hidden"><?php esc_html_e('Speakers:', TEXT_DOMAIN); ?></span> <?php echo esc_html(implode(', ', $s['speakers'])); ?></li>
                    <?php endif; ?>
                  </ul>
                </div>
                <div class="programme-actions">
                  <a class="uk-button uk-button-default uk-button-small" href="<?php echo esc_url($s['url']); ?>"><?php esc_html_e('View session', TEXT_DOMAIN); ?><span class="uk-visually-hidden"> - <?php echo esc_html($s['title']); ?></span></a>
                  <?php if ($ics): ?>
                    <a class="uk-button uk-button-text uk-button-small" href="<?php echo esc_url($ics); ?>" rel="nofollow"><span data-uk-icon="icon: calendar; ratio: .9" aria-hidden="true"></span> <?php esc_html_e('Add to calendar', TEXT_DOMAIN); ?><span class="uk-visually-hidden"> - <?php echo esc_html($s['title']); ?></span></a>
                  <?php endif; ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ol>
        </section>
      <?php endforeach; ?>
      </div>

      <?php if ($has_filters): ?>
      <script>
      (function () {
        var form = document.getElementById('programme-filters');
        var list = document.getElementById('programme-list');
        var counter = document.getElementById('programme-count');
        if (!form || !list) { return; }
        form.hidden = false; // filters only make sense with JavaScript
        var items = list.querySelectorAll('.programme-item');
        var days = list.querySelectorAll('[data-day]');
        function apply() {
          var f = {};
          form.querySelectorAll('select[data-filter]').forEach(function (s) { f[s.getAttribute('data-filter')] = s.value; });
          var shown = 0;
          items.forEach(function (li) {
            var ok = (!f.theme || (' ' + li.getAttribute('data-themes') + ' ').indexOf(' ' + f.theme + ' ') > -1) &&
                     (!f.speaker || (' ' + li.getAttribute('data-speakers') + ' ').indexOf(' ' + f.speaker + ' ') > -1);
            li.hidden = !ok;
            if (ok) { shown++; }
          });
          days.forEach(function (d) { d.hidden = !d.querySelector('.programme-item:not([hidden])'); });
          if (counter) {
            counter.textContent = shown === 0 ? counter.getAttribute('data-none')
              : shown === 1 ? counter.getAttribute('data-one')
              : counter.getAttribute('data-many').replace('%d', shown);
          }
        }
        form.addEventListener('change', apply);
        form.querySelector('[data-programme-reset]').addEventListener('click', function () {
          form.reset(); apply();
        });
        apply();
      })();
      </script>
      <?php endif; ?>

    <?php endif; ?>
  </div>
</section>
