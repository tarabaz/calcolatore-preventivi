/**
 * Controlla il caricamento e l'inizializzazione di Chart.js
 */
jQuery(document).ready(function($) {
    // Verifica che Chart.js sia caricato correttamente
    if (typeof Chart === 'undefined') {
        console.error('Chart.js non è stato caricato correttamente');
        
        // Tenta di caricare Chart.js dinamicamente
        $.getScript('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js')
            .done(function() {
                console.log('Chart.js caricato con successo');
                // Verifica che l'elemento canvas esista
                if ($('#pieChart').length) {
                    console.log('Elemento canvas trovato');
                } else {
                    console.error('Elemento canvas non trovato');
                }
            })
            .fail(function() {
                console.error('Impossibile caricare Chart.js dinamicamente');
                // Mostra un messaggio all'utente
                $('.prev-calc-chart-container').html('<div style="color: red; text-align: center;">Errore nel caricamento del grafico. Ricarica la pagina.</div>');
            });
    } else {
        console.log('Chart.js già caricato');
        // Verifica che l'elemento canvas esista
        if ($('#pieChart').length) {
            console.log('Elemento canvas trovato');
        } else {
            console.error('Elemento canvas non trovato');
        }
    }
});