<?php

namespace Tests\Feature;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use RuntimeException;
use Tests\TestCase;

class NotFoundTest extends TestCase
{
    public function test_unknown_routes_return_json_404(): void
    {
        $this->getJson('/')->assertNotFound()->assertExactJson([
            'message' => 'Not Found',
        ]);

        $this->postJson('/api/unknown')->assertNotFound()->assertExactJson([
            'message' => 'Not Found',
        ]);
    }

    public function test_unhandled_exceptions_return_common_json_500(): void
    {
        config()->set('app.debug', true);

        $response = app(ExceptionHandler::class)->render(
            Request::create('/failure'),
            new RuntimeException('Sensitive error'),
        );

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame('Internal Server Error', $response->getData(true)['message']);
        $this->assertArrayHasKey('file', $response->getData(true));
        $this->assertArrayHasKey('line', $response->getData(true));
        $this->assertArrayHasKey('trace', $response->getData(true));
    }
}
