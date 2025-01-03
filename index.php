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
    </head>
    <body class="mainPage-body">
        <div id="loading" style="display: block;">
            Caricamento...
        </div>
        <div id="content" style="display: none;">
            <div class="centeredDiv">
                <a class="btnLink" href="content_displayer.php"><h1 id="sessions_txt"></h1></a>
                <a class="btnLink" href="#"><h1 id="pgs_txt"></h1></a>
                <a class="btnLink" href="#"><h1 id="locations_txt"></h1></a>
                <a class="btnLink" href="#"><h1 id="families_txt"></h1></a>
            </div>
        </div>
        <script>

            function showPage(constants) {
                // Carico la localizzazione su js da php
                // Trasforma $lang_map in un oggetto JS
                var lang_map = <?php echo json_encode($lang_map); ?>;

                console.log(lang_map);

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
