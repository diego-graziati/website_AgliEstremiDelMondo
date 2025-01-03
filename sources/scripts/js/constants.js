// constants.js

// Funzione per caricare le costanti nel localStorage
function loadConstants() {
    return new Promise((resolve, reject) => {
        // Prova a caricare le costanti dal sessionStorage
        let constants = sessionStorage.getItem('lang_map');

        if (constants) {
            // Se le costanti sono nel sessionStorage, risolvi la promise con i dati
            resolve(JSON.parse(constants));
        } else {
            // Se non ci sono, carica il JSON statico presente nella cartella del progetto
            const filePath = 'sources/scripts/js/constants.json';  // Sostituisci con il percorso relativo del file JSON

            // Usando fetch per caricare il file JSON
            fetch(filePath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Errore nel caricamento del file JSON');
                    }
                    return response.json();
                })
                .then(data => {
                    // Salva nel sessionStorage
                    sessionStorage.setItem('constants', JSON.stringify(data));
                    // Risolvi la promise con i dati caricati
                    resolve(data);
                })
                .catch(error => {
                    // Gestisci eventuali errori nella richiesta
                    reject('Errore nel caricamento delle costanti: ' + error.message);
                });
        }
    });
}
