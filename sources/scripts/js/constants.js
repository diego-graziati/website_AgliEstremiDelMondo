// constants.js

// Funzione per caricare le costanti nel localStorage
function loadConstants() {
    return new Promise((resolve, reject) => {
        let constants = sessionStorage.getItem('lang_map');

        if (constants) {
            resolve(JSON.parse(constants));
        } else {
            const filePath = 'sources/scripts/js/constants.json';

            // Usando fetch per caricare il file JSON
            fetch(filePath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Errore nel caricamento del file JSON');
                    }
                    return response.json();
                })
                .then(data => {
                    sessionStorage.setItem('constants', JSON.stringify(data));
                    resolve(data);
                })
                .catch(error => {
                    reject('Errore nel caricamento delle costanti: ' + error.message);
                });
        }
    });
}
