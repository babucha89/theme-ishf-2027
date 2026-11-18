<?php
// -----------------------------------------------------------------------------
//  Block: NEWS CARD (used inside The Loop: home news, archive, category, search)
//  Internal article  -> links to single.php, label = first category.
//  Clipping (external URL, see includes/post-meta-clipping.php) -> label = its "Clipping" category
//  (translated by WPML), links to the third-party site in a new tab; date line: "In Público, 17 Nov 2026"
//  (publication = post tags), followed by an "In Portuguese" pill when the article's language differs.
//  Dates use the abbreviated month (ishf_short_date(): "17 Nov 2026").
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
      <span class="uk-label ishf-label<?php echo $ishf_clip || ishf_clipping_category() ? ' ishf-label--clipping' : ''; ?> news-card-label"><?php echo esc_html($ishf_label); ?></span>
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

  <?php if ($ishf_clip):
    // "In Público, 17 Nov 2026" + [In Portuguese] pill: publication(s) from the post tags, short date, language note
    $ishf_date_html = '<time datetime="' . esc_attr($ishf_clip['date_iso']) . '">' . esc_html($ishf_clip['date_short']) . '</time>';
    $ishf_sources   = implode(', ', $ishf_clip['sources']);
  ?>
    <p class="news-card-date uk-text-small">
      <?php if ($ishf_sources !== ''):
        /* translators: 1: publication name(s), e.g. "Público"; 2: date, e.g. "17 Nov 2026" */
        printf(esc_html__('In %1$s, %2$s', TEXT_DOMAIN), '<span class="news-card-source">' . esc_html($ishf_sources) . '</span>', $ishf_date_html);
      else:
        echo $ishf_date_html;
      endif; ?>
      <?php if ($ishf_clip['lang_note']): ?>
        <span class="news-card-tag news-card-tag--lang"><?php echo esc_html($ishf_clip['lang_note']); ?></span>
      <?php endif; ?>
    </p>
  <?php else: ?>
    <p class="news-card-date uk-text-small"><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(ishf_short_date((int) get_post_time('U'))); ?></time></p>
  <?php endif; ?>
</article>
