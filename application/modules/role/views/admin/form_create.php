<section id="content">
    <div id="content-head">
        <h2>Role</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/role'); ?>"></a></li>
            <li><a class="action-create active" href="<?php echo base_url('admin/role/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
    
        <?php echo form_open('admin/role/create/'); ?>
            <?php echo form_fieldset('General'); ?>
            <div class="half">
                <ul>
                    <li>
                        <?php echo form_label('Name<span>*</span>', 'name'); ?>
                        <?php echo form_input('name', set_value('name'), 'class=\'medium\''); ?>
                    </li>
                    
                    <li>
                        <?php echo form_label('Removable', 'removable'); ?>
                        <select class="medium-2" name="removable">
                            <option value="1" <?php echo set_select('removable"', '1', TRUE); ?>>Yes</option>
                            <option value="0" <?php echo set_select('removable', '0'); ?>>No</option>
                        </select>  
                    </li>
                    
                    <li>
                        <?php echo form_label('Description', 'description'); ?>
                        <?php echo form_textarea('description', set_value('description'), 'class=\'large\''); ?>
                    </li>
                </ul>
            </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Permissions'); ?>
                <?php foreach($permission as $module => $perms) { ?>
                <div class="half">
                    <label><?php echo $module; ?></label>
                    <ul class="permission-checkbox-list">
                        <li><input type="checkbox" class="checkbox check-all"><b>All</b></li>
                        <?php foreach($perms as $perm) { ?>
                        <?php $checked = in_array($perm->getId(), $selected_perm_ids)?true:false; ?>
                        <li>
                            <?php echo form_checkbox('perm_ids[]', $perm->getId(), $checked, 'class=\'checkbox\''); ?> <?php echo $perm->getName(); ?>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
            <?php echo form_fieldset_close(); ?>
            
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('role_create', '', 'class=\'btn-create\''); ?></li>
                    <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>