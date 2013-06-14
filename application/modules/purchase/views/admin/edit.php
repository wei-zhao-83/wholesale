<section id="content">
    <div id="content-head">
        <h2>Purchase</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/purchase'); ?>"></a></li>
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
                            <?php echo form_label('Vendor', ''); ?>
                            <div class="text">
                                <div class="medium"><?php echo $purchase->getVendor()->getName(); ?></div>
                            </div>
                        </li>
                        
                        <li>
                            <?php echo form_label('Purchases', ''); ?>
                            <div class="text">
                                <div class="medium"><?php echo $this->session->userdata('username'); ?></div>
                            </div>
                        </li>
                        
                        <li>
                            <?php echo form_label('Order Date', ''); ?>
                            <div class="text">
                                <div class="medium"><?php echo $purchase->getCreatedAt(); ?></div>
                            </div>
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
                            <th class="xsmall">BOH</th>
                            <th class="xsmall">In Transit</th>
                            <th class="xsmall">Freq. (<?php echo $purchase->getVendor()->getOrderFrequency(); ?>)</th>
                            <th class="xsmall">Qty.</th>                            
                            <th class="xsmall">Cost</th>
                            <th class="small">Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($products) > 0) { ?>
                            <?php foreach($products as $product) { ?>
                                <tr>
                                    <td><a href="<?php echo base_url('/admin/product/edit/' . $product->getID()); ?>"><?php echo $product->getName(); ?></a></td>
                                    <td><?php echo $product->getBarcode(); ?></td>
                                    <td><?php echo $product->getCategory()->getName(); ?></td>
                                    <td><?php echo $product->getTotalQty(); ?></td>
                                    <td><?php echo $product->getPickedQty(); ?></td>
                                    <td><?php echo isset($frequency[$product->getID()]) ? $frequency[$product->getID()] : 0; ?></td>
                                    <td><input type="text"
                                               class="xxsmall item-update-field"
                                               name="products[<?php echo $product->getId(); ?>][qty]"
                                               value="<?php echo isset($purchased_items[$product->getID()]) ? $purchased_items[$product->getID()]->getQty() : 0; ?>" /></td>
                                    <td><?php echo $product->getCost(); ?></td>
                                    <td><input type="text"
                                               class="small"
                                               name="products[<?php echo $product->getId(); ?>][comment]"
                                               value="<?php echo isset($purchased_items[$product->getID()]) ? $purchased_items[$product->getID()]->getComment() : ''; ?>" /></td>
                                </tr>
                            <?php } ?>
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