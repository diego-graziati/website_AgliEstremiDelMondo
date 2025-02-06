<?php
    require_once 'sources/scripts/header.php';

    $caller_page = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Leggi il corpo della richiesta
        $caller_page = isset($_POST[POST_VAR_CALLER_PAGE]) ? $_POST[POST_VAR_CALLER_PAGE] : "";

        if($content_type != "" && $caller_page != ""){
            $_SESSION[SESSION_VAR_CALLER_PAGE] = $caller_page;

            header('Location: index.php');
        }else{
            header('Location: index.php');  //Fallback
        }
    }else if($_SERVER['REQUEST_METHOD'] === 'GET'){
        // Leggi i dati dalla sessione
        $caller_page = isset($_SESSION[SESSION_VAR_CALLER_PAGE]) ? $_SESSION[SESSION_VAR_CALLER_PAGE] : "";

        if($caller_page == CALLER_PAGE_SINGLE_CONTENT_DISPLAYER) {
            // Pulisci i dati dalla sessione dopo averli usati
            unset($_SESSION[SESSION_VAR_CONTENT_TYPE]);
            unset($_SESSION[SESSION_VAR_CONTENT_ID]);
            unset($_SESSION[SESSION_VAR_CONTENT_TITLE]);
        }else if($caller_page == CALLER_PAGE_CONTENT_DISPLAYER){
            unset($_SESSION[SESSION_VAR_CONTENT_TYPE]);
        }
        unset($_SESSION[SESSION_VAR_CALLER_PAGE]);
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agli Estremi del Mondo</title>
        <link rel="icon" type="image/x-icon" href="favicon.jpeg">

        <!--Linked stylesheet-->
        <link rel="stylesheet" href="sources/style/style.css">

        <script src="sources/scripts/js/constants.js">
        </script>
        <script src="sources/scripts/js/redirect.js">
        </script>
    </head>
    <body class="mainPage-body">
        <div id="loading" style="display: block;">
            Caricamento...
        </div>
        <div id="content" style="display: none;">
            <div class="centeredDiv">
                <a id="sessions_link" class="btnLink" href="#"><h1 id="sessions_txt"></h1></a>
                <a id="pgs_link" class="btnLink" href="#"><h1 id="pgs_txt"></h1></a>
                <a id="locations_link" class="btnLink" href="#"><h1 id="locations_txt"></h1></a>
                <a id="families_link" class="btnLink" href="#"><h1 id="families_txt"></h1></a>
            </div>
        </div>
        <script>

            function showPage(constants) {
                // Carico la localizzazione su js da php
                // Trasforma $lang_map in un oggetto JS
                var lang_map = <?php echo json_encode($lang_map); ?>;

                console.log(lang_map);

                const sessions_link = document.getElementById("sessions_link");
                const pgs_link = document.getElementById("pgs_link");
                const locations_link = document.getElementById("locations_link");
                const families_link = document.getElementById("families_link");
                sessions_link.addEventListener('click', function(event) {
                    event.preventDefault(); // Previene il comportamento predefinito del link
                    const data = [{
                            "name": "caller-page",
                            "value": constants.ROUTING_PATH_INDEX
                        },{
                            "name": "content-type",
                            "value": constants.CONTENT_TYPE_SESSIONS
                        }];
                    redirectWithPost(constants.ROUTING_PATH_SESSIONS, data);
                });
                pgs_link.addEventListener('click', function(event) {
                    event.preventDefault(); // Previene il comportamento predefinito del link
                    const data = [{
                            "name": "caller-page",
                            "value": constants.ROUTING_PATH_INDEX
                        },{
                            "name": "content-type",
                            "value": constants.CONTENT_TYPE_PGS
                        }];
                    redirectWithPost(constants.ROUTING_PATH_PGS, data);
                });
                locations_link.addEventListener('click', function(event) {
                    event.preventDefault(); // Previene il comportamento predefinito del link
                    const data = [{
                            "name": "caller-page",
                            "value": constants.ROUTING_PATH_INDEX
                        },{
                            "name": "content-type",
                            "value": constants.CONTENT_TYPE_LOCATIONS
                        }];
                    redirectWithPost(constants.ROUTING_PATH_LOCATIONS, data);
                });
                families_link.addEventListener('click', function(event) {
                    event.preventDefault(); // Previene il comportamento predefinito del link
                    const data = [{
                            "name": "caller-page",
                            "value": constants.ROUTING_PATH_INDEX
                        },{
                            "name": "content-type",
                            "value": constants.CONTENT_TYPE_FAMILIES
                        }];
                    redirectWithPost(constants.ROUTING_PATH_FAMILIES, data);
                });

                const session_txt = document.getElementById("sessions_txt");
                const pgs_txt = document.getElementById("pgs_txt");
                const locations_txt = document.getElementById("locations_txt");
                const families_txt = document.getElementById("families_txt");
                session_txt.innerHTML = lang_map[constants.LANG_INDEX_BTN_SESSIONS];
                pgs_txt.innerHTML = lang_map[constants.LANG_INDEX_BTN_PGS];
                locations_txt.innerHTML = lang_map[constants.LANG_INDEX_BTN_LOCATIONS];
                families_txt.innerHTML = lang_map[constants.LANG_INDEX_BTN_FAMILIES];

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
