<?php

namespace Library;

use Controller;

class Route
{
    private $uri;
    private $action;
    private $compiled;


    /**
     * The regular expression requirements.
     *
     * @var array
     */
    public $wheres = [];

    /**
     * The array of matched parameters.
     *
     * @var array
     */
    public $parameters;

    /**
     * The parameter names for the route.
     *
     * @var array|null
     */
    public $parameterNames;


    static private $instance = null;

    private function __construct() { /* ... @return Singleton */ }  // Protect from creation via new Singleton
    private function __clone() { /* ... @return Singleton */ }  // Protect from creation via cloning
    private function __wakeup() { /* ... @return Singleton */ }  // Protect from creation via unserialize

    private function getInstance() {
        if (static::$instance === null) {
            static::$instance = $this;
        }

        return static::$instance;
    }

    private function initialize($uri, $action)
    {
        $this->setUri($uri)->setAction($action);
    }

    /**
     * Set the URI that the route responds to.
     *
     * @param  string  $uri
     * @return $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Set the action array for the route.
     *
     * @param  array  $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Register a new GET route with the router.
     *
     * @param  string  $uri
     * @param  string  $action
     */
    public function get($uri, $action)
    {
        return self::actionCall('GET', $uri, $action);
    }

    /**
     * Register a new POST route with the router.
     *
     * @param  string  $uri
     * @param  string  $action
     */
    public function post($uri, $action)
    {
        return self::actionCall('POST', $uri, $action);
    }

    /**
     * Register a new PUT route with the router.
     *
     * @param  string  $uri
     * @param  string  $action
     */
    public function put($uri, $action)
    {
        return self::actionCall('PUT', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string  $uri
     * @param  string  $action
     */
    public function delete($uri, $action)
    {
        return self::actionCall('DELETE', $uri, $action);
    }

    /**
     * Call a action from Controller.
     *
     * @param  string  $uri
     * @param  string  $action
     */
    public function actionCall($method, $uri, $action)
    {
        if ($method !== request()->getMethod()) {
            return false;
        }

        $routeUrl = '/' . ltrim($uri, '/');
        $requestUrl = strtok(request()->url(), '?');

        if ($routeUrl !== $requestUrl) {
            return false;
        }      

        $actionParts = explode('@', $action);
        $controller = $actionParts[0];
        $action = $actionParts[1];

        $namespaceController = '\\App\\Controllers\\' . $controller;

        if (!class_exists($namespaceController)) {
            throw new \Exception("The class {$namespaceController} does not exist");
        }

        $controller = new $namespaceController;

        if (!method_exists($controller, $action)) {
            throw new \Exception("The method {$action} does't exist");
        }

        echo $controller->$action();
    }
}
