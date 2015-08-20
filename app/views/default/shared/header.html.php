<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");?>
<!DOCTYPE html>
<html lang="en" class="no-js" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="<?php echo get_config('charset');?>" />  
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta name="description" content="<?php echo $meta_description;?>" />
    <meta name="keywords" content="<?php echo $meta_keywords;?>" />
    <meta name="author" content="<?php echo $meta_author;?>" />
    <meta name="robots" content="index" />
    <meta name="googlebot" content="index" />
    <meta name="robots" content="follow" />
    <meta name="robots" content="nocache" /> 
    <meta http-equiv="cache-control" content="no-cache" />                       
	<meta http-equiv="expires" content="-1" />
    
    <title><?php echo $page_title;?></title>
    
    <base href="<?php echo base_url();?>" />
 
    <link rel="icon" type="image/png" href="<?php echo images_url("icons/favicon.png?v=" . STATIC_CONTENT_VERSION);?>" />
    <!--[if lte IE 7]>
        <link rel="shortcut icon" type="image/icon" href="<?php echo images_url("icons/favicon.ico?v=" . STATIC_CONTENT_VERSION);?>" />
        <link rel="address bar icon" type="image/icon" href="<?php echo images_url("icons/favicon.ico?v=" . STATIC_CONTENT_VERSION);?>" />
        <link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo images_url("icons/favicon.ico?v=" . STATIC_CONTENT_VERSION);?>" />
    <![endif]-->    
    <link rel="apple-touch-icon-precomposed" href="<?php echo images_url("icons/apple-touch-icon-144-precomposed.png?v=" . STATIC_CONTENT_VERSION);?>">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo images_url("icons/apple-touch-icon-144-precomposed.png?v=" . STATIC_CONTENT_VERSION);?>">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo images_url("icons/apple-touch-icon-114-precomposed.png?v=" . STATIC_CONTENT_VERSION);?>">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo images_url("icons/apple-touch-icon-72-precomposed.png?v=" . STATIC_CONTENT_VERSION);?>">
    <link rel="apple-touch-icon-precomposed" href="<?php echo images_url("icons/apple-touch-icon-57-precomposed.png?v=" . STATIC_CONTENT_VERSION);?>">
    
    
    <link href="<?php echo third_party_url("bootstrap/css/bootstrap.min.css?v=" . STATIC_CONTENT_VERSION);?>" rel="stylesheet">
    <link href="<?php echo css_url("shared.min.css?v=" . STATIC_CONTENT_VERSION);?>" rel="stylesheet">
    <link href="<?php echo css_url("default.min.css?v=" . STATIC_CONTENT_VERSION);?>" rel="stylesheet">
    
    <!--[if lt IE 9]>
        <script type="text/javascript" src="<?php echo third_party_url("bootstrap/js/html5shiv.min.js?v=" . STATIC_CONTENT_VERSION);?>"></script>    
        <script type="text/javascript" src="<?php echo third_party_url("bootstrap/js/respond.min.js?v=" . STATIC_CONTENT_VERSION);?>"></script>    
    <![endif]-->
    
    <script>
        var $BASE_URL = "<?php echo base_url();?>";
        var $STATIC_URL = "<?php echo static_url();?>";
        var $IMAGES_PATH = "<?php echo images_url();?>";
        var $TOKEN_ID = "<?php echo csrf_token_id();?>";
        var $TOKEN = "<?php echo csrf_token();?>";
        var $TOKEN_URL_QSTRING = "<?php echo csrf_token_url_query_string();?>";
    </script>

</head>

<body>
<header id="header">
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header" id="vs-logo-wrapper">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="" title="Go to <?php echo get_config('name') . " Home.";?>" id="logo">
                <?php echo get_config('short_name');?>
              </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">        
            <ul class="nav navbar-nav navbar-right">   
                <li class=""><a href="<?php echo base_url("signup");?>">Sign Up</a></li>         
                <li class=""><a href="<?php echo base_url("login");?>">Login</a></li> 
            </ul> 
        </div>
      </div>
    </nav>
</header>