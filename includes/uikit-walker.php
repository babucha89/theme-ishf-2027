<?php
// -----------------------------------------------------------------------------
//  Classes: WALKER
//  Edited: 3L, 31-MAI-2024
// -----------------------------------------------------------------------------



// -----------------------------------------------------------------------------
// Base Walker com validação de itens agendados
// -----------------------------------------------------------------------------
class UIKit_Cofina_Nav_Walker extends Walker_Nav_Menu {

  /**
   * Verifica se o item do menu (ou o post/página de destino) está agendado para publicação.
   *
   * @param object $item Objeto do item de menu.
   * @return bool True se estiver agendado/não publicado, false caso contrário.
   */
  protected function is_item_scheduled( $item ) {
    if ( empty( $item ) ) {
      return false;
    }

    // 1. Check if the nav_menu_item post itself is scheduled
    if ( ( isset( $item->post_status ) && $item->post_status === 'future' ) || ( ! empty( $item->ID ) && get_post_status( $item->ID ) === 'future' ) ) {
      return true;
    }

    // 2. Only check target post status if this item points explicitly to a single post/page (type === 'post_type')
    if ( isset( $item->type ) && $item->type === 'post_type' ) {
      $target_id = ! empty( $item->object_id ) ? (int) $item->object_id : 0;

      if ( $target_id > 0 ) {
        $status = get_post_status( $target_id );
        if ( $status === 'future' || $status === 'draft' || $status === 'pending' || $status === false ) {
          return true;
        }

        $target_post = get_post( $target_id );
        if ( $target_post ) {
          if ( $target_post->post_status === 'future' ) {
            return true;
          }
          if ( ! empty( $target_post->post_date_gmt ) && $target_post->post_date_gmt !== '0000-00-00 00:00:00' ) {
            if ( strtotime( $target_post->post_date_gmt . ' +0000' ) > time() && $target_post->post_status !== 'publish' ) {
              return true;
            }
          }
        }
      }
    }

    return false;
  }

  /**
   * Sobrescreve display_element para omitir elementos agendados e os seus filhos.
   */
  public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
    if ( ! $element ) {
      return;
    }

    if ( $this->is_item_scheduled( $element ) ) {
      return;
    }

    parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
  }
}

// -----------------------------------------------------------------------------
// Walker para UI Kit Navbar
// -----------------------------------------------------------------------------
class UIKit_Cofina_Navbar_Walker extends UIKit_Cofina_Nav_Walker {
  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    global $wp_query;

    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $class_names = $value = '';

    $classes = empty($item->classes) ? array() : (array) $item->classes;
    $classes[] = 'menu-item-id-'.$item->ID;

    $parts = explode('/', $item->url);
    if (count($parts) > 2):
      $menu_slug = $parts[count($parts)-2];
      $classes[] = 'menu-item-slug-'.$menu_slug;
    endif;

    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes),$item,$args));
    $class_names = strlen($class_names) ? ' class="'.esc_attr($class_names).'"' : '';

    $id = apply_filters('nav_menu_item_id','menu-item-'.$item->ID,$item,$args,$id);
    $id = strlen($id) ? ' id="'.esc_attr($id).'"' : '';

    $class_names = str_replace('menu-item-has-children','uk-parent',$class_names);
    $class_names = str_replace('current-menu-item','uk-active',$class_names);

    // Adding Separator / Divider
    if (($item->title === '-') || ($item->title === '{separator}') || ($item->title === '{divider}')):
      $output .= '<li class="uk-nav-divider">';  
    else:      
      // Primeiro OUTPUT (li)
      $output .= $indent . '<li'. $id . $value . $class_names .'>';

      $attributes  = !empty($item->attr_title) ? ' title="'  .esc_attr($item->attr_title) .'"' : '';
      $attributes .= !empty($item->target)     ? ' target="' .esc_attr($item->target)     .'"' : '';
      $attributes .= !empty($item->xfn)        ? ' rel="'    .esc_attr($item->xfn)        .'"' : '';
      $attributes .= !empty($item->url)        ? ' href="'   .esc_attr($item->url)        .'"' : '';

      $item_output = $args->before;
      $item_output .= '<a'. $attributes .'>';
      $item_output .= $args->link_before.apply_filters('the_title',$item->title, $item->ID).$args->link_after;
      if ($args->walker->has_children) $item_output .= ' <span uk-icon="icon: chevron-down"></span>';
      $item_output .= '</a>';
      $item_output .= $args->after;

      // Segundo OUTPUT (a)
      $output .= apply_filters('walker_nav_menu_start_el',$item_output,$item,$depth,$args,$id);
    endif;

    if (defined('REDIRECT_SITE')):
      $output = str_replace(BASE_SITE, REDIRECT_SITE, $output);
    endif;
  }

  function start_lvl( &$output, $depth = 0, $args = array() ) {
    $output .= "\n<div class=\"uk-navbar-dropdown\">\n";
    $output .= "\n<ul class=\"uk-nav uk-navbar-dropdown-nav\" >\n";
  }
}

