<?php

namespace Enpii_Base\Deps\Illuminate\Contracts\Broadcasting;

interface Factory
{
    /**
     * Get a broadcaster implementation by name.
     *
     * @param  string|null  $name
     * @return \Enpii_Base\Deps\Illuminate\Contracts\Broadcasting\Broadcaster
     */
    public function connection($name = null);
}
