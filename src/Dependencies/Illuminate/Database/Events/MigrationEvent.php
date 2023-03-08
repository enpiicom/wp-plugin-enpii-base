<?php

namespace Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Events;

use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Database\Events\MigrationEvent as MigrationEventContract;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Migrations\Migration;

abstract class MigrationEvent implements MigrationEventContract
{
    /**
     * An migration instance.
     *
     * @var \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Migrations\Migration
     */
    public $migration;

    /**
     * The migration method that was called.
     *
     * @var string
     */
    public $method;

    /**
     * Create a new event instance.
     *
     * @param  \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Database\Migrations\Migration  $migration
     * @param  string  $method
     * @return void
     */
    public function __construct(Migration $migration, $method)
    {
        $this->method = $method;
        $this->migration = $migration;
    }
}
