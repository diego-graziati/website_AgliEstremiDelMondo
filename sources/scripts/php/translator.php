<?php

    //$dataMap = '';

    function load_localization($selected_lang, $state){
        echo "Lingua selezionata: $selected_lang<br>";
        echo "Stato: $state<br>";
        $file_name = $selected_lang . '-' . $state . '.json';
        $file_path = LANG_DIR_PATH . $file_name;
        echo "File path: $file_path<br>";

        // Controlla se il file esiste
        if (!file_exists($file_path)) {
            die("Errore: Il file JSON non esiste.");
        }

        // Legge il contenuto del file JSON
        $jsonContent = file_get_contents($file_path);

        // Decodifica il JSON in un array associativo
        $dataMap = json_decode($jsonContent, true);

        // Controlla eventuali errori di decodifica
        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Errore nella decodifica del JSON: " . json_last_error_msg());
        }
        //print_r($dataMap);

        return $dataMap;
    }
?>