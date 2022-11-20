<?php
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\assets\DashboardAsset;
use app\assets\HighchartAsset;

DashboardAsset::register($this);
HighchartAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\models\AbstractsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row tile_count">
<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-user"></i> Abstract Submitted</span>
  <div class="count" id="count_abstract_submitted">0</div>
  <!-- <span class="count_bottom"><i class="green">4% </i> From last Week</span> -->
</div>
<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-clock-o"></i> Abstract Accepted</span>
  <div class="count green" id="count_abstract_accepted">0</div>
  <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span> -->
</div>
<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-user"></i> Abstract Rejected</span>
  <div class="count red" id="count_abstract_rejected">0</div>
  <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span> -->
</div>
<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-user"></i> Fullpaper Submitted</span>
  <div class="count" id="count_paper_submitted">0</div>
  <!-- <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span> -->
</div>
<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-user"></i> Fullpaper Accepted</span>
  <div class="count green" id="count_paper_accepted">0</div>
  <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span> -->
</div>
<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-user"></i> Fullpaper Rejected</span>
  <div class="count red" id="count_paper_rejected">0</div>
  <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span> -->
</div>
</div>
<div class="row">
	<div class="col-md-4 col-sm-4 col-xs-12">
      <div class="x_panel tile  overflow_hidden">
        <div class="x_title">
          <h2>Topic distribution</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Settings 1</a>
                </li>
                <li><a href="#">Settings 2</a>
                </li>
              </ul>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          	<div id="containerPie"></div>
        </div>
      </div>
    </div>
	

	
</div>


<?php

$this->registerJs(' 
init_chart_doughnut()



$.ajax({
    url: "/ajax/ajax-count-abstract",
    type : "POST",
    async : true,
    error : function(e){
        console.log(e.responseText)
        $("#loading_abstract").hide()
    },
    beforeSend: function(){
        $("#loading_abstract").show()
    },
    success: function (data) {
        $("#loading_abstract").hide()

        var hasil = $.parseJSON(data)
        if(hasil.code == 200){
            $("#count_abstract_submitted").html(hasil.submitted.total)
            $("#count_abstract_accepted").html(hasil.accepted.total)
            $("#count_abstract_rejected").html(hasil.rejected.total)
            $("#count_abstract_waiting").html(hasil.waiting.total)
        }

    }
}) 


$.ajax({
    url: "/ajax/ajax-count-paper",
    type : "POST",
    async : true,
    error : function(e){
        console.log(e.responseText)
        $("#loading_paper").hide()
    },
    beforeSend: function(){
        $("#loading_paper").show()
    },
    success: function (data) {
       	$("#loading_paper").hide()
        var hasil = $.parseJSON(data)
        if(hasil.code == 200){
            $("#count_paper_submitted").html(hasil.submitted.total)
            $("#count_paper_accepted").html(hasil.accepted.total)
            $("#count_paper_rejected").html(hasil.rejected.total)
            $("#count_paper_waiting").html(hasil.waiting.total)
        }

    }
}) 

', \yii\web\View::POS_READY);

?>


