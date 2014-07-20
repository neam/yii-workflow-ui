<?php
/**
 * global.php file.
 * Global shorthand functions for commonly used Yii methods.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * Returns the application instance.
 * @return WebApplication
 */
function app()
{
    return Yii::app();
}

/**
 * Returns the application parameter with the given name.
 * @param $name
 * @return mixed
 */
function param($name)
{
    return isset(Yii::app()->params[$name]) ? Yii::app()->params[$name] : null;
}

/**
 * Returns the client script instance.
 * @return CClientScript
 */
function clientScript()
{
    return Yii::app()->getClientScript();
}

/**
 * Returns the main database connection.
 * @return CDbConnection
 */
function db()
{
    return Yii::app()->getDb();
}

/**
 * Returns the formatter instance.
 * @return Formatter
 */
function format()
{
    return Yii::app()->getComponent('format');
}

/**
 * Returns the date formatter instance.
 * @return CDateFormatter
 */
function dateFormatter()
{
    return Yii::app()->getDateFormatter();
}

/**
 * Returns the date formatter instance.
 * @return CDateFormatter
 */
function numberFormatter()
{
    return Yii::app()->getNumberFormatter();
}

/**
 * Returns the request instance.
 * @return CHttpRequest
 */
function request()
{
    return Yii::app()->getRequest();
}

/**
 * Returns the session instance.
 * @return CHttpSession
 */
function session()
{
    return Yii::app()->getSession();
}

/**
 * Returns the web user instance for the logged in user.
 * @return CWebUser
 */
function user()
{
    return Yii::app()->getUser();
}

/**
 * Translates the given string using Yii::t().
 * @param $category
 * @param $message
 * @param array $params
 * @param string $source
 * @param string $language
 * @return string
 */
function t($category, $message, $params = array(), $source = null, $language = null)
{
    return Yii::t($category, $message, $params, $source, $language);
}

/**
 * Returns the base URL for the given URL.
 * @param string $url
 * @return string
 */
function baseUrl($url = '')
{
    static $baseUrl;
    if (!isset($baseUrl)) {
        $baseUrl = Yii::app()->request->baseUrl;
    }
    return $baseUrl . '/' . ltrim($url, '/');
}

/**
 * Registers the given CSS file.
 * @param $url
 * @param string $media
 */
function css($url, $media = '')
{
    Yii::app()->clientScript->registerCssFile(baseUrl($url), $media);
}

/**
 * Registers the given JavaScript file.
 * @param $url
 * @param null $position
 */
function js($url, $position = null)
{
    Yii::app()->clientScript->registerScriptFile(baseUrl($url), $position);
}

/**
 * Publishes and registers a JavaScript file.
 * @param string $file path to file (relative to the app directory).
 */
function publishJs($file, $position = null)
{
    $hashByName = true;
    $level = -1;
    $forceCopy = false; //(defined('DEV') && DEV) || !empty($_GET['refresh_assets']) ? true : false;

    $filePath = $file;
    $jsFile = Yii::app()->getAssetManager()->publish($filePath, $hashByName, $level, $forceCopy);
    Yii::app()->clientScript->registerScriptFile($jsFile, $position);
}

/**
 * Publishes and registers a CSS file.
 * @param string $file the CSS file.
 * @param string $media
 */
function publishCss($file, $media = '')
{
    $hashByName = true;
    $level = -1;
    $forceCopy = false; //(defined('DEV') && DEV) || !empty($_GET['refresh_assets']) ? true : false;

    $filePath = $file;
    $cssFile = Yii::app()->getAssetManager()->publish($filePath, $hashByName, $level, $forceCopy);
    Yii::app()->clientScript->registerCssFile($cssFile, $media);
}

/**
 * Registers an asset package.
 * @param string $name the package name.
 * @param string $basePath the base path for the assets.
 * @param array $css the CSS files to register.
 * @param array $js the JavaScript files to register.
 * @param array $js the package dependencies (e.g. jquery).
 */
function registerPackage($name, $basePath, $css = array(), $js = array(), $depends = array())
{
    $package['basePath'] = $basePath;
    $package['css'] = $css;
    $package['js'] = $js;
    $package['depends'] = $depends;

    Yii::app()->clientScript
        ->addPackage($name, $package)
        ->registerPackage($name);
}

/**
 * Escapes the given string using CHtml::encode().
 * @param $text
 * @return string
 */
