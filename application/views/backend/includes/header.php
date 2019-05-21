<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php echo BRAND;?></title>
	
	<link rel="icon" href="<?php echo $this->config->item("uploads_url")."/settings/logo.png";?>" type="image/gif">

    <link href="<?php echo $this->config->item("inspinia_css_url");?>/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $this->config->item("inspinia_font_awesome_url");?>/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="<?php echo $this->config->item("inspinia_js_url");?>/plugins/gritter/jquery.gritter.css" rel="stylesheet">

    <link href="<?php echo $this->config->item("inspinia_css_url");?>/animate.css" rel="stylesheet">
    <link href="<?php echo $this->config->item("inspinia_css_url");?>/style.css" rel="stylesheet">
	<script src="<?php echo $this->config->item("inspinia_js_url");?>/jquery-3.1.1.min.js"></script>
</head>

<body>
    <div id="wrapper">