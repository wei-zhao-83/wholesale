<section id="content">
    <div id="content-head">
        <h2>Vendor</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/vendor'); ?>"></a></li>
            <li><a class="action-create active" href="<?php echo base_url('admin/vendor/create'); ?>"></a></li>
        </ul>
    </div>

    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
    
        <?php echo form_open_multipart('admin/vendor/create/'); ?>
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
                            <?php echo form_input('tags', '', 'class=\'large\' id=\'tags\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Order Frequency', 'order_frequency'); ?>
                            <?php echo form_input('order_frequency', set_value('order_frequency'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('HST number', 'hst_number'); ?>
                            <?php echo form_input('hst_number', set_value('hst_number'), 'class=\'medium\''); ?>
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
            
            <?php echo form_fieldset('Bank Account'); ?>
            <div class="half">
                <ul>
                    <li>
                        <?php echo form_label('Bank Name', 'bank_name'); ?>
                        <?php echo form_input('bank_name', set_value('bank_name'), 'class=\'large\''); ?>
                    </li>
                </ul>
            </div>
            <div class="half">
                <ul>
                    <li>
                        <?php echo form_label('Branch Number', 'bank_branch'); ?>
                        <?php echo form_input('bank_branch', set_value('bank_branch'), 'class=\'medium\''); ?>
                    </li>
                    <li>
                        <?php echo form_label('Account Number', 'bank_account'); ?>
                        <?php echo form_input('bank_account', set_value('bank_account'), 'class=\'medium\''); ?>
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
            
            <?php echo form_fieldset('Contacts'); ?>
                <table id="contact-input">
                    <tr>
                        <th class="small">Name</th>
                        <th class="xsmall">Direct Line</th>
                        <th class="small">Phone</th>
                        <th class="small">Comment</th>
                        <th class="xxsmall"><a href="#" class="btn-add"></a></th>
                    </tr>
                    <tr id="row-0">
                        <td><?php echo form_input('vendor_contacts[0][name]', '', 'class=\'small\''); ?></td>
                        <td><?php echo form_input('vendor_contacts[0][direct_line]', '', 'class=\'xxsmall\''); ?></td>
                        <td><?php echo form_input('vendor_contacts[0][phone]', '', 'class=\'small\''); ?></td>
                        <td><?php echo form_input('vendor_contacts[0][comment]', '', 'class=\'medium\''); ?></td>
                        <td><a href="#" class="btn-remove"></a></td>
                    </tr>
                </table>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Images'); ?>
                <table id="image-input">
                    <tr>
                        <th class="medium">File</th>
                        <th class="small">Name</th>
                        <th class="small">Alt</th>
                        <th class="xxsmall">Order</th>
                        <th class="xsmall">Main</th>
                        <th class="xxsmall"><a href="#" class="btn-add"></a></th>
                    </tr>
                    <tr id="row-0">
                        <td><?php echo form_upload('image_file_0', '', 'class=\'medium\''); ?></td>
                        <td><?php echo form_input('vendor_images[0][name]', '', 'class=\'small\''); ?></td>
                        <td><?php echo form_input('vendor_images[0][alt]', '', 'class=\'small\''); ?></td>
                        <td><?php echo form_input('vendor_images[0][arrange]', '', 'class=\'xxsmall\''); ?></td>
                        <td>
                            <select class="xsmall" name="vendor_images[0][main]">
                                <option value="1" <?php echo set_select('vendor_images[0][main]', '1', TRUE); ?>>Yes</option>
                                <option value="0" <?php echo set_select('vendor_images[0][main]', '0'); ?>>No</option>
                            </select>
                        </td>
                        <td><a href="#" class="btn-remove"></a></td>
                    </tr>
                </table>
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
<script>
    $(document).ready(function() {
        $("#tags").autoSuggest("<?php echo site_url('admin/tag/ajax_search');?>", {
            minChars: 2,
            neverSubmit: "true",
            startText: "Tags",
            asHtmlID: "tags",
            preFill: "<?php echo $post_tags; ?>"
        });
        
        $(".add-tag").fancybox({
           maxWidth: 530,
           minWidth: 530,
           maxHeight: 390,
           minHeight: 390
        });
    });
</script>