function e($text)
{
    return CHtml::encode($text);
}

/**
 * Returns the escaped value of a model attribute.
 * @param $model
 * @param $attribute
 * @param null $defaultValue
 * @return string
 */
function v($model, $attribute, $defaultValue = null)
{
    return CHtml::encode(CHtml::value($model, $attribute, $defaultValue));
}

/**
 * Purifies the given HTML.
 * @param $text
 * @return string
 */
function purify($text)
{
    static $purifier;
    if (!isset($purifier)) {
        $purifier = new CHtmlPurifier;
    }
    return $purifier->purify($text);
}

/**
 * Returns the given markdown text as purified HTML.
 * @param $text
 * @return string
 */
function markdown($text)
{
    static $parser;
    if (!isset($parser)) {
        $parser = new MarkdownParser;
    }
    return $parser->safeTransform($text);
}

/**
 * Creates an image tag using CHtml::image().
 * @param $src
 * @param string $alt
 * @param array $htmlOptions
 * @return string
 */
function img($src, $alt = '', $htmlOptions = array())
{
    return CHtml::image(baseUrl($src), $alt, $htmlOptions);
}

/**
 * Creates a link to the given url using CHtml::link().
 * @param $text
 * @param string $url
 * @param array $htmlOptions
 * @return string
 */
function l($text, $url = '#', $htmlOptions = array())
{
    return CHtml::link($text, $url, $htmlOptions);
}

/**
 * Creates a relative URL using CUrlManager::createUrl().
 * @param $route
 * @param array $params
 * @param string $ampersand
 * @return mixed
 */
function url($route, $params = array(), $ampersand = '&')
{
    return Yii::app()->urlManager->createUrl($route, $params, $ampersand);
}

/**
 * Encodes the given object using json_encode().
 * @param mixed $value
 * @param integer $options
 * @return string
 */
function jsonEncode($value, $options = 0)
{
    return json_encode($value, $options);
}

/**
 * Decodes the given JSON string using json_decode().
 * @param $string
 * @param boolean $assoc
 * @param integer $depth
 * @param integer $options
 * @return mixed
 */
function jsonDecode($string, $assoc = true, $depth = 512, $options = 0)
{
    return json_decode($string, $assoc, $depth, $options);
}

/**
 * Sets a flash message.
 * @param string $type the flash message type.
 * @param string $message the message.
 */
function setFlash($type, $message)
{
    Yii::app()->user->setFlash($type, $message);
}

/**
 * Returns the current time as a MySQL date.
 * @param integer $timestamp the timestamp.
 * @return string the date.
 */
function sqlDate($timestamp = null)
{
    if ($timestamp === null) {
        $timestamp = time();
    }
    return date('Y-m-d', $timestamp);
}

/**
 * Returns the current time as a MySQL date time.
 * @param integer $timestamp the timestamp.
 * @return string the date time.
 */
function sqlDateTime($timestamp = null)
{
    if ($timestamp === null) {
        $timestamp = time();
    }
    return date('Y-m-d H:i:s', $timestamp);
}

/**
 * Dumps the given variable using CVarDumper::dumpAsString().
 * @param mixed $var
 * @param int $depth
 * @param bool $highlight
 */
function dump($var, $depth = 10, $highlight = true)
{
    echo CVarDumper::dumpAsString($var, $depth, $highlight);
}

/**
 * Dumps the given variable using CVarDumper::dumpAsString(), and executes die().
 * @param mixed $var
 * @param int $depth
 * @param bool $highlight
 */
function dumpd($var, $depth = 10, $highlight = true)
{
    dump($var, $depth, $highlight);
    die();
}

/**
 * Dumps the given variable using var_export().
 * @param mixed $var
 */
function ajaxdump($var)
{
    var_export($var);
}

/**
 * Dumps the given variable and terminates the application.
 * @param mixed $var
 */
function ajaxdumpd($var)
{
    ajaxdump($var);
    endApp();
}

/**
 * Terminates the application.
 */
function endApp()
{
    Yii::app()->end();
}

/**
 * Sends a message to an appropriate debug output/log
 */
function inspect($data)
{

    if (function_exists('codecept_debug')) {
        codecept_debug($data);
    } elseif (DEBUG_LOGS === true) {
        Yii::log("Debug: " . print_r($data, true), 'inspection', __METHOD__);
    } else {
        var_dump($data);
    }

}