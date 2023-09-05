<?php
 class Route {

        private function route_to($file, $val = false, $prev = false,  $params = []) {
            include('init.php');
            if ($prev) {
                Utils::add_location('previous', $GLOBALS['base']."/".$_REQUEST['uri']);
            }
            if ($val) {
                
                include('validate.php');
            }
            include_once('components/headers/html_header.php');
            include_once('components/headers/account_header.php'); 

            include($file);
            include('components/html_footer.php');
            exit();
            // 
        }

        private function simpleRoute($file, $route, $val = false, $prev = false){

        
            //replacing first and last forward slashes
            //$_REQUEST['uri'] will be empty if req uri is /
            if(!empty($_REQUEST['uri'])){
                
                $route = preg_replace("/(^\/)|(\/$)/","",$route);
                $reqUri =  preg_replace("/(^\/)|(\/$)/","",$_REQUEST['uri']);
                // unset($_REQUEST['uri']);
            }else{
                $reqUri = "/";
            }
    
            if($reqUri == $route){
                $params = [];
                $this->route_to($file, $val, $prev);
    
            }
    
        }
    
        function add($route,$file, $val = false, $prev = false){
            
            //will store all the parameters value in this array
            $params = [];
    
            //will store all the parameters names in this array
            $paramKey = [];
    
            //finding if there is any {?} parameter in $route
            preg_match_all("/(?<={).+?(?=})/", $route, $paramMatches);
    
            //if the route does not contain any param call simpleRoute();
            if(empty($paramMatches[0])){
                $this->simpleRoute($file,$route, $val, $prev);
                return;
            }
    
            //setting parameters names
            foreach($paramMatches[0] as $key){
                $paramKey[] = $key;
            }
    
           
            //replacing first and last forward slashes
            //$_REQUEST['uri'] will be empty if req uri is /
    
            if(!empty($_REQUEST['uri'])){
                $route = preg_replace("/(^\/)|(\/$)/","",$route);
                $reqUri =  preg_replace("/(^\/)|(\/$)/","",$_REQUEST['uri']);
            }else{
                $reqUri = "/";
            }
    
            //exploding route address
            $uri = explode("/", $route);
    
            //will store index number where {?} parameter is required in the $route 
            $indexNum = []; 
    
            //storing index number, where {?} parameter is required with the help of regex
            foreach($uri as $index => $param){
                if(preg_match("/{.*}/", $param)){
                    $indexNum[] = $index;
                }
            }
    
            //exploding request uri string to array to get
            //the exact index number value of parameter from $_REQUEST['uri']
            $reqUri = explode("/", $reqUri);
    
            //running for each loop to set the exact index number with reg expression
            //this will help in matching route
            foreach($indexNum as $key => $index){
    
                 //in case if req uri with param index is empty then return
                //because url is not valid for this route
                if(empty($reqUri[$index])){
                    return;
                }
    
                //setting params with params names
                $params[$paramKey[$key]] = $reqUri[$index];
    
                //this is to create a regex for comparing route address
                $reqUri[$index] = "{.*}";
            }
    
            //converting array to sting
            $reqUri = implode("/",$reqUri);
    
            //replace all / with \/ for reg expression
            //regex to match route is ready !
            $reqUri = str_replace("/", '\\/', $reqUri);
    
            //now matching route with regex
            if(preg_match("/$reqUri/", $route))
            {
                $this->route_to($file,$val,$prev, $params);
    
            }
        }
    
        function notFound($file){
            $this->route_to($file);
            exit();
        }
    }

    $route = new Route();
    $route->add('/welcome', 'welcome.php');
    $route->add('index.php', 'index.php', true, true);
    $route->add('/login', 'account/login.php');
    $route->add('/tags', 'tags/index.php', true);
    $route->add('/upload', 'upload/upload.php', true);
    $route->add('/logout', 'account/logout.php');
    $route->add('/dataset/{table_id}', 'share/dataset.php', true, true);
    $route->add('bookmarks', 'bookmarks/bookmarks.php', true, true);
    $route->add('view/{table_id}', 'view/view_table.php', true, true);
    $route->add('manage/edit/{table_id}', 'manage/edit.php', true, true);
    $route->add('create_view', 'view/view.php', true, true);
    $route->add('tags/add', 'tags/add.php', true, true);
    $route->add('tags/delete/{id}', 'tags/delete.php', true);
    $route->add('tags/edit/{id}', 'tags/add.php', true);
    $route->add('/collections', 'collections/index.php', true);
    $route->add("/register", 'account/register.php');
    $route->add('add-bookmark', 'account/add-bookmark.php', true);
    $route->add('delete-table', 'manage/delete.php', true);

    $route->notFound('welcome.php');

?>
