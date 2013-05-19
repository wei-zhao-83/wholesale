<section id="content">
    <div id="content-head">
        <h2>Product</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/product'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/product/create'); ?>"></a></li>
            <li><a class="action-edit active" href=""></a></li>
        </ul>
    </div>

    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
    
        <?php echo form_open_multipart('admin/product/edit/' . $product->getId()); ?>
            <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Name', 'name'); ?>
                            <?php echo form_input('name', set_value('name', $product->getName()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Barcode', 'barcode'); ?>
                            <?php echo form_input('barcode', set_value('barcode', $product->getBarcode()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('SKU', 'sku'); ?>
                            <?php echo form_input('sku', set_value('sku', $product->getSKU()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Section', 'section'); ?>
                            <?php echo form_input('section', set_value('section', $product->getSection()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Active', 'active'); ?>
                            <?php $selected_active = ($this->input->post('active'))?$this->input->post('active'):$product->getActive(); ?>
                            <select class="medium-2" name="active">
                                <option value="1" <?php if($selected_active == 1){ ?> selected="selected" <?php } ?>>Yes</option>
                                <option value="0" <?php if($selected_active == 0){ ?> selected="selected" <?php } ?>>No</option>
                            </select>
                        </li>
                        
                        <li>
                            <?php echo form_label('No Discount', 'no_discount'); ?>
                            <?php $selected_discount = ($this->input->post('no_discount'))?$this->input->post('no_discount'):$product->getNoDiscount(); ?>
                            <select class="medium-2" name="no_discount">
                                <option value="1" <?php if($selected_discount == 1){ ?> selected="selected" <?php } ?>>Yes</option>
                                <option value="0" <?php if($selected_discount == 0){ ?> selected="selected" <?php } ?>>No</option>
                            </select>
                        </li>
                        
                        <li>
                            <?php echo form_label('Category', 'category'); ?>
                            <?php $selected_category = ($this->input->post('category'))?$this->input->post('category'):$product->getCategory()->getId(); ?>
                            <select class="medium-2" name="category">
                                <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category->getId(); ?>" <?php if ($category->getId() == $selected_category){ ?>selected<?php } ?> ><?php echo $category->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        
                        <li>
                            <?php echo form_label('Vendor', 'vendor'); ?>
                            <select class="large-2" name="product_vendor">
                                <option value="" <?php echo set_select('product_vendor', ''); ?>></option>
                                <?php foreach($vendors as $vendor): ?>
                                <option value="<?php echo $vendor->getId(); ?>" <?php echo (!empty($selected_vendor) && $selected_vendor->getId() == $vendor->getId())? 'selected' : ''; ?> ><?php echo $vendor->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        
                        <li>
                            <label for="tag">Tags <span>[<a class="add-tag" data-fancybox-type="iframe" href="<?php echo site_url('admin/tag/create/');?>/ajax">Add New</a>]</span></label>
                            <input name="tags" class="large" id="tags" data-url="<?php echo site_url('admin/tag/ajax_search/'); ?>" data-prefill="<?php echo $current_tags; ?>" >
                        </li>
                    </ul>
                </div>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Description', 'description'); ?>
                            <?php echo form_textarea('description', set_value('description', $product->getDescription()), 'class=\'large-2\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Comment', 'comment'); ?>
                            <?php echo form_textarea('comment', set_value('comment', $product->getComment()), 'class=\'large-2\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Pricing'); ?>
            <div class="full">
                    <ul>
                        <li>
                            <?php echo form_label('Cost', 'cost'); ?>
                            <?php echo form_input('cost', set_value('cost', $product->getCost()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Suggested Retail Price', 'suggested_price'); ?>
                            <?php echo form_input('suggested_price', set_value('suggested_price', $product->getSuggestedPrice()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('No Service Price', 'no_service_price'); ?>
                            <?php echo form_input('no_service_price', set_value('no_service_price', $product->getNoServicePrice()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Full Service Price', 'full_service_price'); ?>
                            <?php echo form_input('full_service_price', set_value('full_service_price', $product->getFullServicePrice()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('CNC', 'CNC'); ?>
                            <?php echo form_input('CNC', set_value('CNC', $product->getCNC()), 'class=\'medium\''); ?>
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
                                <option value="<?php echo $measure; ?>" <?php if($measure == $product->getUnit()) { ?> selected="selected" <?php } ?> ><?php echo $name; ?></option>
                            <?php } ?>
                            </select>                      
                        </li>
                        
                        <li>
                            <?php echo form_label('Quantity / Standard Unit', 'qty_unit'); ?>
                            <?php echo form_input('qty_unit', set_value('qty_unit', $product->getQtyUnit()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Standard Unit / Case', 'unit_case'); ?>
                            <?php echo form_input('unit_case', set_value('unit_case', $product->getUnitCase()), 'class=\'medium\''); ?>
                        </li>
                        
                        <li>
                            <?php echo form_label('Total Quantity', 'total_qty'); ?>
                            <?php echo form_input('total_qty', set_value('total_qty', $product->getTotalQty()), 'class=\'medium\''); ?>
                        </li>
                    </ul>
            </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('History'); ?>
            <?php if ($histories->count()) { ?>
                <?php foreach($histories as $history) { ?>
                    <div class="history-element">
                        <?php $changes = $history->getChanges(); ?>
                        <div class="history-time"><?php echo date('Y-m-d H:i:s', $history->getTimeStamp()); ?></div>
                        <div class="history-user"><?php echo $changes['user']['username']; ?></div>
                        <div class="history-view"><a class="view-history btn-view" data-fancybox-type="iframe" href="<?php echo site_url('admin/product/view_history/');?>/<?php echo $product->getId(); ?>/<?php echo $history->getTimeStamp(); ?>"></a></div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="text-center">No Records</p>
            <?php } ?>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_hidden('id', $product->getId()); ?>
            <?php echo form_hidden('edit', 1); ?>
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('product_create', '', 'class=\'btn-create\''); ?></li>
                    <li><?php echo form_reset('reset', '', 'class=\'btn-reset\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>