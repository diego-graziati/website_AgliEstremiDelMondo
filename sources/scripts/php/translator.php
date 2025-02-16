<?php
    function load_localization($selected_lang, $state){
        $file_name = $selected_lang . '-' . $state . '.json';
        $file_path = LANG_DIR_PATH . $file_name;

        // Controlla se il file esiste
        if (!file_exists($file_path)) {
            error_log("[".date("Y-M-D h:m:s")."] [Error] [translator.php] the JSON '$file_path' localization file doesn't exist\n", 3, PHP_LOGS_FILE_PATH);
            die();
        }

        // Legge il contenuto del file JSON
        $jsonContent = file_get_contents($file_path);

        // Decodifica il JSON in un array associativo
        $dataMap = json_decode($jsonContent, true);

        // Controlla eventuali errori di decodifica
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("[".date("Y-M-D h:m:s")."] [Error] [translator.php] JSON decoding error: ". json_last_error_msg()."\n", 3, PHP_LOGS_FILE_PATH);
            die();
        }
        //print_r($dataMap);

        return $dataMap;
    }
?>