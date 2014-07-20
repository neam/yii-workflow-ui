<?php

/**
 * VideoPlayer class.
 */
class VideoPlayer extends CWidget
{
    /**
     * @var VideoFile
     */
    public $videoFile;

    /**
     * @var P3Media[]
     */
    public $p3MediaFiles = array();

    /**
     * @var string the path to the mediaelement asset directory
     */
    public $assetBaseUrl;

    /**
     * @var string
     */
    public $srcLang;

    /**
     * Stretches the video horizontally if set to true.
     * @var string
     */
    public $stretch;

    public function init()
    {
        parent::init();
        if (!$this->videoFile instanceof VideoFile) {
            throw new CException(Yii::t('error', 'The passed model is not a VideoFile.'));
        }
        $this->p3MediaFiles = $this->getP3MediaFiles();
        $this->_initSrcLang();
    }

    public function run()
    {
        parent::run();
        $this->_registerAssets();
        $this->_registerJs();
        $this->render('view', array(
            'flashPlayerUrl' => $this->getFlashPlayerUrl(),
            'srcLang' => $this->srcLang,
            'p3MediaFiles' => $this->p3MediaFiles,
        ));
    }

    /**
     * Returns P3Media files.
     * @return P3Media[]
     */
    public function getP3MediaFiles()
    {
        return $this->videoFile->getUploadedVideos();
    }

    /**
     * Returns the Flash video player URL.
     * @return string
     */
    public function getFlashPlayerUrl()
    {
        return $this->assetBaseUrl . '/flashmediaelement.swf';
    }

    /**
     * Returns the raw video URL.
     * @return string
     */
    public function getRawVideoUrl()
    {
        return rawurlencode($this->videoFile->getVideoUrl());
    }

    /**
     * Returns the video URL for a P3Media file.
     * @param integer $p3MediaId
     * @return null|string
     */
    public function getVideoUrl($p3MediaId)
    {
        return $this->videoFile->getVideoUrlForP3Media($p3MediaId);
    }

    /**
     * Returns the subtitle URL.
     * @return string
     */
    public function getSubtitleUrl()
    {
        return e($this->videoFile->getSubtitleUrl());
    }

    /**
     * Checks if a video exists.
     * @return boolean
     */
    public function videoExists()
    {
        return !empty($this->p3MediaFiles);
    }

    /**
     * Returns the inline styles.
     * @return string
     */
    public function getStyles()
    {
        return $this->stretch ? 'width: 100%; height: 100%;' : '';
    }

    /**
     * Initializes the source language.
     */
    protected function _initSrcLang()
    {
        if (!isset($this->srcLang)) {
            $this->srcLang = substr(app()->language, 0, 2);
        }
    }

    /**
     * Registers the JS and CSS assets.
     */
    protected function _registerAssets()
    {
        $assetName = 'medialemenet';
        $assetPath = 'bower-components.mediaelement.build';

        $cssFiles = YII_DEBUG ? array('mediaelementplayer.css') : array('mediaelementplayer.min.css');
        $jsFiles = YII_DEBUG ? array('mediaelement-and-player.js') : array('mediaelement-and-player.min.js');
        $depends = array('jquery');

        registerPackage($assetName, $assetPath, $cssFiles, $jsFiles, $depends);
        $this->assetBaseUrl = clientScript()->getPackageBaseUrl($assetName);
    }

    /**
     * Registers the video player JS.
     */
    protected function _registerJs()
    {
        $js = <<<EOF
$(document).ready(function () {
    $('video').mediaelementplayer({
        startLanguage: '{$this->srcLang}',
        enablePluginDebug: true,
        plugins: ['flash', 'silverlight']
    });
});
EOF;

        app()->clientScript->registerScript('mediaelementplayer', $js, CClientScript::POS_END);
    }
}
