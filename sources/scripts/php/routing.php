<?php
    class Router {
        private $routes;
        private $fallback_route;

        public function __construct(){
            $this->routes = [];
            $this->fallback_route = "";

            $routes_files = [
                ROUTES_DIR_PATH . 'routes.json',
                ROUTES_DIR_PATH . 'sessions/routes.json',
                ROUTES_DIR_PATH . 'families/routes.json',
                ROUTES_DIR_PATH . 'locations/routes.json',
                ROUTES_DIR_PATH . 'pgs/routes.json',
            ];

            foreach($routes_files as $file_path) {
                if(!file_exists($file_path)) {
                    error_log("[".date("Y-M-D h:m:s")."] [Error] [routing.php -> __construct] The JSON file '$file_path' doesn't exist\n" , 3, PHP_LOGS_FILE_PATH);
                    die();
                }

                $file_content = file_get_contents($file_path);
                $routes_data = json_decode($file_content, true);
                if (isset($routes_data["routes"])) {
                    $this->processRoutes($routes_data["routes"], "", 1, MAX_ROUTING_DEPTH);
                }
                if (isset($routes_data["fallback_route"])) {
                    error_log("[".date("Y-M-D h:m:s")."] [Info] [routing.php -> __construct] Fallback route set\n" , 3, PHP_LOGS_FILE_PATH);
                    $this->fallback_route = $routes_data["fallback_route"];
                    if(!file_exists(ROOT_DIR_PATH . "/" . $this->fallback_route['php_file_path'])){
                        error_log("[".date("Y-M-D h:m:s")."] [Error] [routing.php -> __construct function] Php file path ".ROOT_DIR_PATH."/".$this->fallback_route['php_file_path']." doesn't exist\n" , 3, PHP_LOGS_FILE_PATH);
                        die();
                    }
                    $this->fallback_route['actual_php_file_path'] = PROTOCOL . HOST . "/" . $this->fallback_route['php_file_path'];
                }
            }
            error_log("[".date("Y-M-D h:m:s")."] [Info] [routing.php -> __construct] Router initialized\n" , 3, PHP_LOGS_FILE_PATH);
        }

        private function processRoutes(array $routes, string $prefix = "", int $depth = 1, int $max_depth = 2) {
            foreach ($routes as $route) {
                // Costruisci il percorso completo.
                $route_name = $route['route_name'];
                $current_path = $prefix . "/" . $route_name;

                // Rimuovi l'array dei figli per evitare loop ricorsivi nell'output finale.
                $route_copy = $route;
                if(!file_exists(ROOT_DIR_PATH . "/" . $route_copy['php_file_path'])){
                    error_log("[".date("Y-M-D h:m:s")."] [Error] [routing.php -> processRoutes function] Php file path ".ROOT_DIR_PATH."/".$route_copy['php_file_path']." doesn't exist\n" , 3, PHP_LOGS_FILE_PATH);
                    die();
                }
                $route_copy['actual_php_file_path'] = PROTOCOL . HOST . "/" . $route_copy['php_file_path'];
                unset($route_copy['children']);

                // Aggiungi la route all'array associativo.
                $this->routes[$current_path] = $route_copy;
                #error_log("[".date("Y-M-D h:m:s")."] [Info] [routing.php -> processRoutes function] New route set: ".$current_path."\n" , 3, PHP_LOGS_FILE_PATH);

                // Se ci sono figli e non si supera la profondità massima, processali ricorsivamente.
                if ($depth < $max_depth && !empty($route['children'])) {
                    $this->processRoutes($route['children'], $current_path, $depth + 1, $max_depth);
                }
            }
        }

        public function getCompleteRoutingInfo(string $route) {
            $actual_route = $this->fallback_route;

            if(isset($this->route[$route])) {
                $actual_route = $this->route[$route];
            }

            return $actual_route;
        }

        public function callRoute($route_info, $post_body_params) {
            if (!isset($route_info['php_file_path']) || !file_exists($route_info['php_file_path'])) {
                error_log("[".date("Y-M-D h:m:s")."] [Error] [routing.php -> callRoute function] The destination file '".$route_info['php_file_path']."' doesn't exist\n", 3, PHP_LOGS_FILE_PATH);
                die();
            }

            // URL di destinazione
            $url = $route_info['actual_php_file_path'];

            // Parametri POST da inviare
            $post_parameters = $route_info['post_parameters'] ?? [];

            if($post_body_params != ""){
                foreach ($post_body_params as $param) {
                    if(isset($post_parameters[$param["name"]])){
                        $post_parameters[$param["name"]] = $param["value"];
                    }
                }
            }

            // Per evitare loop infiniti e capire chi stia esattamente compiendo la richiesta, inseriamo il seguente flag
            $post_parameters['internal_request'] = true;

            // Inizializza cURL
            $ch = curl_init();

            // Configura cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($post_parameters))
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_parameters));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Per ricevere la risposta
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Segue automaticamente il redirect
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);

            // Esegui la richiesta
            $response = curl_exec($ch);

            // Controlla eventuali errori
            if ($response === false) {
                error_log("[".date("Y-M-D h:m:s")."] [Error] [routing.php -> callRoute function] POST call error: " . curl_error($ch)."\n", 3, PHP_LOGS_FILE_PATH);
                die();
            }else{
                error_log("[".date("Y-M-D h:m:s")."] [Info] [routing.php -> callRoute function] POST call response: " . print_r($response, true) ."\n", 3, PHP_LOGS_FILE_PATH);
            }

            // Chiudi la sessione cURL
            curl_close($ch);

            // Ritorna la risposta della pagina target
            return $response;
        }
    }
?>