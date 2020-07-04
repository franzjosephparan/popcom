<?php
if (!function_exists('urlGenerator')) {
    /**
     * @return \Laravel\Lumen\Routing\UrlGenerator
     */
    function urlGenerator() {
        return new \Laravel\Lumen\Routing\UrlGenerator(app());
    }
}

if (!function_exists('asset')) {
    /**
     * @param $path
     * @param bool $secured
     *
     * @return string
     */
    function asset($path, $secured = false) {
        return urlGenerator()->asset($path, env('REDIRECT_HTTPS'));
    }
}

if (!function_exists('public_path')) {
    function public_path($path = null) {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}
