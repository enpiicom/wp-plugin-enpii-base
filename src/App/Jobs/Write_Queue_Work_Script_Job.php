<?php

declare(strict_types=1);

namespace Enpii_Base\App\Jobs;

use Enpii_Base\Foundation\Bus\Dispatchable_Trait;
use Enpii_Base\Foundation\Shared\Base_Job;
use Illuminate\Contracts\Container\BindingResolutionException;
use PHPUnit\Framework\ExpectationFailedException;
use Exception;

class Write_Queue_Work_Script_Job extends Base_Job {
	use Dispatchable_Trait;

	/**
	 * Write a js to have periodly ajax request to handle the queue
	 *
	 * @return void
	 * @throws BindingResolutionException
	 * @throws ExpectationFailedException
	 * @throws Exception
	 */
	public function handle(): void {
		// We want to add the trailing slash to avoid the redirect in WP webserver rule
		$queue_work_url = esc_js( wp_app_route_wp_url( 'wp-app-queue-work' ) . '/' );

		// We want to have an interval that works every 2 mins (120 000 miliseconds)
		//	To perform the queue execution because we set timeout
		//	for the queue-work endpoint to 60 seconds
		$script = <<<SCRIPT
		<script type="text/javascript">
			var enpii_base_queue_work_url = '$queue_work_url';
			function ajax_request_to_queue_work() {
				if (typeof(jQuery) !== 'undefined') {
					jQuery.ajax({
						url: enpii_base_queue_work_url,
						method: "POST"
					});
				} else {
					const response = fetch(enpii_base_queue_work_url);
				}
			}
			var ajax_request_to_queue_work_interval = window.setInterval(function(){
				ajax_request_to_queue_work();
			}, 120000);
			window.setTimeout(function() {
				ajax_request_to_queue_work();
			}, 12000);
		</script>
SCRIPT;
		echo $script;
	}
}
