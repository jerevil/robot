<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\Handler;
use App\Exceptions\ManipulateException;
use App\Exceptions\UnknownActionException;
use App\Exceptions\UnknownPositionException;
use Exception;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;
use Throwable;

class HandlerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('expectsJson')->andReturn(true);

        $this->app->instance(Request::Class, $request);
    }

    public function exceptionDataProvider()
    {
        $message = 'something went wrong';

        return [
            [new ManipulateException($message), 500],
            [new UnknownPositionException($message), 500],
            [new UnknownActionException($message), 500],
            [new Exception($message), 500],
        ];
    }

    /**
     * @param  Throwable  $exception
     * @param  int  $expectedStatus
     * @dataProvider exceptionDataProvider
     */
    public function test_handler_handles_exceptions(Throwable $exception, int $expectedStatus)
    {
        $handler = $this->app->make(Handler::class);
        $request = $this->app->make(Request::class);
        $response = $handler->render($request, $exception);

        $this->assertEquals($expectedStatus, $response->status());
    }
}
