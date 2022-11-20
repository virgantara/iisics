<?php
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\assets\DashboardAsset;
DashboardAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\models\AbstractsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
	<div class="space-6"></div>

	<div class="col-sm-6">
		<div class="widget-box transparent">
			<div class="widget-header widget-header-flat">
				<h4 class="widget-title lighter">
					<i class="ace-icon fa fa-star orange"></i>
					Abstract 
					<span id="loading_abstract" style="display:none">
						<img width="24px" src="<?=Yii::$app->view->theme->baseUrl;?>/assets/images/loading.gif">
					</span>
				</h4>

				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main no-padding">
					<div class="infobox infobox-green">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-file"></i>
			</div>

			<div class="infobox-data">
				<span class="infobox-data-number" id="count_abstract_submitted">0</span>
				<div class="infobox-content">Abstracts submitted</div>
			</div>

			<!-- <div class="stat stat-success">8%</div> -->
		</div>

		<div class="infobox infobox-blue">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-file"></i>
			</div>

			<div class="infobox-data">
				<span class="infobox-data-number" id="count_abstract_accepted">0</span>
				<div class="infobox-content">Abstracts accepted</div>
			</div>

			<!-- <div class="badge badge-success">
				+32%
				<i class="ace-icon fa fa-arrow-up"></i>
			</div> -->
		</div>
		<div class="infobox infobox-red">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-file"></i>
			</div>

			<div class="infobox-data">
				<span class="infobox-data-number" id="count_abstract_waiting">0</span>
				<div class="infobox-content">Waiting for reviews</div>
			</div>
		</div>
		<div class="infobox infobox-red">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-ban"></i>
			</div>

			<div class="infobox-data">
				<span class="infobox-data-number" id="count_abstract_rejected">0</span>
				<div class="infobox-content">Abstract rejected</div>
			</div>
			<!-- <div class="stat stat-important">4%</div> -->
		</div>
				</div><!-- /.widget-main -->
			</div><!-- /.widget-body -->
		</div>

	</div>

	<div class="vspace-12-sm"></div>
	<div class="col-sm-6">
		<div class="widget-box transparent">
			<div class="widget-header widget-header-flat">
				<h4 class="widget-title lighter">
					<i class="ace-icon fa fa-star orange"></i>
					Paper
					<span id="loading_paper" style="display:none">
						<img width="24px" src="<?=Yii::$app->view->theme->baseUrl;?>/assets/images/loading.gif">
					</span>
				</h4>

				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main no-padding">
					<div class="infobox infobox-green">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-file"></i>
			</div>

			<div class="infobox-data">
				<span class="infobox-data-number" id="count_paper_submitted">0</span>
				<div class="infobox-content">papers submitted</div>
			</div>

			<!-- <div class="stat stat-success">8%</div> -->
		</div>

		<div class="infobox infobox-blue">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-file"></i>
			</div>

			<div class="infobox-data">
				<span class="infobox-data-number" id="count_paper_accepted">0</span>
				<div class="infobox-content">papers accepted</div>
			</div>

			<!-- <div class="badge badge-success">
				+32%
				<i class="ace-icon fa fa-arrow-up"></i>
			</div> -->
		</div>
		<div class="infobox infobox-red">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-file"></i>
			</div>

			<div class="infobox-data">
				<span class="infobox-data-number" id="count_paper_waiting">0</span>
				<div class="infobox-content">Waiting for reviews</div>
			</div>
		</div>
		<div class="infobox infobox-red">
			<div class="infobox-icon">
				<i class="ace-icon fa fa-ban"></i>
			</div>

			<div class="infobox-data">
				<span class="infobox-data-number" id="count_paper_rejected">0</span>
				<div class="infobox-content">paper rejected</div>
			</div>
			<!-- <div class="stat stat-important">4%</div> -->
		</div>
				</div><!-- /.widget-main -->
			</div><!-- /.widget-body -->
		</div>

	</div>
</div>

<div class="row">
	<div class="col-sm-5">
		<div class="widget-box">
			<div class="widget-header widget-header-flat widget-header-small">
				<h5 class="widget-title">
					<i class="ace-icon fa fa-signal"></i>
					Abstract Topics Submitted
				</h5>
