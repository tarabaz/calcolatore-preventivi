<?php
/**
 * Implementazione dello shortcode
 */

// Evita l'accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shortcode del calcolatore
 */
function prev_calc_shortcode() {
    ob_start();
    ?>
    <div class="prev-calc-container">
        <div class="prev-calc-form">
            <h3>Calcolatore Preventivi</h3>
            <form id="prev-calc-form">
                <label for="nome-preventivo">Nome Preventivo</label>
                <input type="text" id="nome-preventivo" name="nome-preventivo" placeholder="Inserisci un nome per questo preventivo">
                
                <label for="vendita">Valore di Vendita al Dettaglio (€)</label>
                <input type="number" id="vendita" name="vendita" step="1" min="0" required>
                
                <label for="costi-scaricabili">Costi Scaricabili (€)</label>
                <input type="number" id="costi-scaricabili" name="costi-scaricabili" step="1" min="0" value="0">
                
                <label for="costi-non-scaricabili">Costi Non Scaricabili (€)</label>
                <input type="number" id="costi-non-scaricabili" name="costi-non-scaricabili" step="1" min="0" value="0">
                
                <button type="submit" class="prev-calc-submit">Calcola Preventivo</button>
            </form>
        </div>
        
        <div id="prev-calc-results" class="prev-calc-results">
<div class="prev-calc-results-header">
                <h3 id="report-titolo"></h3>
    <p>Data: <span id="report-date"></span></p>
</div>
            
<!--            <div class="prev-calc-chart-container">
                <p class="prev-calc-chart-title">Distribuzione Costi, IVA, Imposte e Guadagno</p>
                <div class="prev-calc-chart">
                    <canvas id="pieChart"></canvas>
                </div>
                <div id="pieChartLegend" class="prev-calc-chart-legend"></div> 
            </div>-->
            
            <table>
                <tr>
                    <th colspan="2">Vendita</th>
                </tr>
                <tr>
                    <td>Valore Totale</td>
                    <td id="result-vendita"></td>
                </tr>
                <tr>
                    <td>Imponibile</td>
                    <td id="result-imponibile"></td>
                </tr>
                <tr>
                    <td>IVA (<span id="result-iva-percent"></span>)</td>
                    <td id="result-iva"></td>
                </tr>
                
                <tr>
                    <th colspan="2">Costi</th>
                </tr>
                <tr>
                    <td>Costi Scaricabili</td>
                    <td id="result-costi-scaricabili"></td>
                </tr>
                <tr>
                    <td>Costi Non Scaricabili</td>
                    <td id="result-costi-non-scaricabili"></td>
                </tr>
                <tr>
                    <td>Totale Costi</td>
                    <td id="result-totale-costi"></td>
                </tr>
                
                <tr>
                    <th colspan="2">Risultato</th>
                </tr>
                <tr>
                    <td>Utile Lordo</td>
                    <td id="result-utile"></td>
                </tr>
                <tr>
                    <td>Imposte (<span id="result-imposte-percent"></span>)</td>
                    <td id="result-imposte"></td>
                </tr>
                <tr class="total-row">
                    <td>Guadagno Netto</td>
                    <td id="result-guadagno-netto"></td>
                </tr>
                <tr>
                    <td>Percentuale di Guadagno</td>
                    <td id="result-percentuale-guadagno"></td>
                </tr>
            </table>
            
            <button id="prev-calc-print-btn" class="prev-calc-print-btn">Stampa Report</button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('calcolatore_preventivi', 'prev_calc_shortcode');