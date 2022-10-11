<?php

use PHPUnit\Framework\TestCase;

class KuriRouterTypeTest extends TestCase
{
    function testParamTrueInt()
    {
        $val = 12;

        $result =  kuloadfunc('test_int', null, array('0'=> $val));
        $this->assertEquals($val, $result);
    }

    function testParamFalseInt()
    {
        $result =  kuloadfunc('test_int', null, array('0'=> 'sss'));
        $this->assertEquals(false, $result);
    }

    function testParamTrueBool()
    {
        $result =  kuloadfunc('test_bool', null, array('0'=> true));
        $this->assertEquals(true, $result);
    }

    function testParamTrueFloat()
    {
        $val = 1.234;

        $result =  kuloadfunc('test_float', null, array('0'=> $val));
        $this->assertEquals($val, $result);
    }

    function testParamFalseFloat()
    {
        $result =  kuloadfunc('test_float', null, array('0'=> 'sss'));
        $this->assertEquals(false, $result);
    }
}
