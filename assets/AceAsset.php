<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@ace';
    public $css = [
        'assets/css/bootstrap.min.css',
        'assets/font-awesome/4.5.0/css/font-awesome.min.css',
        'assets/css/fonts.googleapis.com.css',
        'assets/css/ace.min.css',
        'assets/css/ace-skins.min.css',
        'assets/css/ace-rtl.min.css',
    ];
    public $js = [
        'assets/js/bootstrap.min.js',
        'assets/js/ace-extra.min.js',
        'assets/js/jquery-ui.custom.min.js',
        'assets/js/jquery.ui.touch-punch.min.js',
        'assets/js/ace-elements.min.js',
        'assets/js/ace.min.js',
        'assets/js/jquery.mobile.custom.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
