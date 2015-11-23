<?php
session_start();
require_once __DIR__ . '/facebook/src/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '181144855559544',
  'app_secret' => 'd4601ad87968576eb5c4f5fa080e5e23',
  'default_graph_version' => 'v2.5',
]);
$helper = $fb->getCanvasHelper();
$permissions = ['user_photos']; // optionnal
try {
	if (isset($_SESSION['facebook_access_token'])) {
	$accessToken = $_SESSION['facebook_access_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();
  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
 }
if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	} else {
		$_SESSION['facebook_access_token'] = (string) $accessToken;
	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();
		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}
	// validating the access token
	try {
		$request = $fb->get('/me');
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		if ($e->getCode() == 190) {
			unset($_SESSION['facebook_access_token']);
			$helper = $fb->getRedirectLoginHelper();
			$loginUrl = $helper->getLoginUrl('https://apps.facebook.com/kookilogin/', $permissions);
			echo "<script>window.top.location.href='".$loginUrl."'</script>";
		}
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	// getting all photos of user
	try {
		$photos_request = $fb->get('/me/photos?type=uploaded&limit=24');
		$photos = $photos_request->getGraphEdge();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	$all_photos = array();
	if ($fb->next($photos)) {
		$photos_array = $photos->asArray();
		$all_photos = array_merge($photos_array, $all_photos);
			} else {
		$photos_array = $photos->asArray();
		$all_photos = array_merge($photos_array, $all_photos);
	}?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KOOKI - Crea il tuo KOOKI!</title>
    
    <meta name="description" content="KOOKI" />

    <!-- Bootstrap -->
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="../assets/css/demo.html5imageupload.css?v1.3" rel="stylesheet">
    
    
      <script type='text/javascript' src='//code.jquery.com/jquery-2.1.0.js'></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type='text/javascript' src="//cdnjs.cloudflare.com/ajax/libs/svg.js/1.0rc3/svg.min.js"></script>
    <script type='text/javascript'>//<![CDATA[
$(window).load(function(){
jQuery(function() {
    /* create an svg drawing */
    var canvas = SVG('drawing').size(430,430),
        image;
    
    jQuery('form').on('submit', function(e) {
        e.preventDefault();
        
        if (image) {
            canvas.clear();
        }
        
        jQuery.each($('form').serializeArray(), function(i, field) {
            if (field.name == 'image') {
                imgsrc = field.value;
                return false;
            }
        });
        
        image = canvas.image(imgsrc, 430, 430);
    });
});
});//]]> 

</script>
<style>
label > input{ /* HIDE RADIO */
  visibility: hidden; /* Makes input not-clickable */
  position: absolute; /* Remove input from document flow */
}
label > input + img{ /* IMAGE STYLES */
  cursor:pointer;
  border:2px solid transparent;
}
label > input:checked + img{ /* (RADIO CHECKED) IMAGE STYLES */
  border:4px solid #fab700;
}
    #pictureframe {
width:680px;
height:680px;
background-size: cover;

}
#pictureframeback {
width:680px;
height:680px;
background-size: cover;
background-image:url(kooki-base.png);
z-index:-1;
position: absolute;

}
    	.base {
background-image:url(kooki-base.png);
}

.black {
background-image:url(kooki-black.png);
}

.red {
background-image:url(kooki-red.png);
}
.green {
background-image:url(kooki-green.png);
}

#logo-image img {
	width:100%;
}
    	
    </style>

  </head>
  <body>
    
	<div class="container">
	  <div class="row">
	    <div class="col-xs-12">
	      <h1>Crea il tuo KOOKI!</h1>
	    </div>
	  </div>
	  
	  
	  
	  
	  
	  
	 
	  
	  <hr />
	  
	  <div class="row">
	    <div class="col-xs-7">
		  <form enctype="multipart/form-data" action="form.php" method="post" role="form">
		  	 
 <div class="form-group">
		   
		    <div id="pictureframe"><div id="pictureframeback">
