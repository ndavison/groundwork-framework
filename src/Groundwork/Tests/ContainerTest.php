<?php

require __DIR__ . '/../../../../../autoload.php';

class ContainerTest extends PHPUnit_Framework_TestCase
{
    protected $app;
    
    public function setUp()
    {
        $this->app = new \Groundwork\Classes\Container();
    }
        
    public function testIocRegistersAnAlias()
    {
        $this->app->register('foo', function() {
            $object = new \stdClass;
            $object->name = 'foobar';
            return $object;
        });
        
        $foo = $this->app->get('foo');
        
        $this->assertTrue($foo instanceof \stdClass);
        $this->assertEquals($foo->name, 'foobar');
    }
    
    public function testIocReturnsSameInstance()
    {
        $this->app->register('foo', function() {
            $object = new \stdClass;
            $object->name = 'foobar';
            return $object;
        });
        
        $foo = $this->app->get('foo');
        
        $this->assertTrue($foo instanceof \stdClass);
        $this->assertEquals($foo->name, 'foobar');
        
        $foo->name = 'bar';
        
        $foo2 = $this->app->get('foo');
        
        $this->assertEquals($foo2->name, 'bar');
    }
    
    public function testIocReturnsNewInstance()
    {
        $this->app->register('foo', function() {
            $object = new \stdClass;
            $object->name = 'foobar';
            return $object;
        });
        
        $foo = $this->app->get('foo');
        
        $this->assertTrue($foo instanceof \stdClass);
        $this->assertEquals($foo->name, 'foobar');
        
        $foo->name = 'bar';
        
        $foo2 = $this->app->getNew('foo');
        
        $this->assertEquals($foo2->name, 'foobar');
    }
}