<?php

require __DIR__.'/routes_class.php';

use PHPUnit\Framework\TestCase;

class KuriRouterClassTest extends TestCase
{
    // method index in class
    function testFindIndex()
    {
        $items = array(
            '0' => 'news'
        );
            
        $res = array(
            'class' => new news_kuri,
            'func' => 'index',
            'args' => array()
        );
            
        $result = kufind($items);
        $this->assertEquals($res, $result);
    }

    // method in class
    function testFindId()
    {
        $items = array(
            '0' => 'news',
            '1' => 'id',
            '2' => '12'
        );
            
        $res = array(
            'class' => new news_kuri,
            'func' => 'id',
            'args' => array(
                '0' => 12
            )
        );
            
        $result = kufind($items);
        $this->assertEquals($res, $result);
    }
}
