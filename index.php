<?php
include 'config.php';
$api_list  = $db->query("SELECT * FROM ".TBL_APIS." WHERE ap_status = 1 ORDER BY ap_group_id ASC");
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?= APP_NAME ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="NOINDEX, NOFOLLOW">
        <!-- Bootstrap -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="assets/js/theme-github.css" rel="stylesheet">
        <style>
            body {padding-top:50px!important;background:#f9f9f9;}
            section {margin-bottom:40px;}
            pre {border-radius: 0px;margin-bottom:20px;}
            body#body_docs .container,
            body#body_ap_docs .container,
            body[id*='body_ap_'] .container {margin:0px;width:100%;}
            body#body_docs h4,
            body#body_ap_docs h4,
            body[id*='body_ap_'] h4 {margin-top:20px;}
            .nav-header {padding-left: 15px;}
            .nav-list {margin: 0px -1px 0px 0px;border-right: 1px solid #e7e7e7;height: 90%;overflow: hidden;overflow-y: auto;z-index: 99;padding-bottom: 70px;}
            .nav-list>li a {font-size: 12px;color: #008CDD;padding: 3px 15px;}
            .nav-list>li a:hover {color: #333;background-color: transparent;}
            .nav-list>li.active a, .nav-list>li.active a:hover{background:linear-gradient(#4f9fef,#3577d0);color:#fff;font-weight:normal;text-shadow: 0 -1px 0 rgba(0,0,0,0.45);margin-right: -1px;z-index: 1;}
            .col-md-9 h3{color:#4088DD;}
        </style>

    </head>
    <body id="body" data-spy="scroll" data-target="#scollNavbar">
        <header class="navbar navbar-default navbar-fixed-top">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand"><?= APP_NAME ?></a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                </ul>
                <!-- <ul class="nav navbar-nav navbar-right">
                    <li><a href="addApi.php" class="btn-default" style="margin-right: 20px;">Add API</a></li>
                </ul> -->
            </div>
        </header>  

        <div class="col-xs-12">  
            <div class="row">
                <div class="col-md-2" id="scollNavbar" style="padding: 0;position: fixed;height: 100%;z-index: 9999">
                    <ul class="nav nav-list" data-spy="affix" data-offset-bottom="200" style="display: block;background-color:rgb(255,255,255) !important;">
                        <?php
                        if(!empty($api_list))
                        {
                            $group_id = 0;
                            foreach($api_list as $api)
                            {
                                if($group_id != $api['ap_group_id'])
                                {
                                    $group_id = $api['ap_group_id'];
                                    echo '<li class="nav-header"><h3>'.$api['ap_group_name'].'</h3></li>';
                                }

                               echo '<li><a href="#'.str_replace(' ','_',$api['ap_name']).'">'.$api['ap_name'].'</a></li>';
                            }    
                        }
                        ?>                   
                    </ul>
                </div>
                <div class="col-md-10 col-md-offset-2" style="background:#fff;">
                    <?php
                        if(!empty($api_list))
                        {
                            $group_id = 0;
                            foreach($api_list as $api)
                            {
                    ?>
                        <section id="<?= str_replace(' ', '_', $api['ap_name']) ?>">
                            <h3><?= $api['ap_name'] ?></h3>
                            <hr>
                                
                            <h4>Description</h4>
                            <p><?= $api['ap_notes']?></p><br>
                                
                            <h4>Resource URL</h4>
                            <pre class="prettyprint alert-danger"><?= API_BASE_URL ?></pre>

                            <h4>Method</h4>
                            <pre class="prettyprint"><?= $api['ap_method'] ?></pre>
                                
                            <h4>Parameters</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <td width="140">
                                        <b>api_name *</b>
                                    </td>
                                    <td>
                                        <p> <?= $api['ap_request']?> </p>
                                    </td>
                                </tr>

                                <?php 
                                    $params = json_decode($api['ap_parameters'],true);
                                    foreach($params as $key => $parameter)
                                    {
                                        $required = ($parameter['required']) ? ' * ': '';

                                        echo '<tr>
                                                <td width="140"><b>'.$key.$required.'</b></td>
                                                <td><p>'.$parameter['value'].'</p></td>
                                            </tr>';
                                    }
                                ?>
                                </table>

                                <h4>Example Response</h4>
                                <pre class="prettyprint responseData" >
                                    <?= $api['ap_response']?>
                                </pre>
                        </section>
                    <?php
                            }    
                        }
                    ?> 
                </div>
            </div>
        </div>

        <script src="assets/js/jquery-2.1.3.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/prettify.js"></script>
        <script>
            prettyPrint();
            jQuery(document).ready(function () {
                if (jQuery(".responseData").find(".pln:first").text() != "http" || jQuery(".responseData").find(".pln:first").text() != "https") {
                    jQuery(".responseData").find(".pln:first").remove();
                }

                // Add scrollspy to <body>
                $('body').scrollspy({target: ".navbar", offset: 50});

                $(".nav-list a").on('click', function () {
                    var scroll = 0;
                    if ($("button.navbar-toggle").attr('aria-expanded')) {
                        $("button.navbar-toggle").click();
                        scroll = $('.navbar-header').height();
                    } else {
                        scroll = $('.navbar-header').height();
                    }
                    var hash = this.hash;
                    $('html,body').animate({scrollTop: $(this.hash).offset().top - scroll}, 1000);
                });
            });
        </script>
    </body>
</html>