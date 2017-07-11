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

?>

<?php echo $html_title; ?>
<canvas id="obfxChart" <?php echo $posts_data; ?> <?php echo $comments_data; ?> ></canvas>
<?php echo $html_desc; ?>
<?php echo $html_foot; ?>
