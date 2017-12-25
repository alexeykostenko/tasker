<?php

/**
 * Debug function.
 */
function dd()
{
    array_map(function($x) { var_dump($x); }, func_get_args());
    die();
}

/**
 * Set the specified settings.
 */
function set_settings()
{
    ini_set('display_errors', config('debug'));
}

function request()
{
    return Library\Request::getInstance();
}

/**
 * Get the specified configuration value.
 *
 * @param  string $key
 * @param  mixed  $default
 * @return mixed
 */
function config($key = null, $default = null)
{
    $configFile = 'config.php';
    $config = new Library\Config;

    if (is_null($key)) {
        return $config->all();
    }

    return $config->get($key, $default);
}

/**
 * Get the path to the application folder.
 *
 * @param  string $path
 * @return string
 */
function app_path($path = '')
{
    return __DIR__ . ($path ? DIRECTORY_SEPARATOR . $path : $path);
}

/**
 * Get the relative path to the image storage.
 *
 * @param  string $path
 * @return string
 */
function image_relative_path($path = '')
{
    return config('image_folder') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
}


/**
 * Get the model by name.
 *
 * @param  string $model
 * @return object
 */
function model($model)
{
    $namespaceModel = '\\App\\Models\\' . $model;
    return new $namespaceModel();
}

/**
 * Get the twig view by name.
 *
 * @param  string $view
 * @param  array  $params
 * @return object
 */
function view($view, $params = [])
{
    $loader = new Twig_Loader_Filesystem(APP_PATH . '/views');

    $twig = new Twig_Environment($loader, array(
        'debug' => config('debug'),
        'cache' => APP_PATH . '/' . config('twig_cache_folder'),
    ));

    $twig->addGlobal('auth', auth()->check());

    $twigExtension = '.html.twig';
    $twig->loadTemplate(config('twig_template_name') . $twigExtension);
    $twig->render($view . $twigExtension, $params);

    return $twig->render($view . $twigExtension, $params);
}

/**
 * Get actual image path (rename if file exists).
 *
 * @param  string $path
 * @return string
 */
function get_actual_path($path)
{
    $original_name = pathinfo($path,PATHINFO_FILENAME);
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    $actual_path = $path;
    $i = 1;

    while(file_exists($actual_path))
    {
        $actual_name = (string) $original_name . '(' . $i . ')';
        $actual_path = str_replace($original_name . "." . $extension, $actual_name . "." . $extension, $path);
        $i++;
    }

    return $actual_path;
}

/**
 * Get session.
 *
 * @return object
 */
function session()
{
    return new Library\Session;
}

/**
 * Get auth.
 *
 * @return object
 */
function auth()
{
    return new Library\Auth;
}