</div><div class="dropzone" data-width="960" data-height="960" data-ajax="false" data-originalsave="true" data-save="true" data-ghost="true" data-originalsize="false" style="height:430px; width: 430px; margin:0 auto; display:block; padding-top:18.5%;" id="drawing">
		      
		    </div>
		  </div>
		  </div>
	  
		    <div class="row">
		  	<div id="logo-image" class="col-xs-3" style="text-align:center" onclick = "pictureframe.className = 'base'"><img src="white.png"></div>
<div id="logo-image" class="col-xs-3" style="text-align:center" onclick = "pictureframe.className = 'black'"><img src="black.png"></div>
<div id="logo-image" class="col-xs-3"  style="text-align:center" onclick = "pictureframe.className = 'red'"><img src="red.png"></div>
<div id="logo-image" class="col-xs-3"  style="text-align:center" onclick = "pictureframe.className = 'green'"><img src="green.png"></div></div>
		  <div class="form-group">
		    <label for="name">Dedica</label>
		    <input type="text" name="dedica" class="form-control" />
		  </div>
		  
		  <button type="submit" class="btn btn-success">Crea il tuo Kooki!</button>
		  		  </form>
	    </div>
	    
	    <div class="col-xs-5">
<div class="row">
		  	<div class="col-xs-4"  style="text-align:center"><a onclick="$('input[id=article_file_input]').click();"><i class="fa fa-5x fa-arrow-circle-up"></i></a><br/>Scegli una foto dal tuo Hard Disk o Smartphone</div>
		<div class="col-xs-4" style="text-align:center"><a href="https://api.instagram.com/oauth/authorize/?client_id=ccb88295cf444b48bbdde580a98ba463&redirect_uri=http%3a%2f%2fwww.mawitalia.it%2fkooki%2finstagram_fancybox%2finstagammy.php&response_type=code"><i class="fa fa-5x fa-instagram"></i></a><br/>Scegli una foto dal tuo account Instagram</div>
		  <div class="col-xs-4"  style="text-align:center"><a href="kooki-fb.php"><i class="fa fa-5x fa-facebook"></i></a><br/>Scegli una foto dal tuo account Facebook</div></div>
		  <div class"row" style="margin-bottom:3%;"></div>	

	    	<form name="imageselect">
<div class="row">
	<?
	foreach ($all_photos as $key) {
		$photo_request = $fb->get('/'.$key['id'].'?fields=images');
		$photo = $photo_request->getGraphNode()->asArray();
		echo '<div class="col-xs-3"><label><input type="radio" name="image" value="'.$photo['images'][2]['source'].'" ><img src="'.$photo['images'][2]['source'].'" style="width:95%; height:100px;"></label></div>';
	}
  	// Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']
} else {
	$helper = $fb->getRedirectLoginHelper();
	$loginUrl = $helper->getLoginUrl('http://www.mawitalia.it/kooki/facebook/index.php');
	echo "<script>window.top.location.href='".$loginUrl."'</script>";
}
?>  <div style="margin:3%"><button type="submit" class="btn btn-success">Aggiorna il tuo Kooki!</button></div>
</form></div>
	      
	    </div>
	    
	
	  
	  
	  
	 
	
	  
	
	 
	  
	  
	</div>
	
	
	

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="../assets/js/i18next.min.js?v1.8.0"></script>
    
    <script src="../assets/js/html5imageupload.min.js?v1.4.3"></script>
    <script src="../assets/js/notify.js"></script>
    
    <script>
    
    $('#retrievingfilename').html5imageupload({
    	onAfterProcessImage: function() {
    		$('#filename').val($(this.element).data('name'));
    	},
    	onAfterCancel: function() {
    		$('#filename').val('');
    	}
    	
    });
    
    $('#save').html5imageupload({
    	onSave: function(data) {
    		console.log(data);
    	},
    	
    });
    
    $('.dropzone').html5imageupload();
    
    $( "#myModal" ).on('shown.bs.modal', function(){
    	$('#modaldialog').html5imageupload();
    });
    /*
    $('#form').html5imageupload({
    	onAfterProcessImage: function() {
    		$(this.element).closest('form').submit();
    	}
    });
    
    $('form button.btn').unbind('click').click(function(e) {
    	  e.preventDefault()
    	  $(this).closest('form').find('#form').data('html5imageupload').imageCrop()
    });*/

    
    </script>
    
  </body>
</html>
