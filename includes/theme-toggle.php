<?php
// -----------------------------------------------------------------------------
//  Block: THEME TOGGLE (Dark / Light Mode)
//  Base Theme: ISHF 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------
?>
<button class="theme-toggle uk-button-link uk-display-inline-flex uk-flex-middle" 
        type="button" 
        role="switch"
        aria-checked="false"
        aria-label="<?php esc_attr_e('Toggle dark / light mode', TEXT_DOMAIN); ?>" 
        title="<?php esc_attr_e('Toggle theme', TEXT_DOMAIN); ?>">
    <span class="theme-toggle-track uk-position-relative uk-display-inline-block uk-border-pill">
        <span class="theme-toggle-knob uk-position-absolute uk-border-circle uk-flex uk-flex-center uk-flex-middle">
            <img class="theme-toggle-icon theme-toggle-icon-sun uk-position-center" src="<?php echo BASE_ASSETS . 'sun.svg'; ?>" data-uk-svg alt="" aria-hidden="true">
            <img class="theme-toggle-icon theme-toggle-icon-moon uk-position-center" src="<?php echo BASE_ASSETS . 'moon.svg'; ?>" data-uk-svg alt="" aria-hidden="true">
        </span>
    </span>
</button>
