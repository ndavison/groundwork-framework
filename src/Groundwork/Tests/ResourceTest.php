<?php

require __DIR__ . '/../../../../../autoload.php';

class ResourceTest extends PHPUnit_Framework_TestCase
{
    public function httpRequestMethodDataSet()
    {
        return array(
            array('GET'),
            array('POST'),
            array('PUT'),
            array('DELETE')
        );
    }
    
    /**
     * @dataProvider httpRequestMethodDataSet
     */
    public function testResourceOutputMethodCallsCorrectMethod($httpMethod)
    {
        $_SERVER['REQUEST_URI'] = '/api/test';
        $_SERVER['REQUEST_METHOD'] = $httpMethod;
        $_SERVER['QUERY_STRING'] = '';
        
        $request = new \Groundwork\Classes\Request('/api/');
        $response = new \Groundwork\Classes\Response();
        $resource = new Dummy($request, $response);
        
        $this->assertEquals($resource->output(), $httpMethod);
    }
}

class Dummy extends \Groundwork\Classes\Resource
{
    protected $init;
        
    protected function http_GET()
    {
        return 'GET';
    }
    
    protected function http_POST()
    {
        return 'POST';
    }
    
    protected function http_PUT()
    {
        return 'PUT';
    }
    
    protected function http_DELETE()
    {
        return 'DELETE';
    }
}
