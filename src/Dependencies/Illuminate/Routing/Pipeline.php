<?php

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Routing;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Debug\ExceptionHandler;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Support\Responsable;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Http\Request;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Pipeline\Pipeline as BasePipeline;
use Throwable;

/**
 * This extended pipeline catches any exceptions that occur during each slice.
 *
 * The exceptions are converted to HTTP responses for proper middleware handling.
 */
class Pipeline extends BasePipeline
{
    /**
     * Handles the value returned from each pipe before passing it to the next.
     *
     * @param  mixed  $carry
     * @return mixed
     */
    protected function handleCarry($carry)
    {
        return $carry instanceof Responsable
            ? $carry->toResponse($this->getContainer()->make(Request::class))
            : $carry;
    }

    /**
     * Handle the given exception.
     *
     * @param  mixed  $passable
     * @param  \Throwable  $e
     * @return mixed
     *
     * @throws \Throwable
     */
    protected function handleException($passable, Throwable $e)
    {
        if (! $this->container->bound(ExceptionHandler::class) ||
            ! $passable instanceof Request) {
            throw $e;
        }

        $handler = $this->container->make(ExceptionHandler::class);

        $handler->report($e);

        $response = $handler->render($passable, $e);

        if (is_object($response) && method_exists($response, 'withException')) {
            $response->withException($e);
        }

        return $response;
    }
}
