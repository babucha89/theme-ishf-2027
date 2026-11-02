<?php
// -----------------------------------------------------------------------------
//  Block: ARTICLE
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

// -- Initializations
$title = get_the_title();
$excerpt = apply_filters('the_excerpt', get_the_excerpt());
$content = apply_filters('the_content', get_the_content());
$image_url = str_replace(BASE_SITE, REDIRECT_SITE, get_the_post_thumbnail_url(get_the_ID(),'image-3x2'));
$image_alt = get_post_meta(get_post_thumbnail_id(get_the_ID()), '_wp_attachment_image_alt', true);
$image_alt = (!empty($image_alt)) ? $image_alt : $title;
$internal_url = str_replace(BASE_SITE, REDIRECT_SITE, get_permalink(get_the_ID()));
$external_url = get_post_meta(get_the_ID(), 'uikit_bs_external_url', true);
$is_external = (!empty($external_url)) ? true : false;
$article_url = ($is_external) ? $external_url : $internal_url;
$date_published = get_the_date('d M Y');

$tags = get_the_tags();
$medialivre_property_name = 'Jornal de Negócios';
if ($tags):
  foreach ($tags as $tag):
     if (($tag->slug == 'jornal-de-negocios') || ($tag->slug == 'correio-da-manha') || ($tag->slug == 'sabado')):
       $medialivre_property_name = $tag->name;
    endif;
  endforeach;
endif;
?>
<a class="uk-inline uk-link-reset" href="<?php echo $article_url; ?>"<?php if ($is_external): ?> target="_blank" rel="noopener"<?php endif; ?>>
  <img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" loading="lazy" />
  <h3 class="uk-margin-top uk-margin-remove-adjacent"><?php echo $title; ?></h3>
  <?php
  // -- Show EXCERPT or CONTENT 
  if (!empty($excerpt)):
    echo $excerpt;
  else:
    echo $content;
  endif;
  ?>

  <?php
  // -- Show META (if has external URL)
  if (!empty($external_url)):
  ?>
  <p class="uk-text-small uk-text-muted"><em>In</em> <?php echo $medialivre_property_name; ?>, <?php echo $date_published; ?></p>
  <?php
  endif;
  ?>
</a>