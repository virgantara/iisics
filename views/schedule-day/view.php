<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\ScheduleDay */

$this->title = $model->day_name;
$this->params['breadcrumbs'][] = ['label' => 'Schedule Days', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

<?= Html::a('Delete', ['delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Are you sure you want to delete this item?',
        'method' => 'post',
    ],
]) ?>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?=$this->title?></h3>
            </div>

            <div class="panel-body ">
        
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        
                        'day_name',
                        'sequence',
                    ],
                ]) ?>

            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
          
            <div class="panel-body ">

                <p>
                    <?= Html::a('<i class="fa fa-plus"></i> Add Time', ['schedule-time/create','day_id' => $model->id], ['class' => 'btn btn-success']) ?>
                    <button class="delete-selected btn btn-danger"><i class="fa fa-trash"></i> Delete Selected</button>
                </p>
                <?php
                $gridColumns = [
                ['class' => '\kartik\grid\CheckboxColumn'],
                [
                    'class'=>'kartik\grid\SerialColumn',
                    'contentOptions'=>['class'=>'kartik-sheet-style'],
                    'width'=>'36px',
                    'pageSummary'=>'Total',
                    'pageSummaryOptions' => ['colspan' => 6],
                    'header'=>'',
                    'headerOptions'=>['class'=>'kartik-sheet-style']
                ],
                        'waktu_mulai',
                        'waktu_selesai',
                        'agenda:html',
                        'description:html',
                        //'day_id',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if($action == 'update') {
                          return Url::to(['schedule-time/update','id'=>$model->id]); 
                        }
                        else if($action == 'view') {
                          return Url::to(['schedule-time/view','id'=>$model->id]); 
                        }
                        else if($action == 'delete') {
                          return Url::to(['schedule-time/delete','id'=>$model->id]); 
                        }
                        else {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }   
                    },
                ]
            ];?>    
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => $gridColumns,
                    'containerOptions' => ['style' => 'overflow: auto'], 
                    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                    'containerOptions' => ['style'=>'overflow: auto'], 
                    'beforeHeader'=>[
                        [
                            'columns'=>[
                                ['content'=> $this->title, 'options'=>['colspan'=>14, 'class'=>'text-center warning']], //cuma satu 
                            ], 
                            'options'=>['class'=>'skip-export'] 
                        ]
                    ],
                    'exportConfig' => [
                          GridView::PDF => ['label' => 'Save as PDF'],
                          GridView::EXCEL => ['label' => 'Save as EXCEL'], //untuk menghidupkan button export ke Excell
                          GridView::HTML => ['label' => 'Save as HTML'], //untuk menghidupkan button export ke HTML
                          GridView::CSV => ['label' => 'Save as CSV'], //untuk menghidupkan button export ke CVS
                      ],
                      
                    'toolbar' =>  [
                        '{export}', 

                       '{toggleData}' //uncoment untuk menghidupkan button menampilkan semua data..
                    ],
                    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                // set export properties
                    'export' => [
                        'fontAwesome' => true
                    ],
                    'pjax' => true,
                    'pjaxSettings' =>[
                        'neverTimeout'=>true,
                        'options'=>[
                            'id'=>'pjax-container',
                        ]
                    ],  
                    'id' => 'grid-schedule-time',
                    'bordered' => true,
                    'striped' => true,
                    // 'condensed' => false,
                    // 'responsive' => false,
                    'hover' => true,
                    // 'floatHeader' => true,
                    // 'showPageSummary' => true, //true untuk menjumlahkan nilai di suatu kolom, kebetulan pada contoh tidak ada angka.
                    'panel' => [
                        'type' => GridView::TYPE_PRIMARY
                    ],
                ]); ?>

            </div>
        </div>
    </div>

</div>


<?php 

$this->registerJs(' 

$(".delete-selected").click(function(e){
    var keys = $(\'#grid-schedule-time\').yiiGridView(\'getSelectedRows\');
    e.preventDefault();
    
    Swal.fire({
      title: \'Schedule Time!\',
      text: "This data will be deleted and cannot be restored. Are you sure?",
      icon: \'warning\',
      showCancelButton: true,
      confirmButtonColor: \'#3085d6\',
      cancelButtonColor: \'#d33\',
      confirmButtonText: \'Yes, delete now!\'
    }).then((result) => {
        if(result.isConfirmed){
            var obj = new Object;
            obj.keys = keys;
            $.ajax({

                type : "POST",
                url : "/schedule-time/delete-multiple",
                data : {
                    dataPost : obj
                },
               
                beforeSend: function(){
                   Swal.fire({
                        title : "Please wait",
                        html: "Processing your request...",
                        
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        },
                        
                    })
                },
                error: function(e){
                    Swal.close()
                },
                success: function(data){
                    Swal.close()
                    var data = $.parseJSON(data)
                    

                    if(data.code == 200){
                        Swal.fire({
                            title: \'Yeay!\',
                            icon: \'success\',
                            text: data.message
                        });

                        $.pjax.reload({container: \'#pjax-container\', async: true});
                    }
                    
                    else{
                        Swal.fire({
                            title: \'Oops!\',
                            icon: \'error\',
                            text: data.message
                        });

                    }
                }
            })
        }
    });
});


', \yii\web\View::POS_READY);

?>