//------------------------------------------------------------------------------
// Walker para UI Kit Offcanvas
//------------------------------------------------------------------------------
class UIKit_Cofina_Offcanvas_Walker extends UIKit_Cofina_Nav_Walker {

  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    global $wp_query;

    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $classes = empty($item->classes) ? array() : (array) $item->classes;
    $class_names = '';
    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    if (!empty($class_names)):
      $class_names = ' class="'.esc_attr($class_names).'"';
      $class_names = str_replace('menu-item-has-children','uk-parent',$class_names);
      $class_names = str_replace('current-menu-item','uk-active',$class_names);
    endif;

    $id = apply_filters('nav_menu_item_id','menu-item-'.$item->ID,$item,$args,$id);
    $id = strlen($id) ? ' id="'.esc_attr($id).'"' : '';

    // Adding Separator / Divider
    if (($item->title === '-') || ($item->title === '{separator}') || ($item->title === '{divider}')):
      $output .= '<li class="uk-nav-divider">';  
    else:
      // Primeiro OUTPUT (li)
      $output .= $indent . '<li'. $class_names .'>';

      $attributes  = !empty($item->attr_title) ? ' title="'  .esc_attr($item->attr_title) .'"' : '';
      $attributes .= !empty($item->target)     ? ' target="' .esc_attr($item->target)     .'"' : '';
      $attributes .= !empty($item->xfn)        ? ' rel="'    .esc_attr($item->xfn)        .'"' : '';
      $attributes .= !empty($item->url)        ? ' href="'   .esc_attr($item->url)        .'"' : ' href="#"';

      $item_output = $args->before;
      $item_output .= '<a'. $attributes .'>';
      $item_output .= $args->link_before.apply_filters('the_title',$item->title, $item->ID).$args->link_after;
      $item_output .= '</a>';
      $item_output .= $args->after;

      // Segundo OUTPUT (a)
      $output .= apply_filters('walker_nav_menu_start_el',$item_output,$item,$depth,$args,$id);
    endif;

    if (defined('REDIRECT_SITE')):
      $output = str_replace(BASE_SITE, REDIRECT_SITE, $output);
    endif;
  }

  function start_lvl( &$output, $depth = 0, $args = array() ) {
    $output .= "\n<ul class=\"uk-nav-sub\">\n";
  }

}

//------------------------------------------------------------------------------
// Walker para UI Kit Footer Nav / List
//------------------------------------------------------------------------------
class UIKit_Cofina_FooterNav_Walker extends UIKit_Cofina_Nav_Walker {

  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    global $wp_query;

    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $classes = empty($item->classes) ? array() : (array) $item->classes;
    $class_names = '';
    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    if (!empty($class_names)):
      $class_names = ' class="'.esc_attr($class_names).'"';
      $class_names = str_replace('menu-item-has-children','uk-parent',$class_names);
      $class_names = str_replace('current-menu-item','',$class_names);
    endif;

    $id = apply_filters('nav_menu_item_id','menu-item-'.$item->ID,$item,$args,$id);
    $id = strlen($id) ? ' id="'.esc_attr($id).'"' : '';

    // Adding Separator / Divider
    if (($item->title === '-') || ($item->title === '{separator}') || ($item->title === '{divider}')):
      $output .= '<li class="uk-nav-divider">';  
    else:
      // Primeiro OUTPUT (li)
      $output .= $indent . '<li'. $class_names .'>';

