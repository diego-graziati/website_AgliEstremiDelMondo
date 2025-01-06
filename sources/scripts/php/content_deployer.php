<?php
function obtain_contents_list_file($content_type){
    $contents_list_file_path = CONTENT_DIR_PATH . $content_type . '/contents_list.json';

    echo "<p>Contents_list_file_path: $contents_list_file_path</p>";

    // Controlla se il file esiste
    if (!file_exists($contents_list_file_path)) {
        die("Errore: Il file JSON non esiste.");
    }

    // Legge il contenuto del file JSON
    $jsonContent = file_get_contents($contents_list_file_path);

    // Decodifica il JSON in un array associativo
    $data = json_decode($jsonContent, true)["contents_list"];
    print_r($data);

    // Controlla eventuali errori di decodifica
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Errore nella decodifica del JSON: " . json_last_error_msg());
    }

    // Ordina i dati alfabeticamente in base alla chiave 'id'
    usort($data, function ($a, $b) {
        return strcmp($a['id'], $b['id']);
    });

    return $data;
}

function obtain_content_file($content_type, $content_id){
    $content_file_path = CONTENT_DIR_PATH . $content_type . '/' . $content_id . '.json';

    // Controlla se il file esiste
    if (!file_exists($content_file_path)) {
        die("Errore: Il file JSON non esiste.");
    }

    // Legge il contenuto del file JSON
    $jsonContent = file_get_contents($content_file_path);

    // Decodifica il JSON in un array associativo
    $data = json_decode($jsonContent, true);

    // Controlla eventuali errori di decodifica
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Errore nella decodifica del JSON: " . json_last_error_msg());
    }

    return $data;
}
?>