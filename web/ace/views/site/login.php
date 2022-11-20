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
				<div id="login-box" class="login-box visible widget-box no-border">
					<div class="widget-body">
						<div class="widget-main">
							<h4 class="header blue lighter bigger">
								<i class="ace-icon fa fa-coffee green"></i>
								Please Enter Your Information
							</h4>

							<div class="space-6"></div>

							<?php 
							$form = ActiveForm::begin(['class' => 'form-auth-small']); 
							echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);
							?>

								<fieldset>
									<label class="block clearfix">
										<span class="block input-icon input-icon-right">
											<?= $form->field($model, 'username',['options' => ['tag' => false]])->textInput(['placeholder' => Yii::t('app', 'Enter your username'), 'autofocus' => true,'class'=>'form-control'])->label(false) ?>
											<i class="ace-icon fa fa-user"></i>
										</span>
									</label>

									<label class="block clearfix">
										<span class="block input-icon input-icon-right">
											<?= $form->field($model, 'password',['options' => ['tag' => false]])->passwordInput(['placeholder' => Yii::t('app', 'Enter your password'),'class'=>'form-control'])->label(false) ?>
											<i class="ace-icon fa fa-lock"></i>
										</span>
									</label>

									<div class="space"></div>

									<div class="clearfix">
										

										<button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
											<i class="ace-icon fa fa-key"></i>
											<span class="bigger-110">Login</span>
										</button>
									</div>

									<div class="space-4"></div>
								</fieldset>
							<?php ActiveForm::end(); ?>

						</div><!-- /.widget-main -->

						<div class="toolbar clearfix">
							<div>
								<a href="<?=Url::to(['site/request-password-reset'])?>" class="forgot-password-link">
									<i class="ace-icon fa fa-arrow-left"></i>
									I forgot my password
								</a>
							</div>

							<div>
								<a href="<?=Url::to(['site/signup'])?>"  class="user-signup-link">
									I want to register
									<i class="ace-icon fa fa-arrow-right"></i>
								</a>
							</div>
						</div>
					</div><!-- /.widget-body -->
				</div><!-- /.login-box -->

				<div id="forgot-box" class="forgot-box widget-box no-border">
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

							<form>
								<fieldset>
									<label class="block clearfix">
										<span class="block input-icon input-icon-right">
											<input type="email" class="form-control" placeholder="Email" />
											<i class="ace-icon fa fa-envelope"></i>
										</span>
									</label>

									<div class="clearfix">
										<button type="button" class="width-35 pull-right btn btn-sm btn-danger">
											<i class="ace-icon fa fa-lightbulb-o"></i>
											<span class="bigger-110">Send Me!</span>
										</button>
									</div>
								</fieldset>
							</form>
						</div><!-- /.widget-main -->

						<div class="toolbar center">
							<a href="#" data-target="#login-box" class="back-to-login-link">
								Back to login
								<i class="ace-icon fa fa-arrow-right"></i>
							</a>
						</div>
					</div><!-- /.widget-body -->
				</div><!-- /.forgot-box -->

				<div id="signup-box" class="signup-box widget-box no-border">
					<div class="widget-body">
						<div class="widget-main">
							<h4 class="header green lighter bigger">
								<i class="ace-icon fa fa-users blue"></i>
								New User Registration
							</h4>

							<div class="space-6"></div>
							<p> Enter your details to begin: </p>

							<form>
								<fieldset>
									<label class="block clearfix">
										<span class="block input-icon input-icon-right">
											<input type="email" class="form-control" placeholder="Email" />
											<i class="ace-icon fa fa-envelope"></i>
										</span>
									</label>

									<label class="block clearfix">
										<span class="block input-icon input-icon-right">
											<input type="text" class="form-control" placeholder="Username" />
											<i class="ace-icon fa fa-user"></i>
										</span>
									</label>

									<label class="block clearfix">
										<span class="block input-icon input-icon-right">
											<input type="password" class="form-control" placeholder="Password" />
											<i class="ace-icon fa fa-lock"></i>
										</span>
									</label>

									<label class="block clearfix">
										<span class="block input-icon input-icon-right">
											<input type="password" class="form-control" placeholder="Repeat password" />
											<i class="ace-icon fa fa-retweet"></i>
										</span>
									</label>

									<label class="block">
										<input type="checkbox" class="ace" />
										<span class="lbl">
											I accept the
											<a href="#">User Agreement</a>
										</span>
									</label>

									<div class="space-24"></div>

									<div class="clearfix">
										<button type="reset" class="width-30 pull-left btn btn-sm">
											<i class="ace-icon fa fa-refresh"></i>
											<span class="bigger-110">Reset</span>
										</button>

										<button type="button" class="width-65 pull-right btn btn-sm btn-success">
											<span class="bigger-110">Register</span>

											<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
										</button>
									</div>
								</fieldset>
							</form>
						</div>

						<div class="toolbar center">
							<a href="#" data-target="#login-box" class="back-to-login-link">
								<i class="ace-icon fa fa-arrow-left"></i>
								Back to login
							</a>
						</div>
					</div><!-- /.widget-body -->
				</div><!-- /.signup-box -->
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