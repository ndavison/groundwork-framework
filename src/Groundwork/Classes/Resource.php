<?php

namespace Groundwork\Classes;

/**
 * All resource classes should extend from this class.
 */
abstract class Resource
{    
    /**
     * The Request instance.
     *
     * @var Request
     */
    protected $request;
    
    /**
     * The Response instance.
     *
     * @var Response
     */
    protected $response;
        
    /**
     * A reference to the Request instance's property of the same name, just 
     * so it's a bit nicer to access.
     *
     * @var object
     */
    protected $routeParams;
    
    /**
     * Establish the properties on object creation.
     * 
     * @param Request $request The Request instance
     * @param Response $response The Response instance
     */
    public function __construct(
        Request $request, 
        Response $response
    ) {
        // Establish the instances as properties for easy access
        $this->request = $request;
        $this->response = $response;
        $this->routeParams = $this->request->routeParams();
    }
                
    /**
     * Generate the output of the requested Resource instance.
     * 
     * @return mixed
     */
    public function output()
    {
        // Check that the method for this HTTP request method exists.
        $methodName = 'http_' . $this->request->httpMethod();
        if (method_exists($this, $methodName)) {
            // Execute the requested Resource instance's method that 
            // corresponds with the requested HTTP method.
            return $this->$methodName();
        } else {
            // The Resource instance didn't define a method for the request's 
            // HTTP method - return a 405.
            return $this->response->send(
                    405,
                    'The requested resource does not support the HTTP method'.
                    ' "' . $this->request->httpMethod() . '".'
            );
        }   
    }
}
