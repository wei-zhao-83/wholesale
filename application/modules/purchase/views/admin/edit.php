<section id="content">
    <div id="content-head">
        <h2>Purchase #<?php echo $purchase->getId(); ?></h2>
        
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
                            <?php echo form_label('BOH Updated?', ''); ?>
                            <div class="text">
                                <div class="medium"><?php echo ($boh_updated == 1)? 'Yes' : 'No'; ?></div>
                            </div>
                        </li>
                        
                        <li>
                            <?php echo form_label('YTD', ''); ?>
                            <div class="text">
                                <div class="medium">$<?php echo $ytd; ?></div>
                            </div>
                        </li>
                        
                        <li>
                            <label for="status">Status</label>
                            <select class="medium-2" name="status">
                                <?php $selected_status = ($this->input->post('status'))?$this->input->post('status'):$purchase->getStatus(); ?>
                                <?php foreach($statuses as $status): ?>
                                <option value="<?php echo $status; ?>" <?php if($selected_status == $status){ ?> selected="selected" <?php } ?> ><?php echo get_full_name($status); ?></option>
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
            
            <?php echo form_fieldset('Payment History'); ?>
                <table>
                    <thead>
                        <tr>
                            <th class="small">Date</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Comment</th>
                            <th class="xxsmall"><a href="#" class="btn-add"></a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($payments->count() > 0) { ?>
                            <?php foreach($payments as $payment) { ?>
                                <tr>
                                    <td><?php echo $payment->getCreatedAt(); ?></td>
                                    <td><?php echo get_full_name($payment->getPaymentType()); ?></td>
                                    <td>
                                        <select class="small" name="payments[<?php echo $payment->getID(); ?>][status]">
                                            <?php foreach(transaction\models\TransactionPayment::getStatuses() as $status) { ?>
                                                <option value="<?php echo $status; ?>" <?php if($payment->getStatus() == $status) { ?> selected="selected" <?php } ?>><?php echo ucfirst($status); ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>$<?php echo $payment->getAmount(); ?></td>
                                    <td><?php echo $payment->getComment(); ?></td>
                                    <td></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        <tr id="row-0">
                            <td>-</td>
                            <td>
                                <select class="small" name="payments[0][payment_type]">
                                    <?php foreach($payment_types as $payment => $payment_name) { ?>
                                        <option value="<?php echo $payment; ?>"><?php echo $payment_name; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <select class="small" name="payments[0][status]">
                                    <?php foreach(transaction\models\TransactionPayment::getStatuses() as $status) { ?>
                                        <option value="<?php echo $status; ?>"><?php echo ucfirst($status); ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><?php echo form_input('payments[0][amount]', '', 'class=\'small\''); ?></td>
                            <td><?php echo form_input('payments[0][comment]', '', 'class=\'medium\''); ?></td>
                            <td><a href="#" class="btn-remove"></a></td>
                        </tr>
                    </tbody>
                </table>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Product'); ?>
                <table id="search-products">
                    <thead>
                        <tr>
                            <th class="small sortable" data-sort="string">Name</th>
                            <th class="small sortable" data-sort="string">SKU</th>
                            <th class="xxsmall sortable" data-sort="string">Category</th>
                            <th class="xxsmall sortable" data-sort="int">BOH</th>
                            <!--<th class="xsmall">In Transit</th>-->
                            <th class="xxsmall sortable" data-sort="int">Freq. <?php echo $purchase->getVendor()->getOrderFrequency(); ?></th>
                            <th class="xxsmall">Qty.</th>
                            <th class="xxsmall">Recieved</th>
                            <th class="xxsmall">Cost</th>
                            <th class="xsmall">Sub Total</th>
                            <!--<th class="small">Comment</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($products) > 0) { ?>
                            <?php foreach($products as $product) { ?>
                                <tr data-qty="<?php echo isset($purchased_items[$product->getID()]) ? $purchased_items[$product->getID()]->getQty() : 0; ?>">
                                    <td>
                                        <a href="<?php echo base_url('/admin/product/edit/' . $product->getID()); ?>">
                                            <?php echo $product->getName(); ?>
                                        </a>
                                        <br><?php echo $product->getBarcode(); ?>
                                    </td>
                                    <td><?php echo $product->getSKU(); ?></td>
                                    <td><?php echo $product->getCategory()->getName(); ?></td>
                                    <td><?php echo $product->getTotalQty(); ?></td>
                                    <td><?php echo isset($frequency[$product->getID()]) ? $frequency[$product->getID()] : 0; ?></td>
                                    <td><input type="text"
                                               autocomplete="off"
                                               class="xxxsmall item-update-field field-qty"
                                               name="products[<?php echo $product->getId(); ?>][qty]"
                                               value="<?php echo isset($purchased_items[$product->getID()]) ? $purchased_items[$product->getID()]->getQty() : 0; ?>" /></td>
                                    <td>
                                        <?php if($boh_updated == 0) { ?>
                                            <input type="text"
                                                   autocomplete="off"
                                                   class="xxxsmall recieved-field"
                                                   name="products[<?php echo $product->getId(); ?>][received]"
                                                   value="<?php echo isset($purchased_items[$product->getID()]) ? $purchased_items[$product->getID()]->getReceived() : 0; ?>" />
                                        <?php } else { ?>
                                            <?php echo $purchased_items[$product->getID()]->getReceived(); ?>
                                        <?php } ?>
                                    </td>
                                    <td>$<?php echo $product->getCost(); ?></td>
                                    <td><?php echo isset($purchased_items[$product->getID()]) ? '$' . $purchased_items[$product->getID()]->getRowTotal() : '-'; ?></td>
                                    <!--<td><input type="text"
                                               class="small"
                                               name="products[<?php echo $product->getId(); ?>][comment]"
                                               value="<?php echo isset($purchased_items[$product->getID()]) ? $purchased_items[$product->getID()]->getComment() : ''; ?>" /></td>-->
                                </tr>
                            <?php } ?>
                            </tbody>
                    <?php } ?>
                </table>
                <table id="tbl-summary">
                    <tbody>
                        <?php if(count($products) > 0) { ?>
                            <tr>
                                <td colspan="7" class="text-rgt"><strong>Sub Total</strong></td>
                                <td class="small">$<?php echo $summary['sub_total'] ?></td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-rgt"><strong>Tax</strong></td>
                                <td>$<?php echo $summary['tax'] ?></td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-rgt"><strong>Total</strong></td>
                                <td>$<?php echo $summary['total'] ?></td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-rgt"><strong>Total Due</strong></td>
                                <td class="text-highlight">$<?php echo $summary['total_due'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8" class="text-lft">
                                <?php if($boh_updated == 0) { ?>
                                    <label>
                                        <input type="checkbox" name="update_boh" value="1" class="checkbox update-boh">
                                        Update BOH
                                    </label>
                                <?php } else { ?>
                                    <label>
                                        <input type="checkbox" name="update_boh" value="0" class="checkbox update-boh">
                                        <strong>UNDO</strong> BOH Update
                                    </label>
                                <?php } ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_hidden('id', $purchase->getId()); ?>
            <?php echo form_hidden('edit', 1); ?>
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('purchase_create', '', 'class=\'btn-create\''); ?></li>
                    <li><a class="button" href="<?php echo base_url('/admin/purchase/credit/' . $purchase->getId()); ?>">Credit Claim</a></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>