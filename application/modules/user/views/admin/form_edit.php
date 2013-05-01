<section id="content">
    <div id="content-head">
        <h2>User</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/user'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/user/create'); ?>"></a></li>
            <li><a class="action-edit active" href=""></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <?php echo form_open('admin/user/edit/' . $user->getId()); ?>
            <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>            
                            <?php echo form_label('Firstname', 'firstname'); ?>
                            <?php $firstname = $user->getUserMeta('firstname'); ?>
                            <?php echo form_input('user_metas[firstname]', set_value('user_metas[firstname]', !empty($firstname)?$firstname->getValue():''), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Lastname', 'lastname'); ?>
                            <?php $lastname = $user->getUserMeta('lastname'); ?>
                            <?php echo form_input('user_metas[lastname]', set_value('user_metas[lastname]', !empty($lastname)?$lastname->getValue():''), 'class=\'medium\''); ?>
                        </li>
                    
                        <li>
                            <?php echo form_label('Username<span>*</span>', 'username'); ?>
                            <?php echo form_input('username', set_value('username', $user->getUsername()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Password<span>*</span>', 'password'); ?>
                            <?php echo form_input('password', set_value('password'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Email<span>*</span>', 'email'); ?>
                            <?php echo form_input('email', set_value('email', $user->getEmail()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Phone<span>*</span>', 'phone'); ?>
                            <?php echo form_input('phone', set_value('phone', $user->getPhone()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Role', 'role'); ?>
                            <select class="medium-2" name="role">
                                <?php $selected_role = ($this->input->post('role'))?$this->input->post('role'):$user->getRole()->getId(); ?>
                                <?php foreach($roles as $role): ?>
                                <option value="<?php echo $role->getId(); ?>" <?php if($selected_role == $role->getId()){ ?> selected="selected" <?php } ?> ><?php echo $role->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>                
                        </li>
                        
                        <li>
                            <?php echo form_label('Active', 'active'); ?>
                            <?php $selected_active = ($this->input->post('active'))?$this->input->post('active'):$user->getActive(); ?>
                            <select class="medium-2" name="active">
                                <option value="1" <?php if($selected_active == 1){ ?> selected="selected" <?php } ?>>Yes</option>
                                <option value="0" <?php if($selected_active == 0){ ?> selected="selected" <?php } ?>>No</option>
                            </select>
                        </li>
                    </ul>
                </div>
                
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Comment', 'comment'); ?>
                            <?php $comment = $user->getUserMeta('comment'); ?>
                            <?php echo form_textarea('user_metas[comment]', set_value('user_metas[comment]', !empty($comment)?$comment->getValue():''), 'class=\'large-2\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_hidden('id', $user->getId()); ?>
            <?php echo form_hidden('edit', 1); ?>
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('user_create', '', 'class=\'btn-create\''); ?></li>
                    <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>