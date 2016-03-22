<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");?>

    <footer id="footer">
        <div class="container" style="text-align: center;">
            <span id="copyright">
                <?php echo get_config('copyright_stamp');?> 
            </span>  
        </div>  
    </footer>   
    <script type="text/javascript" src="<?php echo third_party_url("jquery/jquery.js?v=" . STATIC_CONTENT_VERSION);?>"></script>
    <script type="text/javascript" src="<?php echo third_party_url("bootstrap/js/bootstrap.min.js?v=" . STATIC_CONTENT_VERSION);?>"></script>    

</body>
</html>