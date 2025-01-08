<?php
    require_once 'sources/scripts/header.php';
    require_once 'sources/scripts/php/content_deployer.php';

    $contents_list = [];
    $content_type = "";
    $caller_page = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Leggi il corpo della richiesta
        $content_type = isset($_POST[POST_VAR_CONTENT_TYPE]) ? $_POST[POST_VAR_CONTENT_TYPE] : "";
        $caller_page = isset($_POST[POST_VAR_CALLER_PAGE]) ? $_POST[POST_VAR_CALLER_PAGE] : "";

        if($content_type != "" && $caller_page != ""){
            $_SESSION[SESSION_VAR_CONTENT_TYPE] = $content_type;
            $_SESSION[SESSION_VAR_CALLER_PAGE] = $caller_page;

            header('Location: content_displayer.php');
        }else{
            header('Location: index.php');  //Fallback
        }
    }else if($_SERVER['REQUEST_METHOD'] === 'GET'){
        // Leggi i dati dalla sessione
        $content_type = isset($_SESSION[SESSION_VAR_CONTENT_TYPE]) ? $_SESSION[SESSION_VAR_CONTENT_TYPE] : "";
        $caller_page = isset($_SESSION[SESSION_VAR_CALLER_PAGE]) ? $_SESSION[SESSION_VAR_CALLER_PAGE] : "";

        if ($content_type != "") {
            $contents_list = obtain_contents_list_file($content_type);
        } else {
            header('Location: index.php');  //Fallback
        }

        if($caller_page == CALLER_PAGE_SINGLE_CONTENT_DISPLAYER) {
            // Pulisci i dati dalla sessione dopo averli usati
            unset($_SESSION[SESSION_VAR_CONTENT_ID]);
            unset($_SESSION[SESSION_VAR_CONTENT_TITLE]);
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
    <body class="body_class">
        <div id="loading" style="display: block;">
            Caricamento...
        </div>
        <div id="content" style="display: none;">
            <div id="pages_navigator">
            </div>
            <div id="cards_box" class="card_box">
            </div>
        </div>
        <script>

            function showPage(constants) {
                // Carico la localizzazione su js da php
                // Trasforma $lang_map in un oggetto JS
                var lang_map = <?php echo json_encode($lang_map); ?>;
                var content_type = <?php echo json_encode($content_type); ?>;
                var contents_list = <?php echo json_encode($contents_list); ?>;

                console.log('Lang map:', lang_map);
                console.log('Content type:', content_type);
                console.log('Contents list:', contents_list);
                const loading = document.getElementById('loading');
                const pages_navigator = document.getElementById('pages_navigator');
                const cards_box = document.getElementById('cards_box');

                const home_anchor = document.createElement("a");
                home_anchor.innerText = "home";
                home_anchor.addEventListener("click", function(event) {
                    event.preventDefault(); // Previene il comportamento predefinito del link
                    const data = {
                        "caller-page": constants.CALLER_PAGE_CONTENT_DISPLAYER
                    }
                    redirectWithPost("single_content_displayer.php", data);
                })
                const current_page_span = document.createElement("span");
                current_page_span.innerText = " > " + content_type;

                pages_navigator.append(home_anchor, current_page_span);

                const fragment = document.createDocumentFragment();
                contents_list.forEach(element => {
                    const card_anchor = document.createElement("a");
                    card_anchor.className = "card-anchor";
                    card_anchor.id = element.id;
                    card_anchor.addEventListener("click", function(event) {
                        event.preventDefault(); // Previene il comportamento predefinito del link
                        const data = {
                            "caller-page": constants.CALLER_PAGE_CONTENT_DISPLAYER,
                            "content-type": content_type,
                            "content-id": element.id,
                            "content-title": element.title
                        }
                        redirectWithPost("single_content_displayer.php", data);
                    });

                    const card_div = document.createElement("div");
                    card_div.className = "card";

                    const card_image = document.createElement("img");
                    card_image.src = element["card-image"] !== "" ? element["card-image"]: constants.PATH_SESSIONS_DEFAULT_CARD_IMAGE;
                    card_image.className = "card-image";
                    card_image.alt = "card-image not loaded";

                    const card_container = document.createElement("div");
                    card_container.className = "card-container";

                    const card_title = document.createElement("h4");
                    card_title.className = "card-title";
                    card_title.innerText = lang_map[element.title] ? lang_map[element.title]: element.title;

                    const card_subtitle = document.createElement("p");
                    card_subtitle.innerText = element.date !== "" ? element.date : "N/A";

                    card_container.append(card_title, card_subtitle);
                    card_div.append(card_image, card_container);
                    card_anchor.appendChild(card_div);

                    fragment.appendChild(card_anchor);
                });
                cards_box.appendChild(fragment);

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
