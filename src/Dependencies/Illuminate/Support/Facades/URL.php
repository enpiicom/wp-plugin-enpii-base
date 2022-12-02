<?php

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Support\Facades;

/**
 * @method static \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Contracts\Routing\UrlGenerator setRootControllerNamespace(string $rootNamespace)
 * @method static bool hasValidSignature(\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Http\Request $request, bool $absolute = true)
 * @method static string wp_app_action(string $action, $parameters = [], bool $absolute = true)
 * @method static string asset(string $path, bool $secure = null)
 * @method static string current()
 * @method static string full()
 * @method static string previous($fallback = false)
 * @method static string route(string $name, $parameters = [], bool $absolute = true)
 * @method static string secure(string $path, array $parameters = [])
 * @method static string signedRoute(string $name, array $parameters = [], \DateTimeInterface|\DateInterval|int $expiration = null, bool $absolute = true)
 * @method static string temporarySignedRoute(string $name, \DateTimeInterface|\DateInterval|int $expiration, array $parameters = [], bool $absolute = true)
 * @method static string to(string $path, $extra = [], bool $secure = null)
 * @method static void defaults(array $defaults)
 * @method static void forceScheme(string $scheme)
 *
 * @see \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Routing\UrlGenerator
 */
class URL extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'url';
    }
}