<!-- 
				<div class="widget-toolbar no-border">
					<div class="inline dropdown-hover">
						<button class="btn btn-minier btn-primary">
							This Week
							<i class="ace-icon fa fa-angle-down icon-on-right bigger-110"></i>
						</button>

						<ul class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
							<li class="active">
								<a href="#" class="blue">
									<i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
									This Week
								</a>
							</li>

							<li>
								<a href="#">
									<i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
									Last Week
								</a>
							</li>

							<li>
								<a href="#">
									<i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
									This Month
								</a>
							</li>

							<li>
								<a href="#">
									<i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
									Last Month
								</a>
							</li>
						</ul>
					</div>
				</div> -->
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<div id="piechart-placeholder" style="width: 90%; min-height: 150px; padding: 0px; position: relative;"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 570px; height: 150px;" width="570" height="150"></canvas><canvas class="flot-overlay" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 570px; height: 150px;" width="570" height="150"></canvas><div class="legend"><div style="position: absolute; width: 90px; height: 110px; top: 15px; right: -30px; background-color: rgb(255, 255, 255); opacity: 0.85;"> </div><table style="position:absolute;top:15px;right:-30px;;font-size:smaller;color:#545454"><tbody><tr><td class="legendColorBox"><div style="border:1px solid null;padding:1px"><div style="width:4px;height:0;border:5px solid #68BC31;overflow:hidden"></div></div></td><td class="legendLabel">social networks</td></tr><tr><td class="legendColorBox"><div style="border:1px solid null;padding:1px"><div style="width:4px;height:0;border:5px solid #2091CF;overflow:hidden"></div></div></td><td class="legendLabel">search engines</td></tr><tr><td class="legendColorBox"><div style="border:1px solid null;padding:1px"><div style="width:4px;height:0;border:5px solid #AF4E96;overflow:hidden"></div></div></td><td class="legendLabel">ad campaigns</td></tr><tr><td class="legendColorBox"><div style="border:1px solid null;padding:1px"><div style="width:4px;height:0;border:5px solid #DA5430;overflow:hidden"></div></div></td><td class="legendLabel">direct traffic</td></tr><tr><td class="legendColorBox"><div style="border:1px solid null;padding:1px"><div style="width:4px;height:0;border:5px solid #FEE074;overflow:hidden"></div></div></td><td class="legendLabel">other</td></tr></tbody></table></div></div>

					
				</div><!-- /.widget-main -->
			</div><!-- /.widget-body -->
		</div><!-- /.widget-box -->
	</div>
</div>

<?php

$this->registerJs(' 

$(".easy-pie-chart.percentage").each(function(){
	var $box = $(this).closest(".infobox");
	var barColor = $(this).data("color") || (!$box.hasClass("infobox-dark") ? $box.css("color") : "rgba(255,255,255,0.95)");
	var trackColor = barColor == "rgba(255,255,255,0.95)" ? "rgba(255,255,255,0.25)" : "#E2E2E2";
	var size = parseInt($(this).data("size")) || 50;
	$(this).easyPieChart({
		barColor: barColor,
		trackColor: trackColor,
		scaleColor: false,
		lineCap: "butt",
		lineWidth: parseInt(size/10),
		animate: ace.vars["old_ie"] ? false : 1000,
		size: size
	});
})

var placeholder = $("#piechart-placeholder").css({"width":"90%" , "min-height":"150px"});

function drawPieChart(placeholder, data, position) {
 	  $.plot(placeholder, data, {
		series: {
			pie: {
				show: true,
				tilt:0.8,
				highlight: {
					opacity: 0.25
				},
				stroke: {
					color: "#fff",
					width: 2
				},
				startAngle: 2
			}
		},
		legend: {
			show: true,
			position: position || \'ne\', 
			labelBoxBorderColor: null,
			margin:[-30,15]
		}
		,
		grid: {
			hoverable: true,
			clickable: true
		}
	 })
 }


placeholder.data("draw", drawPieChart);

var $tooltip = $(\'<div class="tooltip top in"><div class="tooltip-inner"></div></div>\').hide().appendTo("body");
var previousPoint = null;

placeholder.on("plothover", function (event, pos, item) {
	if(item) {
		if (previousPoint != item.seriesIndex) {
			previousPoint = item.seriesIndex;
			var tip = item.series["label"] + \' : \' + item.series["percent"]+"%";
			$tooltip.show().children(0).text(tip);
		}
		$tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
	} else {
		$tooltip.hide();
		previousPoint = null;
	}
});
$.ajax({
    url: "/ajax/ajax-count-topic-abstract",
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
        	var data = []
        	$.each(hasil.items, function(i,obj){
        		data.push({
        			label: obj.topic_title,
        			data: Math.round(obj.total),
        			color:"#"+(Math.random() * 0xFFFFFF << 0).toString(16).padStart(6, "0")
        		})
        	})
   //      	var data = [
			// 	{ label: \'social networks\',  data: 38.7, color: \'#68BC31\'},
			// 	{ label: \'search engines\',  data: 24.5, color: \'#2091CF\'},
			// 	{ label: \'ad campaigns\',  data: 8.2, color: \'#AF4E96\'},
			// 	{ label: \'direct traffic\',  data: 18.6, color: \'#DA5430\'},
			// 	{ label: \'other\',  data: 10, color: \'#FEE074\'}
			// ]
			drawPieChart(placeholder, data);    
        }

    }
}) 

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


