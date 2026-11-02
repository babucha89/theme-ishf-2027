jQuery(document).ready(function($) {
  // Functions for cookie manipulation
  function setCookie(name, value, days) {
      var expires = "";
      if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
          expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + (value || "") + expires + "; path=/";
  }

  function getCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) === ' ') c = c.substring(1, c.length);
          if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
  }

  // 1. Check preference from cookie or localStorage on load
  var manualTheme = getCookie('ISHF2027_Theme_Preference') || localStorage.getItem('manualThemePreference');
  var isDark = false;
  
  if (manualTheme === 'dark') {
      $('html').addClass('dark-mode');
      isDark = true;
  } else if (manualTheme === 'light') {
      $('html').removeClass('dark-mode');
      isDark = false;
  } else {
      isDark = $('html').hasClass('dark-mode');
  }

  // Update accessible state for switch controls
  $('.theme-toggle').attr('aria-checked', isDark ? 'true' : 'false');
  
  // Add smooth transition only after initial render (prevents color flash)
  setTimeout(function() {
      $('html, body').css('transition', 'background-color 0.4s ease, color 0.4s ease');
  }, 100);
  
  // 2. Handle theme toggle click
  $(document).on('click', '.theme-toggle', function(e) {
    e.preventDefault();
    
    $('html').toggleClass('dark-mode');
    var darkActive = $('html').hasClass('dark-mode');
    
    // Update accessible attribute
    $('.theme-toggle').attr('aria-checked', darkActive ? 'true' : 'false');

    setCookie('ISHF2027_Theme_Preference', darkActive ? 'dark' : 'light', 365);
    localStorage.setItem('manualThemePreference', darkActive ? 'dark' : 'light');
  });
});
