<?php
    include 'config.php';


    if(!isset($_GET['aid']) || isset($_REQUEST['aid']))
    {
        header('Location : add.php');
    }


    $ap_id =  ( !empty($_GET['aid']) || !empty($_REQUEST['aid'])) ? $_REQUEST['aid'] : '';

    if(empty($ap_id))
            header('Location:index.php');

    if(isset($_POST) && !empty($_POST))
    {
        
        $api_name       = (isset($_POST['apiName'])) ? $_POST['apiName'] : '' ;
        $api_request    = (isset($_POST['apiRequest'])) ? $_POST['apiRequest'] : '' ;
        $api_method     = (isset($_POST['apiMethod'])) ? $_POST['apiMethod'] : '' ;
        $api_group_name = (isset($_POST['apiGroupName'])) ? $_POST['apiGroupName'] : '';
        $api_group_id   = (isset($_POST['apiGroupId'])) ? $_POST['apiGroupId'] :'' ;
        $api_response   = (isset($_POST['apiResponse'])) ? $_POST['apiResponse'] : '';
        $api_note       = (isset($_POST['apiNote'])) ? $_POST['apiNote'] : '';

        $requiredParameter  = (isset($_POST['requiredParameter'])) ? $_POST['requiredParameter'] : array();
        $apiParameter       = (isset($_POST['apiParameter'])) ? $_POST['apiParameter'] : array();
        $apiParameterValue  = (isset($_POST['apiParameterValue'])) ? $_POST['apiParameterValue'] : array();

        $requiredHeader     = (isset($_POST['requiredHeader'])) ? $_POST['requiredHeader'] : array();
        $apiHeaders         = (isset($_POST['apiHeaders'])) ? $_POST['apiHeaders'] : array();
        $apiHeaderValue     = (isset($_POST['apiHeaderValue'])) ? $_POST['apiHeaderValue'] : array();

        $apiURL = (isset($_POST['apiUrl'])) ? $_POST['apiUrl'] : '';


        $request = array();
        if(!empty($apiParameter))
        {
            foreach($apiParameter as $key => $parameter)
            {
                $request[$parameter] = array(
                                    'value' => $apiParameterValue[$key],
                                    'required' => (isset($requiredParameter[$key])) ? true : false,
                                );
            }
        }

        $headers = array();
        if(!empty($apiHeaders))
        {
            foreach($apiHeaders as $key => $header)
            {
                if(!empty($header))
                {
                    $headers[$header] = array(
                                        'value' => $apiHeaderValue[$key],
                                        'required' => (isset($requiredHeader[$key])) ? true : false,
                                    );
                }

            }
        }

        //insert new user
        $params = array(
            'ap_name' => $api_name,
            //'ap_url' => $apiURL,
            'ap_parameters'  => json_encode($request,true),
            //'ap_headers' => !empty($headers) ? json_encode($headers,true) :'',
            'ap_method' => $api_method,
            'ap_request' => $api_request,
            'ap_response' => $api_response,
            'ap_notes' => $api_note,
            'ap_group_name' => $api_group_name,
            'ap_group_id' => $api_group_id,
            'ap_created_date' => time(),
            'ap_status' => 1,
        );

        $inserted = $db->where('ap_id',$ap_id)->update(TBL_APIS,$params);

        header('Location : add.php');
    }


    $api_list  = $db->query("SELECT * FROM ".TBL_APIS." WHERE ap_status = 1 ORDER BY ap_group_id ASC");

    $api_data = $db->where('ap_id',$ap_id)->getOne(TBL_APIS);


?>

