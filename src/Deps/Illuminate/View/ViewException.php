<?php

namespace Enpii_Base\Deps\Illuminate\View;

use ErrorException;
use Enpii_Base\Deps\Illuminate\Container\Container;
use Enpii_Base\Deps\Illuminate\Support\Reflector;

class ViewException extends ErrorException
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        $exception = $this->getPrevious();

        if (Reflector::isCallable($reportCallable = [$exception, 'report'])) {
            return Container::getInstance()->call($reportCallable);
        }

        return false;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Enpii_Base\Deps\Illuminate\Http\Request  $request
     * @return \Enpii_Base\Deps\Illuminate\Http\Response
     */
    public function render($request)
    {
        $exception = $this->getPrevious();

        if ($exception && method_exists($exception, 'render')) {
            return $exception->render($request);
        }
    }
}
