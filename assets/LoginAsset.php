<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@gentela';
    public $css = [
        'assets/bootstrap/dist/css/bootstrap.min.css',
        'assets/font-awesome/css/font-awesome.min.css',
        'assets/nprogress/nprogress.css',
        'assets/animate.css/animate.min.css',
        'build/css/custom.min.css',
    ];
    public $js = [
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\jui\JuiAsset',
        // 'yii\bootstrap\BootstrapAsset',
        // 'rmrevin\yii\fontawesome\CdnProAssetBundle'
    ];
}
