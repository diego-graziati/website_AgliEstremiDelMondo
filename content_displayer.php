<?php
    require_once 'sources/scripts/header.php';
    require_once 'sources/scripts/php/content_deployer.php';

    $contents_list = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Leggi il corpo della richiesta
        $content_type = isset($_POST['content-type']) ? $_POST['content-type'] : "";

        if($content_type != ""){
            $_SESSION['content-type'] = $content_type;

            header('Location: content_displayer.php');
        }else{
            header('Location: index.php');  //Fallback
        }
    }else if($_SERVER['REQUEST_METHOD'] === 'GET'){
        // Leggi i dati dalla sessione
        $content_type = isset($_SESSION['content-type']) ? $_SESSION['content-type'] : "";

        if ($content_type != "") {
            $contents_list = obtain_contents_list_file($content_type);
        } else {
            header('Location: index.php');  //Fallback
        }

        // Pulisci i dati dalla sessione dopo averli usati
        unset($_SESSION['content_type']);
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agli Estremi del Mondo</title>

        <!--Linked stylesheet-->
        <link rel="stylesheet" href="sources/style/style.css">

        <script src="sources/scripts/js/constants.js">
        </script>
    </head>
    <body>
        <div id="loading" style="display: block;">
            Caricamento...
        </div>
        <div id="content" style="display: none;">

        </div>
        <script>

            function showPage(constants) {
                // Carico la localizzazione su js da php
                // Trasforma $lang_map in un oggetto JS
                var lang_map = <?php echo json_encode($lang_map); ?>;
                var contents_list = <?php echo json_encode($contents_list); ?>;

                console.log(lang_map);
                console.log(contents_list);
                const loading = document.getElementById('loading');

                document.getElementById('loading').style.display = 'none';
                document.getElementById('content').style.display = 'block';
            }

            window.onload = function() {
                loadConstants()
                    .then(constants => showPage(constants))
                    .catch(error => {
                        console.error(error);
                        document.getElementById('loading').innerText = 'Errore nel caricamento delle costanti';
                    });
            };
        </script>
    </body>
</html>
