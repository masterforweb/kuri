<?php
	declare(strict_types=1);
	
	require __DIR__.'/../kuri.php';

	use PHPUnit\Framework\TestCase;
	/**
 	* @covers Email
 	*/
	
	final class kuriTests extends TestCase {

	
		function testKuParser(){
			
			$url = 'http://argumenti.ru/test/12';
			$result =  kuparser($url);
			var_dump($result);

		}


		function testKuFind(){

			$result = kufind(array('0'=>'test', '1'=>'id', '2'=>12));
			print_r($result);

		}



	}


	
	/*
	* test routing functions
	*/
	function test($id){
		return $id;
	}	
