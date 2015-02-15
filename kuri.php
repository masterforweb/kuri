<?php 

		/**
		* kURI: return scheme, host, path, controller, action, items, http method 
		* @license http://www.opensource.org/licenses/mit-license.html  MIT License
		* @author АК Delfin <masterforweb@hotmail.com>
		*/


		class kURI {

			
			/**
			* return scheme, host, path, controller, action, items 
			*/

			static $params = array();


			function parser($uri = null) {

				$result = array();
				$method = 'get'; /* defaul method get*/

				if ($uri == null) { // parse current url 
					if (isset($_SERVER['QUERY_STRING']) and $_SERVER['QUERY_STRING'] !== '')
						$uri = $_SERVER['QUERY_STRING'];
					elseif(isset($_SERVER['PATH_INFO']) and $_SERVER['PATH_INFO'] !== '')
						$uri = $_SERVER['PATH_INFO'];
					else
						$uri = '';

					if (isset($_SERVER['REQUEST_METHOD']))
						$method = $_SERVER['REQUEST_METHOD'];

				}

				$uri = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$uri.'/';

				$result = parse_url(urldecode($uri));
				$result['method'] = strtolower($method);

				if ($result['path'] !== '/') {
					$result['items'] = explode('/', trim($result['path'], '/'));
				}

				return $result;

			}


		
		function run() {
			
			$items = $this->parser();
			$result = $this->findfunc($items['items'], $items['method']);
			$this->loadfunc($result['func'], $result['class'], $result['args']);
			

		}


		
		
		function findfunc($items = array(), $method = "get") {

			print_r($items);

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
					
			return array('class'=>False, 'func'=>$fname, 'args'=>$arguments);	
					

		}




		function loadclass($cname){

			if (!class_exists($cname)) {
				$cfile = 'app'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$cname.'.php';
				if (file_exists($cfile)) 
					require ($cfile);
				else
					return False;
			}

			return new $cname();
		}
		

		function loadfunc($func, $class = False, $args = array()) {

			if ($class == null) {
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



		//папка = контрол, метод = файл, все остально параметры
		function view (){

		}



		function er404() {
			echo '404 no find page';
		}  


	}	


		





