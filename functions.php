<?php
// -----------------------------------------------------------------------------
//  File: FUNCTIONS
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------


//------------------------------------------------------------------------------
//  Defining CONSTANTS
//------------------------------------------------------------------------------

// -- Local environment? (localhost, 127.0.0.1, *.local, *.test, *.localhost, or WP_ENVIRONMENT_TYPE=local).
//    Safe in CLI / cron contexts where SERVER_NAME is not set.
defined('ISHF_IS_LOCAL') or define('ISHF_IS_LOCAL',
  in_array(($_SERVER['SERVER_NAME'] ?? ''), array('localhost', '127.0.0.1', '::1'), true)
  || (bool) preg_match('/\.(local|test|localhost)$/', ($_SERVER['SERVER_NAME'] ?? ''))
  || (function_exists('wp_get_environment_type') && wp_get_environment_type() === 'local')
);

// -- Redirections
if (!ISHF_IS_LOCAL):
  // defined('REDIRECT_SITE') or define('REDIRECT_SITE', 'https://www.socialhousingfestival.eu');
  defined('REDIRECT_SITE') or define('REDIRECT_SITE', 'https://bs.xl.pt/ishf-2027');
else:
  defined('REDIRECT_SITE') or define('REDIRECT_SITE', get_home_url());
endif;

// -- CDN Versions
defined('CDN_JQUERY_VERSION') or define('CDN_JQUERY_VERSION', '4.0.0');
defined('CDN_UIKIT_VERSION') or define('CDN_UIKIT_VERSION', '3.25.28');

// -- Site Constants
defined('SITE_VERSION') or define('SITE_VERSION', '1.0.0');
defined('SITE_LATEST_UPDATE') or define('SITE_LATEST_UPDATE', '27-OUT-2026');
defined('SITE_GTM_ID') or define('SITE_GTM_ID', 'GTM-WKBFD6MJ');
defined('SITE_SHARETHIS_PROPERTY') or define('SITE_SHARETHIS_PROPERTY', '6ae11d95204704d053d4d751');
defined('SITE_NAME') or define('SITE_NAME', 'International Social Housing Festival');
defined('SITE_SHORT_NAME') or define('SITE_SHORT_NAME', 'ISHF 2027');
defined('SITE_CLIENT_NAME') or define('SITE_CLIENT_NAME', 'ISHF 2027 Lisbon');
defined('SITE_CONTACT_NAME') or define('SITE_CONTACT_NAME', 'Medialivre Boost Activate');
defined('SITE_CONTACT_EMAIL') or define('SITE_CONTACT_EMAIL', 'boostactivate@medialivre.pt');
defined('SITE_CONTACT_NAME_2') or define('SITE_CONTACT_NAME_2', null);
defined('SITE_CONTACT_EMAIL_2') or define('SITE_CONTACT_EMAIL_2', null);
defined('TEXT_DOMAIN') or define('TEXT_DOMAIN', 'ishf-2027');
defined('SITE_USE_NAVSCROLLER') or define('SITE_USE_NAVSCROLLER', true);
defined('SITE_USE_SEARCH') or define('SITE_USE_SEARCH', false);
defined('SITE_COOKIES_DISCLAIMER_POSITION') or define('SITE_COOKIES_DISCLAIMER_POSITION', 'top'); // 'top' or 'bottom'

// -- Call for Sessions External Form URL
// Form link from Centro de Congressos de Lisboa.
defined('SITE_CALL_FOR_SESSIONS_URL') or define('SITE_CALL_FOR_SESSIONS_URL', 'https://congressos.leading.pt/geral/inserirresumo.aspx?evento=338&formulario=757&lingua=en-GB&login=false');

// -- GDPR / Privacy Consent Disclaimers (Configurable)
// For Partnership & Newsletter Forms it's applied the same consent.
defined('SITE_DEFAULT_CONSENT_DISCLAIMER') or define('SITE_DEFAULT_CONSENT_DISCLAIMER',
'I expressly authorize the processing of my personal data for marketing communications by Medialivre, S.A. and its partner entities, via telephone, email, SMS, and postal mail.');
defined('SITE_PARTNERSHIP_CONSENT_DISCLAIMER') or define('SITE_PARTNERSHIP_CONSENT_DISCLAIMER', SITE_DEFAULT_CONSENT_DISCLAIMER);
defined('SITE_NEWSLETTER_CONSENT_DISCLAIMER') or define('SITE_NEWSLETTER_CONSENT_DISCLAIMER', SITE_DEFAULT_CONSENT_DISCLAIMER);

defined('RECAPTCHA_SITE_KEY') or define('RECAPTCHA_SITE_KEY', null);
defined('RECAPTCHA_SECRET_KEY') or define('RECAPTCHA_SECRET_KEY', null);

defined('SITE_USE_ATTENDANTS') or define('SITE_USE_ATTENDANTS', true);
defined('SITE_USE_BANNERS') or define('SITE_USE_BANNERS', false);
defined('SITE_USE_COAUTHORS') or define('SITE_USE_COAUTHORS', false);
defined('SITE_USE_FAQS') or define('SITE_USE_FAQS', true);
defined('SITE_USE_HEADER_SLIDES') or define('SITE_USE_HEADER_SLIDES', false);
defined('SITE_USE_PARTNERSHIPS') or define('SITE_USE_PARTNERSHIPS', false);
defined('SITE_USE_PODCASTS') or define('SITE_USE_PODCASTS', false);
defined('SITE_USE_PROJECTS') or define('SITE_USE_PROJECTS', false);
defined('SITE_USE_QUOTATIONS') or define('SITE_USE_QUOTATIONS', false);
defined('SITE_USE_SEMINARS') or define('SITE_USE_SEMINARS', true);
defined('SITE_USE_SPONSORS') or define('SITE_USE_SPONSORS', true);
defined('SITE_USE_VIDEOCASTS') or define('SITE_USE_VIDEOCASTS', false);
defined('SITE_USE_WINNERS') or define('SITE_USE_WINNERS', false);

