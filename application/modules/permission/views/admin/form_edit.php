<section id="content">
    <div id="content-head">
        <h2>Permission</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/permission'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/permission/create'); ?>"></a></li>
            <li><a class="action-edit active" href=""></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
    
        <?php echo form_open('admin/permission/edit/' . $permission->getId()); ?>
            <?php echo form_fieldset('General'); ?>
            <div class="half">
                <ul>
                    <li>
                        <?php echo form_label('Name', 'name'); ?>
                        <?php echo form_input('name', set_value('name', $permission->getName()), 'class=\'medium\''); ?>
                    </li>
                    
                    <li>
                        <?php echo form_label('Module', 'module'); ?>
                        <select class='medium' name="module">
                            <?php $selected_module = ($this->input->post('module'))?$this->input->post('module'):$permission->getModule(); ?>
                            <?php foreach($modules as $module): ?>
                            <option value="<?php echo $module->getSlug(); ?>" <?php if($selected_module == $module->getSlug()){ ?> selected="selected" <?php } ?>><?php echo $module->getName(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </li>
                    
                    <li>
                        <?php $selected_removable = ($this->input->post('removable'))?$this->input->post('removable'):$permission->getRemovable(); ?>
                        <?php echo form_label('Removable', 'removable'); ?>
                        <select class='medium' name="removable">
                            <option value="1" <?php if($selected_removable == 1){ ?> selected="selected" <?php } ?>>Yes</option>
                            <option value="0" <?php if($selected_removable == 0){ ?> selected="selected" <?php } ?>>No</option>
                        </select>
                    </li>
                </ul>
            </div>
            
            <div class="half">
                <ul>
                    <li>
                        <?php echo form_label('Description', 'description'); ?>
                        <?php echo form_textarea('description', set_value('description', $permission->getDescription()), 'class=\'large-2\''); ?>
                    </li>
                </ul>
            </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_hidden('id', $permission->getId()); ?>
            <?php echo form_hidden('edit', 1); ?>
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('permission_create', '', 'class=\'btn-create\''); ?></li>
                    <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>