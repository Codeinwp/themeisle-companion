<?php
/**
 * The View Page for Orbit Fox Modules.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app/views
 * @codeCoverageIgnore
 */

if ( ! isset( $no_modules ) ) {
	$no_modules = true;
}

if ( ! isset( $empty_tpl ) ) {
	$empty_tpl = '';
}

if ( ! isset( $count_modules ) ) {
	$count_modules = 0;
}

if ( ! isset( $tiles ) ) {
	$tiles = '';
}

if ( ! isset( $toasts ) ) {
    $toasts = '';
}

if ( ! isset( $panels ) ) {
	$panels = '';
}
?>
<div id="obfx-wrapper" style="border-top: 4px solid #5764c6;">
	<figure class="avatar avatar-xl" style="padding: .5rem;">
		<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0MDYgNDI0Ljg5Ij48ZGVmcz48c3R5bGU+LmNscy0xe2ZpbGw6I2ZmZjt9PC9zdHlsZT48L2RlZnM+PHRpdGxlPkFzc2V0IDI8L3RpdGxlPjxnIGlkPSJMYXllcl8yIiBkYXRhLW5hbWU9IkxheWVyIDIiPjxnIGlkPSJMYXllcl8xLTIiIGRhdGEtbmFtZT0iTGF5ZXIgMSI+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMzI0LjcyLDE4NC4zNWM3LTEzLjMyLTIuNzktODMuMzItMy04My44N2wtMy4zLTguNzEtOS4yOS44MWMtLjU5LDAtNTkuNjQsMTcuNC02OC43NywyNS41OWExMTUuMTUsMTE1LjE1LDAsMCwwLTc0LjcyLDBDMTU2LjUxLDExMCw5Ny40Niw5Mi42Miw5Ni44Nyw5Mi41NmwtOS4yOS0uODEtMy4zLDguNzJjLS4yMS41NS0xMCw3MC41NS0zLDgzLjg3LTE5LjIyLDI3LjgxLTMwLjI4LDk2LTMwLjI4LDk2TDIwMywzNDNsMTUyLTYyLjU5UzM0My45MywyMTIuMTYsMzI0LjcyLDE4NC4zNVptLTIzLjA3LTY1LjljMy4yOSwxMS42Nyw2LjczLDI5LjIyLDQuNjksNDMuMTdhMTczLDE3MywwLDAsMC0zOS45LTMxLjc4QzI3Ny40OSwxMjMuODMsMjkxLjM5LDEyMC4yNSwzMDEuNjUsMTE4LjQ1Wk0yMDMsMTM2LjU3YzI2LjI5LDAsNTMuODMsMTIuMjIsNzcuMzQsMzQtMjAuMjgsMS44My00Ny42MywzLjc4LTUzLjY5LDIyLjA5LTI3LjE2LDgyLjE2LTE4LjE2LDgyLjE2LTUxLjQ1Ljg3LTcuMTMtMTcuNDItMzMuNTctMTguMTUtNTMuNTUtMTkuMTRDMTQ2LDE1MC4yMywxNzUuMiwxMzYuNTcsMjAzLDEzNi41N1ptLTk4LjY4LTE4LjA5YTE1MC45MiwxNTAuOTIsMCwwLDEsMTYuNzQsMy45Myw5Nyw5NywwLDAsMSwxOC40OCw3LjQ0LDE3MywxNzMsMCwwLDAtMzkuOTEsMzEuOEM5Ny41MywxNDcuNzUsMTAxLDEzMC4zMiwxMDQuMzIsMTE4LjQ5Wk0yMDMsMzE2LjMzLDc2LDI2NGMxLjgtMjAuNiwxMC4yNy00Mi44NiwyMy44Ni02My4yM2E3NCw3NCwwLDAsMSw4OSw1NS41OGgyNy41MkE3NCw3NCwwLDAsMSwzMDIuODEsMTk2YzE1LjU0LDIxLjYzLDI1LjI1LDQ1LjgsMjcuMTksNjhaIi8+PGNpcmNsZSBjbGFzcz0iY2xzLTEiIGN4PSIyNjQuNjIiIGN5PSIyMjkuNjkiIHI9IjEyLjMyIi8+PGNpcmNsZSBjbGFzcz0iY2xzLTEiIGN4PSIxNDEuMzgiIGN5PSIyMjkuNjkiIHI9IjEyLjMyIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMjExLjIyLDI2Mi40NUgxOTQuNzhhOC4yMiw4LjIyLDAsMCwwLTguMjIsOC4yMmMwLDguMjIsMTYuNDMsMTYuNDMsMTYuNDMsMTYuNDNzMTYuNDMtOC4yMiwxNi40My0xNi40M0E4LjIyLDguMjIsMCwwLDAsMjExLjIyLDI2Mi40NVoiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0yMDMsNDA2QzkxLjA3LDQwNiwwLDMxNC45MywwLDIwM1M5MS4wNywwLDIwMywwLDQwNiw5MS4wNyw0MDYsMjAzLDMxNC45Myw0MDYsMjAzLDQwNlptMC0zODJDMTA0LjMsMjQsMjQsMTA0LjMsMjQsMjAzczgwLjMsMTc5LDE3OSwxNzksMTc5LTgwLjMsMTc5LTE3OVMzMDEuNywyNCwyMDMsMjRaIi8+PGVsbGlwc2UgY2xhc3M9ImNscy0xIiBjeD0iMjM4LjI5IiBjeT0iNTguMzEiIHJ4PSIxMC40NSIgcnk9IjQ1LjUiIHRyYW5zZm9ybT0ibWF0cml4KDAuNTQsIC0wLjg0LCAwLjg0LCAwLjU0LCA2MC4xNCwgMjI2Ljk3KSIvPjxyZWN0IGNsYXNzPSJjbHMtMSIgeD0iNjkuNyIgeT0iMzUyLjg5IiB3aWR0aD0iMjY5LjMiIGhlaWdodD0iMjQiIHJ4PSIxMiIgcnk9IjEyIi8+PHJlY3QgY2xhc3M9ImNscy0xIiB4PSI4Mi43IiB5PSIzNzYuODkiIHdpZHRoPSIyNDMuMyIgaGVpZ2h0PSIyNCIgcng9IjEyIiByeT0iMTIiLz48cmVjdCBjbGFzcz0iY2xzLTEiIHg9Ijg5LjciIHk9IjQwMC44OSIgd2lkdGg9IjIyOS4zIiBoZWlnaHQ9IjI0IiByeD0iMTIiIHJ5PSIxMiIvPjwvZz48L2c+PC9zdmc+">
	</figure>
	<h1><?php echo __( 'Orbit Fox Companion', 'obfx' ); ?></h1><span class="powered"> by <b>ThemeIsle</b></span>
</div>
<div id="obfx-wrapper" style="padding: 0; margin-top: 10px; margin-bottom: 5px;">
    <?php
        echo $toasts;
    ?>
</div>
<div id="obfx-wrapper">
	<?php
	if ( $no_modules ) {
		echo $empty_tpl;
	} else {
	?>
	<div class="panel">
		<div class="panel-header text-center">
			<div class="panel-title mt-10"><?php echo __( 'Modules', 'obfx' ); ?></div>
		</div>
		<div class="panel-nav">
			<ul class="tab tab-block">
				<li class="tab-item active">
					<a href="#" class="badge" data-badge="<?php echo $count_modules; ?>" data-initial="0"><?php echo __( 'Modules List', 'themeisle-companion' ); ?></a>
				</li>
				<li class="tab-item">
					<a href="#"><?php echo __( 'Settings', 'themeisle-companion' ); ?></a>
				</li>
			</ul>
		</div>
		<div class="panel-body">
			<?php echo $tiles; ?>
		</div>
		<div class="panel-footer">
			<!-- buttons or inputs -->
		</div>
	</div>
	<?php echo $panels; ?>
	<?php
	}
	?>
</div>
