<section id="content">
    <div id="content-head">
        <h2>Sale #<?php echo $sale->getId(); ?></h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/sale'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/sale/create'); ?>"></a></li>
            <li><a class="action-edit active" href=""></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <?php echo form_open_multipart('admin/sale/edit/' . $sale->getId(), array('id' => 'transaction-edit-form')); ?>
            <?php echo form_fieldset('General'); ?>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Sales', ''); ?>
                            <div class="text">
                                <div class="medium"><?php echo $this->session->userdata('username'); ?></div>
                            </div>
                        </li>
                        
                        <li>
                            <?php echo form_label('Order Date', ''); ?>
                            <div class="text">
                                <div class="medium"><?php echo $sale->getCreatedAt(); ?></div>
                            </div>
                        </li>
                        
                        <li>
                            <?php echo form_label('Customer', 'customer'); ?>
                            <select class="medium-2" name="customer">
                                <option>Select a Customer</option>
                                <?php foreach($customers as $customer): ?>
                                <option value="<?php echo $customer->getId(); ?>" <?php if($selected_customer == $customer->getId()){ ?> selected="selected" <?php } ?> ><?php echo $customer->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        
                        <li>
                            <label for="status">Status</label>
                            <select class="medium-2" name="status">
                                <?php $selected_status = ($this->input->post('status'))?$this->input->post('status'):$sale->getStatus(); ?>
                                <?php foreach($statuses as $status): ?>
                                <option value="<?php echo $status; ?>" <?php if($selected_status == $status){ ?> selected="selected" <?php } ?> ><?php echo get_full_name($status); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        
                        <!--<li>
                            <label for="payment">Payment</label>
                            
                            <select class="medium-2" name="payment">
                                <?php // foreach($payment_types as $payment => $payment_name) { ?>
                                    <option value="<?php //echo $payment; ?>" <?php // if($payment == $sale->getPayment()){ ?> selected="selected" <?php // } ?> ><?php // echo $payment_name; ?></option>
                                <?php // } ?>
                            </select>
                        </li>-->
                        
                        <li>
                            <label for="type">Type</label>
                            
                            <select class="medium-2" name="type" id="sale-type">
                                <?php foreach($types as $type => $type_name) { ?>
                                    <option value="<?php echo $type; ?>" <?php if($type == $sale->getType()){ ?> selected="selected" <?php } ?> ><?php echo $type_name; ?></option>
                                <?php } ?>
                            </select>
                        </li>
                        
                        <li>
                            <label for="default_discount">Discount</label>
                            
                            <select class="medium-2" name="default_discount" id="default-discount">
                                
                                <?php $_discount = ($this->input->post('default_discount')) ? $this->input->post('default_discount') : $sale->getDefaultDiscount(); ?>
                                
                                <option value="0" <?php if ($_discount == '0.00') { ?> selected="selected" <?php } ?> >None</option>
                                <option value="0.01" <?php if ($_discount == '0.01') { ?> selected="selected" <?php } ?> >1%</option>
                                <option value="0.02" <?php if($_discount == '0.02'){ ?> selected="selected" <?php } ?> >2%</option>
                                <option value="0.05" <?php if($_discount == '0.05'){ ?> selected="selected" <?php } ?> >5%</option>
                            </select>
                        </li>
                        
                        <li>
                            <label for="ship_date">Ship Date</label>
                            <input name="ship_date" value="<?php echo $sale->getShipDate() ?>" class="medium">
                        </li>
                    </ul>
                </div>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Comment', 'comment'); ?>
                            <?php echo form_textarea('comment', set_value('comment', $sale->getComment()), 'class=\'large-2\''); ?>
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
                                            <?php foreach(sale\models\SalePayment::getStatuses() as $status) { ?>
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
                                    <?php foreach(sale\models\SalePayment::getStatuses() as $status) { ?>
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
            
            <?php echo form_fieldset('Products'); ?>
                <div class="full" id="product-ajax-search">
                    <ul>
                        <li>
                            <?php echo form_input('search[name]', set_value('search[name]'), 'class=\'small\' id=\'search-name\' placeholder=\'Name\''); ?>
                        </li>
                        <li>
                            <?php echo form_input('search[barcode]', set_value('search[barcode]'), 'class=\'medium\' id=\'search-barcode\' placeholder=\'Barcode\''); ?>
                        </li>
                        <li>
                            <?php echo form_input('search[section]', set_value('search[section]'), 'class=\'xxsmall\' id=\'search-section\'  placeholder=\'Section\''); ?>
                        </li>
                        <li>
                            <select name="search[category]" id="search-category">
                                <option value="">Select a Category</option>
                                <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category->getId(); ?>" <?php echo set_select('search[category]'); ?>><?php echo $category->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        <li>
                            <select name="search[vendor]" id="search-vendor">
                                <option value="">Select a Vendor</option>
                                <?php foreach($vendors as $vendor): ?>
                                <option value="<?php echo $vendor->getId(); ?>" <?php echo set_select('search[vendor]'); ?>><?php echo $vendor->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        <li>
                            <a data-url='<?php echo site_url('admin/product/ajax_search/'); ?>' href="#" id="product-ajax-search-btn" class="btn-filter" data-type="sale"></a>
                        </li>
                    </ul>
                </div>
                
                <table id="search-products">
                    <thead>
                        <tr>
                            <th class="medium">Name</th>
                            <th class="small">Barcode</th>
                            <th class="xsmall">Category</th>
                            <th class="xsmall">Qty</th>
                            <th class="xsmall">BOH*</th>
                            <th class="xsmall">Price</th>
                            <th class="xsmall">Discount</th>
                            <th class="small">Comment</th>
                            <th class="xxsmall"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sale->getItems() as $item) { ?>
                        
                        <tr data-id="<?php echo $item->getProduct()->getId(); ?>"
                            data-cash-and-carry="<?php echo $item->getProduct()->getCNC(); ?>"
                            data-full-service="<?php echo $item->getProduct()->getFullServicePrice(); ?>"
                            data-standard-service="<?php echo $item->getProduct()->getNoServicePrice(); ?>"
                            data-current-price="<?php echo $item->getSalePrice(); ?>">
                            
                            <td><a target="_blank" href="<?php echo site_url('admin/product/edit/' . $item->getProduct()->getId()); ?>"><?php echo $item->getProduct()->getName(); ?></a></td>
                            <td><?php echo $item->getProduct()->getBarcode(); ?></td>
                            <td><?php echo $item->getProduct()->getCategory()->getName(); ?></td>
                            <td><input type="text" name="products[<?php echo $item->getProduct()->getId(); ?>][qty]" value="<?php echo $item->getQty(); ?>" class="xxsmall field-qty" /></td>
                            <td><?php echo $item->getProduct()->getTotalQty(); ?>[<?php echo $item->getProduct()->getUnit() ?>]</td>
                            <td class="field-price"><?php echo $item->getSalePrice(); ?></td>
                            
                            <td>
                                <?php if (!$item->getProduct()->getNoDiscount()) { ?>
                                <input type="text" name="products[<?php echo $item->getProduct()->getId(); ?>][discount]" value="<?php echo $item->getDiscount(); ?>" class="xxsmall field-discount" />
                                <?php } else { ?>
                                -
                                <?php } ?>
                            </td>
                            <td><input type="text" name="products[<?php echo $item->getProduct()->getId(); ?>][comment]" value="<?php echo $item->getComment(); ?>" class="small" /></td>
                            <td><a href="#" class="btn-remove show-inline"></a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php echo form_fieldset_close(); ?>
            
            <p class="note">* Banlance on Hand</p>
            
            <?php echo form_hidden('id', $sale->getId()); ?>
            <?php echo form_hidden('edit', 1); ?>
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('sale_create', '', 'class=\'btn-create\''); ?></li>
                    <li><a href="<?php echo site_url('admin/sale/picklist/' . $sale->getId()); ?>" class="button">Picklist</a></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>