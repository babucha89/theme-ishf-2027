<?php
// -----------------------------------------------------------------------------
//  Block: MENUS
//  Edited: 3L, 22-SET-2023
// -----------------------------------------------------------------------------


add_theme_support('menus');

// -----------------------------------------------------------------------------
// Registar menus (no TEMA)
// -----------------------------------------------------------------------------
register_nav_menus(array(
  'top-navbar-left'             => 'Top Navbar / Left',
  'top-navbar-center'           => 'Top Navbar / Center',
  'top-navbar-right'            => 'Top Navbar / Right',
  'sidenav-menu'                => 'Side Nav',
  'footer-subnav-left-menu'     => 'Footer Subnav / Left',
  'footer-subnav-center-menu'   => 'Footer Subnav / Center',
  'footer-subnav-right-menu'    => 'Footer Subnav / Right',
));


// -----------------------------------------------------------------------------
// Top Navbar (Left)
// -----------------------------------------------------------------------------
if (!function_exists('uikit_top_navbar_left')):
  function uikit_top_navbar_left() {
    wp_nav_menu(array(
      'menu_id' => 'top-navbar-left',                   // menu ID
      'menu' => '',                                     // menu name
      'theme_location' => 'top-navbar-left',            // where it's located in the theme
      'menu_class' => 'uk-navbar-nav uk-visible@s',     // adding custom nav class
      'container' => false,                             // remove nav container
      'container_class' => 'uk-navbar-nav',           	// class of container
      'before' => '',                                   // before each link <a>
      'after' => '',                                    // after each link </a>
      'link_before' => '',                              // before each link text
      'link_after' => '',                               // after each link text
      'depth' => 5,                                     // limit the depth of the nav
      'fallback_cb' => false,                           // fallback function (see below)
      'walker' => new UIKit_Cofina_Navbar_Walker()
    ));
  }
endif;

// -----------------------------------------------------------------------------
// Top Navbar (Center)
// -----------------------------------------------------------------------------
if (!function_exists('uikit_top_navbar_center')):
  function uikit_top_navbar_center() {
    wp_nav_menu(array(
      'menu_id' => 'top-navbar-center',                  // menu ID
      'menu' => '',                                     // menu name
      'theme_location' => 'top-navbar-center',           // where it's located in the theme
      'menu_class' => 'uk-navbar-nav uk-visible@s',     // adding custom nav class
      'container' => false,                             // remove nav container
      'container_class' => '',                          // class of container
      'before' => '',                                   // before each link <a>
      'after' => '',                                    // after each link </a>
      'link_before' => '',                              // before each link text
      'link_after' => '',                               // after each link text
      'depth' => 5,                                     // limit the depth of the nav
      'fallback_cb' => false,                           // fallback function (see below)
      'walker' => new UIKit_Cofina_Navbar_Walker()
    ));
  }
endif;

// -----------------------------------------------------------------------------
// Top Navbar (Right)
// -----------------------------------------------------------------------------
if (!function_exists('uikit_top_navbar_right')):
  function uikit_top_navbar_right() {
    wp_nav_menu(array(
      'menu_id' => 'top-navbar-right',                  // menu ID
      'menu' => '',                                     // menu name
      'theme_location' => 'top-navbar-right',           // where it's located in the theme
      'menu_class' => 'uk-navbar-nav uk-visible@s',     // adding custom nav class
      'container' => false,                             // remove nav container
      'container_class' => '',                          // class of container
      'before' => '',                                   // before each link <a>
      'after' => '',                                    // after each link </a>
      'link_before' => '',                              // before each link text
      'link_after' => '',                               // after each link text
      'depth' => 5,                                     // limit the depth of the nav
      'fallback_cb' => false,                           // fallback function (see below)
      'walker' => new UIKit_Cofina_Navbar_Walker()
    ));
  }
endif;

// -----------------------------------------------------------------------------
// Offcanvas (Left)
// -----------------------------------------------------------------------------
if (!function_exists('uikit_offcanvas_left')):
  function uikit_offcanvas_left() {
    wp_nav_menu(array(
      'menu_id' => 'offcanvas-left',                    // menu ID
      'menu' => '',                                     // menu name
      'theme_location' => 'top-navbar-left',            // where it's located in the theme
      'menu_class' => 'uk-nav uk-nav-default uk-nav-offcanvas uk-nav-parent-icon',        // adding custom nav class
      'container' => false,                             // remove nav container
      'container_class' => '',           				  // class of container
      'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
      'before' => '',                                   // before each link <a>
      'after' => '',                                    // after each link </a>
      'link_before' => '',                              // before each link text
      'link_after' => '',                               // after each link text
      'depth' => 5,                                     // limit the depth of the nav
      'fallback_cb' => false,                           // fallback function (see below)
      'walker' => new UIKit_Cofina_Offcanvas_Walker()
    ));
  }
endif;

// -----------------------------------------------------------------------------
// Offcanvas (Center)
// -----------------------------------------------------------------------------
if (!function_exists('uikit_offcanvas_center')):
  function uikit_offcanvas_center() {
    wp_nav_menu(array(
      'menu_id' => 'offcanvas-center',                  // menu ID
      'menu' => '',                                     // menu name
      'theme_location' => 'top-navbar-center',          // where it's located in the theme
      'menu_class' => 'uk-nav uk-nav-default uk-nav-offcanvas uk-nav-parent-icon',        // adding custom nav class
      'container' => false,                             // remove nav container
      'container_class' => '',                          // class of container
      'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
      'before' => '',                                   // before each link <a>
      'after' => '',                                    // after each link </a>
      'link_before' => '',                              // before each link text
      'link_after' => '',                               // after each link text
      'depth' => 5,                                     // limit the depth of the nav
      'fallback_cb' => false,                           // fallback function (see below)
      'walker' => new UIKit_Cofina_Offcanvas_Walker()
    ));
  }
