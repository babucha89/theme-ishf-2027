<?php
//----------------------------------------------------------------------------------
//  Block: VIDEO YOUTUBE (EMBED)
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Reference: base template includes/video-youtube-embed.php
//  Edited: BB, 30-OUT-2026
//----------------------------------------------------------------------------------

// -- Initializations
global $youtube_id, $autoplay, $youtube_title;

// Support both global variables (base-template standard) and $args array
$yt_id    = !empty($args['youtube_id']) ? $args['youtube_id'] : $youtube_id;
$yt_auto  = isset($args['autoplay']) ? (bool) $args['autoplay'] : (bool) $autoplay;
$yt_title = !empty($args['youtube_title']) ? $args['youtube_title'] : $youtube_title;

$autoplay_string = ($yt_auto) ? 'true' : 'false';

// Fallback title if not set (WCAG 2.1 AA iframe title requirement)
$yt_title = isset($yt_title) && !empty($yt_title) ? esc_attr($yt_title) : esc_attr__('YouTube Video', TEXT_DOMAIN);

if (!empty($yt_id)):
?>
<iframe width="1920" height="1080" src="https://www.youtube.com/embed/<?php echo esc_attr($yt_id); ?>" title="<?php echo $yt_title; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen data-uk-responsive data-uk-video="autoplay: <?php echo esc_attr($autoplay_string); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
<?php
endif;

// -- Reset
$youtube_id = null;
$autoplay = null;
$autoplay_string = null;
$youtube_title = null;
?>

