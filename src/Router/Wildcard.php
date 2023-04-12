<?php

namespace Code\Router;

class Wildcard
{
    private $parameters;

    public function resolveRoute($uri, &$routeCollection)
    {
        $keysRouteCollection = array_keys($routeCollection);
        $routeWithParameters = [];


        foreach ($keysRouteCollection as $route) {
            if (preg_match('/{(\w+?)\}/', $route)) {
                $routeWithParameters[] = $route;
            }
        }

        foreach ($routeWithParameters as $route ) {
            $routeWithoutParameters = preg_replace('/\/{(\w+?)\}/', '', $route);
            $uriWithoutParameters = preg_replace('/\/[0-9]+$/', '', $uri);

            if ($routeWithoutParameters === $uriWithoutParameters) {
                $routeCollection[$uri] = $routeCollection[$route];
                $this->parameters = $this->resolveParameter($uri);
            }
            
        }
    }

    public function getParameters()
    {
        return $this->parameters;
    }
    
    private function resolveParameter($uri)
    {
        $matches = [];

        preg_match('/[0-9]+$/', $uri, $matches);

        return $matches;
    }
}
