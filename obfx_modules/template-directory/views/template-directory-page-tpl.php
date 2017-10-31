<?php
/**
 * The View for Rendering the Template Directory Main Dashboard Page.
 *
 * @link       https://themeisle.com
 * @since      2.0.0
 *
 * @package    Orbit_Fox_Modules
 * @subpackage Orbit_Fox_Modules/template-directory
 * @codeCoverageIgnore
 */

$customizer = add_query_arg( 'obfx_templates', '', home_url() ); //Define query arg for custom endpoint.
?>

<div class="obfx-template-dir wrap">
    <h1 class="wp-heading-inline">Orbit Fox Template Directory</h1>
    <div class="obfx-template-browser">
        <div class="obfx-template">
            <h2 class="template-name template-header">Hestia About Page</h2>
            <div class="obfx-template-screenshot">
                <img src="https://i0.wp.com/themes.svn.wordpress.org/hestia/1.1.52/screenshot.png" alt="">
            </div>
            <div class="obfx-template-actions">
                <a class="button obfx-preview-template"
                       href="<?php echo esc_url( admin_url() . 'customize.php?url=' . urlencode( $customizer ) ) . '&?return=' . urlencode( trailingslashit( admin_url() ) . 'tools.php?page=obfx_template_dir' ); ?>">Preview</a>
                <a class="button button-primary" href="#">Import</a>
            </div><!-- /.obfx-template-actions -->
        </div><!-- /.obfx-template -->
    </div> <!-- /.obfx-template-browser -->
</div><!-- /.obfx-template-dir -->
<?php
