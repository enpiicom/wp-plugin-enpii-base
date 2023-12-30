<?php

namespace Enpii_Base\App\Jobs;

use Enpii_Base\App\Support\App_Const;
use Enpii_Base\Foundation\Bus\Dispatchable_Trait;
use Enpii_Base\Foundation\Shared\Base_Job;
use Illuminate\Support\Facades\Artisan;

class Perform_Queue_Work_Job extends Base_Job {
	use Dispatchable_Trait;

	public function handle() {
		Artisan::call(
			'queue:work',
			[
				'connection' => 'database',
				'--queue' => App_Const::QUEUE_HIGH . ',' . App_Const::QUEUE_DEFAULT . ',' . App_Const::QUEUE_LOW,
				'--tries' => 3,
				'--quiet' => true,
				'--stop-when-empty' => true,
				'--timeout' => 60,
				'--memory' => 256,
			]
		);
	}
}
