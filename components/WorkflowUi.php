<?php

class WorkflowUi extends CComponent
{

    // Theme constants
    const THEME_FRONTEND = 'frontend';
    const THEME_BACKEND2 = 'backend2';

    // View layout constants
    const LAYOUT_MAIN = 'vendor.neam.yii-simplicity-theme.views.layouts.main';
    const LAYOUT_REGULAR = 'vendor.neam.yii-simplicity-theme.views.layouts.regular';
    const LAYOUT_MINIMAL = 'vendor.neam.yii-simplicity-theme.views.layouts.minimal';
    const LAYOUT_NARROW = 'vendor.neam.yii-simplicity-theme.views.layouts.narrow';

    /**
     * @var string application version
     * TODO: Update this automatically.
     */
    public $version = '0.4.0';

    public function init()
    {

    }

    /**
     * Registers CSS files.
     */
    public function registerCss()
    {
        $path = 'assets';
        $files = array('main.css', 'bootstrap-switch.min.css');

        if (!empty($files)) {

            // Set the CSS path
            $forceCopy = (defined('DEV') && DEV) || !empty($_GET['refresh_assets']) ? true : false;
            if ($forceCopy) {
                // The "forceCopy" and "linkAssets" cannot be both true.
                // Get an error if both true
                Yii::app()->assetManager->linkAssets = false;
            }
            $css = Yii::app()->assetManager->publish(
                Yii::app()->theme->basePath . '/' . $path,
                true, // hash by name
                -1, // level
                $forceCopy
            );

            $clientScript = Yii::app()->getClientScript();
            foreach ($files as $file) {
                $clientScript->registerCssFile($css . '/' . $file);
            }
        }
    }

    /**
     * Registers fonts.
     */
    public function registerFonts()
    {
        $fonts = isset(app()->params['fonts'])
            ? app()->params['fonts']
            : array();

        foreach ($fonts as $fontName => $config) {
            registerPackage($fontName, $config['basePathAlias'], $config['css']);
        }
    }

    /**
     * Registers JavaScript files.
     */
    public function registerScripts()
    {
        $baseUrl = Yii::app()->baseUrl;
        $clientScript = Yii::app()->getClientScript();
        $js = Yii::app()->assetManager->publish(
            Yii::app()->theme->basePath . '/assets',
            true, // hash by name
            -1 // level

        );
        $clientScript->registerScriptFile($js . '/angular.min.js');

        $clientScript->registerScriptFile($js . '/angular-loader.min.js');
        $clientScript->registerScriptFile($js . '/angular-route.min.js');
        $clientScript->registerScriptFile($js . '/angular-sanitize.min.js');

        //$this->ga->registerTracking();
        Yii::app()->yiistrap->registerAllScripts();
        Html::jsDirtyForms(); // TODO: Load this only when needed.
    }

    /**
     * Returns all available languages.
     * @return array
     */
    public function getLanguages()
    {
        return LanguageHelper::getLanguageList();
    }

    /**
     * Returns a language name by the given language code.
     * @param string $languageCode
     * @return string
     */
    public function getLanguageNameByCode($languageCode)
    {
        $languages = $this->getLanguages();

        return (isset($languages[$languageCode]))
            ? $languages[$languageCode]
            : $languageCode;
    }

    public function clientConfigJson()
    {
        return
            CJSON::encode(
                array(
                    'baseUrl' => baseUrl(),
                    'cacheBuster' => $this->resolveCacheBuster(),
                )
            );
    }

    /**
     * Returns the cache buster for this application.
     * @return string cache buster.
     */
    public function resolveCacheBuster()
    {
        return md5(YII_DEBUG ? time() : $this->version);
    }

    /**
     * Returns the user role specific home URL (overrides CApplication::getHomeUrl)
     * @return string
     */
    public function getHomeUrl()
    {
        return !user()->isAdmin() && (user()->isTranslator || user()->isReviewer)
            ? app()->createUrl('/dashboard/index')
            : app()->createUrl('/site/index');
    }

    /**
     * Returns the root breadcrumb label.
     * @return string
     */
    public function getBreadcrumbRootLabel()
    {
        return Yii::t('app', 'Home');
    }

    /**
     * Renders a footer link.
     * @param string $label
     * @param string $paramKey the Yii::app()->params key mapped to the corresponding page ID.
     * @param array $htmlOptions
     * @return string
     */
    public function renderFooterLink($label, $paramKey, array $htmlOptions = array())
    {
        $url = '#';
        $label = Yii::t('app', $label);

        $params = Yii::app()->params;

        if (isset($params['pages']) && isset($params['pages'][$paramKey])) {
            $url = TbHtml::normalizeUrl($params['pages'][$paramKey]);
        }

        return TbHtml::link($label, $url, $htmlOptions);
    }

}