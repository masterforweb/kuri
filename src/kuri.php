<?php

/**
* @author Аndrey Delfin <masterforweb@hotmail.com>
* @copyright 2014-2022 Andrey Delfin
* @license http://www.opensource.org/licenses/mit-license.html  MIT License
*/

/**
* Return params url and method request.
*
* @param string $url Add current url
*
* @return array Parser params
*/
function kuri_parser($url = '')
{
    $result = array();
	
    $result = parse_url(urldecode($url));

    if ($result['path'] !== '/') {
        $result['items'] = explode('/', trim($result['path'], '/'));
    }

    if (isset($_SERVER['REQUEST_METHOD'])) {
        $result['method'] = $_SERVER['REQUEST_METHOD'];
    } else {
        $result['method'] = '';
    }

    return $result;
}

/**
* Find console arguments
*
* @param array $argv
*
* @return array
*/
function kuri_argv($argv)
{
    if (isset($argv)) {
        $items = $_SERVER['argv'];

        $result['script'] = array_shift($items);
        $result['method'] = 'command';
        $result['items'] = $items;

        return $result;
    }

    return null;
}

/**
* Looking for current url
*
* @return string Return url
*/
function kurl()
{
    if (isset($_SERVER['REQUEST_URI']) and $_SERVER['REQUEST_URI'] !== '') {
        $uri = $_SERVER['REQUEST_URI'];
    } elseif (isset($_SERVER['PATH_INFO']) and $_SERVER['PATH_INFO'] !== '') {
        $uri = $_SERVER['PATH_INFO'];
    } else {
        return false;
    }

    // get query
    if (isset($_SERVER['QUERY_STRING']) and $_SERVER['QUERY_STRING'] !== '') {
        if ($uri) {
            $uri = str_replace($_SERVER['QUERY_STRING'], '',$uri);
        } else {
            $uri = $_SERVER['REQUEST_URI'];
        }
    }
				
    $uri = trim($uri, '/');

    $result = kuri_scheme().$_SERVER['SERVER_NAME'].'/';
    if ($uri !== '') {
        $result .= $uri;
    }
		
    return $result;
}

/**
 * Define http(s) protocol
 *
 * @param string $prefix
 *
 * @return string
 */
