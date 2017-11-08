<?php
/**
 * The Dashboard Widget View for Stats Module of Orbit Fox.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox_Modules
 * @subpackage Orbit_Fox_Modules/stats/views
 * @codeCoverageIgnore
 */

$html_title = '';
if ( trim( $title ) != '' ) {
	$html_title = '<h3>' . $title . ' <small><i> for ' . $display_year . '</i></small></h3>';
}

$html_desc = '';
if ( trim( $desc ) != '' ) {
	$html_desc = '<hr/><small>' . $desc . '</small>';
}

$posts_data    = '';
$comments_data = '';
$graph_shows   = $this->get_option( 'graph_shows' );
if ( $graph_shows == 0 || $graph_shows == 2 ) {
	$posts_data = 'data-posts="' . json_encode( $posts_count ) . '"';
}
if ( $graph_shows == 1 || $graph_shows == 2 ) {
	$comments_data = 'data-comments="' . json_encode( $comments_count ) . '"';
}

?>

<?php echo $html_title; ?>
<canvas id="obfxChart" <?php echo $posts_data; ?> <?php echo $comments_data; ?> ></canvas>
<?php echo $html_desc; ?>
