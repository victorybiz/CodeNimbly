<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart."); ?>
<html>
<head>
    <meta http-equiv="cache-control" content="no-cache" />                       
	<meta http-equiv="expires" content="-1" />
    <title>Page Not Found</title>
</head>
<body style="margin: 0 auto;">
    <div style="width: ; text-align: center; padding: 100px 10px; font-size: 20px;">
            <strong>Error 404: Page Not Found</strong>
            <br /><hr />
            
            <?php
                if (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"])){
                    $back = "<a href=\"{$_SERVER["HTTP_REFERER"]}\">Back</a>";
                } else {
                    $back = "Back";
                } 
                $msg = "<p>Oops! The link you followed may be broken, or the page may have been removed.</p>
                         <p>Please double-check the URL (address) you followed, or <a href=\"support\">contact us</a> 
                         if you feel you have reached this page in error. <br />
                         <strong>Click the \"$back\" button on your browser or <a href=\"\">go to the home page</a></p></strong>";
                echo $msg;
            ?>  
    </div>    
</body>
</html>