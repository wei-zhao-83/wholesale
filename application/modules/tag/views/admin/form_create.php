<section id="content">
    <div id="content-head">
        <h2>Tag</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/tag'); ?>"></a></li>
            <li><a class="action-create active" href="<?php echo base_url('admin/tag/create'); ?>"></a></li>
        </ul>
    </div>

    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>

        <?php echo form_open('admin/tag/create/'); ?>
            <?php echo form_fieldset('General'); ?>
            <div class="half">
                <ul>
                    <li>
                        <?php echo form_label('Name', 'name'); ?>
                        <?php echo form_input('name', set_value('name')); ?>
                    </li>
                </ul>
            </div>
            <?php echo form_fieldset_close(); ?>
            
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('tag_create', '', 'class=\'btn-create\''); ?></li>
                    <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
                </ul>
            </div>
            
        <?php echo form_close(); ?>
    </div>
</section>