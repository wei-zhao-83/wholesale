<section id="content">
    <div id="content-head">
        <h2>Product</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/product'); ?>"></a></li>
            <li><a class="action-create active" href="<?php echo base_url('admin/product/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
    
        <?php echo form_open_multipart('admin/product/create/'); ?>
            <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Name', 'name'); ?>
                            <?php echo form_input('name', set_value('name'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Barcode', 'barcode'); ?>
                            <?php echo form_input('barcode', set_value('barcode'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('SKU', 'sku'); ?>
                            <?php echo form_input('sku', set_value('sku'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Section', 'section'); ?>
                            <?php echo form_input('section', set_value('section'), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Active', 'active'); ?>
                            <select class="medium-2" name="active">
                                <option value="1" <?php echo set_select('active', '1', TRUE); ?>>Yes</option>
                                <option value="0" <?php echo set_select('active', '0'); ?>>No</option>
                            </select>
                        </li>
                        
                        <li>
                            <?php echo form_label('Category', 'category'); ?>
                            <select class="medium-2" name="category">
                                <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category->getId(); ?>" <?php echo set_select('category', $category->getId()); ?>><?php echo $category->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        
                        <li>
                            <?php echo form_label('Vendor', 'vendor'); ?>
                            <select class="large-2" name="product_vendor">
                                <option value="" <?php echo set_select('product_vendor', ''); ?>></option>
                                <?php foreach($vendors as $vendor): ?>
                                <option value="<?php echo $vendor->getId(); ?>" <?php echo set_select('product_vendor', $vendor->getId()); ?>><?php echo $vendor->getName(); ?></option>
                                <?php endforeach; ?>
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
                        
                        <li>
                            <?php echo form_label('Comment', 'comment'); ?>
                            <?php echo form_textarea('comment', set_value('comment'), 'class=\'large-2\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Pricing'); ?>
            <div class="full">
                <ul>
                    <li>
                        <?php echo form_label('Cost', 'cost'); ?>
                        <?php echo form_input('cost', set_value('cost'), 'class=\'medium\''); ?>
                    </li>
                    
                    <li>
                        <?php echo form_label('Suggested Retail Price', 'suggested_price'); ?>
                        <?php echo form_input('suggested_price', set_value('suggested_price'), 'class=\'medium\''); ?>
                    </li>
                    
                    <li>
                        <?php echo form_label('No Service Price', 'no_service_price'); ?>
                        <?php echo form_input('no_service_price', set_value('no_service_price'), 'class=\'medium\''); ?>
                    </li>
                    
                    <li>
                        <?php echo form_label('Full Service Price', 'full_service_price'); ?>
                        <?php echo form_input('full_service_price', set_value('full_service_price'), 'class=\'medium\''); ?>
                    </li>
                    
                    <li>
                        <?php echo form_label('CNC', 'cash_and_carry'); ?>
                        <?php echo form_input('cash_and_carry', set_value('cash_and_carry'), 'class=\'medium\''); ?>
                    </li>
                    
                    <li>
                        <?php echo form_label('Discount', 'discount'); ?>
                        <?php echo form_input('discount', set_value('discount'), 'class=\'medium\''); ?>
                    </li>
                </ul>
            </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Stock'); ?>
            <div class="full">
                <ul>
                    <li>
                        <label for="unit">Standard Unit</label>
                        <select class="medium-2" name="unit">
                        <?php foreach($unit_measures as $measure => $name) { ?>
                            <option value="<?php echo $measure; ?>" <?php echo set_select('unit', $measure); ?>><?php echo $name; ?></option>
                        <?php } ?>
                        </select>                      
                    </li>
                    
                    <li>
                        <?php echo form_label('Quantity / Standard Unit', 'qty_unit'); ?>
                        <?php echo form_input('qty_unit', set_value('qty_unit'), 'class=\'medium\''); ?>
                    </li>
                    
                    <li>
                        <?php echo form_label('Standard Unit / Case', 'unit_case'); ?>
                        <?php echo form_input('unit_case', set_value('unit_case'), 'class=\'medium\''); ?>
                    </li>
                    
                    <li>
                        <?php echo form_label('Total Quantity', 'total_qty'); ?>
                        <?php echo form_input('total_qty', set_value('total_qty'), 'class=\'medium\''); ?>
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
                        <td><?php echo form_input('product_images[0][name]', '', 'class=\'small\''); ?></td>
                        <td><?php echo form_input('product_images[0][alt]', '', 'class=\'small\''); ?></td>
                        <td><?php echo form_input('product_images[0][arrange]', '', 'class=\'xxsmall\''); ?></td>
                        <td>
                            <select class="xsmall" name="product_images[0][main]">
                                <option value="1" <?php echo set_select('product_images[0][main]', '1', TRUE); ?>>Yes</option>
                                <option value="0" <?php echo set_select('product_images[0][main]', '0'); ?>>No</option>
                            </select>
                        </td>
                        <td><a href="#" class="btn-remove"></a></td>
                    </tr>
                </table>
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