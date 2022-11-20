<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\LoginAsset;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;
use kartik\nav\NavX;

LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title.' '.Yii::$app->name) ?></title>

    <?php $this->head(); ?>
    <style type="text/css">
        .swal2-popup {
          font-size: 1.6rem !important;
        }
    </style>
</head>
<body class="login-layout blur-login">
<?php $this->beginBody() ?>
    <div class="main-container">
        <div class="main-content">
            <?php
            foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
            } ?>
            <?=$content?>
        </div>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
