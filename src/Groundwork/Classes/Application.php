<?php

namespace Groundwork\Classes;

/**
 * The main app scope.
 */
class Application extends Container
{
    /**
     * An array of the application's configuration values.
     * 
     * @var array
     */
    protected $config;
    
    /**
     * Bring the config in on Application instantiation.
     * 
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    /**
     * Initialise the IoC aliases and routes.
     */
    public function init()
    {
        $this->register('request', function($app) {
            return new Request($app->config['baseurl']);
        });
        
        $this->register('response', function($app) {
            return new Response();
        });
        
        $this->register('router', function($app) {
            return new Router();
        });
        
        // Register the app's IoC binds
        $app = $this;
        require $this->config['appdir'] . '/iocbinds.php';
        
        // Register the app's routes
        $router = $this->get('router');
        require $this->config['appdir'] . '/routes.php';
    }
    
    /**
     * Execute the application.
     */
    public function execute()
    {
        $request = $this->get('request');
        $router = $this->get('router');
        $response = $this->get('response');

        // Attempt to match the requested route with a registered route
        if ($router->matchRequest($request->route(), $request->httpMethod())) {

            // A match was found - pass any route params to the Request instance
            $request->routeParams($router->params());

            // The closure associated with the matched route
            $closure = $router->getClosure($this);

            // Attempt to call the closure
            if ($closure) {
                // Call the output
                $closure($request, $response);
            } else {
                // There was a problem calling the route's closure
                $response->send(500, 'This route\'s callback is invalid.');
            }

        } else {

            // A route wasn't matched - return a 404
            $response->send(404, 'The requested resource was not found.');
        }
    }
}