// -- Base Path Constants (Standalone Base Theme)
defined('BASE_SITE') or define('BASE_SITE', get_home_url());
defined('BASE_CSS') or define('BASE_CSS', str_replace(BASE_SITE, REDIRECT_SITE, get_stylesheet_directory_uri()) . '/css/');
defined('BASE_IMAGES') or define('BASE_IMAGES', str_replace(BASE_SITE, REDIRECT_SITE, get_stylesheet_directory_uri()) . '/img/');
defined('BASE_BACKGROUNDS') or define('BASE_BACKGROUNDS', str_replace(BASE_SITE, REDIRECT_SITE, get_stylesheet_directory_uri()) . '/img/backgrounds/');
defined('BASE_BRANDING') or define('BASE_BRANDING', str_replace(BASE_SITE, REDIRECT_SITE, get_stylesheet_directory_uri()) . '/img/branding/');
defined('BASE_ASSETS') or define('BASE_ASSETS', str_replace(BASE_SITE, REDIRECT_SITE, get_stylesheet_directory_uri()) . '/img/assets/');
defined('BASE_BANNERS') or define('BASE_BANNERS', str_replace(BASE_SITE, REDIRECT_SITE, get_stylesheet_directory_uri()) . '/img/banners/');
defined('BASE_OG_IMAGES') or define('BASE_OG_IMAGES', str_replace(BASE_SITE, REDIRECT_SITE, get_stylesheet_directory_uri()) . '/img/og-images/');
defined('BASE_SPONSORS') or define('BASE_SPONSORS', str_replace(BASE_SITE, REDIRECT_SITE, get_stylesheet_directory_uri()) . '/img/sponsors/');
defined('BASE_FAVICONS') or define('BASE_FAVICONS', str_replace(BASE_SITE, REDIRECT_SITE, get_stylesheet_directory_uri()) . '/favicons/');
defined('BASE_JS') or define('BASE_JS', str_replace(BASE_SITE, REDIRECT_SITE, get_stylesheet_directory_uri()) . '/js/');
defined('BASE_FONTS') or define('BASE_FONTS', str_replace(BASE_SITE, REDIRECT_SITE, get_stylesheet_directory_uri()) . '/fonts/');

// -- Backwards Compatibility Aliases
defined('CHILD_BASE_SITE') or define('CHILD_BASE_SITE', BASE_SITE);
defined('CHILD_BASE_CSS') or define('CHILD_BASE_CSS', BASE_CSS);
defined('CHILD_BASE_IMAGES') or define('CHILD_BASE_IMAGES', BASE_IMAGES);
defined('CHILD_BASE_BACKGROUNDS') or define('CHILD_BASE_BACKGROUNDS', BASE_BACKGROUNDS);
defined('CHILD_BASE_BRANDING') or define('CHILD_BASE_BRANDING', BASE_BRANDING);
defined('CHILD_BASE_ASSETS') or define('CHILD_BASE_ASSETS', BASE_ASSETS);
defined('CHILD_BASE_BANNERS') or define('CHILD_BASE_BANNERS', BASE_BANNERS);
defined('CHILD_BASE_OG_IMAGES') or define('CHILD_BASE_OG_IMAGES', BASE_OG_IMAGES);
defined('CHILD_BASE_SPONSORS') or define('CHILD_BASE_SPONSORS', BASE_SPONSORS);
defined('CHILD_BASE_FAVICONS') or define('CHILD_BASE_FAVICONS', BASE_FAVICONS);
defined('CHILD_BASE_JS') or define('CHILD_BASE_JS', BASE_JS);

// -- API Webhook Endpoint & Form Project Tokens
// https://api-mm.boostsolutions.pt/
defined('NEWSLETTER_API_ENDPOINT') or define('NEWSLETTER_API_ENDPOINT', 'https://api-mm.boostsolutions.pt/api/webhooks/gravity-forms');
defined('PARTNERSHIP_PROJECT_TOKEN') or define('PARTNERSHIP_PROJECT_TOKEN', 'tk-8XZPBw3Hnl');
defined('NEWSLETTER_PROJECT_TOKEN') or define('NEWSLETTER_PROJECT_TOKEN', 'tk-lOObEAt91Z');


//------------------------------------------------------------------------------
//  Defining THEME STARTUP
//------------------------------------------------------------------------------
add_action('after_setup_theme', 'ishf_setup');
function ishf_setup()
{
  global $content_width;
  if (!isset($content_width))
    $content_width = 1200;

  // Load translations for the base theme
  load_theme_textdomain(TEXT_DOMAIN, __DIR__ . '/languages');

  date_default_timezone_set('Europe/Lisbon');
  $locale = !ISHF_IS_LOCAL ? 'pt_PT.UTF-8' : 'pt-PT.UTF-8';
  setlocale(LC_ALL, $locale);

  // Modern Theme Features (Explicitly declared <title> in header.php for WAVE / WCAG 2.1 AA compliance)
  remove_action('wp_head', '_wp_render_title_tag', 1);
  add_theme_support('post-thumbnails');
  add_post_type_support('page', array('excerpt', 'custom-fields')); // sections/hero: lead + labels, translatable via WPML
  add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'));
  add_theme_support('responsive-embeds');

  // Navigation menus: registered in includes/uikit-menus.php (UIkit locations + walkers in includes/uikit-walker.php)

  add_image_size('image-speaker-face', 450, 450, true);
  add_image_size('image-gallery', 1600, 900, true);
  add_image_size('image-featured', 1280, 720, true);
  add_image_size('image-3x2', 900, 600, true);
}


