<section id="content">
    <div id="content-head">
        <h2>Category</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/category'); ?>"></a></li>
            <li><a class="action-create active" href="<?php echo base_url('admin/category/create'); ?>"></a></li>
        </ul>
    </div>

    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
    
        <?php echo form_open_multipart('admin/category/create/'); ?>
            <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Name', 'name'); ?>
                            <?php echo form_input('name', set_value('name'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Slug', 'slug'); ?>
                            <?php echo form_input('slug', set_value('name'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Arrange', 'arrange'); ?>
                            <?php echo form_input('arrange', set_value('arrange'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Active', 'active'); ?>
                            <select class="medium-2" name="active">
                                <option value="1" <?php echo set_select('active', '1', TRUE); ?>>Yes</option>
                                <option value="0" <?php echo set_select('active', '0'); ?>>No</option>
                            </select>
                        </li>
                        
                        <li>
                            <label for="tag">Tags <span>[<a class="add-tag" data-fancybox-type="iframe" href="<?php echo site_url('admin/tag/create/');?>/ajax">Add New</a>]</span></label>
                           <input name="tags" class="large" id="tags" data-url="<?php echo site_url('admin/tag/ajax_search/'); ?>" data-prefill="<?php echo $this->input->post('as_values_tags'); ?>" >
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
            
            <?php echo form_fieldset('SEO'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Page Title', 'SEO_title'); ?>
                            <?php echo form_input('SEO_title', set_value('SEO_title'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Canonical Link', 'SEO_canonical_link'); ?>
                            <?php echo form_input('SEO_canonical_link', set_value('SEO_canonical_link'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('URL', 'SEO_URL'); ?>
                            <?php echo form_input('SEO_URL', set_value('SEO_URL'), 'class=\'large\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Robots', 'SEO_robots'); ?>
                            <select class="medium-2" name="SEO_robots">
                                <option value="1" <?php echo set_select('SEO_robots', '1', TRUE); ?>>Yes</option>
                                <option value="0" <?php echo set_select('SEO_robots', '0'); ?>>No</option>
                            </select>
                        </li>
                    </ul>
                </div>
                <div class="half">
                    <ul>        
                        <li>
                            <?php echo form_label('Keywords', 'SEO_keywords'); ?>
                            <?php echo form_textarea('SEO_keywords', set_value('SEO_keywords'), 'class=\'large-2\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('category_create', '', 'class=\'btn-create\''); ?></li>
                    <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>