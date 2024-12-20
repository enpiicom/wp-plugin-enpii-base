<?php

declare(strict_types=1);

namespace Enpii_Base\App\Http\Controllers\Api;

use Enpii_Base\App\Actions\Get_WP_App_Info_Action;
use Enpii_Base\App\Support\App_Const;
use Symfony\Component\HttpFoundation\JsonResponse;
use Enpii_Base\Foundation\Http\Base_Controller;

class Main_Controller extends Base_Controller {
	public function home(): JsonResponse {
		$data = Get_WP_App_Info_Action::exec();
		if ( ! empty( wp_get_current_user()->ID ) ) {
			$data['current_logged_in_user'] = wp_get_current_user();
		}

		return response()->json(
			[
				'message' => 'Welcome to Enpii Base WP App API',
				'data' => $data,
			]
		);
	}

	public function web_worker() {
		do_action( App_Const::ACTION_WP_APP_WEB_WORKER );

		return response()->json(
			[
				'message' => 'Web worker executed',
			]
		);
	}
}
