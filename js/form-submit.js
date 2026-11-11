/*
 * Partnership & Newsletter forms: once a valid submit starts, the button is
 * disabled (no double submissions) and shows a "sending" label.
 * Text comes from data-sending on the button (translated server-side).
 * Integrated with Google reCAPTCHA v3 anti-bot protection when keys are present.
 */
(function () {
  'use strict';
  function lock(form) {
    var btn = form.querySelector('button[type="submit"]');
    if (!btn) { return; }
    btn.disabled = true;
    btn.setAttribute('aria-disabled', 'true');
    btn.setAttribute('aria-busy', 'true');
    var label = btn.getAttribute('data-sending');
    if (label) { btn.textContent = label; }
  }
  function unlock(form) {
    var btn = form.querySelector('button[type="submit"]');
    if (!btn || !btn.disabled) { return; }
    btn.disabled = false;
    btn.removeAttribute('aria-disabled');
    btn.removeAttribute('aria-busy');
    if (btn.hasAttribute('data-label')) { btn.innerHTML = btn.getAttribute('data-label'); }
  }
  document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('#form-partnership, #form-newsletter-landing');
    var siteKey = window.ishf_recaptcha_site_key || '';

    Array.prototype.forEach.call(forms, function (form) {
      var btn = form.querySelector('button[type="submit"]');
      if (btn) { btn.setAttribute('data-label', btn.innerHTML); }

      form.addEventListener('submit', function (e) {
        if (form.getAttribute('data-submitting') === '1') {
          e.preventDefault();
          return;
        }

        // Validate required HTML5 fields first before locking or triggering reCAPTCHA
        if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
          return;
        }

        var tokenInput = form.querySelector('input[name="g-recaptcha-response"]');
        if (siteKey && typeof grecaptcha !== 'undefined' && (!tokenInput || !tokenInput.value)) {
          e.preventDefault();
          lock(form);
          grecaptcha.ready(function () {
            var action = form.id === 'form-partnership' ? 'partnership' : 'newsletter';
            grecaptcha.execute(siteKey, { action: action }).then(function (token) {
              if (!tokenInput) {
                tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = 'g-recaptcha-response';
                form.appendChild(tokenInput);
              }
              tokenInput.value = token;
              form.setAttribute('data-submitting', '1');
              form.submit();
            }).catch(function (err) {
              console.warn('[reCAPTCHA] Execution fallback:', err);
              form.setAttribute('data-submitting', '1');
              form.submit();
            });
          });
          return;
        }

        form.setAttribute('data-submitting', '1');
        setTimeout(function () { lock(form); }, 0);
      });
    });
  });

  // Coming back with the browser's Back button must not leave the button locked.
  window.addEventListener('pageshow', function (e) {
    if (!e.persisted) { return; }
    Array.prototype.forEach.call(document.querySelectorAll('#form-partnership, #form-newsletter-landing'), function (form) {
      form.removeAttribute('data-submitting');
      var tokenInput = form.querySelector('input[name="g-recaptcha-response"]');
      if (tokenInput) { tokenInput.value = ''; }
      unlock(form);
    });
  });
})();
