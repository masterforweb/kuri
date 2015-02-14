<?php 

		/**
		* kURI: return scheme, host, path, controller, action, items 
		* @license http://www.opensource.org/licenses/mit-license.html  MIT License
		* @author Jonathan H. Wage <masterforweb@hotmail.com>
		*/


		class kURI {

			
			/**
			* return scheme, host, path, controller, action, items 
			*/

			static $params = array();



			

			function parser($uri = null) {

				if ($uri == null) {
					if (isset($_SERVER['QUERY_STRING']) and $_SERVER['QUERY_STRING'] !== '')
						$uri = $_SERVER['QUERY_STRING'];
					elseif(isset($_SERVER['PATH_INFO']) and $_SERVER['PATH_INFO'] !== '')
						$uri = $_SERVER['PATH_INFO'];
					else
						$uri = '';
				}

				$uri = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$uri.'/';

				$result = parse_url(urldecode($uri));
		
				if ($result['path'] !== '/') {
					$items = explode('/', trim($result['path'], '/'));
					$sized = sizeof($items);
					if ($sized > 0) {
						$result['controller'] = array_shift($items);
						if ($sized > 1) {
							$result['action'] = array_shift($items);
							if ($sized > 2) {
								$result['items'] = $items;
							}
						}	
					}

				}

				return $result;

			}


		
		function action() {

			$result = $this->parser();

			
			if (!isset($result['controller']))
				$result['controller'] = 'main';

			if (!isset($result['action']))
				$result['action'] = 'index';


			$cname = $result['controller'];

				
			if ($control = $this->loadclass($result['controller'])){ //autoload class
				if (method_exists($control, $result['action']))
					return $this->loadfunc($result['action'], $result['controller'], $result['items']);
			}
			
			
			$fname = $result['controller'].'_'.$result['action'];
						
			if (function_exists($fname)){ //loadfunction
				return $this->loadfunc($fname, null, $result['items']);
			}
			else {
				return $this->er404();
			}

		
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
		

		function loadfunc($func, $class=null, $args = array()) {

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
			echo '404 страница не существует';
		}  


	}	


		





