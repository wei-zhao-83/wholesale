<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Site Title</title>
    
    <link rel="stylesheet" href="<?php echo site_url('assets/css/reset.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/text.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/site.css') ?>">
</head>

<body>
    <div id="content-wrapper-ajax">
        <div class="container">
            <section id="content-popup">
            
            <div id="content-head">
                <h4>Tag</h5>
            </div>
                <div id="white-bg-container">
                    <?php $this->load->view('admin/message'); ?>
                    
                    <?php echo form_open('admin/tag/create/ajax'); ?>
                        <?php echo form_fieldset(''); ?>
                            <div>
                                <ul>
                                    <li>
                                        <?php echo form_label('Name', 'name'); ?>
                                        <?php echo form_input('name', set_value('name'), 'class=\'large-2\''); ?>
                                    </li>
                                </ul>
                            </div>
                        <?php echo form_fieldset_close(); ?>
                        
                        <div class="btn-box">
                            <ul>
                                <li><?php echo form_submit('user_create', '', 'class=\'btn-create\''); ?></li>
                                <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
                            </ul>
                        </div>
                        
                    <?php echo form_close(); ?>
                </div>
            </section>
        </div>
    </div>
</body>
</html>