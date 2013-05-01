<section id="content">
    <div id="content-head">
        <h2>Role</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/role'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/role/create'); ?>"></a></li>
            <li><a class="action-edit active" href=""></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        <?php echo form_open('admin/role/edit/' . $role->getId()); ?>
                <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Name', 'name'); ?>
                            <?php echo form_input('name', set_value('name', $role->getName()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Removable', 'removable'); ?>
                            <?php $selected_removable = ($this->input->post('removable'))?$this->input->post('removable'):$role->getRemovable(); ?>
                            <select class="medium" name="removable">
                                <option value="1" <?php if($selected_removable == 1){ ?> selected="selected" <?php } ?>>Yes</option>
                                <option value="0" <?php if($selected_removable == 0){ ?> selected="selected" <?php } ?>>No</option>
                            </select>
                        </li>
                        
                        <li>
                            <?php echo form_label('Description', 'description'); ?>
                            <?php echo form_textarea('description', set_value('description', $role->getDescription()), 'class=\'full\''); ?>
                        </li>
                    </ul>
                <div class="half">
                    
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
                            <?php echo form_checkbox('perm_ids[]', $perm->getId(), $checked, 'class=\'checkbox\''); ?><?php echo $perm->getName(); ?>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_hidden('id', $role->getId()); ?>
            <?php echo form_hidden('edit', 1); ?>
            
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('role_create', '', 'class=\'btn-create\''); ?></li>
                    <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>