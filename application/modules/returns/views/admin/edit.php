<section id="content">
    <div id="content-head">
        <h2></h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/returns'); ?>"></a></li>
            <li><a class="action-create active" href="<?php echo base_url('admin/returns/create'); ?>"></a></li>
        </ul>
    </div>

    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <?php echo form_open_multipart('admin/returns/create', array('id' => 'transaction-edit-form')); ?>
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
                            <?php echo form_label('Return Date', ''); ?>
                            <div class="text">
                                <div class="medium"><?php echo date('F d, Y', time()) ?></div>
                            </div>
                        </li>
                        
                        <li>
                            <?php echo form_label('Customer', 'customer'); ?>
                            <select class="medium-2" name="customer">
                                <option></option>
                                <?php foreach($customers as $customer): ?>
                                <option value="<?php echo $customer->getId(); ?>"><?php echo $customer->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                    </ul>
                </div>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Comment', 'comment'); ?>
                            <?php echo form_textarea('comment', set_value('comment', $return->getComment()), 'class=\'large-2\''); ?>
                        </li>
                    </ul>
                </div>
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
                            <a data-url='<?php echo site_url('admin/product/ajax_search/'); ?>' href="#" id="return-ajax-search-btn" class="btn-filter" data-type="return"></a>
                        </li>
                    </ul>
                </div>
                
                <table id="search-products">
                    <thead>
                        <tr>
                            <th class="medium sortable" data-sort="string">Name</th>
                            <th class="small sortable" data-sort="string">Barcode</th>
                            <th class="xsmall sortable" data-sort="string">Category</th>
                            <th class="xxsmall">Qty</th>
                            <th class="xxsmall sortable" data-sort="int">BOH*</th>
                            <th class="xxsmall sortable" data-sort="float">Price</th>
                            <th class="small">Comment</th>
                            <th class="xxsmall"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($return->getItems() as $item) { ?>
                        
                        <tr data-id="<?php echo $item->getProduct()->getId(); ?>"
                            data-cash-and-carry="<?php echo $item->getProduct()->getCNC(); ?>"
                            data-full-service="<?php echo $item->getProduct()->getFullServicePrice(); ?>"
                            data-standard-service="<?php echo $item->getProduct()->getNoServicePrice(); ?>"
                            data-current-price="<?php echo $item->getSalePrice(); ?>">
                            
                            <td><a target="_blank" href="<?php echo site_url('admin/product/edit/' . $item->getProduct()->getId()); ?>"><?php echo $item->getProduct()->getName(); ?></a></td>
                            <td><?php echo $item->getProduct()->getBarcode(); ?></td>
                            <td><?php echo $item->getProduct()->getCategory()->getName(); ?></td>
                            <td>
                                <input type="text"
                                       autocomplete="off"
                                       name="products[<?php echo $item->getProduct()->getId(); ?>][qty]"
                                       value="<?php echo $item->getQty(); ?>"
                                       class="xxxsmall field-qty" />
                            </td>
                            <td><?php echo $item->getProduct()->getTotalQty(); ?><?php // echo $item->getProduct()->getUnit() ?></td>
                            <td class="field-price"><?php echo $item->getSalePrice(); ?></td>
                            
                            <td>
                                <?php if (!$item->getProduct()->getNoDiscount()) { ?>
                                <input type="text" name="products[<?php echo $item->getProduct()->getId(); ?>][discount]" value="<?php echo $item->getDiscount(); ?>" class="xxxsmall field-discount" />
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
            
            
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('return_create', '', 'class=\'btn-create\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>