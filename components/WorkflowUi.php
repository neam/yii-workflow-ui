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
        $files = array('main.css');

        if (!empty($files)) {
            // Set the CSS path
            $forceCopy = (defined('DEV') && DEV) || !empty($_GET['refresh_assets']) ? true : false;
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

    // TODO: Use this methods to supply hints?

    /**
     * Operations that affect the item's visibility within the context (own/group)
     * See "Item Visibility" tab in CMS Metadata spreadsheet
     * @return array
     */
    static public function itemVisibilityOperations()
    {

        return array(
            // group-dependent
            'List' => 'Lists an item in the group',
            'Unlist' => 'Unlists an item from the group',
            'Suggest' => 'Suggest an item to be listed in the group',
            'Publish/Replace' => 'Share with anyone, making the item public for the first time / Publishing this version as the official version, replacing a previous version',
            'Unpublish/Revert' => 'Unshare with anyone, revert to previous version if such exists',
        );

    }

    static public function itemInteractionOperations()
    {

        return array(
            // group-independent,own-dependent
            'Add' => 'Adds a temporary empty item to the database',
            'Remove' => 'Remove item from the database',
            // group/own-dependent
            'Browse' => 'Browse amongst items',
            'View' => 'View items',
            'PrepareForReview' => 'Prepare item for review, by stepping through fields required for IN_REVIEW',
            //'Review' => 'Preview, Evaluate, Proofread',
            'Preview' => 'Preview the current content',
            'Evaluate' => 'Evaluating an item in Preview-mode by grading and commenting on it\'s fields or the total itemVersion',
            'Proofread' => 'Review and improve language',
            'PrepareForPublishing' => 'Prepare for publishing, by stepping through fields required for PUBLIC',
            'Approve' => 'Approving the full content by stepping to next field approved==false',
            'Translate' => 'Translate to languages that you added to our profile',
            //'TranslateIntoLanguage1' => 'Translate to the primary language that you added to our profile',
            //'TranslateIntoLanguage2' => 'Translate to the secondary language that you added to our profile',
            //'TranslateIntoLanguage3' => 'Translate to the tertiary language that you added to our profile',
            'TranslateUnrestricted' => 'Translate to all languages',
            'Edit' => 'Look at and edit all fields, no obvious goal',
            'Clone' => 'Creates a new itemVersion with incremented version number and goes to "edit" workFlow',
        );

    }

}