//------------------------------------------------------------------------------
//  Layout variant flag: minimal (landing page, 404, maintenance) vs full
//  (navigation-menu with links + language switch, footer with columns).
//  Templates call ishf_use_full_layout(true) before get_header().
//------------------------------------------------------------------------------
if (!function_exists('ishf_use_full_layout')):
  function ishf_use_full_layout($set = null)
  {
    static $full = false;
    if ($set !== null) $full = (bool) $set;
    return $full;
  }
endif;


//------------------------------------------------------------------------------
//  Loading SCRIPTS and STYLESHEETS
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//  WPML helper: returns the post / page / ID translated into the current language.
//  No-op when WPML is not active (returns the input unchanged).
//------------------------------------------------------------------------------
if (!function_exists('ishf_translate_post')):
  function ishf_translate_post($post, $post_type = 'page')
  {
    $id = is_object($post) ? (int) $post->ID : (int) $post;
    if (!$id) {
      return $post;
    }
    $translated = (int) apply_filters('wpml_object_id', $id, $post_type, true);
    if (!$translated || $translated === $id) {
      return $post;
    }
    return is_object($post) ? get_post($translated) : $translated;
  }
endif;

add_action('wp_enqueue_scripts', 'ishf_load_scripts');
function ishf_load_scripts()
{
  // -- ENQUEUE STYLESHEETS
  wp_enqueue_style('uikit', 'https://cdn.jsdelivr.net/npm/uikit@' . CDN_UIKIT_VERSION . '/dist/css/uikit.min.css', array(), CDN_UIKIT_VERSION);
  wp_enqueue_style('googlefonts', add_query_arg(array('family' => 'Plus+Jakarta+Sans:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900', 'display' => 'swap'), 'https://fonts.googleapis.com/css2'), array(), null);

  $deps = array('uikit');
  if (!ISHF_IS_LOCAL):
    wp_enqueue_style('app', BASE_CSS . 'app.min.css', $deps, filemtime(__DIR__ . '/css/app.min.css'));
  else:
    wp_enqueue_style('app', BASE_CSS . 'app.css', $deps, filemtime(__DIR__ . '/css/app.css'));
  endif;

  // -- ENQUEUE SCRIPTS
  wp_deregister_script('jquery');
  wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/' . CDN_JQUERY_VERSION . '/jquery.min.js', array(), CDN_JQUERY_VERSION, false);

  $deps = array('jquery');
  wp_enqueue_script('uikit', 'https://cdn.jsdelivr.net/npm/uikit@' . CDN_UIKIT_VERSION . '/dist/js/uikit.min.js', $deps, CDN_UIKIT_VERSION, false);
  wp_enqueue_script('uikit-icons', 'https://cdn.jsdelivr.net/npm/uikit@' . CDN_UIKIT_VERSION . '/dist/js/uikit-icons.min.js', array('uikit'), CDN_UIKIT_VERSION, true);

  if (!is_page('widget') && file_exists(__DIR__ . '/js/cookies.js')):
    wp_enqueue_script('cookies', BASE_JS . 'cookies.js', array('jquery', 'uikit'), SITE_VERSION, true);
  endif;

  wp_enqueue_script('ishf-form-submit', BASE_JS . 'form-submit.js', array(), SITE_VERSION, true);
  wp_enqueue_script('light-dark-switch', BASE_JS . 'light-dark-switch.js', array('jquery'), false, true);

  // -- ShareThis Sticky Buttons
  if (defined('SITE_SHARETHIS_PROPERTY') && !empty(SITE_SHARETHIS_PROPERTY)):
    wp_enqueue_script('sharethis', 'https://platform-api.sharethis.com/js/sharethis.js#property=' . SITE_SHARETHIS_PROPERTY . '&product=sop', array(), null, false);
  endif;

}

//------------------------------------------------------------------------------
//  ShareThis: add async attribute to script tag
//------------------------------------------------------------------------------
if (!function_exists('ishf_add_async_to_sharethis')):
  add_filter('script_loader_tag', 'ishf_add_async_to_sharethis', 10, 2);
  function ishf_add_async_to_sharethis($tag, $handle)
  {
    if ('sharethis' === $handle) {
      if (strpos($tag, ' async') === false) {
        $tag = str_replace('<script ', '<script async ', $tag);
      }
    }
    return $tag;
  }
endif;


//------------------------------------------------------------------------------
//  Dark / Light Mode: Early script to avoid FOUC (flash of light mode)
//------------------------------------------------------------------------------
add_action('wp_head', 'ishf_dark_mode_init_script', 5);
function ishf_dark_mode_init_script()
{
  ?>
  <script type="text/javascript">
    (function () {
      var theme = localStorage.getItem('manualThemePreference');
      if (!theme) {
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
          var c = ca[i].trim();
          if (c.indexOf('ISHF2027_Theme_Preference=') === 0) {
            theme = c.substring(26);
            break;
          }
        }
      }
      if (theme === 'dark') {
        document.documentElement.classList.add('dark-mode');
      }
    })();
  </script>
  <?php
}


