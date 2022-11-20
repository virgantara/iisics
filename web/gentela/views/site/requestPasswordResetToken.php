<?php 
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>

<div class="row">
  <div class="col-sm-10 col-sm-offset-1">
    <div class="login-container">
      <div class="center">
        <h1>
          <i class="ace-icon fa fa-leaf green"></i>
          <span class="white" id="id-text2"><?=$seminar['alias']->sys_content?></span>
        </h1>
        <h4 class="blue" id="id-company-text">&copy; <?=$seminar['institution']->sys_content?></h4>
      </div>

      <div class="space-6"></div>

      <div class="position-relative">
        

        <div class="widget-body">
            <div class="widget-main">
              <h4 class="header red lighter bigger">
                <i class="ace-icon fa fa-key"></i>
                Retrieve Password
              </h4>

              <div class="space-6"></div>
              <p>
                Enter your email and to receive instructions
              </p>

              <?php 
              $form = ActiveForm::begin(['class' => 'form-auth-small']); 
              echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);
              ?>
                <fieldset>
                  <label class="block clearfix">
                    <span class="block input-icon input-icon-right">
                      <?= $form->field($model, 'email',['options' => ['tag' => false]])->textInput(['placeholder' => Yii::t('app', 'Email'), 'autofocus' => true,'class'=>'form-control'])->label(false) ?>
                      <i class="ace-icon fa fa-envelope"></i>
                    </span>
                  </label>

                  <div class="clearfix">
                    <button type="submit" class="width-35 pull-right btn btn-sm btn-danger">
                      <i class="ace-icon fa fa-lightbulb-o"></i>
                      <span class="bigger-110">Send Me!</span>
                    </button>
                  </div>
                </fieldset>
              <?php ActiveForm::end(); ?>
            </div><!-- /.widget-main -->

            <!-- <div class="toolbar center">
              <a href="#" data-target="#login-box" class="back-to-login-link">
                Back to login
                <i class="ace-icon fa fa-arrow-right"></i>
              </a>
            </div> -->
          </div><!-- /.widget-body -->

      
      </div><!-- /.position-relative -->

      
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<?php

$this->registerJs('
$(document).on("click", ".toolbar a[data-target]", function(e) {
  e.preventDefault();
  var target = $(this).data("target");
  $(".widget-box.visible").removeClass("visible");//hide others
  $(target).addClass("visible");//show target
 });

 $("#btn-login-dark").on("click", function(e) {
  $("body").attr("class", "login-layout");
  $("#id-text2").attr("class", "white");
  $("#id-company-text").attr("class", "blue");
  
  e.preventDefault();
 });
 $("#btn-login-light").on("click", function(e) {
  $("body").attr("class", "login-layout light-login");
  $("#id-text2").attr("class", "grey");
  $("#id-company-text").attr("class", "blue");
  
  e.preventDefault();
 });
 $("#btn-login-blur").on("click", function(e) {
  $("body").attr("class", "login-layout blur-login");
  $("#id-text2").attr("class", "white");
  $("#id-company-text").attr("class", "light-blue");
  
  e.preventDefault();
 });

');

?>