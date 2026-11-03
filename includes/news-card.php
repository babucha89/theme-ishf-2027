<?php
// -----------------------------------------------------------------------------
//  Block: NEWS CARD (used inside The Loop: home news, archive, category, search)
//  Internal article  -> links to single.php, label = first category.
//  Clipping (external URL, see includes/post-meta-clipping.php) -> label "Clipping"/"Cobertura",
//  links to the third-party site in a new tab, shows the publication (post tags), the publication
//  date and, when the language differs from the site's, the "In Portuguese" note.
//  Base Theme: ISHF 2027 Lisbon
// -----------------------------------------------------------------------------
$ishf_clip  = ishf_get_clipping();
$ishf_label = ishf_post_label();
$ishf_href  = $ishf_clip ? $ishf_clip['url'] : get_permalink();
$ishf_lang_attr = ($ishf_clip && $ishf_clip['lang_note']) ? ' lang="' . esc_attr($ishf_clip['lang']) . '"' : '';
?>
<article class="news-card<?php echo $ishf_clip ? ' news-card--clipping' : ''; ?>">
  <div class="news-card-media">
    <?php if (has_post_thumbnail()): ?>
      <?php the_post_thumbnail('image-3x2', array('alt' => '', 'loading' => 'lazy')); ?>
    <?php endif; ?>
    <?php if ($ishf_label): ?>
      <span class="uk-label ishf-label<?php echo $ishf_clip || has_category(array('clipping', 'cobertura')) ? ' ishf-label--clipping' : ''; ?> news-card-label"><?php echo esc_html($ishf_label); ?></span>
    <?php endif; ?>
  </div>

  <h3 class="news-card-title uk-h5">
    <a href="<?php echo esc_url($ishf_href); ?>"<?php echo $ishf_lang_attr; ?><?php echo $ishf_clip ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
      <?php the_title(); ?>
      <?php if ($ishf_clip): ?>
        <span class="uk-visually-hidden">
          (<?php echo esc_html($ishf_clip['lang_note'] ? $ishf_clip['lang_note'] . ', ' : ''); ?><?php _e('opens in a new tab', TEXT_DOMAIN); ?>)
        </span>
      <?php endif; ?>
    </a>
  </h3>

  <?php if ($ishf_clip): ?>
    <ul class="news-card-sources" role="list">
      <?php foreach ($ishf_clip['sources'] as $source): ?>
        <li class="news-card-tag"><?php echo esc_html($source); ?></li>
      <?php endforeach; ?>
      <?php if ($ishf_clip['lang_note']): ?>
        <li class="news-card-tag news-card-tag--lang"><?php echo esc_html($ishf_clip['lang_note']); ?></li>
      <?php endif; ?>
    </ul>
    <p class="news-card-date uk-text-small"><time datetime="<?php echo esc_attr($ishf_clip['date_iso']); ?>"><?php echo esc_html($ishf_clip['date_label']); ?></time></p>
  <?php else: ?>
    <p class="news-card-date uk-text-small"><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time></p>
  <?php endif; ?>
</article>