endif;

// -----------------------------------------------------------------------------
// Offcanvas (Right)
// -----------------------------------------------------------------------------
if (!function_exists('uikit_offcanvas_right')):
  function uikit_offcanvas_right() {
    wp_nav_menu(array(
      'menu_id' => 'offcanvas-right',                   // menu ID
      'menu' => '',                                     // menu name
      'theme_location' => 'top-navbar-right',           // where it's located in the theme
      'menu_class' => 'uk-nav uk-nav-default uk-nav-offcanvas uk-nav-parent-icon',        // adding custom nav class
      'container' => false,                             // remove nav container
      'container_class' => '',                          // class of container
      'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
      'before' => '',                                   // before each link <a>
      'after' => '',                                    // after each link </a>
      'link_before' => '',                              // before each link text
      'link_after' => '',                               // after each link text
      'depth' => 5,                                     // limit the depth of the nav
      'fallback_cb' => false,                           // fallback function (see below)
      'walker' => new UIKit_Cofina_Offcanvas_Walker()
    ));
  }
endif;

// -----------------------------------------------------------------------------
// Side Nav
// -----------------------------------------------------------------------------
if (!function_exists('uikit_side_nav')):
  function uikit_side_nav() {
    wp_nav_menu(array(
      'menu_id' => 'side-nav',                        // menu ID
      'menu' => '',                      	        	  // menu name
      'theme_location' => 'sidenav-menu',    			    // where it's located in the theme
      'menu_class' => 'side-nav',                     // adding custom nav class
      'container_id' => '',                          	// nav container ID
      'container' => false,                          	// remove nav container
      'container_class' => '',     					          // class of container
      'before' => '',                                 // before each link <a>
      'after' => '',                                  // after each link </a>
      'link_before' => '',                            // before each link text
      'link_after' => '',                             // after each link text
      'depth' => 5,                                   // limit the depth of the nav
      'fallback_cb' => false,                         // fallback function (see below)
      'walker' => new UIKit_Cofina_Navbar_Walker()
    ));
  }
endif;

// -----------------------------------------------------------------------------
// Footer Left Subnav
// -----------------------------------------------------------------------------
if (!function_exists('uikit_footer_subnav_left')):
  function uikit_footer_subnav_left() {
    wp_nav_menu(array(
      'menu_id' => 'footer-subnav-left',                      	      // menu ID
      'menu' => '',                                                   // menu name
      'theme_location' => 'footer-subnav-left-menu',                  // where it's located in the theme
      'menu_class' => 'uk-subnav',                                    // adding custom nav class
      'container_id' => '',                          									// nav container ID
      'container' => false,                          									// remove nav container
      'container_class' => '',                                        // class of container
      'before' => '',                                 								// before each link <a>
      'after' => '',                                  								// after each link </a>
      'link_before' => '',                            								// before each link text
      'link_after' => '',                             								// after each link text
      'depth' => 5,                                   								// limit the depth of the nav
      'fallback_cb' => false,                         								// fallback function (see below)
      'walker' => new UIKit_Cofina_FooterNav_Walker()
    ));
  }
endif;

// -----------------------------------------------------------------------------
// Footer Center Subnav
// -----------------------------------------------------------------------------
if (!function_exists('uikit_footer_subnav_center')):
  function uikit_footer_subnav_center() {
    wp_nav_menu(array(
      'menu_id' => 'footer-subnav-center',                      	    // menu ID
      'menu' => '',                      	        									  // menu name
      'theme_location' => 'footer-subnav-center-menu',                // where it's located in the theme
      'menu_class' => 'uk-subnav',                                    // adding custom nav class
      'container_id' => '',                          									// nav container ID
      'container' => false,                          									// remove nav container
      'container_class' => '',                                        // class of container
      'before' => '',                                 								// before each link <a>
      'after' => '',                                  								// after each link </a>
      'link_before' => '',                            								// before each link text
      'link_after' => '',                             								// after each link text
      'depth' => 5,                                   								// limit the depth of the nav
      'fallback_cb' => false,                         								// fallback function (see below)
      'walker' => new UIKit_Cofina_FooterNav_Walker()
    ));
  }
endif;

// -----------------------------------------------------------------------------
// Footer Right Subnav
// -----------------------------------------------------------------------------
if (!function_exists('uikit_footer_subnav_right')):
  function uikit_footer_subnav_right() {
    wp_nav_menu(array(
      'menu_id' => 'footer-subnav-right',                             // menu ID
      'menu' => '',                      	        									  // menu name
      'theme_location' => 'footer-subnav-right-menu',                 // where it's located in the theme
      'menu_class' => 'uk-subnav',                                    // adding custom nav class
      'container_id' => '',                          									// nav container ID
      'container' => false,                          									// remove nav container
      'container_class' => '',     													          // class of container
      'before' => '',                                 								// before each link <a>
      'after' => '',                                  								// after each link </a>
      'link_before' => '',                            								// before each link text
      'link_after' => '',                             								// after each link text
      'depth' => 5,                                   								// limit the depth of the nav
      'fallback_cb' => false,                         								// fallback function (see below)
      'walker' => new UIKit_Cofina_FooterNav_Walker()
    ));
  }
endif;
?>