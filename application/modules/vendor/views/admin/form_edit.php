<section id="content">
    <div id="content-head">
        <h2>Vendor</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/vendor'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/vendor/create'); ?>"></a></li>
            <li><a class="action-edit active" href=""></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <?php echo form_open_multipart('admin/vendor/edit/' . $vendor->getId()); ?>
            <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Name', 'name'); ?>
                            <?php echo form_input('name', set_value('name', $vendor->getName()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Email', 'email'); ?>
                            <?php echo form_input('email', set_value('email', $vendor->getEmail()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Phone', 'phone'); ?>
                            <?php echo form_input('phone', set_value('phone', $vendor->getPhone()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Fax', 'fax'); ?>
                            <?php echo form_input('fax', set_value('fax', $vendor->getFax()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <label for="tag">Tags <span>[<a class="add-tag" data-fancybox-type="iframe" href="<?php echo site_url('admin/tag/create/');?>/ajax">Add New</a>]</span></label>
                            <input name="tags" class="large" id="tags" data-prefill="<?php echo $current_tags; ?>" >
                        </li>
                        
                        <li>
                            <?php echo form_label('Order Frequency', 'order_frequency'); ?>
                            <?php echo form_input('order_frequency', set_value('order_frequency', $vendor->getOrderFrequency()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('HST number', 'hst_number'); ?>
                            <?php echo form_input('hst_number', set_value('hst_number', $vendor->getHstNumber()), 'class=\'medium\''); ?>
                        </li>
                    </ul>
                </div>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Description', 'description'); ?>
                            <?php echo form_textarea('description', set_value('description', $vendor->getDescription()), 'class=\'large-2\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Bank Account'); ?>
            <div class="half">
                <ul>
                    <li>
                        <?php echo form_label('Bank Name', 'bank_name'); ?>
                        <?php echo form_input('bank_name', set_value('bank_name', $vendor->getBankName()), 'class=\'large\''); ?>
                    </li>
                </ul>
            </div>
            <div class="half">
                <ul>
                    <li>
                        <?php echo form_label('Branch Number', 'bank_branch'); ?>
                        <?php echo form_input('bank_branch', set_value('bank_branch', $vendor->getBankBranch()), 'class=\'medium\''); ?>
                    </li>
                    <li>
                        <?php echo form_label('Account Number', 'bank_account'); ?>
                        <?php echo form_input('bank_account', set_value('bank_account', $vendor->getBankAccount()), 'class=\'medium\''); ?>
                    </li>
                </ul>
            </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Billing & Shipping'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Shipping Address', 'shipping_address'); ?>
                            <?php echo form_input('shipping_address', set_value('shipping_address', $vendor->getShippingAddress()), 'class=\'large\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Shipping City', 'shipping_city'); ?>
                            <?php echo form_input('shipping_city', set_value('shipping_city', $vendor->getShippingCity()), 'class=\'small\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Shipping Province', 'shipping_province_abbr'); ?>
                            <?php echo form_input('shipping_province_abbr', set_value('shipping_province_abbr', $vendor->getShippingProvinceAbbr()), 'class=\'small\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Shipping Postal', 'shipping_postal'); ?>
                            <?php echo form_input('shipping_postal', set_value('shipping_postal', $vendor->getShippingPostal()), 'class=\'small\''); ?>
                        </li>
                    </ul>
                </div>
                
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Billing Address', 'billing_address'); ?>
                            <?php echo form_input('billing_address', set_value('billing_address', $vendor->getBillingAddress()), 'class=\'large\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Billing City', 'billing_city'); ?>
                            <?php echo form_input('billing_city', set_value('billing_city', $vendor->getBillingCity()), 'class=\'small\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Billing Province', 'billing_province_abbr'); ?>
                            <?php echo form_input('billing_province_abbr', set_value('billing_province_abbr', $vendor->getBillingProvinceAbbr()), 'class=\'small\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Billing Postal', 'billing_postal'); ?>
                            <?php echo form_input('billing_postal', set_value('billing_postal', $vendor->getBillingPostal()), 'class=\'small\''); ?>
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
                    
                    <?php foreach($vendor->getContacts() as $contact) { ?>
                        <tr>
                            <td><?php echo form_input('vendor_contacts['.$contact->getId().'][name]', $contact->getName(), 'class=\'small\''); ?></td>
                            <td><?php echo form_input('vendor_contacts['.$contact->getId().'][direct_line]', $contact->getDirectLine(), 'class=\'xxsmall\''); ?></td>
                            <td><?php echo form_input('vendor_contacts['.$contact->getId().'][phone]', $contact->getPhone(), 'class=\'small\''); ?></td>
                            <td><?php echo form_input('vendor_contacts['.$contact->getId().'][comment]', $contact->getComment(), 'class=\'medium\''); ?></td>
                            <td><a href="#" class="btn-remove show-inline"></a></td>
                        </tr>
                    <?php } ?>
                    <tr id="row-0">
                        <td><?php echo form_input('vendor_contacts[0][name]', '', 'class=\'small\''); ?></td>
                        <td><?php echo form_input('vendor_contacts[0][direct_line]', '', 'class=\'xxsmall\''); ?></td>
                        <td><?php echo form_input('vendor_contacts[0][phone]', '', 'class=\'small\''); ?></td>
                        <td><?php echo form_input('vendor_contacts[0][comment]', '', 'class=\'medium\''); ?></td>
                        <td><a href="#" class="btn-remove"></a></td>
                    </tr>
                </table>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Transactions'); ?>
                <?php foreach($vendor->getPurchases() as $purchase) { ?>
                    <?php if(!$purchase->getDeletedAt()) { ?>
                        <div class="history-element">
                            <div class="history-time"><?php echo $purchase->getCreatedAt(); ?></div>
                            <div class="history-status"><?php echo $purchase->getStatus()->getName(); ?></div>
                            <div class="history-total">$<?php echo $purchase->getTotal(); ?></div>
                            <div class="history-view"><a class="view-history btn-view" href="<?php echo site_url('admin/purchase/edit/');?>/<?php echo $purchase->getId(); ?>"></a></div>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_hidden('id', $vendor->getId()); ?>
            <?php echo form_hidden('edit', 1); ?>
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('vendor_create', '', 'class=\'btn-create\''); ?></li>
                    <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>