function kuri_scheme($prefix = '://')
{
    if ( (! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
     (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
     (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ) {
        $scheme = 'https';
    } else {
        $scheme = 'http';
    }

    if (is_string($prefix)) {
        return  $scheme.$prefix;
    }

    return  $scheme;
}

/**
* Find controllers in the array of parameters
*
* @param  array $items
* @param string $method
* @param string $prefix
*
* @return array  Return name function and arguments
*/
function kufind($items = array(), $method = 'get', $prefix = '_kuri')
{
    $size = sizeof($items);
    $action = 'index'; //default
    $method = strtolower($method);
			
    if ($size == 0) {// mainpage
        $cname = 'index';
    } else {
        $cname = array_shift($items); //title action
        if ($size > 1) {
            $action = $items[0];
        }
    }

    $cname = str_replace('-', '_', $cname);

    if ($prefix !== '') {
        $cname .= $prefix;
    }

    // find class
    if (class_exists($cname)) {
        $control = new $cname;
			
        if (method_exists($control, $action)) {
            if ($size > 2) {
                $action = array_shift($items);
            }
				
            $func = $action;
            $args = $items;
        } elseif (method_exists($control, $method)) { //REST API post, get ...
            $func = $method;
            $args =  $items;
        }
			
        return array('class'=>$control, 'func'=>$func, 'args'=>$args);
    }
			
    // find function
    $func_temp = str_replace('-', '_', $cname);

    if (function_exists($func = $func_temp.'_'.$action)) {
        $action = array_shift($items);
        $args = $items;
    } elseif (function_exists($func = $func_temp.'_'.$method)) {
        $args = $items;
    } elseif (function_exists($func = $func_temp)) {
        $args = $items;
    } else {
        return false;
    }
					
    return array(
        'class'=>false,
        'func'=>$func,
        'args'=>$args,
        'cname'=>$cname
    );
}

function kuload($cname, $p = '')
{
    $class = kuri_load_class($cname);

    if (isset($class)) {
        return $class;
    }

    // if (defined(APPPATH))
    //     $path_load = APPPATH;
    //  else
    //     $path_load = 'app/';

    $cfile = $path_load.'routes'.DIRECTORY_SEPARATOR.$cname.DIRECTORY_SEPARATOR.$cname.'.php';

    if (file_exists($cfile)) {
        require $cfile;

        return kuri_load_class($cname);
    }

    return null;
}

function kuri_load_class($cname)
{
    if (class_exists($cname)) {
        return new $cname();
    }

    return null;
}

/**
* Base load controller class in
*/
function kucontroller($cname, $path)
{
    if ($path == null) {
        $path = 'app'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR;
    }
			
    if (!class_exists($cname)) {
        $cfile = $path.'.php';
        if (file_exists($cfile)) {
            require $cfile;
        } else {
            return false;
        }
    }
	
    return new $cname();
}

/**
 * @param array $items
 * @param string $method
 *
 * @return array
 */
function kufindfunc($items = array(), $method = 'GET')
{
    $size = sizeof($items);
    $action = 'index';
			
    if ($size == 0) {// mainpage
        $cname = 'main';
    } else {
        $cname = array_shift($items);
		
        if ($size > 2) {
            $action = $items[0];
        }
    }
		
    if ($control = $this->loadclass($cname)) { //autoload class
        if (method_exists($control, $action)) {
            if ($size > 2) {
                $action = array_shift($items);
            }
            $func = $action;
            $args = $items;
        } elseif (method_exists($control, $method)) { //REST API post, get ...
            $func = $method;
            $args =  $items;
        }
			
        if ($func) {
            return array('class'=>$cname, 'func'=>$func, 'args'=>$args);
        }
    }
				
    if (function_exists($func = $cname.'_'.$action)) {
        $action = array_shift($items);
        $args = $items;
    } elseif (function_exists($func = $cname.'_'.$method)) {
        $args = $items;
    } elseif (function_exists($func = $cname)) {
        $args = $items;
    } else {
        return kuri_http_error(404);
    }
					
    return array('class'=>false, 'func'=>$func, 'args'=>$arguments);
}

/**
 * Load function controller
 *
 * @param string $func Name function
 * @param string $class Name class or false
 * @param array $args
 *
 * @return mixed
 */
function kuloadfunc($func, $class = false, $args = array())
{
    $realparams = kuri_real_params($func, $class);

    if ($class == false) {
        $arg_count = sizeof($args);
        
        if (is_array($args) and $arg_count > 0) {
            $realparams = kuri_real_params($func);
            
            if ($arg_count > sizeof($realparams)) {
                return kuri_http_error(404);
            }
            
            // find arguments functions
            if ($realparams[0] == 'array') {
                $params = $args;
            } else {
                for ($i = 0; $i < $arg_count; $i++) {
                    if ($realparams[$i] == 'int') {
                        $valid = filter_var($args[$i], FILTER_VALIDATE_INT);
                    } elseif ($realparams[$i] == 'boolean') {
                        $valid = filter_var($args[$i], FILTER_VALIDATE_BOOLEAN);
                    } elseif ($realparams[$i] == 'float') {
                        $valid = filter_var($args[$i], FILTER_VALIDATE_FLOAT);
                    } else {
                        $valid = $args[$i];
                    }

                    if ($valid) {
                        $params[$i] = $valid;
                    } else {
                        return false;
                    }
                }
            }

            try {
                return call_user_func_array($func, $params);
            } catch (Error $e) {
                return kuri_http_error(404);
            }
        } else {
            return call_user_func($func);
        }
    } else {
        if (is_array($args) and sizeof($args) > 0) {
            return call_user_func_array(array($class, $func), $args);
        }
			
        return call_user_func(array($class, $func));
    }
}

/**
 * @param $func name function
 * @param object|string|null $class
 * @param string $func
 *
 * @return array
 */
function kuri_real_params($func, $class = null)
{
    $params = array();

    //function
    if ($class == null) {
        $reflectionFunc = new ReflectionFunction($func);
        $args  = $reflectionFunc->getParameters();
    } else { //class method
        $reflection = new \ReflectionMethod($class, $func);
        $args = $reflection->getParameters();
    }
    
    foreach ($args as $key=>$arg) {
        if ($arg->hasType()) {
            $type = (string) $arg->getType();
        } else {
            $type = 'string';
        }
       
        $params[$key] = (string) $type;
    }
    
    return $params;
}

/**
 *  local handler errors
 */
/* set_error_handler('kuri_error');

function kuri_error($errno, $errstr, $errfile, $errline)
{
    if ($errno == E_RECOVERABLE_ERROR) {
        $str = "VIEW: <b>E_RECOVERABLE_ERROR<b> {$errstr} FILE: {$errfile}  LINE:  {$errline}<br>";
        echo $str;
    }

    return false;
} */

/** Find function error
*  @return string http code error
*/
function kuri_http_error($code)
{
    $errfunc = 'kuri_'.$code;

    if (function_exists($errfunc)) {
        return $errfunc();
    }
    
    return http_response_code($code);
}

/**
 * Start find functions in prefix kuri
 *
 * @param string $currurl
 * @param string $charset (default utf-8)
 *
 * @return mixed
 */
function _kuri($currurl = null, $charset = 'utf-8')
{
    return kuri(null, '_kuri', $charset);
}

/**
* Find current route. Return 200 OK (html / json). Or return header 404 code
*
* @param string $currurl
* @param string $prefix
* @param string $charset default utf-8
* @param string $autotype default true
*
* @return string
*/
function kuri($currurl = null, $prefix = '', $charset = 'utf-8', $autotype = true)
{
    if ($currurl == null) {
        $url = kurl();
    } else {
        $url = $currurl;
    }
		
    if ($url !== false) {
        $params = kuri_parser($url);
    } else {
        $params = kuri_argv($_SERVER['argv']);
    }

    if (!is_array($params)) {
        return kuri_http_error(404);
    }
				
    if (isset($params['items'])) {
        $result = kufind($params['items'], $params['method'], $prefix);
    } else {
        $result = kufind(array(), $params['method'], $prefix);
    }

    if ($currurl == null) {
        //define('KURI_CNAME', $result['cname']);
    }

    if (is_array($result)) {
        if ($result['func'] !== '') {
            $data =  kuloadfunc($result['func'], $result['class'], $result['args']);
            if ($autotype) {
                if (is_array($data)) {
                    header('Content-Type: application/json; '.$charset);
                    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                } elseif (is_string($data)) {
                    header('Content-type: text/html; charset='.$charset);
                    echo $data;
                } else {
                    return $data;
                }
            }
        } else {
            return false;
        }
    } else {
        kuri_http_error(404);
    }
}

/**
 * add define SITE, SITEPATH and APPPATH
 *
 * @return boolean
 */
function kuri_define()
{
    $sitepath = '';
    $site = '';

    if (isset($_SERVER['SERVER_PORT']) and isset($_SERVER['HTTP_HOST'])) {
        if ($_SERVER['SERVER_PORT'] == 443 or $_SERVER['HTTPS'] == 'on') {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }
        
        $site =  $protocol.$_SERVER['HTTP_HOST'].'/';
        $sitepath = $_SERVER['DOCUMENT_ROOT'].'/';
    } else {
        $sitepath =  $_SERVER['PWD'].'/';
    }

    if (!defined('SITE') and $site !== '') {
        define('SITE', $site);
    }

    if (!defined('SITEPATH') and $sitepath !== '') {
        define('SITEPATH',  $sitepath);
    }

    return true;
}

/**
 * static array vars (key=>value)
 *
 * @param string $name
 * @param mixed $value
 *
 * @return mixed
 */
function kuri_set($name = null, $value = null)
{
    static $vars = array();
			   
    if ($name == null) {
        return $vars;
    }
	
    if ($value == null) {
        if (array_key_exists($name, $vars)) {
            return $vars[$name];
        }
    } else {
        $vars[$name] = $value;
    }

    return null;
}

/**
 * View
 *
 * @param string $view
 * @param array $data
 * @param string $layer
 *
 * @return string render string
 */
function kuri_view($view, $data = array(), $layer = null)
{
    ob_start();
        		
    if (is_array($data)) {
        extract($data);
    }
	
    if ($layer !== null) {
        $content = view($view, $data);
        require $layer;
    } else {
        require $view;
    }
   	       	       	
    return trim(ob_get_clean());
}
