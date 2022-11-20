<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class LandingAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@landing';
    public $css = [
        'plugins/bootstrap/css/bootstrap.min.css',
        'plugins/font-awesome/css/font-awesome.min.css',
        'plugins/owlcarousel2/assets/owl.carousel.min.css',
        'plugins/owlcarousel2/assets/owl.theme.default.min.css',
        'templates/seminar/css/main.css',
        'templates/seminar/css/animate.css',
        'templates/seminar/css/responsive.css',
        'templates/seminar/css/header2.css' 
    ];
    public $js = [
        'plugins/jquery/jquery-3.2.1.min.js',
        'plugins/bootstrap/js/popper.min.js',
        'plugins/bootstrap/js/bootstrap.min.js',
        'plugins/bootstrap/js/holder.min.js',
        'plugins/bootstrap/js/ie10-viewport-bug-workaround.js',
        'plugins/owlcarousel2/owl.carousel.min.js',
        'templates/seminar/js/jquery.countdown.min.js',
        'templates/seminar/js/wow.min.js',
        'templates/seminar/js/script.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
       
    ];
}
