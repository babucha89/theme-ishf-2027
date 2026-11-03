<?php
//----------------------------------------------------------------------------------
//  Block: META TAGS
//  Edited: 3L, 20-NOV-2023
//----------------------------------------------------------------------------------

global $post, $meta_title, $meta_keywords, $meta_description;

// -- Pre-validating META TAGS
if (is_home()):
  $meta_title = __('Homepage','uikit-boostsolutions').' | '.SITE_NAME;
  $meta_keywords = uikit_cbs_meta_keywords_strip(__('start page','uikit-boostsolutions').', '.__('start','uikit-boostsolutions').', homepage');
  $meta_description = uikit_cbs_meta_description_strip(SITE_NAME, false);
endif;

if ((is_category()) || (is_tax())):
  $meta_title = single_cat_title('',false).' | '.SITE_NAME;
  $meta_keywords = uikit_cbs_meta_keywords_strip(single_cat_title('',false).', categoria, canal');
  $meta_description = uikit_cbs_meta_description_strip(category_description($cat), false);
  if (empty($meta_description)):
    $meta_description = uikit_cbs_meta_description_strip(single_cat_title('',false).' | '.SITE_NAME, false);
  endif;
endif;

// TODO: add translation strings to following block
if (is_post_type_archive('videocasts')):
  $meta_title = 'Videocast | '.SITE_NAME;
  $meta_keywords = uikit_cbs_meta_keywords_strip('videocast, categoria, canal');
  $meta_description = uikit_cbs_meta_description_strip('Descubra todos os episódios em vídeo | '.SITE_NAME, false);
endif;

// TODO: add translation strings to following block
if (is_post_type_archive('podcasts')):
  $meta_title = 'Podcast | '.SITE_NAME;
  $meta_keywords = uikit_cbs_meta_keywords_strip('podcast, categoria, canal');
  $meta_description = uikit_cbs_meta_description_strip('Descubra todos os episódios | '.SITE_NAME, false);
endif;

if (is_single()):
  $meta_title = get_the_title().' | ';
  if ((is_category()) && (!is_category('1'))):
    $category_name = get_the_category()[0]->name;
    $meta_title .= $category_name.' | ';
  endif;
  $meta_title .= SITE_NAME;
  $post_tags = uikit_cbs_get_post_tags($post->ID, 'text', 'regular');
  $tags_complemento = (!empty($post_tags)) ? ', detalhe' : 'detalhe';
  $meta_keywords = uikit_cbs_meta_keywords_strip($post_tags.$tags_complemento);
  $post = get_post($post->ID);
  $content = (has_excerpt()) ? get_the_excerpt() : get_the_content();
  $meta_description = uikit_cbs_meta_description_strip($content, false);
endif;

// TODO: add translation strings to following block

// TODO: validate string to be presented using cleanup functions (i.e. html_entity_decode), problems detected using > and < signs on text (content)

if (is_page()):
  $meta_title = get_the_title().' | '.SITE_NAME;
  $meta_keywords = uikit_cbs_meta_keywords_strip(wp_title(',', false, 'right').', Página');
  $page_object = get_post($post->ID);
  $meta_description = uikit_cbs_meta_description_strip($page_object->post_title.'. '.$page_object->post_content, false);
endif;
?>