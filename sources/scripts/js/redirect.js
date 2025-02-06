function redirectWithPost(url, data) {

    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: data
    });

    // Creazione di un form HTML nascosto
    /*const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;

    // Aggiunta dei dati come input nascosti
    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = data[key];
            form.appendChild(input);
        }
    }

    console.log("Form: ", form);
    // Aggiunta del form al DOM e invio
    document.body.appendChild(form);
    form.submit();
    // Pulizia: rimuovi il form dal DOM dopo l'invio
    document.body.removeChild(form);*/
}