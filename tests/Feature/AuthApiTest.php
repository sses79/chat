<?php

namespace Tests\Feature;

use App\Http\Middleware\AuthApiMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    public function test_api_request_with_x_api_key()
    {
        $request = Request::create('/api/demo');

        $request->headers->set('X-API-KEY', 'Gm638pb1jA');

        $middleware = new AuthApiMiddleware();

        $response = $middleware->handle($request, function ($request) {
            return new Response();
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_api_request_without_x_api_key()
    {
        $request = Request::create('/api/demo');

        $middleware = new AuthApiMiddleware();

        $response = $middleware->handle($request, function ($request) {
            return new Response();
        });

        $this->assertEquals(401, $response->getStatusCode());
    }
}
