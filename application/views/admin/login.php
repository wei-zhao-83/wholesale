<section id="content">
    <div id="content-head">
        <h2></h2>
    </div>
    
        <div id="login">
            
            <?php $this->load->view('admin/message'); ?>
            
            <?php echo form_open('admin/login/'); ?>
            <?php echo form_fieldset(''); ?>
                <ul>
                    <li>
                        <?php echo form_label('Username or Email', 'identity'); ?>
                        <?php echo form_input('identity', set_value('identity'), 'class=\'large\''); ?>
                    </li>
                    
                    <li>
                        <?php echo form_label('Password', 'password'); ?>
                        <?php echo form_password('password', set_value('password'), 'class=\'large\''); ?>
                    </li>
                </ul>
            <?php echo form_fieldset_close(); ?>
            
                <div class="btn-box">
                    <ul>
                        <li><?php echo form_submit('login', 'Login', 'class=\'btn-login\''); ?></li>
                    </ul>
                </div>
            <?php echo form_close(); ?>
        </div>
</section>