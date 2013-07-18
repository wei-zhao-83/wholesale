<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Site Title</title>
    
    <link rel="stylesheet" href="<?php echo site_url('assets/css/reset.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/text.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/autoSuggest.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.fancybox.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/site.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/flick/jquery-ui-1.10.3.custom.min.css') ?>">
</head>
<body data-view="<?php echo !empty($current_view) ? $current_view : ''; ?>">
    <div id="header-wrapper">
        <header id="header" class="container">
            <a href="<?php echo site_url('admin'); ?>" id="link-dashboard">
                Home
            </a>
            
            <ul id="quicklinks">
                <?php if($this->auth->is_logged_in()){ ?>
                    <li><a href="#">howdy, <?php echo $this->session->userdata('username'); ?></a></li>
                    <li>
                        <a href="<?php echo site_url('admin/message'); ?>">
                            message
                            <?php $num_unread_msg = $this->em->getRepository('message\models\Message')->getTotalUnreadMessage($this->session->userdata('id')); ?>
                            <?php if($num_unread_msg > 0) { ?>
                            <span class="bubble"><?php echo $num_unread_msg; ?></span>
                            <?php } ?>
                        </a>
                    </li>
                    <li><a href="<?php echo site_url('admin/logout'); ?>">logout</a></li>
                <?php } else { ?>
                    <li><a href="<?php echo site_url('admin/login'); ?>">login</a></li>
                <?php } ?>
            </ul>
        </header>
    </div>
    
    <div id="notification"></div>
    
    <div id="content-wrapper">
        <div class="container">
            <?php if($this->auth->is_logged_in()) { ?>
                <?php $this->load->view('admin/menu'); ?>
            <?php } ?>
                