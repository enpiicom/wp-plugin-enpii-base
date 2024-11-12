<?php

declare(strict_types=1);

namespace Enpii_Base\App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class WP_User_Can_Or extends Middleware {

	public function handle( $request, Closure $next, ...$capabilities ) {
		$message = config( 'app.debug' ) ?
			__( 'Access Denied! You need to login with proper account to perform this action!', 'enpii' ) . ' :: ' . implode( ', ', (array) $capabilities ) :
			__( 'Access Denied!', 'enpii' );

		foreach ( $capabilities as $capability ) {
			if ( current_user_can( $capability ) ) {
				return $next( $request );
			}
		}

		abort( 403, $message );
	}
}
