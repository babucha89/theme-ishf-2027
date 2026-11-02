<?php
// -----------------------------------------------------------------------------
//  Block: THEME TOGGLE (Dark / Light Mode)
//  Design System v2: 60x32 pill, sun + moon always visible, knob shows the
//  active mode. role="switch" + aria-checked is kept in sync by
//  js/light-dark-switch.js (applies to every .theme-toggle on the page).
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 27-OUT-2026 | v2 design update
// -----------------------------------------------------------------------------
?>
<button class="theme-toggle uk-button-link uk-display-inline-flex uk-flex-middle" 
        type="button" 
        role="switch"
        aria-checked="false"
        aria-label="<?php esc_attr_e('Toggle dark / light mode', TEXT_DOMAIN); ?>" 
        title="<?php esc_attr_e('Toggle theme', TEXT_DOMAIN); ?>">
    <span class="theme-toggle-track uk-position-relative uk-display-inline-block uk-border-pill">
        <span class="theme-toggle-track-icon theme-toggle-track-icon--sun" aria-hidden="true">
            <img src="<?php echo esc_url(BASE_ASSETS . 'sun.svg'); ?>" data-uk-svg alt="" aria-hidden="true">
        </span>
        <span class="theme-toggle-track-icon theme-toggle-track-icon--moon" aria-hidden="true">
            <img src="<?php echo esc_url(BASE_ASSETS . 'moon.svg'); ?>" data-uk-svg alt="" aria-hidden="true">
        </span>
        <span class="theme-toggle-knob uk-position-absolute uk-border-circle uk-flex uk-flex-center uk-flex-middle">
            <img class="theme-toggle-icon theme-toggle-icon-sun uk-position-center" src="<?php echo esc_url(BASE_ASSETS . 'sun.svg'); ?>" data-uk-svg alt="" aria-hidden="true">
            <img class="theme-toggle-icon theme-toggle-icon-moon uk-position-center" src="<?php echo esc_url(BASE_ASSETS . 'moon.svg'); ?>" data-uk-svg alt="" aria-hidden="true">
        </span>
    </span>
</button>
