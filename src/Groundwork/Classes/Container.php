<?php

namespace Groundwork\Classes;

use Exception;

/**
 * The Inversion of Control container.
 */
class Container
{
    /**
     * Object instances.
     * 
     * @var array
     */
    protected $instance = array();
    
    /**
     * Aliases registered.
     * 
     * @var array
     */
    protected $registered;
    
    /**
     * Registers a alias and Closure pair in the IoC container.
     * 
     * @param string $alias
     * @param \Closure $closure
     * @return boolean
     */
    public function register($alias, \Closure $closure)
    {
        if (!is_string($alias))
            throw new Exception('Application::register() requires a string as the first parameter.');
        
        $this->registered[$alias] = $closure;
        
        return true;
    }
    
    /**
     * Returns the instance associated with the supplied alias.
     * 
     * @param string $alias
     * @return object|boolean
     */
    public function get($alias)
    {
        if (!isset($this->registered[$alias]))
            return false;
        
        // Does an instance exist for this alias?
        if (isset($this->instance[$alias]) && is_object($this->instance[$alias]))
            return $this->instance[$alias];
        
        // Execute the Closure to get the first instance
        $this->instance[$alias] = $this->registered[$alias]($this);
        if (!is_object($this->instance[$alias]))
            throw new Exception('The alias "' . $alias . '" does not return an object.');
        
        return $this->instance[$alias];
    }
    
    /**
     * Returns a new instance associated with the supplies alias.
     * 
     * @param string $alias
     * @return object|boolean
     */
    public function getNew($alias)
    {
        if (!isset($this->registered[$alias]))
            return false;
        
        // Execute the Closure to get the first instance
        $object = $this->registered[$alias]($this);
        if (!is_object($object))
            throw new Exception('The alias "' . $alias . '" does not return an object.');
        
        return $object;
    }
}