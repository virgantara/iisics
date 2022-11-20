<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@gentela';
    public $css = [
        'assets/bootstrap/dist/css/bootstrap.min.css',
        'assets/font-awesome/css/font-awesome.min.css',
        'assets/nprogress/nprogress.css',
        'assets/iCheck/skins/flat/green.css',
        'build/css/custom.min.css',
    ];
    public $js = [
        'assets/bootstrap/dist/js/bootstrap.min.js',
        'assets/fastclick/lib/fastclick.js',
        'assets/nprogress/nprogress.js',
        'assets/DateJS/build/date.js',
        'build/js/custom.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
