<?php

namespace CodeTests\Router;

use PHPUnit\Framework\TestCase;
use Code\Router\Router;

class RouterTest extends TestCase
{
    //CONFLITANDO COM OUTRO TESTE
    // public function testRouterSetOfRoutes()
    // {
    //     $_SERVER['REQUEST_URI'] = '/users';

    //     $router = new Router();

    //     $router->addRoute('/users', function() {
    //         return 'Primeira rota';
    //     });

    //     $result = $router->run();
    //     $this->assertEquals('Primeira rota', $result);
    // }

    public function testValidateANoRouteFound()
    {
        $this->expectException('\Exception');
        $this->expectExceptionMessage('Route Not Found');

        $_SERVER['REQUEST_URI'] = '/products';
        $router = new Router();
        $router->run();
    }

    public function testWithAControllerAssociate()
    {
        $_SERVER['REQUEST_URI'] = '/products';
        $router = new Router();
        $router->addRoute('/products', '\\CodeTests\\Controller\\ProductController@index');

        $result = $router->run();

        $this->assertEquals('Controller Product', $result);
    }

    public function testAWrongFormatToACallControllerAsASecondParameterOfTheOurRouter()
    {
        $this->expectException('\InvalidArgumentException');
        $this->expectExceptionMessage('Wrong format to call a controller.');

        $_SERVER['REQUEST_URI'] = '/products';

        $router = new Router();

        $router->addRoute('/products', '\\CodeTests\\Controller\\ProductController');

        $router->run();
    }

    public function testThrowExceptionWhenAMethodDoesNotExistsInAController()
    {
        $this->expectException('\Exception');
        $this->expectExceptionMessage('Method does not exists');

        $_SERVER['REQUEST_URI'] = '/products';
        $router = new Router();

        $router->addRoute('/products', '\\CodeTests\\Controller\\ProductController@getProduct');

        $router->run();
    }

    public function testRouteWithDynamicParameters()
    {
        $_SERVER['REQUEST_URI'] = '/users/10';

        $router = new Router();

        $router->addRoute('/users/{id}', function ($id) {
            return 'Rota com parametro & parametro é igual a ' . $id;
        });

        $result = $router->run();

        $this->assertEquals('Rota com parametro & parametro é igual a 10', $result);
    }

    public function testRouteWithPrefix()
    {
        $_SERVER['REQUEST_URI'] = '/users/edit/10';

        $router = new Router();

        $router->prefix('/users', function (Router $router) {
            $router->addRoute('/edit/{id}', function ($id) {
                return 'Rota com prefixo e id ' . $id;
            });
            $router->addRoute('/update/{id}', function ($id) {
                return 'Rota com prefixo e id ' . $id;
            });
        });

        $result = $router->run();

        $this->assertEquals('Rota com prefixo e id 10', $result);
    }
}
