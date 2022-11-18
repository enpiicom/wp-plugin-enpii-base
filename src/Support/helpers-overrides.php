<?php

declare(strict_types=1);

/**
| We want to define helper functions that override other helpers functions here
|
*/

if (! function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function storage_path($path = '')
    {
		die(wp_app('path.storage'));
        return wp_app('path.storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
