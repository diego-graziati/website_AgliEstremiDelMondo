<?php
    require_once 'sources/scripts/header.php';
    require_once 'sources/scripts/php/content_deployer.php';

    $content = "";
    $content_type = "";
    $content_id = "";
    $content_title = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Leggi il corpo della richiesta
        $content_type = isset($_POST[POST_VAR_CONTENT_TYPE]) ? $_POST[POST_VAR_CONTENT_TYPE] : "";
        $content_id = isset($_POST[POST_VAR_CONTENT_ID]) ? $_POST[POST_VAR_CONTENT_ID] : "";
        $content_title = isset($_POST[POST_VAR_CONTENT_TITLE]) ? $_POST[POST_VAR_CONTENT_TITLE] : "";

        if($content_type != "" && $content_id != ""){
            $_SESSION[SESSION_VAR_CONTENT_TYPE] = $content_type;
            $_SESSION[SESSION_VAR_CONTENT_ID] = $content_id;
            $_SESSION[SESSION_VAR_CONTENT_TITLE] = $content_title;

            header('Location: single_content_displayer.php');
        }else{
            header('Location: index.php');  //Fallback
        }
    }else if($_SERVER['REQUEST_METHOD'] === 'GET'){
        // Leggi i dati dalla sessione
        $content_type = isset($_SESSION[SESSION_VAR_CONTENT_TYPE]) ? $_SESSION[SESSION_VAR_CONTENT_TYPE] : "";
        $content_id = isset($_SESSION[SESSION_VAR_CONTENT_ID]) ? $_SESSION[SESSION_VAR_CONTENT_ID] : "";
        $content_title = isset($_SESSION[SESSION_VAR_CONTENT_TITLE]) ? $_SESSION[SESSION_VAR_CONTENT_TITLE] : "";

        if ($content_type != "" && $content_id != "") {
            $content = obtain_content_file($content_type, $content_id);
        } else {
            header('Location: index.php');  //Fallback
        }
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
            <div id="page_content">
                <div id="progress_indicator">
                </div>
                <div id="container">
                </div>
            </div>
        </div>
        <script>

            function showPage(constants) {
                // Carico la localizzazione su js da php
                // Trasforma $lang_map in un oggetto JS
                var lang_map = <?php echo json_encode($lang_map); ?>;
                var content_type = <?php echo json_encode($content_type); ?>;
                var content_id = <?php echo json_encode($content_id); ?>;
                var content = <?php echo json_encode($content); ?>;
                var content_title = <?php echo json_encode($content_title); ?>;

                console.log('Lang map:', lang_map);
                console.log('Content type:', content_type);
                console.log('Content id:', content_id);
                console.log('Content:', content);
                console.log('Content title:', content_title);
                const loading = document.getElementById('loading');
                const pages_navigator = document.getElementById('pages_navigator');
                const cards_box = document.getElementById('cards_box');
                const progress_indicator = document.getElementById('progress_indicator');
                const container = document.getElementById('container');

                const home_anchor = document.createElement("a");
                home_anchor.innerText = "home";
                home_anchor.href = "index.php";
                const intermediary_span = document.createElement("span");
                intermediary_span.innerText = " > ";
                const content_type_anchor = document.createElement("a");
                content_type_anchor.innerText = content_type;
                content_type_anchor.addEventListener("click", function(event) {
                    event.preventDefault(); // Previene il comportamento predefinito del link
                    const data = {
                        "caller-page": constants.CALLER_PAGE_SINGLE_CONTENT_DISPLAYER,
                        "content-type": content_type
                    }
                    redirectWithPost("content_displayer.php", data);
                });
                const second_intermediary_span = document.createElement("span");
                second_intermediary_span.innerText = " > ";
                const current_page_span = document.createElement("span");
                current_page_span.innerText = lang_map[content_title]? lang_map[content_title]: constants.LANG_NO_PAGE_TITLE_FALLBACK;

                pages_navigator.append(home_anchor, intermediary_span, content_type_anchor, second_intermediary_span, current_page_span);

                const dots = [];
                const sections = [];
                const fragment = document.createDocumentFragment();
                const progress_fragment = document.createDocumentFragment();
                content.sections.forEach((element, index) => {
                    const dot = document.createElement("div");
                    dot.className = "dot";
                    dot["data-section"] = index;
                    dots.push(dot);

                    const section = document.createElement("section");
                    section.className = "section";
                    const section_title = document.createElement("h2");
                    section_title.innerText =  lang_map[element.title]? lang_map[element.title]: element.title;
                    const paragraph_fragment = document.createDocumentFragment();
                    element.paragraphs.forEach(paragraph => {
                        const p = document.createElement("p");
                        p.innerText = lang_map[paragraph]? lang_map[paragraph]: p;
                        progress_fragment.appendChild(dot);
                        paragraph_fragment.appendChild(p);
                    });
                    section.append(section_title, paragraph_fragment);
                    sections.push(section);

                    fragment.appendChild(section);
                });
                progress_indicator.appendChild(progress_fragment);
                container.appendChild(fragment);

                document.getElementById('loading').style.display = 'none';
                document.getElementById('content').style.display = 'block';

                //Elements are on the DOM, so I can now access their parameters value.
                container.addEventListener("scroll", ()=>{
                    const scroll_position = container.scrollTop;

                    sections.forEach((section, index) => {
                        const section_top = section.offsetTop;
                        const section_height = section.offsetHeight;

                        //Update dots active status when scrolling
                        if(scroll_position >= (section_top - section_height/2) && scroll_position < (section_top + section_height/2)){
                            dots.forEach((dot, i) => {
                                dot.classList.toggle('active', i === index);
                            });
                        }
                    });
                });

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        sections[index].scrollIntoView({ behavior: 'smooth' });
                    });
                });
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
