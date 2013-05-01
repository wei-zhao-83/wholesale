<section id="content">
    <div id="content-head">
        <h2>Permission</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/permission'); ?>"></a></li>
            <li><a class="action-create active" href="<?php echo base_url('admin/permission/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <?php echo form_open('admin/permission/create/'); ?>
            <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Name', 'name'); ?>
                            <?php echo form_input('name', set_value('name'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Module', 'module'); ?>
                            <select class='medium' name="module">
                                <?php foreach($modules as $module): ?>
                                <option value="<?php echo $module->getSlug(); ?>" <?php echo set_select('module', $module->getSlug()); ?>><?php echo $module->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        
                        <li>
                            <?php echo form_label('Removable', 'removable'); ?>
                            <select class='medium' name="removable">
                                <option value="1" <?php echo set_select('removable"', '1', TRUE); ?>>Yes</option>
                                <option value="0" <?php echo set_select('removable', '0'); ?>>No</option>
                            </select>
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
            
            <div class="btn-box">
            <ul>
                <li><?php echo form_submit('permission_create', '', 'class=\'btn-create\''); ?></li>
                <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
            </ul>
        </div>
        <?php echo form_close(); ?>
    </div>
</section>