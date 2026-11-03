<?php
//----------------------------------------------------------------------------------
//  Block: SECTION LABEL (section heading with the ISHF yellow accent)
//  Variables (set before including):
//    $section_name  string  Heading text (required)
//    $heading_size  int     1-6, default 2
//    $text_align    string  'left' (default), 'center', 'right', or UIkit responsive
//                           forms such as 'right@s' / 'center@m'
//  Base Theme: ISHF 2027 Lisbon
//----------------------------------------------------------------------------------

$ishf_label_name  = isset($section_name) ? (string) $section_name : '';
$ishf_label_size  = (isset($heading_size) && (int) $heading_size >= 1 && (int) $heading_size <= 6) ? (int) $heading_size : 2;
$ishf_label_align = (isset($text_align) && preg_match('/^(left|center|right)(@[smlx]l?)?$/', (string) $text_align)) ? (string) $text_align : 'left';

if ($ishf_label_name !== ''):
?>
<h<?php echo $ishf_label_size; ?> class="section-label uk-h<?php echo $ishf_label_size; ?> uk-text-<?php echo esc_attr($ishf_label_align); ?> uk-margin-medium-bottom"><?php echo esc_html($ishf_label_name); ?></h<?php echo $ishf_label_size; ?>>
<?php
endif;

// -- Reset so the next include starts clean
unset($section_name, $heading_size, $text_align, $ishf_label_name, $ishf_label_size, $ishf_label_align);
