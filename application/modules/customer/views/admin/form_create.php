<section id="content">
    <div id="content-head">
        <h2>Customer</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/customer'); ?>"></a></li>
            <li><a class="action-create active" href="<?php echo base_url('admin/customer/create'); ?>"></a></li>
        </ul>
    </div>

    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
    
        <?php echo form_open_multipart('admin/customer/create/'); ?>
            <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Name', 'name'); ?>
                            <?php echo form_input('name', set_value('name'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Email', 'email'); ?>
                            <?php echo form_input('email', set_value('email'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Phone', 'phone'); ?>
                            <?php echo form_input('phone', set_value('phone'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Fax', 'fax'); ?>
                            <?php echo form_input('fax', set_value('fax'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <label for="tag">Tags <span>[<a class="add-tag" data-fancybox-type="iframe" href="<?php echo site_url('admin/tag/create/');?>/ajax">Add New</a>]</span></label>
                            <input name="tags" class="large" id="tags" data-url="<?php echo site_url('admin/tag/ajax_search/'); ?>" data-prefill="<?php echo $this->input->post('as_values_tags'); ?>" >
                        </li>
                    </ul>
                </div>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Description', 'description'); ?>
                            <?php echo form_textarea('description', set_value('description'), 'class=\'large-2\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Billing & Shipping'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Shipping Address', 'shipping_address'); ?>
                            <?php echo form_input('shipping_address', set_value('shipping_address'), 'class=\'large\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Shipping City', 'shipping_city'); ?>
                            <?php echo form_input('shipping_city', set_value('shipping_city'), 'class=\'small\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Shipping Province', 'shipping_province_abbr'); ?>
                            <?php echo form_input('shipping_province_abbr', set_value('shipping_province_abbr'), 'class=\'small\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Shipping Postal', 'shipping_postal'); ?>
                            <?php echo form_input('shipping_postal', set_value('shipping_postal'), 'class=\'small\''); ?>
                        </li>
                    </ul>
                </div>
                
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Billing Address', 'billing_address'); ?>
                            <?php echo form_input('billing_address', set_value('billing_address'), 'class=\'large\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Billing City', 'billing_city'); ?>
                            <?php echo form_input('billing_city', set_value('billing_city'), 'class=\'small\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Billing Province', 'billing_province_abbr'); ?>
                            <?php echo form_input('billing_province_abbr', set_value('billing_province_abbr'), 'class=\'small\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Billing Postal', 'billing_postal'); ?>
                            <?php echo form_input('billing_postal', set_value('billing_postal'), 'class=\'small\''); ?>
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