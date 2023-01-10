<?php 
use app\assets\LandingAsset;
use yii\helpers\Html;
LandingAsset::register($this);
 ?>
<?php $this->beginPage() ?>
 <!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="https://iicics.unida.gontor.ac.id/assets/images/favicon.ico">
		<title>Islamic International Conference on International Studies and Communication Science (IISICS)</title>
		<?= Html::csrfMetaTags() ?>
		<?php $this->head(); ?>
	</head>
<body>
	<?php $this->beginBody() ?>
	<?=$content?>
	<?php $this->endBody() ?>
</body>
<?php $this->endPage() ?>