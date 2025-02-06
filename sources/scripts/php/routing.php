<?php
    class Router {
        private $routes;
        private $fallback_route;

        public function __constructor(){
            $this->routes = [];

            $routes_files = [
                ROUTES_DIR_PATH . 'routes.json',
                ROUTES_DIR_PATH . 'sessions/routes.json',
                ROUTES_DIR_PATH . 'families/routes.json',
                ROUTES_DIR_PATH . 'locations/routes.json',
                ROUTES_DIR_PATH . 'pgs/routes.json',
            ];

            foreach($routes_files as $file_path) {
                if(!file_exists($file_path)) {
                    die("Errore: Il file JSON $file_path non esiste.");
                }

                $file_content = file_get_contents($file_path);
                $routes_data = json_decode($file_content, true);
                if (isset($routes_data["routes"])) {
                    $this->processRoutes($routes_data["routes"], $max_depth = MAX_ROUTING_DEPTH);
                }
                if (isset($routes_data["fallback_route"])) {
                    $this->fallback_route = $routes_data["fallback_route"];
                }
            }
        }

        private function processRoutes(array $routes, string $prefix = "", int $depth = 1, int $max_depth = 2) {
            foreach ($routes as $route) {
                // Costruisci il percorso completo.
                $route_name = $route['route_name'];
                $current_path = $prefix . "/" . $route_name;

                // Rimuovi l'array dei figli per evitare loop ricorsivi nell'output finale.
                $route_copy = $route;
                unset($route_copy['children']);

                // Aggiungi la route all'array associativo.
                $this->routes[$current_path] = $route_copy;

                // Se ci sono figli e non si supera la profondità massima, processali ricorsivamente.
                if ($depth < $max_depth && !empty($route['children'])) {
                    $this->processRoutes($route['children'], $current_path, $depth + 1, $max_depth);
                }
            }
        }

        public function getCompleteRoutingInfo(string $route) {
            $actual_route = $fallback_route;

            if(isset($this->route[$route])) {
                $actual_route = $this->route[$route];
            }

            return $actual_route;
        }

        public function callRoute($route_info, $post_body_params) {
            if (!isset($route_info['php_file_path']) || !file_exists($route_info['php_file_path'])) {
                die("Errore: Il file di destinazione non esiste.");
            }

            // URL di destinazione
            $url = $route_info['php_file_path'];

            // Parametri POST da inviare
            $post_parameters = $route_info['post_parameters'] ?? [];

            if($post_body_params != ""){
                foreach ($post_body_params as $param) {
                    if(isset($post_parameters[$param["name"]])){
                        $post_parameters[$param["name"]] = $param["value"];
                    }
                }
            }

            // Inizializza cURL
            $ch = curl_init();

            // Configura cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_parameters));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Per ricevere la risposta
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Segue automaticamente il redirect

            // Esegui la richiesta
            $response = curl_exec($ch);

            // Controlla eventuali errori
            if ($response === false) {
                die("Errore nella chiamata POST: " . curl_error($ch));
            }

            // Chiudi la sessione cURL
            curl_close($ch);

            // Ritorna la risposta della pagina target
            return $response;
        }
    }
?>