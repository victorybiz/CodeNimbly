<?php defined('PATH_ACCESS') or exit("NO DICE! Don't Play Too Smart.");?>

<?php echo $page_header;?>

<div class="container" id="page">

    <main id="main" class="col-md-12">       
        <div class="row">
            <div class="jumbotron">
                <h1>Welcome to <?php echo get_config('name');?></h1>
                <p><?php echo get_config('description');?></p>
            </div>            
            <p>Random data sent from controller: <strong><?php echo $my_data;?></strong></p>
            <p>Date Time set from the controller <strong><?php echo $date_time;?></strong></p>
        </div>         
    </main>

</div>


<?php echo $page_footer;?>