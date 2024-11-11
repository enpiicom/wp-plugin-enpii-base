<?php

declare(strict_types=1);

namespace Enpii_Base\App\Actions;

use Enpii_Base\App\Support\Traits\Enpii_Base_Trans_Trait;
use Enpii_Base\Foundation\Actions\Base_Action;
use Enpii_Base\Foundation\Support\Executable_Trait;
use Enpii_Base\Foundation\WP\WP_Plugin_Interface;
use Illuminate\Support\Facades\Session;

/**
 * @method static function exec(): void
 */
class Show_Admin_Notice_And_Disable_Plugin_Action extends Base_Action {
	use Executable_Trait;
	use Enpii_Base_Trans_Trait;

	/**
	 * @var \Enpii_Base\Foundation\WP\WP_Plugin
	 */
	protected $plugin;
	protected $extra_messages;

	public function __construct( WP_Plugin_Interface $plugin, array $extra_messages = [] ) {
		$this->plugin = $plugin;
		$this->extra_messages = $extra_messages;
	}

	/**
		 * Handle the action.
		 *
		 * @throws \Exception
		 */
	public function handle(): void {
		foreach ( $this->extra_messages as $message ) {
			Session::push( 'caution', $message );
		}

		Session::push(
			'caution',
			sprintf(
				// translators: %s is replaced with "string" plugin name
				__( 'Plugin <strong>%s</strong> is disabled.', 'enpii' ),
				$this->plugin->get_name() . ' ' . $this->plugin->get_version()
			)
		);

		$this->load_plugin_file();
		deactivate_plugins( $this->plugin->get_plugin_basename() );
	}

	protected function load_plugin_file(): void {
		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
	}
}