//------------------------------------------------------------------------------
//  Helper: Post Views Counter
//------------------------------------------------------------------------------
if (!function_exists('ishf_set_post_views')):
  function ishf_set_post_views($postID)
  {
    if (empty($postID)) return;
    $count_key = 'ishf_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == ''):
      $count = 0;
      delete_post_meta($postID, $count_key);
      add_post_meta($postID, $count_key, '0');
    else:
      if (get_post_status($postID) === 'publish'):
        $count++;
        update_post_meta($postID, $count_key, $count);
      endif;
    endif;
  }
endif;


//------------------------------------------------------------------------------
//  Helper: Open Graph Tags
//------------------------------------------------------------------------------
if (!function_exists('ishf_open_graph_tags')):
  function ishf_open_graph_tags($fixed_og_image = null)
  {
    $og_title = wp_get_document_title();
    $og_url = REDIRECT_SITE . ($_SERVER['REQUEST_URI'] ?? '/');
    $og_description = __('International Social Housing Festival (ISHF) 2027 Lisbon. Cities with Soul: It Begins with a Key.', TEXT_DOMAIN);
    $og_image = !empty($fixed_og_image) ? $fixed_og_image : BASE_OG_IMAGES . 'og-image.jpg';
    ?>
    <!-- Open Graph / Social Meta Tags -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo esc_url($og_url); ?>" />
    <meta property="og:title" content="<?php echo esc_attr($og_title); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($og_description); ?>" />
    <meta property="og:image" content="<?php echo esc_url($og_image); ?>" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr($og_title); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($og_description); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>" />
    <?php
  }
endif;


