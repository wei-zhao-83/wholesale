<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Site Title</title>
    
    <link rel="stylesheet" href="<?php echo site_url('assets/css/reset.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/text.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/site.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/autoSuggest.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.fancybox.css') ?>">
    
    
    <script src="<?php echo site_url('assets/js/jquery-1.7.2.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo site_url('assets/js/jquery.autoSuggest.js') ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo site_url('assets/js/jquery.fancybox.pack.js') ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo site_url('assets/js/site.js') ?>" type="text/javascript"></script>
    
</head>
<body>
    <div id="header-wrapper">
        <header id="header" class="container">
            <a href="<?php echo site_url('admin'); ?>" id="logo">
                <!--<img src="http://placehold.it/80x40">-->
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
    
    <div id="content-wrapper">
        <div class="container">