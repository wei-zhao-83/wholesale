<section id="content">
    <div id="content-head">
        <h2>Message</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/message'); ?>"></a></li>
            <li><a class="action-create active" href="<?php echo base_url('admin/message/create'); ?>"></a></li>
        </ul>
    </div>

    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <?php echo form_open('admin/message/create/'); ?>
            <?php echo form_fieldset(''); ?>
                <ul>
                    <li>
                        <?php echo form_label('Subject', 'subject'); ?>
                        <?php echo form_input('subject', set_value('subject'), 'class=\'xlarge\''); ?>
                    </li>
                    
                    <li>
                        <?php echo form_label('From:', 'from'); ?>
                        <div class="text">
                            <div class="large-2"><?php echo $this->session->userdata('username')?></div>
                        </div>
                    </li>
                    
                    <li>
                        <?php echo form_label('To:', 'to'); ?>
                        <select class="large-2" name="to">
                            <?php foreach($users as $user) { ?>
                            <?php if ($this->session->userdata('username') != $user->getUsername()) { ?>
                                <option value="<?php echo $user->getId(); ?>"><?php echo $user->getUsername(); ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </li>
                    
                    <li>
                        <?php echo form_label('Content', 'content'); ?>
                        <?php echo form_textarea('content', set_value('content'), 'class=\'xlarge\''); ?>
                    </li>
                </ul>
            <?php echo form_fieldset_close(); ?>
            
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('message_create', '', 'class=\'btn-mail\''); ?></li>
                    <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>