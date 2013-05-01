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
                            <?php echo form_input('tags', '', 'class=\'large\' id=\'tags\''); ?>
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
                            <?php echo form_label('Page Title', 'seo_title'); ?>
                            <?php echo form_input('seo_title', set_value('seo_title'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Canonical Link', 'seo_canonical_link'); ?>
                            <?php echo form_input('seo_canonical_link', set_value('seo_canonical_link'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('URL', 'seo_url'); ?>
                            <?php echo form_input('seo_url', set_value('seo_url'), 'class=\'large\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Robots', 'seo_robots'); ?>
                            <select class="medium-2" name="seo_robots">
                                <option value="1" <?php echo set_select('seo_robots', '1', TRUE); ?>>Yes</option>
                                <option value="0" <?php echo set_select('seo_robots', '0'); ?>>No</option>
                            </select>
                        </li>
                    </ul>
                </div>
                <div class="half">
                    <ul>        
                        <li>
                            <?php echo form_label('Keywords', 'seo_keywords'); ?>
                            <?php echo form_textarea('seo_keywords', set_value('seo_keywords'), 'class=\'large-2\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Images'); ?>
                <table id="image-input">
                    <tr>
                        <th class="medium">File</th>
                        <th class="small">Name</th>
                        <th class="small">Alt</th>
                        <th class="xxsmall">Order</th>
                        <th class="xsmall">Main</th>
                        <th class="xxsmall"><a href="#" class="btn-add"></a></th>
                    </tr>
                    <tr id="row-0">
                        <td><?php echo form_upload('image_file_0', '', 'class=\'medium\''); ?></td>
                        <td><?php echo form_input('category_images[0][name]', '', 'class=\'small\''); ?></td>
                        <td><?php echo form_input('category_images[0][alt]', '', 'class=\'small\''); ?></td>
                        <td><?php echo form_input('category_images[0][arrange]', '', 'class=\'xxsmall\''); ?></td>
                        <td>
                            <select class="xsmall" name="category_images[0][main]">
                                <option value="1" <?php echo set_select('category_images[0][main]', '1', TRUE); ?>>Yes</option>
                                <option value="0" <?php echo set_select('category_images[0][main]', '0'); ?>>No</option>
                            </select>
                        </td>
                        <td><a href="#" class="btn-remove"></a></td>
                    </tr>
                </table>
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

<script>
    $(document).ready(function() {
        $("#tags").autoSuggest("<?php echo site_url('admin/tag/ajax_search');?>", {
            minChars: 2,
            neverSubmit: "true",
            startText: "Tags",
            asHtmlID: "tags",
            preFill: "<?php echo $post_tags; ?>"
        });
        
        $(".add-tag").fancybox({
           maxWidth: 530,
           minWidth: 530,
           maxHeight: 390,
           minHeight: 390
        });
    });
</script>