<?php

declare(strict_types=1);

namespace Enpii_Base\App\Actions\WP_CLI;

use Enpii_Base\Foundation\Actions\Base_Action;
use Enpii_Base\Foundation\Support\Executable_Trait;
use InvalidArgumentException;

/**
 * @method static void exec()
 */
class Process_Artisan_Action extends Base_Action {
	use Executable_Trait;

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(): void {
		$app = app();

		if ( ! is_object( $app ) || ! method_exists( $app, 'make' ) ) {
			throw new InvalidArgumentException( 'Application instance is not valid.' );
		}

		/** @var \Enpii_Base\App\Console\Kernel $kernel */
		$kernel = $app->make(
			\Illuminate\Contracts\Console\Kernel::class
		);

		// We need to remove 2 first items to match the artisan arguments
		$args = isset( $_SERVER['argv'] ) && is_array( $_SERVER['argv'] ) ? array_map(
			function ( $arg ) {
				return is_string( $arg ) ? sanitize_text_field( wp_unslash( $arg ) ) : $arg;
			},
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			(array) $_SERVER['argv']
		) 
			: [];
		
		if ( ! in_array( 'artisan', $args ) ) {
			throw new InvalidArgumentException( 'Not an artisan command' );
		}

		$artisan_args = [];
		foreach ( $args as $arg ) {
			if ( $arg === 'artisan' || ! empty( $artisan_args ) ) {
				$artisan_args[] = $arg;
			}
		}

		$input = new \Symfony\Component\Console\Input\ArgvInput( $artisan_args );

		$status = $kernel->handle(
			$input,
			new \Symfony\Component\Console\Output\ConsoleOutput()
		);

		$this->exit_with_status( $status );
	}

	protected function exit_with_status( int $status ): void {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit( $status );
	}
}
