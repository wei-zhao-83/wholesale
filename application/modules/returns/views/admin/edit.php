<section id="content">
    <div id="content-head">
        <h2>Return</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/returns'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/returns/create'); ?>"></a></li>
            <li><a class="action-edit active" href=""></a></li>
        </ul>
    </div>

    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <?php echo form_open_multipart('admin/returns/edit/' . $return->getId(), array('id' => 'transaction-edit-form')); ?>
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
                                <option value="<?php echo $customer->getId(); ?>" <?php if($selected_customer == $customer->getId()){ ?> selected="selected" <?php } ?> ><?php echo $customer->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        
                        <li>
                            <label for="status">Status</label>
                            <select class="medium-2" name="status">
                                <?php $selected_status = ($this->input->post('status'))?$this->input->post('status'):$return->getStatus()->getId(); ?>
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
                            <?php echo form_textarea('comment', set_value('comment', $return->getComment()), 'class=\'large-2\''); ?>
                        </li>
                    </ul>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset('Product'); ?>
                <div class="full" id="product-ajax-search">
                    <ul>
                        <li>
                            <?php echo form_input('search[name]', set_value('search[name]'), 'class=\'medium\' id=\'search-name\' placeholder=\'Name\''); ?>
                        </li>
                        <li>
                            <?php echo form_input('search[barcode]', set_value('search[barcode]'), 'class=\'medium\' id=\'search-barcode\' placeholder=\'Barcode\''); ?>
                        </li>
                        <li>
                            <?php echo form_input('search[section]', set_value('search[section]'), 'class=\'xxsmall\' id=\'search-section\'  placeholder=\'Section\''); ?>
                        </li>
                        <li>
                            <select name="search[category]" id="search-category">
                                <option value="">Category</option>
                                <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category->getId(); ?>" <?php echo set_select('search[category]'); ?>><?php echo $category->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        <li>
                            <select name="search[vendor]" id="search-vendor">
                                <option value="">Vendor</option>
                                <?php foreach($vendors as $vendor): ?>
                                <option value="<?php echo $vendor->getId(); ?>" <?php echo set_select('search[vendor]'); ?>><?php echo $vendor->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        <li>
                            <a href="<?php echo site_url('admin/product/ajax_search/'); ?>" id="product-ajax-search-btn" class="btn-filter"></a>
                        </li>
                    </ul>
                </div>
                
                <table id="search-products">
                    <thead>
                        <tr>
                            <th class="medium">Name</th>
                            <th class="medium">Barcode</th>
                            <th class="xsmall">Category</th>
                            <th class="xsmall">Qty</th>
                            <th class="xsmall">Stock</th>
                            <th class="xsmall">Return</th>
                            <th class="small">Comment</th>
                            <th class="xxsmall"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($return->getItems() as $item) { ?>
                        <tr>
                            <td><?php echo $item->getProduct()->getName(); ?></td>
                            <td><?php echo $item->getProduct()->getBarcode(); ?></td>
                            <td><?php echo $item->getProduct()->getCategory()->getName(); ?></td>
                            <td><input type="text" name="products[<?php echo $item->getProduct()->getId(); ?>][qty]" value="<?php echo $item->getQty(); ?>" class="xxsmall item-update-field" /></td>
                            <td><?php echo $item->getProduct()->getTotalQty(); ?></td>
                            <td><input type="text" name="products[<?php echo $item->getProduct()->getId(); ?>][return]" value="<?php echo $item->getReturn(); ?>" class="xxsmall item-update-field" /></td>
                            <td><input type="text" name="products[<?php echo $item->getProduct()->getId(); ?>][comment]" value="<?php echo $item->getComment(); ?>" class="small" /></td>
                            <td><a href="<?php echo $item->getProduct()->getId(); ?>" class="product-remove btn-remove-current"></a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_fieldset(''); ?>
                <div id="summary-table-wrapper">
                    <table id="summary">
                        <tr>
                            <td class="subtotal-title">Sub Total:</td>
                            <td class="subtotal">$<?php echo $summary['sub_total']; ?></td>
                        </tr>
                        <tr>
                            <td class="tax-title">Tax:</td>
                            <td class="tax">$<?php echo $summary['tax']; ?></td>
                        </tr>
                        <tr>
                            <td class="total-title">TOTAL:</td>
                            <td class="total">$<?php echo $summary['total']; ?></td>
                        </tr>
                    </table>
                    <div id="summary-table-overlay" class="hide"></div>
                </div>
            <?php echo form_fieldset_close(); ?>
            
            <?php echo form_hidden('id', $return->getId()); ?>
            <?php echo form_hidden('edit', 1); ?>
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('return_create', '', 'class=\'btn-create\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>

<script>
    $(document).ready(function() {
        var selected_products = <?php echo $current_product_ids; ?>;
        
        function hideTotalTable() {
            $('#summary').fadeTo(200, 0.2, function() {
                $('#summary-table-overlay').removeClass('hide');
            });
        }
        
        $(".product-remove").live("click", function(){
            var id = $(this).attr('href');
            
            // remove from local storage
            selected_products = $.grep(selected_products, function(value) {
                return value != id;
            });
            
            hideTotalTable();
        });
        
        $('.item-update-field').live('keyup', function() {
            hideTotalTable();
        });
        
        $('#product-ajax-search-btn').click(function() {
            var url = $(this).attr('href');
            var post_data = $("#product-ajax-search :input").serialize();
            var content = '';
            
            $.ajax({
                type: 'POST',
                url: url,
                data: post_data + "&selected_products=" + selected_products.join(','),
                dataType: 'json',
                success: function(data) {
                    // remove the highlight css from the exsiting product table rows
                    $.each(selected_products , function(index, value){
                        $('#row-' + value).removeClass('highlight');
                    });
                    
                    // Check if data is not empty
                    if (data.length !== 0) {
                        $.each(data, function(index, product) {
                            content += "<tr class='highlight' id=row-" + product.id + ">" +
                                            "<td>" + product.name + "</td>" +
                                            "<td>" + product.barcode + "</td>" +
                                            "<td>" + product.category + "</td>" +
                                            "<td><input type='text' name='products[" + product.id + "][qty]' class='xxsmall item-update-field' value='1' /></td>" +
                                            "<td>" + product.qty + "/" + product.unit + "</td>" +
                                            "<td><input type='text' name='products[" + product.id + "][return]' class='xxsmall item-update-field' /></td>" +
                                            "<td><input type='text' name='products[" + product.id + "][comment]' class='small' /></td>" +
                                            "<td><a href='" + product.id + "' class='product-remove btn-remove'></a></td>" +
                                        "</tr>";
                            
                            // add new product ids to localstore
                            selected_products.push(product.id);
                        });
                        
                        // add new table rows and fade out the total table
                        $('#summary').fadeTo(200, 0.2, function() {
                            $('#summary-table-overlay').removeClass('hide');
                            $(content).hide().prependTo('table#search-products').fadeIn('slow');
                        });
                    }
                    
                }
            });
            
            return false;
        });
        
        // For update total button
        $('#summary-table-overlay').click(function() {
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('admin/purchase/ajax_refresh_total'); ?>',
                data: $('#transaction-edit-form').serialize() + '&type=ajax',
                dataType: 'json',
                success: function(data) {
                    $('.total').html('$' + data.total);
                    $('.subtotal').html('$' + data.sub_total);
                    $('.tax').html('$' + data.tax);
                    
                    $('#summary').fadeTo(200, 1);
                    $('#summary-table-overlay').addClass('hide');
                }
            });
            
            return false;
        });
    });
</script>