<?php
/**
 * Funzioni generali del plugin
 */

// Evita l'accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Crea la struttura delle cartelle e dei file necessari
 */
function prev_calc_create_folders() {
    // Directory principale
    $dirs = array(
        PREV_CALC_DIR . 'assets',
        PREV_CALC_DIR . 'assets/css',
        PREV_CALC_DIR . 'assets/js',
        PREV_CALC_DIR . 'includes',
        PREV_CALC_DIR . 'includes/admin',
        PREV_CALC_DIR . 'includes/frontend'
    );
    
    // Crea le cartelle se non esistono
    foreach ($dirs as $dir) {
        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }
    }
    
    // Crea i file CSS e JS se non esistono
    prev_calc_create_css_file();
    prev_calc_create_js_file();
}

/**
 * Crea il file CSS se non esiste
 */
function prev_calc_create_css_file() {
    $css_file = PREV_CALC_DIR . 'assets/css/style.css';
    
    if (!file_exists($css_file)) {
        $css_content = file_get_contents(PREV_CALC_DIR . 'assets/css/style-template.css');
        if (!$css_content) {
            // Se il template non esiste, usa un contenuto predefinito
            $css_content = '
.prev-calc-container {
    max-width: 900px;
    margin: 0 auto;
    font-family: "Poppins", "Montserrat", sans-serif;
}

/* Form Style */
.prev-calc-form {
    background: linear-gradient(135deg, #6e8efb 0%, #a777e3 100%);
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    margin-bottom: 40px;
    color: #fff;
    transform: translateY(0);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.prev-calc-form:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.prev-calc-form h3 {
    margin-top: 0;
    color: #fff;
    font-size: 28px;
    font-weight: 700;
    border-bottom: 3px solid rgba(255, 255, 255, 0.3);
    padding-bottom: 12px;
    margin-bottom: 25px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.prev-calc-form label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #fff;
    font-size: 17px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.prev-calc-form input[type="text"],
.prev-calc-form input[type="number"] {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 8px;
    margin-bottom: 25px;
    font-size: 17px;
    transition: all 0.3s;
    background-color: rgba(255, 255, 255, 0.9);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.prev-calc-form input[type="text"]:focus,
.prev-calc-form input[type="number"]:focus {
    background-color: #fff;
    outline: none;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.prev-calc-submit {
    background: linear-gradient(to right, #00c6ff, #0072ff);
    color: white;
    border: none;
    padding: 15px 30px;
    font-size: 18px;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s;
    width: 100%;
    font-weight: 700;
    letter-spacing: 1px;
    box-shadow: 0 10px 20px rgba(0, 114, 255, 0.3);
    text-transform: uppercase;
}

.prev-calc-submit:hover {
    background: linear-gradient(to right, #00b4f0, #0063db);
    box-shadow: 0 15px 25px rgba(0, 114, 255, 0.4);
    transform: translateY(-3px);
}

.prev-calc-submit:active {
    transform: translateY(1px);
    box-shadow: 0 5px 15px rgba(0, 114, 255, 0.4);
}

/* Results Style */
.prev-calc-results {
    display: none;
    background: #fff;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.prev-calc-results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.prev-calc-results-header h3 {
    margin: 0;
    font-size: 30px;
    color: #333;
    font-weight: 700;
    position: relative;
    padding-bottom: 0;
    display: inline-block;
}

.prev-calc-results-header h3:after {
    content: "";
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 4px;
    background: linear-gradient(to right, #6e8efb, #a777e3);
    border-radius: 2px;
}

.prev-calc-results-header p {
    font-size: 16px;
    color: #777;
}

/* Chart Styles */
.prev-calc-chart-container {
    margin: 40px 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #f8f9fc;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}

.prev-calc-chart-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.prev-calc-chart {
    width: 100%;
    max-width: 450px;
    height: 450px;
    margin-bottom: 30px;
}

.prev-calc-chart-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 20px;
    text-align: center;
    color: #333;
    position: relative;
    display: inline-block;
}

.prev-calc-chart-title:after {
    content: "";
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(to right, #6e8efb, #a777e3);
    border-radius: 2px;
}

.prev-calc-chart-legend {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
    margin-top: 15px;
}

.prev-calc-chart-legend-item {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.prev-calc-chart-legend-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.prev-calc-chart-legend-color {
    width: 20px;
    height: 20px;
    margin-right: 10px;
    border-radius: 4px;
}

/* Table Styles */
.prev-calc-results table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    overflow: hidden;
}

.prev-calc-results table th,
.prev-calc-results table td {
    padding: 15px 20px;
    text-align: left;
}

.prev-calc-results table th {
    font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, #6e8efb 0%, #a777e3 100%);
    font-size: 18px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.prev-calc-results table tr:nth-child(even) {
    background-color: #f8f9fc;
}

.prev-calc-results table tr:nth-child(odd) {
    background-color: #fff;
}

.prev-calc-results table tr:hover {
    background-color: #f0f4ff;
}

.prev-calc-results table td:first-child {
    font-weight: 600;
    color: #555;
}

.prev-calc-results table td:last-child {
    color: #333;
    font-weight: 600;
}

.prev-calc-results table tr.total-row {
    font-weight: 700;
    font-size: 20px;
}

.prev-calc-results table tr.total-row td {
    border-top: 3px solid #a777e3;
    color: #6e8efb;
    background-color: #f0f4ff;
    padding-top: 20px;
    padding-bottom: 20px;
}

/* Print Button */
.prev-calc-print-btn {
    background: linear-gradient(to right, #11998e, #38ef7d);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 50px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 700;
    margin-top: 20px;
    transition: all 0.3s;
    box-shadow: 0 10px 20px rgba(56, 239, 125, 0.3);
    display: inline-block;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.prev-calc-print-btn:hover {
    background: linear-gradient(to right, #0e8a7f, #30d56d);
    box-shadow: 0 15px 25px rgba(56, 239, 125, 0.4);
    transform: translateY(-3px);
}

.prev-calc-print-btn:active {
    transform: translateY(1px);
    box-shadow: 0 5px 15px rgba(56, 239, 125, 0.4);
}

/* Print Layout */
@media print {
    body * {
        visibility: hidden;
    }
    .prev-calc-results, .prev-calc-results * {
        visibility: visible;
    }
    .prev-calc-results {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
        padding: 0;
        background: white;
    }
    .prev-calc-print-btn {
        display: none;
    }
    .prev-calc-chart-container {
        box-shadow: none;
        margin: 20px 0;
        padding: 15px;
    }
    .prev-calc-results table {
        box-shadow: none;
    }
    .prev-calc-chart-legend-item {
        box-shadow: none;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .prev-calc-form {
        padding: 25px;
    }
    .prev-calc-results {
        padding: 25px;
    }
    .prev-calc-results-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .prev-calc-results-header p {
        margin-top: 10px;
    }
    .prev-calc-results table th,
    .prev-calc-results table td {
        padding: 12px 15px;
    }
    .prev-calc-chart {
        height: 350px;
    }
}
';
        }
        file_put_contents($css_file, $css_content);
    }
}

/**
 * Crea il file JS se non esiste
 */
function prev_calc_create_js_file() {
    $js_file = PREV_CALC_DIR . 'assets/js/script.js';
    
    if (!file_exists($js_file)) {
        $js_content = file_get_contents(PREV_CALC_DIR . 'assets/js/script-template.js');
        if (!$js_content) {
            // Se il template non esiste, usa un contenuto predefinito
            $js_content = '
jQuery(document).ready(function($) {
    const form = $("#prev-calc-form");
    const resultsDiv = $("#prev-calc-results");
    let pieChart = null;
    
    form.on("submit", function(e) {
        e.preventDefault();
        
        // Ottieni i valori dal form
        const nomePreventivo = $("#nome-preventivo").val() || "Preventivo";
        const vendita = parseFloat($("#vendita").val()) || 0;
        const costiScaricabili = parseFloat($("#costi-scaricabili").val()) || 0;
        const costiNonScaricabili = parseFloat($("#costi-non-scaricabili").val()) || 0;
        const ricarico = parseFloat($("#ricarico").val()) || 0;
        
        // Ottieni le percentuali dalle impostazioni
        const iva = parseFloat(prevCalcData.iva) || 22;
        const tassazione = parseFloat(prevCalcData.tassazione) || 34;
        
        // Esegui i calcoli
        const totale = vendita;
        const totaleImponibile = totale / (1 + (iva / 100));
        const totaleIva = totale - totaleImponibile;
        
        const totaleCosti = costiScaricabili + costiNonScaricabili;
        const utile = totaleImponibile - totaleCosti;
        const imposte = utile * (tassazione / 100);
        const guadagnoNetto = utile - imposte;
        
        // Calcolo del ricarico
        const speseTotali = totaleCosti + imposte;
        const ricaricoEuro = (ricarico / 100) * speseTotali;
        const ricaricoTotale = speseTotali + ricaricoEuro;
        
        // Formattazione numeri con separatore di migliaia e 2 decimali
        const formatNumber = (num) => {
            return num.toLocaleString("it-IT", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        };
        
        // Percentuale di guadagno sul totale
        const percentualeGuadagno = (guadagnoNetto / totale) * 100;
        
        // Aggiorna il titolo del report
        $("#report-title").text(nomePreventivo);
        
        // Aggiorna la tabella dei risultati
        $("#result-vendita").text(formatNumber(vendita) + " €");
        $("#result-imponibile").text(formatNumber(totaleImponibile) + " €");
        $("#result-iva").text(formatNumber(totaleIva) + " €");
        $("#result-iva-percent").text(iva + "%");
        
        $("#result-costi-scaricabili").text(formatNumber(costiScaricabili) + " €");
        $("#result-costi-non-scaricabili").text(formatNumber(costiNonScaricabili) + " €");
        $("#result-totale-costi").text(formatNumber(totaleCosti) + " €");
        
        $("#result-utile").text(formatNumber(utile) + " €");
        $("#result-imposte").text(formatNumber(imposte) + " €");
        $("#result-imposte-percent").text(tassazione + "%");
        $("#result-guadagno-netto").text(formatNumber(guadagnoNetto) + " €");
        $("#result-percentuale-guadagno").text(formatNumber(percentualeGuadagno) + "%");
        
        // Aggiorna i campi del ricarico
        $("#result-ricarico-percentuale").text(ricarico + "%");
        $("#result-ricarico-euro").text(formatNumber(ricaricoEuro) + " €");
        $("#result-ricarico-totale").text(formatNumber(ricaricoTotale) + " €");
        
        // Aggiorna la data
        const now = new Date();
        const options = { 
            year: "numeric", 
            month: "long", 
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit"
        };
        $("#report-date").text(now.toLocaleDateString("it-IT", options));
        
        // Crea o aggiorna il grafico a torta
        createPieChart(totaleCosti, imposte, guadagnoNetto);
        
        // Mostra i risultati
        resultsDiv.fadeIn();
        
        // Scorri fino ai risultati
        $("html, body").animate({
            scrollTop: resultsDiv.offset().top - 50
        }, 500);
    });
    
    // Funzione per creare il grafico a torta
    function createPieChart(costi, imposte, guadagno) {
        const ctx = document.getElementById("pieChart").getContext("2d");
        
        // Distruggi il grafico esistente se presente
        if (pieChart) {
            pieChart.destroy();
        }
        
        // Dati per il grafico
        const data = {
            labels: ["Costi", "Imposte", "Guadagno Netto"],
            datasets: [{
                data: [costi, imposte, guadagno],
                backgroundColor: [
                    "#3498db",  // Blu per i costi
                    "#e74c3c",  // Rosso per le imposte
                    "#2ecc71"   // Verde per il guadagno
                ],
                borderColor: "#fff",
                borderWidth: 2
            }]
        };
        
        // Configurazione del grafico
        const config = {
            type: "pie",
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || "";
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                
                                return `${label}: ${value.toLocaleString("it-IT", {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })} € (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        };
        
        // Crea il grafico
        pieChart = new Chart(ctx, config);
        
        // Aggiorna la legenda personalizzata
        updateChartLegend(data.labels, data.datasets[0].backgroundColor, data.datasets[0].data);
    }
    
    // Funzione per aggiornare la legenda personalizzata
    function updateChartLegend(labels, colors, values) {
        const legendContainer = $("#pieChartLegend");
        legendContainer.empty();
        
        const total = values.reduce((a, b) => a + b, 0);
        
        labels.forEach((label, index) => {
            const percentage = ((values[index] / total) * 100).toFixed(1);
            const formattedValue = values[index].toLocaleString("it-IT", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            const legendItem = `
                <div class="prev-calc-chart-legend-item">
                    <div class="prev-calc-chart-legend-color" style="background-color: ${colors[index]}"></div>
                    <div>${label}: ${formattedValue} € (${percentage}%)</div>
                </div>
            `;
            
            legendContainer.append(legendItem);
        });
    }
    
    // Gestione pulsante stampa
    $("#prev-calc-print-btn").on("click", function() {
        window.print();
    });
});';
        }
        file_put_contents($js_file, $js_content);
    }
}