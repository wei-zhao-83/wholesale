<section id="content">
    <div id="content-head">
        <h2>Purchase</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/purchase'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/purchase/create'); ?>"></a></li>
            <li><a class="action-edit active" href=""></a></li>
        </ul>
    </div>

    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <?php echo form_open_multipart('admin/purchase/edit/' . $purchase->getId(), array('id' => 'transaction-edit-form')); ?>
            <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Purchases', ''); ?>
                            <div class="text">
                                <div class="medium"><?php echo $this->session->userdata('username'); ?></div>
                            </div>
                        </li>
                        
                        <li>
                            <?php echo form_label('Order Date', ''); ?>
                            <div class="text">
                                <div class="medium"><?php echo date('F d, Y', time()) ?></div>
                            </div>
                        </li>
                        
                        <li>
                            <?php echo form_label('Vendor', 'vendor'); ?>
                            <select class="medium-2" id="product-search-trigger" data-url="<?php echo site_url('admin/product/ajax_search/'); ?>" data-type="purchase" name="vendor">
                                <option></option>
                                <?php foreach($vendors as $vendor): ?>
                                <option value="<?php echo $vendor->getId(); ?>" <?php if(!empty($selected_vendor) && $selected_vendor->getId() == $vendor->getId()){ ?> selected="selected" <?php } ?> ><?php echo $vendor->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        
                        <li>
                            <label for="status">Status</label>
                            <select class="medium-2" name="status">
                                <?php $selected_status = ($this->input->post('status'))?$this->input->post('status'):$purchase->getStatus()->getId(); ?>
                                <?php foreach($statuses as $status): ?>
                                <option value="<?php echo $status->getId(); ?>" <?php if($selected_status == $status->getId()){ ?> selected="selected" <?php } ?> ><?php echo $status->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                    </ul>
                </div>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Comment', 'comment'); ?>
                            <?php echo form_textarea('comment', set_value('comment', $purchase->getComment()), 'class=\'large-2\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            <?php echo form_fieldset('Product'); ?>
                <table id="search-products">
                    <thead>
                        <tr>
                            <th class="medium">Name</th>
                            <th class="small">Barcode</th>
                            <th class="xsmall">Category</th>
                            <th class="xsmall">Qty</th>
                            <th class="xsmall">Stock</th>
                            <th class="xsmall">Cost</th>
                            <th class="small">Comment</th>
                            <th class="xxsmall"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($purchase->getItems() as $item) { ?>
                        <tr data-id="<?php echo $item->getProduct()->getId(); ?>">
                            <td><?php echo $item->getProduct()->getName(); ?></td>
                            <td><?php echo $item->getProduct()->getBarcode(); ?></td>
                            <td><?php echo $item->getProduct()->getCategory()->getName(); ?></td>
                            <td><input type="text" name="products[<?php echo $item->getProduct()->getId(); ?>][qty]" value="<?php echo $item->getQty(); ?>" class="xxsmall item-update-field" /></td>
                            <td><?php echo $item->getProduct()->getTotalQty(); ?>[<?php echo $item->getProduct()->getUnit() ?>]</td>
                            <td><?php echo $item->getProduct()->getCost(); ?></td>
                            <td><input type="text" name="products[<?php echo $item->getProduct()->getId(); ?>][comment]" value="<?php echo $item->getComment(); ?>" class="small" /></td>
                            <td><a href="#" class="btn-remove show-inline"></a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php echo form_fieldset_close(); ?>
            
            
            
            <?php echo form_hidden('id', $purchase->getId()); ?>
            <?php echo form_hidden('edit', 1); ?>
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('purchase_create', '', 'class=\'btn-create\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>