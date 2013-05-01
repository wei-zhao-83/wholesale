<section id="content">
    <div id="content-head">
        <h2>User</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/user'); ?>"></a></li>
            <li><a class="action-create active" href="<?php echo base_url('admin/user/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <?php echo form_open('admin/user/create/'); ?>
            <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Firstname', 'firstname'); ?>
                            <?php echo form_input('user_metas[firstname]', set_value('user_metas[firstname]'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Lastname', 'lastname'); ?>
                            <?php echo form_input('user_metas[lastname]', set_value('user_metas[lastname]'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Username<span>*</span>', 'username'); ?>
                            <?php echo form_input('username', set_value('username'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Password<span>*</span>', 'password'); ?>
                            <?php echo form_input('password', set_value('password'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Email<span>*</span>', 'email'); ?>
                            <?php echo form_input('email', set_value('email'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Phone<span>*</span>', 'phone'); ?>
                            <?php echo form_input('phone', set_value('phone'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Role', 'role'); ?>
                            <select class="medium-2" name="role">
                                <?php foreach($roles as $role): ?>
                                <option value="<?php echo $role->getId(); ?>" <?php echo set_select('role', $role->getId()); ?>><?php echo $role->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        
                        <li>
                            <?php echo form_label('Active', 'active'); ?>
                            <select class="medium-2" name="active">
                                <option value="1" <?php echo set_select('active"', '1', TRUE); ?>>Yes</option>
                                <option value="0" <?php echo set_select('active', '0'); ?>>No</option>
                            </select>  
                        </li>
                </ul>
            </div>
            <div class="half">
                <ul>
                    <li>
                        <?php echo form_label('Comment', 'comment'); ?>
                        <?php echo form_textarea('user_metas[comment]', set_value('user_metas[comment]'), 'class=\'large-2\''); ?>
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