<?php
// -----------------------------------------------------------------------------
//  Section: PARTNERS / SPONSORS (Home)
//  Figma: Home / Partners. Logos swap automatically with the theme
//  (.light-mode-img / .dark-mode-img). Export the six SVGs from the Figma
//  "Sponsors Logos" section into img/sponsors/ with the file names below.
//  Until a file exists the partner name is shown as text, so nothing breaks.
//  Roles ("Organised with", "Supported by") are still TBC in the design.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$ishf_partners = array(
  array('name' => 'Housing Europe', 'file' => 'housing-europe', 'url' => 'https://www.housingeurope.eu/', 'h' => 105),
  array('name' => 'Gebalis',        'file' => 'gebalis',        'url' => 'https://gebalis.pt/',            'h' => 105),
  array('name' => 'Câmara Municipal de Lisboa', 'file' => 'cm-lisboa', 'url' => 'https://www.lisboa.pt/', 'h' => 58),
);
$ishf_sponsors_dir = get_template_directory() . '/img/sponsors/';
?>
<section id="partners" class="ishf-partners" aria-labelledby="partners-title">
  <div class="uk-container">
    <h2 id="partners-title" class="ishf-eyebrow"><?php _e('Organised with', TEXT_DOMAIN); ?></h2>
    <ul class="partner-logos" role="list">
      <?php foreach ($ishf_partners as $p):
        $has_logos = file_exists($ishf_sponsors_dir . $p['file'] . '-dark.svg') && file_exists($ishf_sponsors_dir . $p['file'] . '-light.svg');
        ?>
        <li>
          <a href="<?php echo esc_url($p['url']); ?>" target="_blank" rel="noopener noreferrer">
            <?php if ($has_logos): ?>
              <img class="light-mode-img" src="<?php echo esc_url(BASE_IMAGES . 'sponsors/' . $p['file'] . '-dark.svg'); ?>" alt="<?php echo esc_attr($p['name']); ?>" height="<?php echo (int) $p['h']; ?>" loading="lazy" />
              <img class="dark-mode-img" src="<?php echo esc_url(BASE_IMAGES . 'sponsors/' . $p['file'] . '-light.svg'); ?>" alt="<?php echo esc_attr($p['name']); ?>" height="<?php echo (int) $p['h']; ?>" loading="lazy" />
            <?php else: ?>
              <span class="partner-name"><?php echo esc_html($p['name']); ?></span>
            <?php endif; ?>
            <span class="uk-visually-hidden"> <?php _e('(opens in a new tab)', TEXT_DOMAIN); ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
