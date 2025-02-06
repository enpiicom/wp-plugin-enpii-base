<?php

declare(strict_types=1);

namespace Enpii_Base\App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class WP_User_Can_And extends Middleware {

	public function handle( $request, Closure $next, ...$capabilities ) {
		$message = config( 'app.debug' ) ?
			__( 'Access Denied! You need to login with proper account to perform this action!', 'enpii-base' ) . ' :: ' . implode( ', ', (array) $capabilities ) :
			__( 'Access Denied!', 'enpii-base' );

		foreach ( $capabilities as $capability ) {
			if ( ! current_user_can( $capability ) ) {
				abort( 403, $message );
			}
		}

		return $next( $request );
	}
}
