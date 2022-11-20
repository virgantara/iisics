<?php
namespace app\helpers;

use Yii;

/**
 * Css helper class.
 */
class MenuHelper
{
    public static function getMenuItems()
    {
    	
    	
    	$menuItems = [];
		$menuItems[] = [
	        'label' => '<i class="fa fa-home"></i> Conference </span>', 
	        'url' => ['site/index'],
	        'template' => '<a href="{url}" target="_blank">{label}</a>',
	    ];

	    $menuItems[] = [
	        'label' => '<i class="fa fa-tachometer"></i> Dashboard </span>',
	        'visible' => !Yii::$app->user->isGuest, 
	        'url' => ['site/dashboard']];



	    // we do not need to display About and Contact pages to employ


	    if (
	    	Yii::$app->user->can('admin') ||
	    	Yii::$app->user->can('participant') ||
	    	Yii::$app->user->can('reviewer')
	    ) {



	        $menuItems[] = ['label' => '<i class="fa fa-file"></i> Abstracts <span class="fa fa-chevron-down"></span>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='nav child_menu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         
	        'items'=>[	           
	            
	            [
	            	'label' => 'List',  
	                'url' => ['/abstracts/index'],	           
	                'visible' => Yii::$app->user->can('admin'),
	            ],
	            [
	            	'label' => 'My Abstracts',  
	                'url' => ['/abstracts/index'],	           
	                'visible' => Yii::$app->user->can('participant') && !Yii::$app->user->can('admin'),
	            ],
	            [
	            	'label' => 'New submission',  
	                'url' => ['/abstracts/create'],	   
	                'visible' => Yii::$app->user->can('participant') && !Yii::$app->user->can('admin'),        
	            ],
	            [
	            	'label' => 'My Review',  
	                'url' => ['/abstracts/my-review'],	   
	                'visible' => Yii::$app->user->can('reviewer') && !Yii::$app->user->can('admin'),        
	            ],
	        ]];

	        $menuItems[] = ['label' => '<i class="fa fa-file"></i> Papers <span class="fa fa-chevron-down"></span>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='nav child_menu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         
	        'items'=>[	           
	            [
	            	'label' => 'My Papers',  
	                'url' => ['/papers/index'],	   
	                'visible' => Yii::$app->user->can('participant') && !Yii::$app->user->can('admin'),        
	            ],
	            [
	            	'label' => 'New submission',  
	                'url' => ['/papers/create'],	   
	                'visible' => Yii::$app->user->can('participant') && !Yii::$app->user->can('admin'),        
	            ],
	            [
	            	'label' => 'List',  
	                'url' => ['/papers/index'],	           
	                'visible' => Yii::$app->user->can('admin'),
	            ],
	            [
	            	'label' => 'My Review',  
	                'url' => ['/papers/my-review'],	   
	                'visible' => Yii::$app->user->can('reviewer') && !Yii::$app->user->can('admin'),        
	            ],
	        ]];

	        $menuItems[] = ['label' => '<i class="fa fa-file"></i> Participants <span class="fa fa-chevron-down"></span>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='nav child_menu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'visible' => Yii::$app->user->can('admin'),
	        'items'=>[	           
	            
	            [
	            	'label' => 'List',  
	                'url' => ['/participants/index'],	           
	            ],
	        ]];
	    }

	    if (
	    	Yii::$app->user->can('finance') ||
	    	Yii::$app->user->can('participant') 
	    ) {

	        $menuItems[] = ['label' => '<i class="fa fa-money"></i> Payment <span class="fa fa-chevron-down"></span>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='nav child_menu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	        'items'=>[	           
	            
	            [
	            	'label' => 'List',  
	                'url' => ['/payment/index'],	     
	                'visible' => Yii::$app->user->can('finance')      
	            ],
	            [
	            	'label' => 'My Payments',  
	                'url' => ['/payment/index'],	           
	                'visible' => Yii::$app->user->can('participant')
	            ],
	        ]];
	    }

	    if (
	    	Yii::$app->user->can('admin') ||
	    	Yii::$app->user->can('participant') ||
	    	Yii::$app->user->can('reviewer')
	    ) {

	        $menuItems[] = ['label' => '<i class="fa fa-file"></i> Reviewers <span class="fa fa-chevron-down"></span>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='nav child_menu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'visible' => Yii::$app->user->can('admin'),
	        'items'=>[	           
	            
	            [
	            	'label' => 'List',  
	                'url' => ['/reviewer/index'],	           
	            ],
	        ]];

	        $menuItems[] = ['label' =>'<i class="fa fa-book"></i> Master <span class="fa fa-chevron-down"></span>', 'url' => '#',
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'submenuTemplate' => "\n<ul class='nav child_menu'>\n{items}\n</ul>\n",
	         'visible' => Yii::$app->user->can('admin'),
	        'items'=>[
	        	[
	                'label' => 'Bank',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['bank/index'],    
	            ],
	            [
	                'label' => 'Bank Account',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['bank-account/index'],    
	            ],
	            [
	                'label' => 'Certificate',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['certificate/index'],    
	            ],
	            [
	                'label' => 'Certificate Type',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['certificate-type/index'],    
	            ],
	        	[
	                'label' => 'FAQ',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['faq/index'],    
	            ],
	            [
	                'label' => 'Homecontent',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['homecontent/index'],    
	            ],
	            [
	                'label' => 'Pages',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['pages/index'],    
	            ],
	            [
	                'label' => 'Schedule',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['schedule-day/index'],    
	            ],
	            [
	                'label' => 'Speakers',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['speakers/index'],    
	            ],
	            [
	                'label' => 'Sponsors',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['sponsor/index'],    
	            ],
	            [
	                'label' => 'System',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['system/index'],    
	            ],
	            [
	                'label' => 'Topics',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['topics/index'],    
	            ],
	            
	        ]];

	       
	    }

	    if (Yii::$app->user->can('theCreator')){


	        $menuItems[] = ['label' => '<i class="fa fa-users"></i> Users </span>', 'url' => ['/user/index']];
	    }

	    if(Yii::$app->user->isGuest){
	    	$menuItems[] = [
		        'label' => '<i class="fa fa-key"></i> Login </span>', 
		        'url' => ['site/login']];
			// $menuItems[] = ['label' => 'Login', 'url' => ['site/login']];   
		}
		return $menuItems;
    }
}