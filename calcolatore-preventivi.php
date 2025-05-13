<?php
/**
 * Plugin Name: Calcolatore Preventivi
 * Plugin URI: https://example.com/calcolatore-preventivi
 * Description: Un calcolatore di preventivi moderno con impostazioni personalizzabili per IVA e tassazione.
 * Version: 1.0
 * Author: Il tuo nome
 * Author URI: https://example.com
 * Text Domain: calcolatore-preventivi
 */

// Evita l'accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

// Definizione delle costanti del plugin
define('PREV_CALC_DIR', plugin_dir_path(__FILE__));
define('PREV_CALC_URL', plugin_dir_url(__FILE__));
define('PREV_CALC_VERSION', '1.0.0');

// Include i file necessari
require_once PREV_CALC_DIR . 'includes/admin/settings.php';    // Impostazioni admin
require_once PREV_CALC_DIR . 'includes/frontend/shortcode.php'; // Shortcode
require_once PREV_CALC_DIR . 'includes/functions.php';          // Funzioni generali

// Registra attivazione e disattivazione
register_activation_hook(__FILE__, 'prev_calc_activate');
register_deactivation_hook(__FILE__, 'prev_calc_deactivate');

/**
 * Funzione di attivazione del plugin
 */
function prev_calc_activate() {
    // Crea la struttura delle cartelle
    prev_calc_create_folders();
    
    // Imposta i valori predefiniti
    if (!get_option('prev_calc_iva')) {
        update_option('prev_calc_iva', 22);
    }
    
    if (!get_option('prev_calc_tassazione')) {
        update_option('prev_calc_tassazione', 34);
    }
    
    // Svuota i rewrite rules
    flush_rewrite_rules();
}

/**
 * Funzione di disattivazione del plugin
 */
function prev_calc_deactivate() {
    // Svuota i rewrite rules
    flush_rewrite_rules();
}

/**
 * Registra script e stili
 */
function prev_calc_enqueue_scripts() {
    if (!is_admin()) {
        // CSS
        wp_register_style('prev-calc-styles', PREV_CALC_URL . 'assets/css/style.css', array(), PREV_CALC_VERSION);
        wp_enqueue_style('prev-calc-styles');
        
        // External Script - Chart.js
        wp_register_script('chart-js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js', array('jquery'), '3.9.1', true);
        
        // JS
        wp_register_script('prev-calc-script', PREV_CALC_URL . 'assets/js/script.js', array('jquery', 'chart-js'), PREV_CALC_VERSION, true);
        
        // Localizzazione dei dati
        wp_localize_script('prev-calc-script', 'prevCalcData', array(
            'iva' => get_option('prev_calc_iva', 22),
            'tassazione' => get_option('prev_calc_tassazione', 34),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('prev_calc_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'prev_calc_enqueue_scripts');

/**
 * Carica script e stili quando viene utilizzato lo shortcode
 */
function prev_calc_load_scripts_conditionally() {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'calcolatore_preventivi')) {
        wp_enqueue_style('prev-calc-styles');
        wp_enqueue_script('chart-js');
        wp_enqueue_script('prev-calc-script');
    }
}
add_action('wp_enqueue_scripts', 'prev_calc_load_scripts_conditionally', 20);