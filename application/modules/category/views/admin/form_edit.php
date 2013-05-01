<section id="content">
    <div id="content-head">
        <h2>Category</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/category'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/category/create'); ?>"></a></li>
            <li><a class="action-edit active" href=""></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
    
        <?php echo form_open_multipart('admin/category/edit/' . $category->getId()); ?>
            <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Name', 'name'); ?>
                            <?php echo form_input('name', set_value('name', $category->getName()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Slug', 'slug'); ?>
                            <?php echo form_input('slug', set_value('slug', $category->getSlug()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Arrange', 'arrange'); ?>
                            <?php echo form_input('arrange', set_value('arrange', $category->getArrange()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Active', 'active'); ?>
                            <?php $selected_active = ($this->input->post('active'))?$this->input->post('active'):$category->getActive(); ?>
                            <select class="medium-2" name="active">
                                <option value="1" <?php if($selected_active == 1){ ?> selected="selected" <?php } ?>>Yes</option>
                                <option value="0" <?php if($selected_active == 0){ ?> selected="selected" <?php } ?>>No</option>
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
                            <?php echo form_textarea('description', set_value('description', $category->getDescription()), 'class=\'large-2\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('SEO'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Page Title', 'seo_title'); ?>
                            <?php echo form_input('seo_title', set_value('seo_title', $category->getSEOTitle()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Canonical Link', 'seo_canonical_link'); ?>
                            <?php echo form_input('seo_canonical_link', set_value('seo_canonical_link', $category->getSEOCanonicalLink()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('URL', 'seo_url'); ?>
                            <?php echo form_input('seo_url', set_value('seo_url', $category->getSEOURL()), 'class=\'large\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Robots', 'seo_robots'); ?>
                            <?php $selected_robots = ($this->input->post('seo_robots'))?$this->input->post('seo_robots'):$category->getSEORobots(); ?>
                            <select class="medium-2" name="seo_robots">
                                <option value="1" <?php if($selected_robots == 1){ ?> selected="selected" <?php } ?>>Yes</option>
                                <option value="0" <?php if($selected_robots == 0){ ?> selected="selected" <?php } ?>>No</option>
                            </select>
                        </li>
                    </ul>
                </div>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Keywords', 'seo_keywords'); ?>
                            <?php echo form_textarea('seo_keywords', set_value('seo_keywords', $category->getSEOKeywords()), 'class=\'large-2\''); ?>
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
                    
                    <?php foreach($category->getImages() as $image) { ?>
                    <tr>
                        <td><img width="35" height="35" src="<?php echo site_url($image->getPath()); ?>">
                            <?php echo form_hidden('current_category_images['.$image->getId().'][path]', $image->getPath(), 'class=\'medium\''); ?>
                        </td>
                        <td><?php echo form_input('current_category_images['.$image->getId().'][name]', $image->getName(), 'class=\'small\''); ?></td>
                        <td><?php echo form_input('current_category_images['.$image->getId().'][alt]', $image->getAlt(), 'class=\'small\''); ?></td>
                        <td><?php echo form_input('current_category_images['.$image->getId().'][arrange]', $image->getArrange(), 'class=\'xxsmall\''); ?></td>
                        <td>
                            <select class="xsmall" name="current_category_images[<?php echo $image->getId(); ?>][main]">
                                <option value="1" <?php if($image->getMain() == 1){ ?> selected="selected" <?php } ?>>Yes</option>
                                <option value="0" <?php if($image->getMain() == 0){ ?> selected="selected" <?php } ?>>No</option>
                            </select>
                        </td>
                        <td><a href="#" class="btn-remove-current"></a></td>
                    </tr>
                    <?php } ?>
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
            
            
            <?php echo form_hidden('id', $category->getId()); ?>
            <?php echo form_hidden('edit', 1); ?>
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
            preFill: "<?php echo $current_tags; ?>"
        });
        
        $(".add-tag").fancybox({
           maxWidth: 530,
           minWidth: 530,
           maxHeight: 390,
           minHeight: 390
        });
    });
</script>