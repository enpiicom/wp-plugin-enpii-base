<?php

namespace Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Testing\Concerns;

use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Console\OutputStyle;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Console\Kernel;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Testing\PendingCommand;

trait InteractsWithConsole
{
    /**
     * Indicates if the console output should be mocked.
     *
     * @var bool
     */
    public $mockConsoleOutput = true;

    /**
     * All of the expected output lines.
     *
     * @var array
     */
    public $expectedOutput = [];

    /**
     * All of the expected ouput tables.
     *
     * @var array
     */
    public $expectedTables = [];

    /**
     * All of the expected questions.
     *
     * @var array
     */
    public $expectedQuestions = [];

    /**
     * All of the expected choice questions.
     *
     * @var array
     */
    public $expectedChoices = [];

    /**
     * Call artisan command and return code.
     *
     * @param  string  $command
     * @param  array  $parameters
     * @return \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Testing\PendingCommand|int
     */
    public function artisan($command, $parameters = [])
    {
        if (! $this->mockConsoleOutput) {
            return $this->app[Kernel::class]->call($command, $parameters);
        }

        return new PendingCommand($this, $this->app, $command, $parameters);
    }

    /**
     * Disable mocking the console output.
     *
     * @return $this
     */
    protected function withoutMockingConsoleOutput()
    {
        $this->mockConsoleOutput = false;

        $this->app->offsetUnset(OutputStyle::class);

        return $this;
    }
}
