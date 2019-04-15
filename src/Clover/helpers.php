<?php

namespace Roots\Clover;

/**
 * Get the available container instance.
 *
 * @param  string $abstract
 * @param  array  $parameters
 * @return mixed|\Roots\Clover\Plugin
 *
 * @copyright Taylor Otwell
 * @license   https://github.com/laravel/framework/blob/v5.6.25/LICENSE.md MIT
 * @link      https://github.com/laravel/framework/blob/v5.6.25/src/Illuminate/Foundation/helpers.php#L106-L120
 */
function plugin($abstract = null, array $parameters = [])
{
    if (is_null($abstract)) {
        return Plugin::getInstance();
    }

    return Plugin::getInstance()->make($abstract, $parameters);
}
