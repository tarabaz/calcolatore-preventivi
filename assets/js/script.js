jQuery(document).ready(function($) {
    const form = $("#prev-calc-form");
    const resultsDiv = $("#prev-calc-results");
    let pieChart = null;
    
    form.on("submit", function(e) {
        e.preventDefault();
        
        // Ottieni i valori dal form
         const titoloPreventivo = $("#nome-preventivo").val() || "Preventivo";
        const vendita = parseFloat($("#vendita").val()) || 0;
        const costiScaricabili = parseFloat($("#costi-scaricabili").val()) || 0;
        const costiNonScaricabili = parseFloat($("#costi-non-scaricabili").val()) || 0;
        
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
       // $("#report-title").text(nomePreventivo);
        $("#report-titolo").text("Report Dettagliato del Preventivo - \"" + titoloPreventivo + "\"");

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
        createPieChart(totaleCosti, totaleIva, imposte, guadagnoNetto);
        
        // Mostra i risultati
        resultsDiv.fadeIn();
        
        // Scorri fino ai risultati
        $("html, body").animate({
            scrollTop: resultsDiv.offset().top - 50
        }, 500);
    });
    
    // Funzione per creare il grafico a torta
    function createPieChart(totaleCosti, totaleIva, imposte, guadagnoNetto) {
        const ctx = document.getElementById("pieChart").getContext("2d");
        
        // Verifica che l'elemento canvas esista
        if (!ctx) {
            console.error("Elemento canvas '#pieChart' non trovato");
            return;
        }
        
        // Distruggi il grafico esistente se presente
        if (pieChart) {
            pieChart.destroy();
        }
        
        // Verifica che tutti i valori siano numeri validi
        if (isNaN(totaleCosti) || isNaN(totaleIva) || isNaN(imposte) || isNaN(guadagnoNetto)) {
            console.error("Valori non validi per il grafico:", { totaleCosti, totaleIva, imposte, guadagnoNetto });
            return;
        }
        
        console.log("Creazione grafico con valori:", { totaleCosti, totaleIva, imposte, guadagnoNetto });
        
        // Dati per il grafico
        const data = {
            labels: ["Costi", "IVA", "Imposte", "Guadagno Netto"],
            datasets: [{
                data: [totaleCosti, totaleIva, imposte, guadagnoNetto],
                backgroundColor: [
                    "#3498db",  // Blu per i costi
                    "#9b59b6",  // Viola per l'IVA
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
});