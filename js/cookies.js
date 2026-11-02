// -----------------------------------------------------------------------------
//  Functions: MANAGE COOKIES
// -----------------------------------------------------------------------------

// -- Accept COOKIE POLICY
$(document).ready(function() {
  $('#btnAcceptCookies').click(function() {
    setCookie('ISHF_2027_Cookie_Accept','true',99999);
    UIkit.toggle($('#cookies-disclaimer'), { animation: 'uk-animation-fade, uk-animation-slide-top-small', duration: 250 });
    // $('#cookies-disclaimer').addClass('disabled');
  });
});

// -- SET Cookie
function setCookie(name,value,days) {
  var expires = "";
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + (days*24*60*60*1000));
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
