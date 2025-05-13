<?php
/**
 * Funzioni per le impostazioni del plugin nella dashboard admin
 */

// Evita l'accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Aggiunge la pagina delle impostazioni nel menu admin
 */
function prev_calc_admin_menu() {
    add_options_page(
        'Impostazioni Calcolatore Preventivi',
        'Calcolatore Preventivi',
        'manage_options',
        'calcolatore-preventivi',
        'prev_calc_settings_page'
    );
}
add_action('admin_menu', 'prev_calc_admin_menu');

/**
 * Registra le impostazioni
 */
function prev_calc_register_settings() {
    register_setting('prev_calc_settings_group', 'prev_calc_iva', array(
        'default' => 22,
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    register_setting('prev_calc_settings_group', 'prev_calc_tassazione', array(
        'default' => 34,
        'sanitize_callback' => 'sanitize_text_field',
    ));
}
add_action('admin_init', 'prev_calc_register_settings');

/**
 * Pagina delle impostazioni
 */
function prev_calc_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('prev_calc_settings_group');
            do_settings_sections('prev_calc_settings_group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">IVA (%)</th>
                    <td>
                        <input type="number" min="0" max="100" step="0.1" name="prev_calc_iva" value="<?php echo esc_attr(get_option('prev_calc_iva', 22)); ?>" />
                        <p class="description">Percentuale IVA (valore predefinito: 22%)</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Tassazione (%)</th>
                    <td>
                        <input type="number" min="0" max="100" step="0.1" name="prev_calc_tassazione" value="<?php echo esc_attr(get_option('prev_calc_tassazione', 34)); ?>" />
                        <p class="description">Percentuale di tassazione (valore predefinito: 34%)</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}