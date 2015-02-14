## URLPARSER

PHP >= 5.2.0

Quickstart MVC router without regular. MVC standart role: _controller/action/params_

Return result array ( _'protocol', 'domain', 'method', 'control', 'action', 'params'_ )

* protocol (http, https)
* domain (default _$_SERVER['QUERY_STRING']_)
* method (GET, POST ...)
* permanent MVC rule: control/action/params

[SHEME FIND FUNCTION]

 - CLASSIC: find class $control and method $action
 - REST: find class $control and method (show, delete)
 - SMALL APP: find function $control_$action
 - SHORT FUNC: find function control 


[EXAMPLE]

require 'vendor/akdelf/kuri/kuri.php';
$result = action('http://www.argumenti.ru/rss/an/yandexnews');

print_r($result);

----------------------------------------------------------------
Array
(
    [protocol] => http
    [domain] => argumenti.ru
    [control] => rss
    [action] => an
    [params] => Array
        (
          [0] => yandexnews
        )


------------------------------------------------



Classic example:

path: rss/type/yandexnews

class rss {
	
	function type($name = 'yandexnews'){

	}

}


REST example:

path: rss/yandexnews

class rss {
	
	function show($name = 'yandexnews'){

	}

}



Function example 1:

path: rss/type/yandexnews

	function rss_type() {
	
	}


Function example 1:

path: rss/yandexnews

	function rss() {

	}



