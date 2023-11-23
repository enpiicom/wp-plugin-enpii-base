<?php

declare(strict_types=1);

namespace Enpii_Base\App\Exceptions;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Exceptions\WhoopsHandler;
use Illuminate\Http\Request;
use Monolog\Handler\HandlerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Exception;
use Throwable;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/** @noinspection PhpFullyQualifiedNameUsageInspection */
	/**
	 * Report or log an exception.
	 *
	 * @param Throwable $exception
	 *
	 * @throws \Exception
	 */
	public function report( Throwable $exception ) {
		parent::report( $exception );
	}

	/** @noinspection PhpFullyQualifiedNameUsageInspection */
	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param Throwable $exception
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
	 * @throws Throwable
	 */
	public function render( $request, Throwable $exception ) {
		return parent::render( $request, $exception );
	}

	/**
	 * @inheritedDoc
	 *
	 * @param  Request  $request
	 * @param  \Throwable  $e
	 * @return Response
	 */
	protected function prepareResponse( $request, Throwable $e ) {
		if ( ! $this->isHttpException( $e ) && wp_app_config( 'app.debug' ) ) {
			return $this->toIlluminateResponse( $this->convertExceptionToResponse( $e ), $e );
		}

		if ( ! $this->isHttpException( $e ) ) {
			$e = new HttpException( 500, $e->getMessage() );
		}

		return $this->toIlluminateResponse(
			$this->renderHttpException( $e ),
			$e
		);
	}

	/**
	 * @inheritedDoc
	 * @param HttpExceptionInterface $e
	 * @return string
	 */
    protected function getHttpExceptionView(HttpExceptionInterface $e)
    {
        return "errors/error";
    }

	/**
	 * Get the Whoops handler for the application.
	 *
	 * @return \Whoops\Handler\Handler
	 */
	protected function whoopsHandler() {
		try {
			return wp_app( HandlerInterface::class );
		} catch ( BindingResolutionException $e ) {
			return ( new WhoopsHandler() )->forDebug();
		}
	}
}
