<?php 

		/**
		* kURI: return scheme, host, path, controller, action, items, http method 
		* @license http://www.opensource.org/licenses/mit-license.html  MIT License
		* @author АК Delfin <masterforweb@hotmail.com>
		*/
		


		function kparser($uri) {

			$result = array();
			
			$result = parse_url(urldecode($uri));
			
				if ($result['path'] !== '/') {
					$result['items'] = explode('/', trim($result['path'], '/'));
				}

				return $result;
		}

		
		/**
		* current url (k - current url)
		*/

		function kuri() {
			

			if (isset($_SERVER['QUERY_STRING']) and $_SERVER['QUERY_STRING'] !== '')
				$uri = $_SERVER['QUERY_STRING'];
			elseif(isset($_SERVER['PATH_INFO']) and $_SERVER['PATH_INFO'] !== '')
				$uri = $_SERVER['PATH_INFO'];
			else
				$uri = '';

			$uri = trim($uri, '/');
			
			if (!isset($_SERVER['REQUEST_SCHEME']) or $_SERVER['REQUEST_SCHEME'] == '')
				$sheme = 'http';
			else
				$sheme = $_SERVER['REQUEST_SCHEME'];

			$uri = $sheme.'://'.$_SERVER['SERVER_NAME'].$uri.'/';

			return $uri;

		}


		
		/**
		* find controller (k - controller)
		*/

		function kfind($items = array(), $method = 'get'){
			
			$size = sizeof($items);
			$action = 'index';
			
			if ($size == 0) {// mainpage
				$cname = 'main';
			}	
			else {
				$cname = array_shift($items); //title action
				if ($size > 2)
					$action = $items[0];
			}

			if ($control = kload($cname)){ //autoload class
				
				if (method_exists($control, $action)){
					if ($size > 2)
						$action = array_shift($items);
					$func = $action;
					$args = $items;
				}
				elseif (method_exists($control, $method)){ //REST API post, get ... 
					$func = $method;
					$args =  $items;
				}	
			
				if ($func)
					return array('class'=>$cname, 'func'=>$func, 'args'=>$args);

			}

						
			if (function_exists($func = $cname.'_'.$action)){
				$action = array_shift($items);
				$args = $items;
			}
			elseif (function_exists($func = $cname.'_'.$method)){
				$args = $items;
			}
			elseif (function_exists($func = $cname)){
				$args = $items;
			}	
			else
				return False;
					
			return array('class'=>False, 'func'=>$func, 'args'=>$args);	

		}


		function kload($cname, $p){

			if (!class_exists($cname)) {
				$cfile = 'app'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$cname.'.php';
				if (file_exists($cfile)) 
					require ($cfile);
				else
					return False;
			}

			return new $cname();
		}
		



		/**
		* Base load controller class in 
		*/

		function kcontroller($cname, $path){

			if ($path == null)
				$path = 'app'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR;
			

			if (!class_exists($cname)) {
				$cfile = $path.'.php';
				if (file_exists($cfile)) 
					require ($cfile);
				else
					return False;
			}

			return new $cname();
		}


		
		
		function kfindfunc($items = array(), $method = "get") {

			$size = sizeof($items);
			$action = 'index';
			
			if ($size == 0) {// mainpage
				$cname = 'main';
			}	
			else {
				$cname = array_shift($items);
				if ($size > 2)
					$action = $items[0];
			}

			if ($control = $this->loadclass($cname)){ //autoload class
				
				if (method_exists($control, $action)){
					if ($size > 2)
						$action = array_shift($items);
					$func = $action;
					$args = $items;
				}
				elseif (method_exists($control, $method)){ //REST API post, get ... 
					$func = $method;
					$args =  $items;
				}	
			
				if ($func)
					return array('class'=>$cname, 'func'=>$func, 'args'=>$args);

			}

				
			if (function_exists($func = $cname.'_'.$action)){
				$action = array_shift($items);
				$args = $items;
			}
			elseif (function_exists($func = $cname.'_'.$method)){
				$args = $items;
			}
			elseif (function_exists($func = $cname)){
				$args = $items;
			}	
			else
				return $this->er404();
					
			return array('class'=>False, 'func'=>$func, 'args'=>$arguments);	
					

		}




		
		function loadfunc($func, $class = False, $args = array()) {

			if ($class == False) {
				if (is_array($args) and sizeof($args) > 0)
					return call_user_func_array($func, $args);
				else
					return call_user_func($func);
			}
			else {


				if (is_array($args) and sizeof($args) > 0)
					return call_user_func_array(array($class, $func), $args);
				else
					return call_user_func(array($class, $func));
			}	

		}


		

				
		
		function view ($view, $data = array(), $layer = null){
			
			ob_start();
        		
        	if (is_array($data))
            	extract($data);

            if ($layer !== null){
            	$content = view($view, $data);
            	require $layer;
            }
            else
            	require $view;
   	       	       	
        	return trim(ob_get_clean());
        	
       	}


       	/*function set($name = null, $value = null) {

       		static $vars = array();

       		if ($name == null)
       			return $vars;	

       		if ($value == null){
       			if(array_key_exists($name, $vars)) 
       				return $vars[$name];
       		}
       		else
       			$vars[$name] = $value; 

			return null;
       	
       	}*/



		if (!function_exists('action')) {

			
			function action($url = null){
			
				if ($url == null)
					$url = kuri();
				$params = kparser($url);
				$result = kfind($params['items']);
				
				if ($result['func'] !== '') 
					return loadfunc($result['func'], $result['class'], $result['args']);
				else
					return false;
				
			}	

		}		










		function er404() {
			echo '404 no find page';
		}  