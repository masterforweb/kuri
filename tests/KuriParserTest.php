<?php
	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;

	/**
	 * Tests parse url
	 */
	
	class kuriParserTest extends TestCase
	{
	    //test http
	    function testKuriParserHttp()
	    {
	        $url = 'http://argumenti.ru/';
	        $items = array(
	            'scheme' => 'http',
	            'host' => 'argumenti.ru',
	            'path' => '/',
	            'method' => ''
	        );

	        $result = kuri_parser($url);

	        $this->assertEquals($items, $result);
	    }

	    //test https
	    function testKuriParserHttps()
	    {
	        $url = 'https://argumenti.ru/';
	        $items = array(
	            'scheme' => 'https',
	            'host' => 'argumenti.ru',
	            'path' => '/',
	            'method' => ''
	        );

	        $result = kuri_parser($url);

	        $this->assertEquals($items, $result);
	    }

	    //test https
	    function testKuriParserParam()
	    {
	        $url = 'https://test.ru/test_id/12/';
	        $items = array(
	            'scheme' => 'https',
	            'host' => 'test.ru',
	            'path' => '/test_id/12/',
	            'items' => array(
	                '0' => 'test_id',
	                '1' => '12'
	            ),
	            'method' => ''
	        );

	        $result = kuri_parser($url);
	        $this->assertEquals($items, $result);
	    }
	}
