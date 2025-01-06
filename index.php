<?php
    require_once 'sources/scripts/header.php'
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
                    const data = {
                        "content-type": "sessions"
                    }
                    redirectWithPost("content_displayer.php", data);
                });
                pgs_link.addEventListener('click', function(event) {
                    event.preventDefault(); // Previene il comportamento predefinito del link
                    const data = {
                        "content_type": "pgs"
                    }
                    redirectWithPost("content_displayer.php", data);
                });
                locations_link.addEventListener('click', function(event) {
                    event.preventDefault(); // Previene il comportamento predefinito del link
                    const data = {
                        "content_type": "locations"
                    }
                    redirectWithPost("content_displayer.php", data);
                });
                families_link.addEventListener('click', function(event) {
                    event.preventDefault(); // Previene il comportamento predefinito del link
                    const data = {
                        "content_type": "families"
                    }
                    redirectWithPost("content_displayer.php", data);
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