//------------------------------------------------------------------------------
//  Document Title Customization (Browser Tab & Head Title)
//------------------------------------------------------------------------------
if (!function_exists('ishf_document_title_parts')):
  function ishf_document_title_parts($title_parts)
  {
    $short_name = defined('SITE_SHORT_NAME') ? SITE_SHORT_NAME : (defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027');
    if (is_front_page() || is_home() || is_page('landing-page') || is_page_template('page-landing-page.php')) {
      $title_parts['title'] = $short_name;
      $title_parts['tagline'] = __('Cities with Soul: It Begins with a Key', TEXT_DOMAIN);
      unset($title_parts['site']);
    } else {
      $title_parts['site'] = $short_name;
    }
    return $title_parts;
  }
  add_filter('document_title_parts', 'ishf_document_title_parts', 20);
endif;

if (!function_exists('ishf_pre_get_document_title')):
  function ishf_pre_get_document_title($title)
  {
    $short_name = defined('SITE_SHORT_NAME') ? SITE_SHORT_NAME : (defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027');
    if (is_front_page() || is_home() || is_page('landing-page') || is_page_template('page-landing-page.php')) {
      return $short_name . ' - ' . __('Cities with Soul: It Begins with a Key', TEXT_DOMAIN);
    }
    return $title;
  }
  add_filter('pre_get_document_title', 'ishf_pre_get_document_title', 20);
endif;


//------------------------------------------------------------------------------
//  Call for Sessions - External Form URL Helper
//------------------------------------------------------------------------------
if (!function_exists('ishf_get_register_phase')):
  /**
   * Registration phase: 'b2b' (until 31/12/2026 23:59:59 UTC) or 'b2c' (from 01/01/2027 00:00:00 UTC).
   * Override the switch moment with the SITE_REGISTER_PHASE2_START constant (any strtotime() string)
   * or force a phase with the 'ishf_register_phase' filter (e.g. for testing).
   */
  function ishf_get_register_phase()
  {
    $start = strtotime(defined('SITE_REGISTER_PHASE2_START') ? SITE_REGISTER_PHASE2_START : '2027-01-01 00:00:00 UTC');
    $phase = (time() >= $start) ? 'b2c' : 'b2b';
    return apply_filters('ishf_register_phase', $phase);
  }
endif;

if (!function_exists('ishf_get_register_url')):
  /**
   * External (third-party) registration form for the current phase.
   *  - Phase 1 (B2B): SITE_REGISTER_B2B_URL
   *  - Phase 2 (B2C): SITE_REGISTER_B2C_URL (final URL still unknown). Until it is defined the
   *    B2B URL is kept as a fallback so the button never points to a dead link.
   * Filter: 'ishf_register_url' ($url, $phase).
   */
  function ishf_get_register_url()
  {
    $b2b   = defined('SITE_REGISTER_B2B_URL') && SITE_REGISTER_B2B_URL ? SITE_REGISTER_B2B_URL : 'https://congressos.leading.pt/geral/inserirresumo.aspx?evento=338&formulario=757&lingua=en-GB&login=false';
    $b2c   = defined('SITE_REGISTER_B2C_URL') && SITE_REGISTER_B2C_URL ? SITE_REGISTER_B2C_URL : '';
    $phase = ishf_get_register_phase();
    $url   = ($phase === 'b2c' && $b2c) ? $b2c : $b2b;
    return apply_filters('ishf_register_url', $url, $phase);
  }
endif;

if (!function_exists('ishf_get_register_cta')):
  /**
   * Register CTA for the current registration phase: url + button label + audience hint.
   * The copy changes with the phase because each phase targets a different focus group
   * (B2B: professionals and organisations; B2C: general public).
   * Texts are PLACEHOLDERS pending approval; translate via .po/WPML or override with the
   * 'ishf_register_cta' filter ($cta, $phase).
   */
  function ishf_get_register_cta()
  {
    $phase = ishf_get_register_phase();
    if ($phase === 'b2c') {
      $cta = array(
        'label' => __('Get Your Ticket', TEXT_DOMAIN),
        'hint'  => __('Open to everyone.', TEXT_DOMAIN),
      );
    } else {
      $cta = array(
        'label' => __('Register as a Professional', TEXT_DOMAIN),
        'hint'  => __('Professional registration, open until 31 December 2026.', TEXT_DOMAIN),
      );
    }
    $cta['url']   = ishf_get_register_url();
    $cta['phase'] = $phase;
    return apply_filters('ishf_register_cta', $cta, $phase);
  }
endif;

if (!function_exists('ishf_get_call_for_sessions_url')):
  /**
   * Obtém o link para o formulário externo de "Call for Sessions".
   * O URL pode ser configurado na constante SITE_CALL_FOR_SESSIONS_URL,
   * alterado diretamente nesta função, ou filtrado através de 'ishf_call_for_sessions_url'.
   *
   * @return string
   */
  function ishf_get_call_for_sessions_url()
  {
    $url = defined('SITE_CALL_FOR_SESSIONS_URL') && !empty(SITE_CALL_FOR_SESSIONS_URL)
      ? SITE_CALL_FOR_SESSIONS_URL
      : 'https://docs.google.com/forms/';

    return apply_filters('ishf_call_for_sessions_url', $url);
  }
endif;


//------------------------------------------------------------------------------
//  Form Disclaimers / GDPR Consent Statements
//------------------------------------------------------------------------------
if (!function_exists('ishf_get_partnership_disclaimer')):
  /**
   * Retorna a frase de consentimento/disclaimer para o formulário de parcerias.
   * Pode ser editado na constante SITE_PARTNERSHIP_CONSENT_DISCLAIMER ou via filtro.
   *
   * @return string
   */
  function ishf_get_partnership_disclaimer()
  {
    $fallback = defined('SITE_DEFAULT_CONSENT_DISCLAIMER')
      ? SITE_DEFAULT_CONSENT_DISCLAIMER
      : __('I expressly authorize the processing of my personal data for marketing communications by Medialivre, S.A. and its partner entities, via telephone, email, SMS, and postal mail.', TEXT_DOMAIN);

    $default = defined('SITE_PARTNERSHIP_CONSENT_DISCLAIMER')
      ? SITE_PARTNERSHIP_CONSENT_DISCLAIMER
      : $fallback;

    return apply_filters('ishf_partnership_consent_disclaimer', $default);
  }
endif;

if (!function_exists('ishf_get_newsletter_disclaimer')):
  /**
   * Retorna a frase de consentimento/disclaimer para o formulário de newsletter.
   * Pode ser editado na constante SITE_NEWSLETTER_CONSENT_DISCLAIMER ou via filtro.
   *
   * @return string
   */
  function ishf_get_newsletter_disclaimer()
  {
    $fallback = defined('SITE_DEFAULT_CONSENT_DISCLAIMER')
      ? SITE_DEFAULT_CONSENT_DISCLAIMER
      : __('I expressly authorize the processing of my personal data for marketing communications by Medialivre, S.A. and its partner entities, via telephone, email, SMS, and postal mail.', TEXT_DOMAIN);

    $default = defined('SITE_NEWSLETTER_CONSENT_DISCLAIMER')
      ? SITE_NEWSLETTER_CONSENT_DISCLAIMER
      : $fallback;

    return apply_filters('ishf_newsletter_consent_disclaimer', $default);
  }
endif;


//------------------------------------------------------------------------------
//  Include Custom Database Leads Manager & Form Handlers
//------------------------------------------------------------------------------
require_once __DIR__ . '/includes/post-meta-clipping.php';
require_once __DIR__ . '/includes/seo.php';
require_once __DIR__ . '/includes/programme.php';
require_once __DIR__ . '/includes/uikit-walker.php';
require_once __DIR__ . '/includes/uikit-menus.php';

// Top navbar links collapse into the off-canvas menu below 960px (UIkit @m), matching the burger button.
// The helper functions in uikit-menus.php hard-code 'uk-visible@s', so the breakpoint is adjusted here.
add_filter('wp_nav_menu_args', function ($args) {
  if (isset($args['theme_location']) && $args['theme_location'] === 'top-navbar-right' && !empty($args['menu_class'])) {
    $args['menu_class'] = str_replace('uk-visible@s', 'uk-visible@m', $args['menu_class']);
  }
  return $args;
});
require_once __DIR__ . '/includes/leads-manager.php';
require_once __DIR__ . '/includes/form-handlers.php';


//------------------------------------------------------------------------------
//  Adding CPT / Custom Post Types (switches: SITE_USE_* constants above)
//------------------------------------------------------------------------------
if (SITE_USE_ATTENDANTS)
  require_once get_theme_file_path('includes/cpt/attendants.php');
if (SITE_USE_FAQS)
  require_once get_theme_file_path('includes/cpt/faqs.php');
if (SITE_USE_SEMINARS)
  require_once get_theme_file_path('includes/cpt/seminars.php');
if (SITE_USE_SPONSORS)
  require_once get_theme_file_path('includes/cpt/sponsors.php');
require_once get_theme_file_path('includes/cpt/functions.php'); // custom fields engine (meta boxes ishf_cf_*)


//------------------------------------------------------------------------------
//  Custom BLOCKS (blocks/ishf-*): each folder has functions.php (register + render),
//  index.js (editor) and style.css. 'ishf-example' is a copy-paste template and is not loaded.
//------------------------------------------------------------------------------
function ishf_get_block_folders()
{
  $folders = glob(get_template_directory() . '/blocks/ishf-*', GLOB_ONLYDIR);
  $folders = is_array($folders) ? $folders : array();
  return array_values(array_filter($folders, function ($folder) {
    return basename($folder) !== 'ishf-example';
  }));
}

foreach (ishf_get_block_folders() as $ishf_block_folder):
  if (file_exists($ishf_block_folder . '/functions.php')):
    require_once $ishf_block_folder . '/functions.php';
  endif;
endforeach;
unset($ishf_block_folder);

if (!function_exists('ishf_enqueue_block_assets')):
  add_action('enqueue_block_editor_assets', 'ishf_enqueue_block_assets');
  add_action('enqueue_block_assets', 'ishf_enqueue_block_assets');
  function ishf_enqueue_block_assets()
  {
    $deps = array('wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n', 'wp-components', 'wp-data', 'wp-api-fetch', 'wp-block-editor');
    foreach (ishf_get_block_folders() as $folder):
      $name = basename($folder);
      $uri  = get_template_directory_uri() . '/blocks/' . $name;
      // Editor script only in the block editor; stylesheet on both editor and front end
      if (file_exists($folder . '/index.js') && is_admin()):
        wp_enqueue_script($name, $uri . '/index.js', $deps, filemtime($folder . '/index.js'), true);
      endif;
      if (file_exists($folder . '/style.css')):
        wp_enqueue_style($name, $uri . '/style.css', array(), filemtime($folder . '/style.css'));
      endif;
    endforeach;
  }
endif;

if (!function_exists('ishf_block_categories')):
  add_filter('block_categories_all', 'ishf_block_categories', 10, 2);
  function ishf_block_categories($categories, $post)
  {
    return array_merge(array(array('slug' => 'ishf', 'title' => 'ISHF', 'icon' => 'star-filled')), $categories);
  }
endif;


//------------------------------------------------------------------------------
//  Helpers used by includes/meta-tags.php and the block / CPT templates
//------------------------------------------------------------------------------
if (!function_exists('ishf_meta_keywords_strip')):
  function ishf_meta_keywords_strip($text)
  {
    return str_replace(' ,', ',', (string) $text);
  }
endif;

if (!function_exists('ishf_meta_description_strip')):
  function ishf_meta_description_strip($text, $trim)
  {
    $text = strip_tags((string) $text);
    $text = str_replace(' [&hellip;]', ' (...)', $text);
    $text = preg_replace('/\r\n+|\r+|\n+|\t+/i', ' ', $text);
    $text = str_replace('&nbsp;', ' ', $text);
    if ($trim) {
      $text = mb_substr($text, 0, 180) . ' (...)';
    }
    return $text;
  }
endif;

if (!function_exists('ishf_get_post_tags')):
  /** Tags of a post as 'text' (comma-separated names), 'strings' ('a', 'b') or 'object' (WP_Term[]). */
  function ishf_get_post_tags($post_id, $format = 'text', $type = 'all')
  {
    $tags = get_the_tags($post_id);
    if (empty($tags)) {
      return null;
    }
    if ($format === 'object') {
      return $tags;
    }
    $names = wp_list_pluck($tags, 'name');
    if ($format === 'strings') {
      return "'" . implode("', '", $names) . "'";
    }
    return implode(', ', $names);
  }
endif;


//------------------------------------------------------------------------------
//  ICS Calendar Generator Query Variables
//------------------------------------------------------------------------------
if (!function_exists('ishf_ics_query_vars')):
  function ishf_ics_query_vars($vars)
  {
    $vars[] = 'ics_start';
    $vars[] = 'ics_end';
    $vars[] = 'ics_name';
    $vars[] = 'ics_description';
    $vars[] = 'ics_location';
    $vars[] = 'ics_alert';
    $vars[] = 'ics_url';
    return $vars;
  }
  add_filter('query_vars', 'ishf_ics_query_vars');
endif;


//------------------------------------------------------------------------------
//  GET the post groups from POST CONTENT (returns an Array)
//  Reference: base template functions.php
//------------------------------------------------------------------------------
if (!function_exists('ishf_get_post_groups')):
  function ishf_get_post_groups($postID, $rendered = true)
  {
    $groups = array();
    $post = get_post($postID);
    if (!$post)
      return $groups;
    $blocks = parse_blocks($post->post_content);
    foreach ($blocks as $block):
      if ($block['blockName'] === 'core/group'):
        if (!$rendered):
          array_push($groups, $block['innerBlocks']);
        else:
          $content = null;
          foreach ($block['innerBlocks'] as $inner_block):
            $content .= render_block($inner_block);
          endforeach;
          $content = apply_filters('the_content', do_blocks($content));
          array_push($groups, $content);
        endif;
      endif;
    endforeach;
    return $groups;
  }
endif;


//------------------------------------------------------------------------------
//  GET the post headings from POST CONTENT (returns an Array)
//  Reference: base template functions.php
//------------------------------------------------------------------------------
if (!function_exists('ishf_get_post_headings')):
  function ishf_get_post_headings($postID)
  {
    $headings = array();
    $post = get_post($postID);
    if (!$post)
      return $headings;
    $blocks = parse_blocks($post->post_content);
    foreach ($blocks as $block):
      if ($block['blockName'] == 'core/heading'):
        array_push($headings, $block['innerHTML']);
      endif;
    endforeach;
    return $headings;
  }
endif;


//------------------------------------------------------------------------------
//  REMOVE the post headings from POST CONTENT
//  Reference: base template functions.php
//------------------------------------------------------------------------------
if (!function_exists('ishf_remove_post_headings')):
  function ishf_remove_post_headings($postID, $position = -1)
  {
    $content = null;
    $counter = 0;
    $post = get_post($postID);
    if (!$post)
      return '';
    $blocks = parse_blocks($post->post_content);
    foreach ($blocks as $block):
      // -- Remove all headings (-1)
      if ($position === -1):
        if ($block['blockName'] != 'core/heading'):
          $content .= render_block($block);
        endif;
      // -- Remove only one heading ($position)
      else:
        if ($block['blockName'] !== 'core/heading'):
          $content .= render_block($block);
        else:
          if ($counter !== $position):
            $content .= render_block($block);
          endif;
          $counter++;
        endif;
      endif;
    endforeach;
    return $content;
  }
endif;


//------------------------------------------------------------------------------
//  REMOVE specific SHORTCODE from POST CONTENT
//  Reference: base template functions.php
//------------------------------------------------------------------------------
if (!function_exists('ishf_strip_shortcode')):
  function ishf_strip_shortcode($content, $shortcode_tag)
  {
    $matches = null;
    $pos = null;
    preg_match_all('/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER);
    if (!empty($matches)):
      foreach ($matches as $shortcode):
        if ($shortcode_tag === $shortcode[2]):
          $pos = strpos($content, $shortcode[0]);
          if (false !== $pos):
            $content = substr_replace($content, '', $pos, strlen($shortcode[0]));
          endif;
        endif;
      endforeach;
    endif;
    return $content;
  }
endif;


//------------------------------------------------------------------------------
//  GET YouTube SHORTCODES from POST CONTENT
//  Returns IDs or HTML (processed SHORTCODE)
//  Reference: base template functions.php
//------------------------------------------------------------------------------
if (!function_exists('ishf_get_post_youtubes')):
  function ishf_get_post_youtubes($post, $html = true)
  {
    if (!$post = get_post($post))
      return array();

    if (!has_shortcode($post->post_content, 'youtube'))
      return array();

    $youtubes = array();
    $matches = null;
    if (preg_match_all('/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER)):
      foreach ($matches as $shortcode):
        if ('youtube' === $shortcode[2]):
          if ($html):
            $youtubes[] = do_shortcode_tag($shortcode);
          else:
            $atts = shortcode_parse_atts($shortcode[3]);
            if (is_array($atts) && !empty($atts['id'])):
              $youtube_id = trim($atts['id']);
            else:
              $youtube_id = trim(str_replace(array(' id="', '"'), '', $shortcode[3]));
            endif;
            array_push($youtubes, $youtube_id);
          endif;
        endif;
      endforeach;
    endif;
    return apply_filters('ishf_get_post_youtubes', $youtubes, $post);
  }
endif;


//------------------------------------------------------------------------------
//  GET YouTube SHORTCODE ATTRIBUTES from POST CONTENT
//  Returns associative array of attributes from the first [youtube] shortcode
//------------------------------------------------------------------------------
if (!function_exists('ishf_get_post_youtube_atts')):
  function ishf_get_post_youtube_atts($post)
  {
    if (!$post = get_post($post))
      return array();

    if (!has_shortcode($post->post_content, 'youtube'))
      return array();

    $matches = null;
    if (preg_match_all('/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER)):
      foreach ($matches as $shortcode):
        if ('youtube' === $shortcode[2]):
          $atts = shortcode_parse_atts($shortcode[3]);
          return is_array($atts) ? $atts : array();
        endif;
      endforeach;
    endif;
    return array();
  }
endif;


//------------------------------------------------------------------------------
//  GET Brightcove SHORTCODES from POST CONTENT
//  Returns IDs or HTML (processed SHORTCODE)
//  Reference: base template functions.php
//------------------------------------------------------------------------------
if (!function_exists('ishf_get_post_brightcoves')):
  function ishf_get_post_brightcoves($post, $html = true)
  {
    if (!$post = get_post($post))
      return array();

    if (!has_shortcode($post->post_content, 'brightcove'))
      return array();

    $brightcoves = array();
    $matches = null;
    if (preg_match_all('/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER)):
      foreach ($matches as $shortcode):
        if ('brightcove' === $shortcode[2]):
          if ($html):
            $brightcoves[] = do_shortcode_tag($shortcode);
          else:
            $atts = shortcode_parse_atts($shortcode[3]);
            if (is_array($atts) && !empty($atts['id'])):
              $brightcove_id = trim($atts['id']);
            else:
              $brightcove_id = trim(str_replace(array(' id="', '"'), '', $shortcode[3]));
            endif;
            array_push($brightcoves, $brightcove_id);
          endif;
        endif;
      endforeach;
    endif;
    return apply_filters('ishf_get_post_brightcoves', $brightcoves, $post);
  }
endif;


//------------------------------------------------------------------------------
//  Function that adds Referrer Policy to YouTube embeds
//  Reference: base template functions.php
//------------------------------------------------------------------------------
if (!function_exists('ishf_youtube_add_referrerpolicy')):
  function ishf_youtube_add_referrerpolicy($html, $url, $attr, $post_id)
  {
    if (strpos($html, 'youtube.com') !== false || strpos($html, 'youtu.be') !== false):
      if (strpos($html, 'referrerpolicy=') === false):
        $html = str_replace('<iframe', '<iframe referrerpolicy="no-referrer-when-downgrade"', $html);
      endif;
    endif;
    return $html;
  }
  add_filter('embed_oembed_html', 'ishf_youtube_add_referrerpolicy', 10, 4);
  add_filter('video_embed_html', 'ishf_youtube_add_referrerpolicy', 10, 4);
endif;


//------------------------------------------------------------------------------
//  Defining SHORTCODES
//  Reference: base template functions.php
//------------------------------------------------------------------------------

// -- VÍDEO YOUTUBE
if (!function_exists('shortcode_youtube')):
  function shortcode_youtube($attributes)
  {
    $id = !empty($attributes['id']) ? trim($attributes['id']) : '';
    $html = '';
    if (!empty($id)):
      // Clean YouTube ID if a full URL was passed
      if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $id, $match)):
        $id = $match[1];
      endif;

      $title = (!empty($attributes['title'])) ? $attributes['title'] : null;
      $title = (!empty($title)) ? str_replace('{br}', '<br/>', $title) : null;
      $iframe_title = (!empty($title)) ? wp_strip_all_tags($title) : __('YouTube Video', TEXT_DOMAIN);
      $title_size = (!empty($attributes['title_size'])) ? (int) $attributes['title_size'] : 2;
      $title_position = (!empty($attributes['title_position'])) ? $attributes['title_position'] : 'none';
      $no_margin = (!empty($attributes['no_margin'])) ? filter_var($attributes['no_margin'], FILTER_VALIDATE_BOOLEAN) : false;
      $list = (!empty($attributes['list'])) ? 'list=' . rawurlencode($attributes['list']) . '&' : '';

      $html = '<div';
      if (!$no_margin):
        $html .= ' class="uk-margin-medium"';
      endif;
      $html .= '>';
      if ((!empty($title)) && ($title_position === 'top')):
        $html .= '<h' . $title_size . '>' . wp_kses_post($title) . '</h' . $title_size . '>';
      endif;
      $html .= '<iframe width="1280" height="720" src="https://www.youtube.com/embed/' . esc_attr($id) . '?' . $list . 'showinfo=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen data-uk-responsive loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="' . esc_attr($iframe_title) . '"></iframe>';
      if ((!empty($title)) && ($title_position === 'bottom')):
        $html .= '<h' . $title_size . ' class="uk-margin-top">' . wp_kses_post($title) . '</h' . $title_size . '>';
      endif;
      $html .= '</div>';
    endif;
    return $html;
  }
  remove_shortcode('youtube');
  add_shortcode('youtube', 'shortcode_youtube');
endif;


// -- VÍDEO BRIGHTCOVE
if (!function_exists('shortcode_brightcove')):
  function shortcode_brightcove($attributes)
  {
    $id = !empty($attributes['id']) ? trim($attributes['id']) : '';
    $html = '';
    if (!empty($id)):
      $title = (!empty($attributes['title'])) ? $attributes['title'] : null;
      $title = (!empty($title)) ? str_replace('{br}', '<br/>', $title) : null;
      $iframe_title = (!empty($title)) ? wp_strip_all_tags($title) : __('BrightCove Video', TEXT_DOMAIN);
      $title_size = (!empty($attributes['title_size'])) ? (int) $attributes['title_size'] : 2;
      $title_position = (!empty($attributes['title_position'])) ? $attributes['title_position'] : 'none';
      $no_margin = (!empty($attributes['no_margin'])) ? filter_var($attributes['no_margin'], FILTER_VALIDATE_BOOLEAN) : false;

      $html = '<div';
      if (!$no_margin):
        $html .= ' class="uk-margin-medium"';
      endif;
      $html .= '>';
      if ((!empty($title)) && ($title_position === 'top')):
        $html .= '<h' . $title_size . '>' . wp_kses_post($title) . '</h' . $title_size . '>';
      endif;
      $html .= '<iframe src="https://players.brightcove.net/6108484330001/kAtzSWivK_default/index.html?videoId=' . esc_attr($id) . '" allowfullscreen="" allow="encrypted-media" width="1280" height="720" data-uk-responsive loading="lazy" title="' . esc_attr($iframe_title) . '"></iframe>';
      if ((!empty($title)) && ($title_position === 'bottom')):
        $html .= '<h' . $title_size . ' class="uk-margin-top">' . wp_kses_post($title) . '</h' . $title_size . '>';
      endif;
      $html .= '</div>';
    endif;
    return $html;
  }
  remove_shortcode('brightcove');
  add_shortcode('brightcove', 'shortcode_brightcove');
endif;


// -- STREAMING EVENT BLOCK SHORTCODE ([streaming] or [streaming-event])
if (!function_exists('shortcode_streaming_event')):
  function shortcode_streaming_event($attributes)
  {
    $attributes = shortcode_atts(
      array(
        'youtube_id'  => '',
        'mode'        => '',
        'title'       => '',
        'badge'       => '',
        'button_text' => '',
        'autoplay'    => '',
        'auto_fire'   => '',
        'modal_id'    => '',
        'poster'      => '',
        'page_path'   => '',
        'page_id'     => '',
      ),
      $attributes
    );

    // Filter out empty string attributes so streaming.php defaults/page values apply
    $clean_args = array_filter($attributes, function ($v) {
      return $v !== '';
    });

    ob_start();
    get_template_part('includes/sections/streaming', null, $clean_args);
    return ob_get_clean();
  }
  remove_shortcode('streaming');
  add_shortcode('streaming', 'shortcode_streaming_event');
  remove_shortcode('streaming-event');
  add_shortcode('streaming-event', 'shortcode_streaming_event');
endif;