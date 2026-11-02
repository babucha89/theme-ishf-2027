---
description: Architecture, UIkit 3 framework conventions, and WCAG 2.1 AA accessibility rules for the ISHF 2027 base theme
globs: **/*
---

# ISHF 2027 - Base Theme & Accessibility Guidelines

## 1. Standalone Base Theme Architecture
- **Theme Type**: Standalone Base Theme (Parent Theme). It does **NOT** depend on `uikit-boostsolutions` as a child theme.
- **Theme Root Files**:
  - `style.css`: Declares `Theme Name: ISHF 2027`, `Text Domain: ishf-2027`. Do **NOT** add a `Template:` header.
  - `header.php`: Root HTML5 boilerplate with `<head>`, Favicons, Open Graph tags, `<body <?php body_class(); ?>>`, and the WCAG skip link.
  - `footer.php`: Calls `wp_footer()` and closes `</body></html>`.
  - `functions.php`: Declares `BASE_*` constants, enqueues UIkit 3 + Google Fonts + jQuery, registers theme supports, and sets up text domain.
- **Path Constants**:
  - Use `BASE_SITE`, `BASE_CSS`, `BASE_IMAGES`, `BASE_BRANDING`, `BASE_ASSETS`, `BASE_FAVICONS`, `BASE_JS`, `BASE_FONTS`.
  - All `CHILD_BASE_*` constants are aliased to `BASE_*` for backwards compatibility.
- **Form Project Tokens & API Webhooks**:
  - `NEWSLETTER_API_ENDPOINT`: `https://api-mm.boostsolutions.pt/api/webhooks/gravity-forms`.
  - `PARTNERSHIP_PROJECT_TOKEN`: Token for Partnership contact inquiries form.
  - `NEWSLETTER_PROJECT_TOKEN`: Token for General Newsletter subscription form.
- **Internationalization (i18n)**:
  - Base code strings must always be in **English** using `__('...', TEXT_DOMAIN)` or `_e('...', TEXT_DOMAIN)`.
  - Translations are compiled in `languages/pt_PT.po`.

---

## 2. UIkit Framework Standards (`getuikit.com`)
This theme strictly implements **UIkit 3.19.2**. All layouts, components, and interactive utilities must follow standard UIkit naming and data attributes:
- **Grids & Flexbox**:
  - Grid: `data-uk-grid`, `uk-grid`, `uk-child-width-1-1`, `uk-child-width-1-2@m`, `uk-grid-match`, `uk-grid-large`.
  - Flex: `uk-flex`, `uk-flex-middle`, `uk-flex-center`, `uk-flex-between`.
- **Containers**:
  - `uk-container`, `uk-container-large`, `uk-container-xlarge`, `uk-container-small`.
- **Forms**:
  - `uk-form-stacked`, `uk-form-label`, `uk-form-controls`, `uk-input`, `uk-textarea`, `uk-checkbox`, `uk-button`.
- **Alerts**:
  - `uk-alert`, `data-uk-alert`, `uk-alert-success`, `uk-alert-warning`, `uk-alert-danger`, `uk-alert-close` (`data-uk-close`).
- **Cards**:
  - `uk-card`, `uk-card-body`.
- **Sticky Elements**:
  - `data-uk-sticky="top: 100; animation: uk-animation-slide-top;"`.

---

## 3. WCAG 2.1 AA Accessibility Guidelines

Every template, section, and block must strictly satisfy WCAG 2.1 AA accessibility requirements:

### A. Landmarks & Semantic Markup
- **Skip Link**: `<a href="#content-start" class="skip-link"><?php _e('Skip to main content', TEXT_DOMAIN); ?></a>` must precede all navigation.
- **Main Landmark**: Primary content wrapped in `<main id="content-start" role="main" class="site-main" tabindex="-1">`.
- **Header Landmark**: `<header role="banner">`.
- **Navigation Landmark**: `<nav role="navigation" aria-label="...">`.
- **Footer Landmark**: `<footer role="contentinfo">`.
- **Interactive Regions**: Distinct sections or cards use `role="region" aria-labelledby="..."`.

### B. Heading Hierarchy
- Exactly **one `<h1>`** per view (on the landing page: `Cities with Soul: It Begins with a Key`).
- Subordinate section titles must use `<h2>`, and inner subheadings use `<h3>`. Never skip heading levels.

### C. Forms & Controls
- **Labels**: Every input (`uk-input`, `uk-textarea`, `uk-checkbox`) must have a matching `<label for="[id]">`.
- **Required Fields**: Explicit `required` and `aria-required="true"`.
- **Help Text**: Linked with `aria-describedby="[help-id]"`.
- **Validation Alerts**: Error banners must declare `role="alert" aria-live="assertive"`; success banners use `role="status" aria-live="polite"`.
- **Close Buttons**: Must include `aria-label="<?php esc_attr_e('Close notification', TEXT_DOMAIN); ?>"`.
- **Autocomplete & Inputmode**: Specify `autocomplete="name"`, `autocomplete="email"`, `inputmode="email"`, etc., for cognitive and mobile accessibility.

### D. Theme Toggle Switch
- Must be rendered as `<button class="theme-toggle" type="button" role="switch" aria-checked="[true|false]" aria-label="...">`.
- `js/light-dark-switch.js` must update `aria-checked` dynamically when the theme state flips.

### E. Keyboard Navigation & Focus Visible
- All interactive controls must support full keyboard navigation (Tab, Enter, Space).
- Clear, high-contrast focus rings (`:focus-visible { outline: 2px solid var(--colors-brand-sunny-yellow); outline-offset: 2px; }`).

### F. Screen Reader Only Utility
- Use `.sr-only` or `.uk-screen-reader-text` for visually hidden, screen-reader-accessible text.

### G. Vestibular Safety (Reduced Motion)
- Support `@media (prefers-reduced-motion: reduce)` to disable transitions and smooth scrolling for users who require it.

### H. Images & Decorative SVGs
- Informative graphics (e.g. logos) must have descriptive `alt` text.
- Decorative icons (e.g., `sun.svg`, `moon.svg`, `icon-key-*.svg`) must have empty `alt=""` and `aria-hidden="true"`.
- Adaptive Light/Dark switching must use `.light-mode-img` (for Light Mode, e.g. `icon-key-light.svg`, `logo-project-horizontal-dark.svg`) and `.dark-mode-img` (for Dark Mode, e.g. `icon-key-dark.svg`, `logo-project-horizontal-light.svg`).