      $attributes  = !empty($item->attr_title) ? ' title="'  .esc_attr($item->attr_title) .'"' : '';
      $attributes .= !empty($item->target)     ? ' target="' .esc_attr($item->target)     .'"' : '';
      $attributes .= !empty($item->xfn)        ? ' rel="'    .esc_attr($item->xfn)        .'"' : '';
      $attributes .= !empty($item->url)        ? ' href="'   .esc_attr($item->url)        .'"' : ' href="#"';

      $item_output = $args->before;
      $item_output .= '<a'. $attributes .'>';
      $item_output .= $args->link_before.apply_filters('the_title',$item->title, $item->ID).$args->link_after;
      $item_output .= '</a>';
      $item_output .= $args->after;

      // Segundo OUTPUT (a)
      $output .= apply_filters('walker_nav_menu_start_el',$item_output,$item,$depth,$args,$id);
    endif;

    if (defined('REDIRECT_SITE')):
      $output = str_replace(BASE_SITE, REDIRECT_SITE, $output);
    endif;
  }

  function start_lvl( &$output, $depth = 0, $args = array() ) {
    $output .= "\n<ul class=\"uk-list uk-margin-small-top\">\n";
  }

}

// -----------------------------------------------------------------------------
//  Walker para formatação de COMENTÁRIOS (Lista)
// -----------------------------------------------------------------------------
class UIKit_Cofina_Comment_Walker extends Walker_Comment {

  // init classwide variables
  var $tree_type = 'comment';
  var $db_fields = array('parent' => 'comment_parent','id' => 'comment_ID');

  /** CONSTRUCTOR
   * You'll have to use this if you plan to get to the top of the comments list, as
   * start_lvl() only goes as high as 1 deep nested comments */
  function __construct() {
  ?>
  <ul class="uk-comment-list">
  <?php
  }

  /** START_LVL
   * Starts the list before the CHILD elements are added. Unlike most of the walkers,
   * the start_lvl function means the start of a nested comment. It applies to the first
   * new level under the comments that are not replies. Also, it appear that, by default,
   * WordPress just echos the walk instead of passing it to &$output properly. Go figure.  */
  function start_lvl(&$output, $depth = 0, $args = array()) {
      $GLOBALS['comment_depth'] = $depth + 1;
  ?>
  <ul>
  <?php
  }

  /* Ends the children list of after the elements are added. */
  function end_lvl(&$output, $depth = 0, $args = array()) {
      $GLOBALS['comment_depth'] = $depth + 1;
  ?>
  </ul>
  <?php
  }

  /** START_EL */
  function start_el(&$output, $comment, $depth = 0, $args = array(), $id = 0) {
      $depth++;
      $GLOBALS['comment_depth'] = $depth;
      $GLOBALS['comment'] = $comment;
      $parent_class = (empty($args['has_children']) ? '' : 'parent');
  ?>
    <li id="comment-<?php comment_ID() ?>">
      <article id="comment-<?php comment_ID() ?>" class="uk-comment">
        <header class="uk-comment-header">
          <?php echo ($args['avatar_size'] != 0 ? get_avatar($comment,$args['avatar_size'],null,null,array('class' => array('uk-comment-avatar'))):''); ?>
          <h4 class="uk-comment-title"><?php echo get_comment_author_link(); ?></h4>
          <div class="uk-comment-meta">
            <a href="<?php echo htmlspecialchars(get_comment_link(get_comment_ID())); ?>"><?php comment_date(); ?><?php _e(' at ',TEXT_DOMAIN); ?><?php comment_time(); ?></a>
            <?php edit_comment_link('Edit'); ?>
          </div>
        </header>
        <div class="uk-comment-body">
          <?php
          if (!$comment->comment_approved) echo '<em class="comment-awaiting-moderation">Your comment is awaiting moderation.</em>';
          else comment_text();
          ?>
          <?php comment_reply_link(array_merge($args, array('add_below'=>$add_below,'depth'=>$depth,'max_depth'=>$args['max_depth']))); ?>
        </div>
      </article>
  <?php
  }

  function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>
    </li>
  <?php
  }

  /** DESTRUCTOR
   * I just using thisclass since we needed to use the constructor to reach the top
   * of the comments list, just seems to balance out :) */
  function __destruct() {
  ?>
  </ul>
  <?php
  }
}
?>