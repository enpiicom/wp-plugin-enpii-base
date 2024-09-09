<?php

declare(strict_types=1);

namespace Enpii_Base\App\Jobs;

use Enpii_Base\App\Support\Traits\Queue_Trait;
use Enpii_Base\Foundation\Support\Executable_Trait;
use Illuminate\Console\Scheduling\Schedule;

/**
 * @method static function exec(): void
 */
class Schedule_Run_Backup {
	use Executable_Trait;
	use Queue_Trait;

	/**
	 * @var Schedule
	 */
	protected $schedule;

	public function __construct( Schedule $schedule ) {
		$this->schedule = $schedule;
	}

	public function handle() {
		$schedule = $this->schedule;

		// We need to have the correct ARTISAN_BINARY value to the second section
		//  of the above console command
		$schedule->command( 'backup:clean' )->daily()->at( '01:00' );
		$schedule->command( 'backup:run' )->daily()->at( '02:00' );
		$schedule->command( 'telescope:prune' )->daily()->at( '03:00' );
	}
}