<!DOCTYPE html>
<html>
    <head>
        <title><?=APP_NAME?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="NOINDEX, NOFOLLOW">
        <!-- Bootstrap -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="assets/css/theme-github.css" rel="stylesheet">

        <style>
            body {padding-top:50px!important;background:#f9f9f9;}
            section {margin-bottom:40px;}
            pre {border-radius: 0px;margin-bottom:20px;}
            body#body_docs .container,
            body#body_api_docs .container,
            body[id*='body_api_'] .container {margin:0px;width:100%;}
            body#body_docs h4,
            body#body_api_docs h4,
            body[id*='body_api_'] h4 {margin-top:20px;}
            .nav-header {padding-left: 15px;}
            .nav-list {margin: 0px -1px 0px 0px;border-right: 1px solid #e7e7e7;height: 90%;width: 225px;position: fixed;overflow: hidden;overflow-y: auto;z-index: 99;padding-bottom: 70px;}
            .nav-list>li a {font-size: 12px;color: #008CDD;padding: 3px 15px;}
            .nav-list>li a:hover {color: #333;background-color: transparent;}
            .nav-list>li.active a, .nav-list>li.active a:hover{background:linear-gradient(#4f9fef,#3577d0);color:#fff;font-weight:normal;text-shadow: 0 -1px 0 rgba(0,0,0,0.45);margin-right: -1px;z-index: 1;}
            .col-md-9 h3{color:#4088DD;}
            .spacer10 { border: 0 none;display: block;font-size: 0;height: 10px;margin: 0;padding: 0;width: 100%;}
            #scollNavbar{position: fixed; float: left; z-index: 9999; height: 90%;}
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
                <ul class="nav navbar-nav navbar-right">
                    <!--<li><a href="addApi.php" class="btn-default" style="margin-right: 20px;">Add API</a></li>-->
                </ul>
            </div>
        </header>  

        <div class="col-xs-12">  
                <div class="col-md-2" id="scollNavbar">
                    <!-- <ul class="nav nav-list" style="display: block;background-color:rgb(255,255,255) !important;">
                        <li class="nav-header"><h3>Basic API</h3></li>                                      
                            <a class="pull-left" href="javascript:void(0);">User Register</a>
                            <a class="pull-right" href="editApi.php?id=1">Edit </a>
                            <div class="clearfix"></div>
                        </li>
                    </ul> -->

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

                               echo '<li>
                                    <a class="pull-left" href="javascript:void(0);">'.$api['ap_name'].'</a>
                                    <a class="pull-right" href="edit.php?aid='.$api['ap_id'].'">Edit </a>
                                    <div class="clearfix"></div>
                                </li>';
                            }    
                        }


                        ?>                   
                    </ul>
                </div>

                <div class="col-md-10 col-md-offset-2" style="background:#fff;">
                    
                    <div class="col-md-9" style="">
                        <div class="spacer10"></div>    
                        <form role="form" method="POST" name="question-form" id="api-form">
     
                             <div class="form-group">
                                <div class="col-md-4">
                                    <label for="apiGroupName">API GROUP NAME:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" id="apiGroupName" name="apiGroupName" value="<?=$api_data['ap_group_name']?>">
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="apiGroupName">API GROUP ID:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" id="apiGroupId" name="apiGroupId" value="<?=$api_data['ap_group_id']?>">
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="apiRequest">Display API NAME:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" id="apiName" name="apiName" value="<?=$api_data['ap_name']?>">
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="apiUrl">API URL:</label>    
                                </div>
                                <div class="col-md-8">
                                    <input type="text"  name="apiUrl" class="form-control required" id="apiUrl" value="<?=API_BASE_URL?>">
                                </div>
                                <div class="clearfix"></div>
                            </div>
                             
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="apiMethod">TYPE OF METHOD:</label>
                                </div>
                                <div class="col-md-8">
                                    <select class="form-control required" id="apiMethod" name="apiMethod">
                                        <option <?= ($api_data['ap_method'] == 'GET') ? 'selected' : '' ?>  value="GET">GET</option>
                                        <option <?= ($api_data['ap_method'] == 'POST') ? 'selected' : '' ?> value="POST">POST</option>
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                             
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="apiRequest">REQUEST API NAME:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" id="apiRequest" name="apiRequest" value="<?= $api_data['ap_request']?>">
                                </div>
                                <div class="clearfix"></div>
                            </div>
                             
                            <!-- <div class="form-group">
                                <div class="col-md-4">
                                    <label for="">REQUEST HEADERS:</label>
                                </div>
                                
                                <div class="col-md-8">
                                    <p>
                                        <span class="help-block">
                                        Checkbox : checked if the header is required
                                        </span>
                                    </p>
                                    <p>
                                        <span class="help-block">
                                        Parameter : name of the header
                                        </span>
                                    </p>
                                    <p>
                                        <span class="help-block">
                                        Value    : Defination of the header
                                        </span>
                                    </p>
                                    
                                    <div id="header-container">
                                        
                                        <?php
                                            $headers = json_decode($api_data['ap_headers'],true);
                                            if(!empty($headers))
                                            {
                                                $header_index= 1;
                                                foreach($headers as $header_name => $header) {
                                                    $header_checkbox = ($header['required'] == 1) ? 'checked' : ''; 
                                                    $header_value = $header['value']; 
                                        ?>
                                                    <div class="col-md-12 option">
                                                        <div class="col-md-1">
                                                            <input type="checkbox" for="choice-1-txt" class="currect-choice-radio" name="requiredHeader[<?=$header_index?>]" value="1" <?= $header_checkbox?>>
                                                        </div>
                                                        
                                                        <div class="form-group col-md-5">
                                                            <input id="apiHeaders" type="text" class="form-control" name="apiHeaders[<?=$header_index?>]" value="<?= $header_name ?>" placeholder="Header <?= $header_index?>">
                                                        </div>
                                                        
                                                        <div class="form-group col-md-5">
                                                            <input id="apiHeaderValue1" type="text" class="form-control choice-txt" name="apiHeaderValue[<?=$header_index?>]" value="<?= $header_value ?>" placeholder="Header Value <?= $header_index?>">
                                                        </div>
                                                        
                                                        <div class="col-md-1">
                                                            <?php
                                                                if($header_index > 1)
                                                                    echo '<a href="javascript:void(0);" class="remove-option input-group-addon h4 pointer">Remove</a>';
                                                            ?>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="spacer10"></div>
                                                    </div>
                                                    <div class="spacer10"></div>
                                        <?php      
                                                $header_index++; }       
                                            }else{
                                        ?>
                                            <div class="col-md-12 option">
                                                <div class="col-md-1">
                                                    <input type="checkbox" for="choice-1-txt" class="currect-choice-radio" name="requiredHeader[1]" value="1">
                                                </div>
                                                
                                                <div class="form-group col-md-5">
                                                    <input id="apiHeaders" type="text" class="form-control" name="apiHeaders[1]" value="" placeholder="Header 1">
                                                </div>
                                                
                                                <div class="form-group col-md-5">
                                                    <input id="apiHeaderValue1" type="text" class="form-control choice-txt" name="apiHeaderValue[1]" value="" placeholder="Header Value 1">
                                                </div>
                                                
                                                <div class="col-md-1"></div>
                                                <div class="clearfix"></div>
                                                <div class="spacer10"></div>
                                            </div>
                                            <div class="spacer10"></div>
                                        <?php
                                           }
                                        ?>
                                    </div>
                                    
                                    <a href="javascript:void(0);" id="moreHeader">
                                        <i class="icon-plus bigger-110"></i>
                                        More..
                                    </a>
                                    
                                </div> 
                                <div class="clearfix"></div>
                                <div class="spacer10"></div>
                             </div> -->
                              
                             <div class="form-group">
                                <div class="col-md-4">
                                    <label for="">API PARAMETERS:</label>
                                </div>
                                
                                <div class="col-md-8">
                                    <p>
                                        <span class="help-block">
                                        Checkbox : checked if the parameter is required
                                        </span>
                                    </p>
                                    <p>
                                        <span class="help-block">
                                        Parameter : name of the parameter
                                        </span>
                                    </p>
                                    <p>
                                        <span class="help-block">
                                        Value    : Defination of the parameter
                                        </span>
                                    </p>
                                    
                                    <div id="answers-container">
                                        <?php
                                            $parameters = json_decode($api_data['ap_parameters'],true);
                                            if(!empty($parameters))
                                            {
                                                $no = 1;
                                                foreach($parameters as $name => $param) {
                                                    $parma_checkbox = ($param['required'] == 1) ? 'checked' : ''; 
                                                    $parma_value = $param['value']; 

                                        ?>

                                        <div class="col-md-12 option">
                                            <div class="col-md-1">
                                                <input type="checkbox" for="choice-1-txt" class="currect-choice-radio" name="requiredParameter[<?= $no ?>]" value="1" <?=$parma_checkbox?>>
                                            </div>
                                            
                                            <div class="form-group col-md-5">
                                                <input id="apiParameter1" type="text" class="required form-control" name="apiParameter[<?= $no ?>]" value="<?=$name?>" placeholder="Parameter <?= $no ?>">
                                            </div>
                                            
                                            <div class="form-group col-md-5">
                                                <input id="apiParameterValue1" type="text" class="form-control choice-txt" name="apiParameterValue[<?= $no ?>]" value="<?= $parma_value ?>" placeholder="Parameter Value <?= $no ?>">
                                            </div>
                                            
                                            <div class="col-md-1">
                                                <?php
                                                                if($no > 1)
                                                                    echo '<a href="javascript:void(0);" class="remove-option input-group-addon h4 pointer">Remove</a>';
                                                            ?>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="spacer10"></div>
                                        </div>
                                        <div class="spacer10"></div>

                                        <?php      
                                             $no++;    }       
                                            }else{
                                        ?>
                                            <div class="col-md-12 option">
                                                <div class="col-md-1">
                                                    <input type="checkbox" for="choice-1-txt" class="currect-choice-radio" name="requiredParameter[1]" value="1">
                                                </div>
                                                
                                                <div class="form-group col-md-5">
                                                    <input id="apiParameter1" type="text" class="required form-control" name="apiParameter[1]" value="" placeholder="Parameter 1">
                                                </div>
                                                
                                                <div class="form-group col-md-5">
                                                    <input id="apiParameterValue1" type="text" class="form-control choice-txt" name="apiParameterValue[1]" value="" placeholder="Parameter Value 1">
                                                </div>
                                                
                                                <div class="col-md-1"></div>
                                                <div class="clearfix"></div>
                                                <div class="spacer10"></div>
                                            </div>
                                            <div class="spacer10"></div>

                                        <?php      
                                                }       
                                        ?>
                                    </div>
                                    
                                    <a href="javascript:void(0);" id="moreOption">
                                        <i class="icon-plus bigger-110"></i>
                                        More..
                                    </a>
                                    
                                </div> 
                                <div class="clearfix"></div>
                                <div class="spacer10"></div>
                             </div>
                             
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="apiResponse">API RESPONSE:</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea placeholder="JSON Response of api" class="required form-control" rows="5" id="apiResponse" name="apiResponse"><?= $api_data['ap_response']?></textarea>    
                                </div>
                                <div class="clearfix"></div>
                            </div>
                             
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="apiNote">API NOTE:</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea placeholder="Api note" class="required form-control" rows="5" id="apiNote" name="apiNote"><?= $api_data['ap_notes']?></textarea>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                             
                             <div class="form-group">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-success" id="questionSubmit" name="submit"><i class="icon-check"></i> Submit</button>
                                    <a class="btn btn-danger" href="index.php"><i class="icon-remove"></i> Cancel</a>
                                </div>
                            </div>
                             
                         </form>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="clearfix"></div>
                </div>
        </div>


        <script src="assets/js/jquery-2.1.3.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.validate.min.js"></script>
        <script src="assets/js/prettify.js"></script>
        <script>
            //prettyPrint();
            
            var optCount = 1;
            var headerCount = 1;
        
            function moreOption()
            {
                optCount = $('#answers-container div.option').length + 1;
                html = '';
                
                html += '<div class="col-md-12 option">'+
                        '<div class="col-md-1">'+
                        '<input type="checkbox" for="choice-1-txt" class="currect-choice-radio" name="requiredParameter['+optCount+']" value="1">'+
                        '</div>'+
                                            
                        '<div class="form-group col-md-5">'+
                        '<input id="apiParameter1" type="text" class="required form-control" name="apiParameter['+optCount+']" value="" placeholder="Parameter '+optCount+'">'+
                        '</div>'+
                                            
                        '<div class="form-group col-md-5">'+
                        '<input id="apiParameterValue'+optCount+'" type="text" class="form-control" name="apiParameterValue['+optCount+']" value="" placeholder="Parameter Value '+optCount+'">'+
                        '</div>'+
                                            
                        '<div class="col-md-1">'+
                        '<a href="javascript:void(0);" class="remove-option input-group-addon h4 pointer">Remove</a>'+
                        '</div>'+
                        '<div class="clearfix"></div>'+
                        '<div class="spacer10"></div>'+
                        '</div>'+
                        '<div class="spacer10"></div>';
                
                
                optCount++;
                $('#answers-container').append(html);
                
            }

            function moreHeader()
            {
                headerCount = $('#header-container div.option').length + 1;
                html = '';
                
                html += '<div class="col-md-12 option">'+
                        '<div class="col-md-1">'+
                        '<input type="checkbox" for="choice-1-txt" class="currect-choice-radio" name="requiredHeader['+headerCount+']" value="1">'+
                        '</div>'+
                                            
                        '<div class="form-group col-md-5">'+
                        '<input id="apiHeaders1" type="text" class="required form-control" name="apiHeaders['+headerCount+']" value="" placeholder="Header '+headerCount+'">'+
                        '</div>'+
                                            
                        '<div class="form-group col-md-5">'+
                        '<input id="apiHeaderValue'+headerCount+'" type="text" class="form-control" name="apiHeaderValue['+headerCount+']" value="" placeholder="Header Value '+headerCount+'">'+
                        '</div>'+
                                            
                        '<div class="col-md-1">'+
                        '<a href="javascript:void(0);" class="remove-option input-group-addon h4 pointer">Remove</a>'+
                        '</div>'+
                        '<div class="clearfix"></div>'+
                        '<div class="spacer10"></div>'+
                        '</div>'+
                        '<div class="spacer10"></div>';
                
                
                headerCount++;
                $('#header-container').append(html);
                
            }
    
            $(document).ready(function(){
                
                $('#api-form').validate({
                    errorElement: 'div',
                    errorClass: 'text-danger',
                    focusInvalid: false,
                    rules: {
                       
                    },
                    messages: {
                    },
                    invalidHandler: function(event, validator) { //display error alert on form submit   
                        $('.alert-danger', $('.login-form')).show();
                    },
                    highlight: function(e) {
                        $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                    },
                    success: function(e) {
                        $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                        $(e).remove();
                    },
                    errorPlacement: function(error, element) {
                        if (element.is(':checkbox') || element.is(':radio')) {
                            var controls = element.closest('div[class*="col-"]');
                            if (controls.find(':checkbox,:radio').length > 1)
                                controls.append(error);
                            else
                                error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        }
                        else if (element.is('.select2')) {
                            error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                        }
                        else if (element.is('.chosen-select')) {
                            error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                        }
                        else
                            error.insertAfter(element);
                    }
                });
            
            
                $('#moreOption').click(function(){
                    moreOption();
                });

                $('#moreHeader').click(function(){
                    moreHeader();
                });
                
                
                $(document).on("click", "a.remove-option" , function() {
                    $(this).parents('.option').remove();
                }); 
                
            });
            
        </script>
    </body>
</html>