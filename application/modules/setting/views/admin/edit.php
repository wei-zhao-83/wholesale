<section id="content">
    <div id="content-head">
        <h2>Setting</h2>
        
        <ul>
            <li><a class="action-edit active" href=""></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <?php echo form_open('admin/setting/'); ?>
            <?php echo form_fieldset('Company'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Name', 'name'); ?>
                            <?php echo form_input('company[name]', set_value('company[name]', $company['name']), 'class=\'large\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Phone', 'phone'); ?>
                            <?php echo form_input('company[phone]', set_value('company[phone]', $company['phone']), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Fax', 'fax'); ?>
                            <?php echo form_input('company[fax]', set_value('company[fax]', $company['fax']), 'class=\'medium\''); ?>
                        </li>
                        
                    </ul>
                </div>
                
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Address', 'address'); ?>
                            <?php echo form_input('company[address]', set_value('company[address]', $company['address']), 'class=\'large\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('City', 'city'); ?>
                            <?php echo form_input('company[city]', set_value('company[city]', $company['city']), 'class=\'small\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Province', 'prov'); ?>
                            <?php echo form_input('company[prov]', set_value('company[prov]', $company['prov']), 'class=\'small\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Postal', 'postal'); ?>
                            <?php echo form_input('company[postal]', set_value('company[postal]', $company['postal']), 'class=\'small\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Account'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Tax', 'tax'); ?>
                            <?php echo form_input('tax', set_value('tax', $tax), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('HST #', 'hst'); ?>
                            <?php echo form_input('hst', set_value('hst', $hst), 'class=\'medium\''); ?>
                        </li>
                    </ul>
                </div>
                
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Currency', 'currency'); ?>
                            <?php echo form_input('currency', set_value('currency', $currency), 'class=\'large\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_hidden('edit', 1); ?>
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('setting_edit', '', 'class=\'btn-create\''); ?></li>
                    <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>