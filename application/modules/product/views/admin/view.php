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
    <div id="content-wrapper">
        <div class="container">
            <section id="content">
            
            <div id="content-head">
                <h4>Modified by <?php echo $changes['user']['username'] ?> @ <?php echo date('Y-m-d H:i:s', $timestamp); ?></h5>
            </div>
            
                <div id="white-bg-container">                    
                    <?php echo form_open(''); ?>
                    
                        <?php echo form_fieldset('General'); ?>
                            <div class="half">
                                <ul>
                                    <li>
                                        <?php echo form_label('Name', 'name'); ?>
                                        <div class="text">
                                            <div class="medium"><?php echo $changes['content']['name'] ?></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <?php echo form_label('Barcode', 'barcode'); ?>
                                        <div class="text">
                                            <div class="medium"><?php echo $changes['content']['barcode'] ?></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <?php echo form_label('SKU', 'SKU'); ?>
                                        <div class="text">
                                            <div class="medium"><?php echo $changes['content']['sku'] ?></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <?php echo form_label('Section', 'section'); ?>
                                        <div class="text">
                                            <div class="medium"><?php echo $changes['content']['section'] ?></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="half">
                                <ul>
                                    <li>
                                        <?php echo form_label('Description', 'description'); ?>
                                        <div class="text">
                                            <div class="large-2"><?php echo $changes['content']['description'] ?></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <?php echo form_label('Comment', 'comment'); ?>
                                        <div class="text">
                                            <div class="large-2"><?php echo $changes['content']['comment'] ?></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        <?php echo form_fieldset_close(); ?>
                    
                        <?php echo form_fieldset('Pricing & Stock'); ?>
                            <div class="half">
                                <ul>
                                    <li>
                                        <?php echo form_label('Cost', 'cost'); ?>
                                        <div class="text">
                                            <div class="medium"><?php echo $changes['content']['cost'] ?></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <?php echo form_label('Suggested Price', 'suggested_price'); ?>
                                        <div class="text">
                                            <div class="medium"><?php echo $changes['content']['suggested_price'] ?></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <?php echo form_label('No Service Price', 'no_service_price'); ?>
                                        <div class="text">
                                            <div class="medium"><?php echo $changes['content']['no_service_price'] ?></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <?php echo form_label('BB2', 'full_service_price'); ?>
                                        <div class="text">
                                            <div class="medium"><?php echo $changes['content']['full_service_price'] ?></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <?php echo form_label('CNC', 'cash_and_carry'); ?>
                                        <div class="text">
                                            <div class="medium"><?php echo $changes['content']['cash_and_carry'] ?></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <?php echo form_label('Discount', 'discount'); ?>
                                        <div class="text">
                                            <div class="medium"><?php echo $changes['content']['discount'] ?></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="half">
                                <ul>
                                    <li>
                                        <?php echo form_label('Unit', 'unit'); ?>
                                        <div class="text">
                                            <div class="small"><?php echo $changes['content']['unit'] ?></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <?php echo form_label('Qty', 'qty'); ?>
                                        <div class="text">
                                            <div class="small"><?php echo $changes['content']['qty'] ?></div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <?php echo form_label('Qty/Unit', 'qty_unit'); ?>
                                        <div class="text">
                                            <div class="small"><?php echo $changes['content']['qty_unit'] ?></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        <?php echo form_fieldset_close(); ?>
                    
                    <?php echo form_close(); ?>
                </div>
            </section>
        </div>
    </div>
</body>
</html>