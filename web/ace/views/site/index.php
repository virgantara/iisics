<?php 
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::$app->name;
setlocale(LC_ALL, 'id_ID', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');
 ?>

<!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center ">
    <div class="container-fluid container-xxl d-flex align-items-center">

      <div id="logo" class="me-auto">
        <!-- Uncomment below if you prefer to use a text logo -->
        <!-- <h1><a href="index.html">The<span>Event</span></a></h1>-->
        <a href="<?=Url::home()?>" class="scrollto"><img src="<?=Yii::$app->view->theme->baseUrl;?>/images/logo_snst_landscape.png" alt="Logo SNST" title=""></a>
      </div>

      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
          <li class="dropdown"><a href="#"><span>About</span> <i class="bi bi-chevron-down"></i></a>
          <ul>
            <li><a href="#about">About Event</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
          <li class="dropdown"><a href="#"><span>Event</span> <i class="bi bi-chevron-down"></i></a>
          <ul>
            <li><a href="#topics">Topics</a></li>
            <li><a href="#schedule">Schedule</a></li>
          </ul>
          <!-- <li class="dropdown"><a class="nav-link scrollto" href="#about">About</a></li> -->
          <!-- <li><a class="nav-link scrollto" href="#topics">Topics</a></li> -->
          <li><a class="nav-link scrollto" href="#speakers">Speakers</a></li>
          <!-- <li><a class="nav-link scrollto" href="#schedule">Schedule</a></li> -->
          <li class="dropdown"><a href="#"><span>Guidelines</span> <i class="bi bi-chevron-down"></i></a>
          <ul>
            <li><a href="#abstract-guideline">Abstract Guideline</a></li>
            <li><a href="#paper-guideline">Paper Guideline</a></li>
            <!-- <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-right"></i></a>
              <ul>
                <li><a href="#">Deep Drop Down 1</a></li>
                <li><a href="#">Deep Drop Down 2</a></li>
                <li><a href="#">Deep Drop Down 3</a></li>
                <li><a href="#">Deep Drop Down 4</a></li>
                <li><a href="#">Deep Drop Down 5</a></li>
              </ul>
            </li>
            <li><a href="#">Drop Down 2</a></li>
            <li><a href="#">Drop Down 3</a></li>
            <li><a href="#">Drop Down 4</a></li> -->
          </ul>
          <li><a class="nav-link scrollto" href="#registration">Registration</a></li>
          <li><a class="nav-link scrollto" href="#venue">Venue</a></li>
          <li><a class="nav-link scrollto" href="#supporters">Sponsors</a></li>

        </li>
        
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
      <a class="buy-tickets scrollto" href="<?=Url::to(['site/signup'])?>">Register Now</a>
      <a class="buy-tickets scrollto" href="<?=Url::to(['site/login'])?>">Login</a>

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero">
    <div class="hero-container" data-aos="zoom-in" data-aos-delay="100">
      <h1 class="mb-4 pb-0"><?=$seminar['name']->sys_content?></h1>
      <p class="mb-4 pb-0">
        <?php 
        if($seminar['date_start']->sys_content == $seminar['date_end']->sys_content){
          echo date('d',strtotime($seminar['date_start']->sys_content)).' '.date('F Y',strtotime($seminar['date_start']->sys_content)).', '.$seminar['city']->sys_content;
        }

        else{
          echo date('d',strtotime($seminar['date_start']->sys_content)).' - '.date('d',strtotime($seminar['date_end']->sys_content)).' '.date('F Y',strtotime($seminar['date_start']->sys_content)).', '.$seminar['city']->sys_content;
        }
         ?>
        
        
      </p>
      <a href="https://www.youtube.com/watch?v=2LP6CssOrsg" class="glightbox play-btn mb-4"></a>
      <a href="#about" class="about-btn scrollto">About The Event</a>
    </div>
  </section><!-- End Hero Section -->

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="col-lg-12">
            <h2>About The Event</h2>
            <p><?=$about->page_content?></p>
          </div>
          
        </div>
      </div>
    </section><!-- End About Section -->

    <!-- ======= Speakers Section ======= -->
    <section id="topics">
      <div class="container" data-aos="fade-up">
        <div class="section-header">
          <h2>Topics</h2>
          <p>Topic of interest includes, but not limited to: </p>
        </div>

        <div class="row">
          <?=$topics->page_content?>
        </div>
      </div>

    </section><!-- End Speakers Section -->

    <!-- ======= Speakers Section ======= -->
    <section id="speakers">
      <div class="container" data-aos="fade-up">
        <div class="section-header">
          <h2>Event Speakers</h2>
          <p>Here are some of our speakers</p>
        </div>

        <div class="row">
        	<?php foreach($speakers as $speaker): ?>
          <div class="col-lg-4 col-md-6">
            <div class="speaker" data-aos="fade-up" data-aos-delay="100">
              <?php 
              if(!empty($speaker->speaker_image)){
                  // // return Html::img(Url::to(['simak-mastermahasiswa/foto','id'=>$data->id]),['width'=>'70px']);
                  // return Html::a(Html::img($data->foto_path,['width'=>'70px']),'',['class'=>'popupModal','data-pjax'=>0,'data-item'=>$data->foto_path]);
                  echo Html::a(Html::img(Url::to(['speakers/foto','id'=>$speaker->speaker_id]),['class' => 'img-fluid','alt' => $speaker->speaker_name]));
              }
                  
              else
                  echo '';
               ?>
              <div class="details">
                <h3><a href="speaker-details.html"><?=$speaker->speaker_name?></a></h3>
                <p><?=htmlspecialchars_decode($speaker->speaker_content)?></p>
                <!-- <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div> -->
              </div>
            </div>
          </div>
      		<?php endforeach ?>
        </div>
      </div>

    </section><!-- End Speakers Section -->

    <!-- ======= Schedule Section ======= -->
    <section id="schedule" class="section-with-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-header">
          <h2>Event Schedule</h2>
          <p>Here is our event schedule</p>
        </div>

        <ul class="nav nav-tabs" role="tablist" data-aos="fade-up" data-aos-delay="100">
        	<?php 

        	foreach($schedule_day as $q => $day){
        		$active = $q == 0 ? 'active' : '';
        	 ?>
          <li class="nav-item">
            <a class="nav-link <?=$active?>" href="#day-<?=$day->sequence?>" role="tab" data-bs-toggle="tab"><?=$day->day_name?></a>
          </li>
          <?php } ?>
        </ul>

        

        <div class="tab-content row justify-content-center" data-aos="fade-up" data-aos-delay="200">
        	<?php 

        	foreach($schedule_day as $q => $day){
        		$active = $q == 0 ? 'active' : '';
        	 ?>
          <!-- Schdule Day 1 -->
          <div role="tabpanel" class="col-lg-9 tab-pane fade show <?=$active?>" id="day-<?=$day->sequence?>">
          	<?php 
          		foreach($day->scheduleTimes as $waktu){
          	 ?>
            <div class="row schedule-item">
              <div class="col-md-2"><time><?=date('H:i',strtotime($waktu->waktu_mulai))?></time> - <time><?=date('H:i',strtotime($waktu->waktu_selesai))?></time></div>
              <div class="col-md-10">
                <h4><?=$waktu->agenda?></h4>
                <p><?=$waktu->description?></p>
              </div>
            </div>
           	<?php } ?>

          </div>
          <!-- End Schdule Day 1 -->
      <?php } ?>
          

        </div>

      </div>

    </section><!-- End Schedule Section -->

    <section id="abstract-guideline">
      <div class="container-fluid" data-aos="fade-up">

        <div class="section-header">
          <h2>Abstract Guidelines</h2>
          <!-- <p>Event venue location info and gallery</p> -->
        </div>

        <div class="row g-0">
          <div class="col-lg-12 venue-map">
            <?=htmlspecialchars_decode($abstract_guidelines->page_content)?>
            
          </div>

          
        </div>

      </div>
    </section><!-- End Abstract Guildline Section -->

    <section id="paper-guideline" class="section-with-bg">
      <div class="container-fluid" data-aos="fade-up">

        <div class="section-header">
          <h2>Paper Guidelines</h2>
          <!-- <p>Event venue location info and gallery</p> -->
        </div>

        <div class="row g-0">
          <div class="col-lg-12 venue-map">
            <?=htmlspecialchars_decode($paper_guidelines->page_content)?>
            
          </div>

          
        </div>

      </div>
    </section><!-- End Abstract Guildline Section -->

    <section id="registration">
      <div class="container-fluid" data-aos="fade-up">

        <div class="section-header">
          <h2>Registration</h2>
          <!-- <p>Event venue location info and gallery</p> -->
        </div>

        <div class="row g-0">
          <div class="col-lg-12 venue-map">
            <?=htmlspecialchars_decode($registration->page_content)?>
            
          </div>

          
        </div>

      </div>
    </section><!-- End Abstract Guildline Section -->

    <!-- ======= Venue Section ======= -->
    <section id="venue">

      <div class="container-fluid" data-aos="fade-up">

        <div class="section-header">
          <h2>Event Venue</h2>
          <p>Event venue location info and gallery</p>
        </div>

        <div class="row g-0">
          <div class="col-lg-12 venue-map">
          	<?=htmlspecialchars_decode($venue->page_content)?>
            
          </div>

          
        </div>

      </div>


    </section><!-- End Venue Section -->

    <!-- ======= Hotels Section ======= -->
    

 

    <!-- ======= Supporters Section ======= -->
    <section id="supporters" class="section-with-bg">

      <div class="container" data-aos="fade-up">
        <div class="section-header">
          <h2>Sponsors</h2>
        </div>

        <div class="row no-gutters supporters-wrap clearfix" data-aos="zoom-in" data-aos-delay="100">
          <?php 
          foreach($sponsors as $sponsor){
           ?>
          
          <div class="col-lg-3 col-md-4 col-xs-6">
            <div class="supporter-logo">
              <?php 
              if(!empty($sponsor->file_path)){
                  echo Html::img(Url::to(['sponsor/foto','id'=>$sponsor->id]),['class' => 'img-fluid','alt' => $sponsor->sponsor_name]);
              }
                  
              else
                  echo '';
               ?>
              
            </div>
          </div>
          <?php } ?>
          

        </div>

      </div>

    </section><!-- End Sponsors Section -->

    <!-- =======  F.A.Q Section ======= -->
    <section id="faq">

      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>F.A.Q </h2>
        </div>

        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
          <div class="col-lg-9">

            <ul class="faq-list">
            	<?php 
            	foreach($faqs as $faq){
            	 ?>
            	
              <li>
                <div data-bs-toggle="collapse" class="collapsed question" href="#faq<?=$faq->faq_id?>"><?=$faq->faq_question?> <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
                <div id="faq<?=$faq->faq_id?>" class="collapse" data-bs-parent=".faq-list">
                  <p>
                    <?=$faq->faq_answer?>
                  </p>
                </div>
              </li>
          <?php } ?>
              

            </ul>

          </div>
        </div>

      </div>

    </section><!-- End  F.A.Q Section -->


    

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="section-bg">

      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Contact Us</h2>
        </div>

        <div class="row contact-info">

        

          <div class="col-md-12">
            <div class="contact-phone">
              <i class="bi bi-phone"></i>
              <h3>Contact Person</h3>
              <p><?=$contact->page_content?></p>
            </div>
          </div>

         

        </div>
<!-- 
        <div class="form">
          <form action="forms/contact.php" method="post" role="form" class="php-email-form">
            <div class="row">
              <div class="form-group col-md-6">
                <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
              </div>
              <div class="form-group col-md-6 mt-3 mt-md-0">
                <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
              </div>
            </div>
            <div class="form-group mt-3">
              <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
            </div>
            <div class="form-group mt-3">
              <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
            </div>
            <div class="my-3">
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">Your message has been sent. Thank you!</div>
            </div>
            <div class="text-center"><button type="submit">Send Message</button></div>
          </form>
        </div> -->

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <!-- <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-info">
            <img src="<?=Yii::getAlias('@themes')?>/../event/assets/img/logo.png" alt="TheEvenet">
            <p>In alias aperiam. Placeat tempore facere. Officiis voluptate ipsam vel eveniet est dolor et totam porro. Perspiciatis ad omnis fugit molestiae recusandae possimus. Aut consectetur id quis. In inventore consequatur ad voluptate cupiditate debitis accusamus repellat cumque.</p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-contact">
            <h4>Contact Us</h4>
            <p>
              A108 Adam Street <br>
              New York, NY 535022<br>
              United States <br>
              <strong>Phone:</strong> +1 5589 55488 55<br>
              <strong>Email:</strong> info@example.com<br>
            </p>

            <div class="social-links">
              <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
              <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
              <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
              <a href="#" class="google-plus"><i class="bi bi-instagram"></i></a>
              <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>

          </div>

        </div>
      </div>
    </div> -->

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>UPT PPTIK UNIDA Gontor</strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!--
        All the links in the footer should remain intact.
        You can delete the links only if you purchased the pro version.
        Licensing information: https://bootstrapmade.com/license/
        Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=TheEvent
      -->
        <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
      </div>
    </div>
  </footer><!-- End  Footer -->