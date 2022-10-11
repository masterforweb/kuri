# _kuri

Kuri is a PHP micro framework. Minimum code - maximum speed. Quick start your application and MVP.

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install Kuri.

```bash
composer require masterforweb/kuri
```

Autoroutes:

https://{your domain}/{your function}/param1

or

https://{your domain}/{your class}/{funtion}/param1/param2

or

command line:
php {application path}/index.php {your function} param


 
## Hello World

```php

require 'vendor/autoload.php';

kuri();

function index() {
	echo 'Hello World! Is index page';	
}
```


## Recommended practice: prefix _kuri

```php

require 'vendor/autoload.php';

_kuri();

function index_kuri() {
	echo 'Hello World! Is index page';	
}

function id_kuri(int $id){
	echo "result $id";
}

```

## Class example

```php

_kuri();

class news_kuri {
	
	function id($id){
		echo 'ID ='.$id;
	}

}

```


## GET, POST

```php

_kuri();

class news_kuri {
	
	function get($id){
		echo 'ID ='.$id;
	}

	function post($title, $text) {
		$sql = "INSERT INTO `news` (`title`, `name`) VALUES($title, $text);";

	}

}

```


## return array => 200 OK Content-Type: application/json

```php

_kuri();

class api_kuri {

	function about(){
		return [
			'name' => 'masterforweb',
			'mail' => 'masterforweb@hotmail.com',
			'product' => 'kuri'
		];
	}

}

```


masterforweb@hotmail.com
