<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\SweetalertAsset;
use app\assets\AppAsset;
use app\widgets\Alert;
use yii\widgets\Menu;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use kartik\nav\NavX;
use yii\helpers\Url;

AppAsset::register($this);
SweetalertAsset::register($this);
$theme = Yii::$app->view->theme->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head(); ?>

</head>
<style>
    .swal2-popup { font-size: 1.6rem !important; }
</style>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                      <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Your page</span></a>
                    </div>

                    <div class="clearfix"></div>
                    <?php 

                    if(!Yii::$app->user->isGuest)
                    {

                    ?>
                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                      <div class="profile_pic">
                        <img src="<?=$theme?>/images/user.png" alt="..." class="img-circle profile_img">
                      </div>
                      <div class="profile_info">
                        <span>Welcome,</span>
                        <h2><?=Yii::$app->user->identity->fullname;?></h2>
                      </div>
                    </div>
                    <!-- /menu profile quick info -->
                    <?php } ?>
                    <br />
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
              

                          <?php 
                            
                            $menuItems = \app\helpers\MenuHelper::getMenuItems();              

                               echo Menu::widget([
                                'options'=>array('class'=>'nav side-menu'),
                                'itemOptions'=>array('class'=>'hover'),
                                
                                // 'itemCssClass'=>'hover',
                                'encodeLabels'=>false,
                                'items' => $menuItems
                            ]);

                  
                        ?>
                        </div>
                    </div>
                    <!-- <div class="sidebar-footer hidden-small">
                      <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                      </a>
                      <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                      </a>
                      <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                      </a>
                      <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                      </a>
                    </div> -->
                </div>
            </div>
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <?php 

                        if(!Yii::$app->user->isGuest)
                        {
                         ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                              <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="<?=$theme?>/images/user.png" alt=""><?=Yii::$app->user->identity->fullname;?>
                                <span class=" fa fa-angle-down"></span>
                              </a>
                              <?php 
                                echo Menu::widget([
                                    'options'=>['class'=>'dropdown-menu dropdown-usermenu pull-right'],
                                    // 'itemOptions'=>array('class'=>'dropdown-menu'),
                                    // 'itemCssClass'=>'item-test',
                                    'encodeLabels'=>false,
                                    'items' => [
                                        ['label'=>'<li><a data-method="POST" href="'.Url::to(['/site/logout']).'">Logout</a></li>'],

                                    ],
                                ]);
                                 ?>
                              
                            </li>
                        </ul>
                        <?php } ?>
                    </nav>
                </div>
            </div>
            <div class="right_col" role="main">
                <?php
                  foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                      echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
                  } ?>
                <?=$content;?>
            </div>
            <footer>
              <div class="pull-right">
                UPT PPTIK UNIDA Gontor &copy; 2022 - <?=date('Y')?></a>
              </div>
              <div class="clearfix"></div>
            </footer>
        </div>
    </div>
     
        
        <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
