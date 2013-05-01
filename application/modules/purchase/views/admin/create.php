<section id="content">
    <div id="content-head">
        <h2>Purchase</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/purchase'); ?>"></a></li>
            <li><a class="action-create active" href="<?php echo base_url('admin/purchase/create'); ?>"></a></li>
        </ul>
    </div>

    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <?php echo form_open_multipart('admin/purchase/create/'); ?>
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
                                <div class="medium"><?php echo date('F d, Y', time()) ?></div>
                            </div>
                        </li>
                        
                        <li>
                            <?php echo form_label('Vendor', 'vendor'); ?>
                            <select class="medium-2" name="vendor">
                                <?php foreach($vendors as $vendor): ?>
                                <option value="<?php echo $vendor->getId(); ?>" <?php echo set_select('vendor', $vendor->getId()); ?>><?php echo $vendor->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        
                        <li>
                            <label for="status">Status <span>[<a class="add-status" data-fancybox-type="iframe" href="<?php echo site_url('admin/transaction_status/create/');?>/ajax">Add New</a>]</span></label>
                            <select class="medium-2" name="status">
                                <?php foreach($statuses as $status): ?>
                                <option value="<?php echo $status->getId(); ?>" <?php echo set_select('status', $status->getId()); ?>><?php echo $status->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                    </ul>
                </div>
                <div class="half">
                    <ul>
                        <li>
                            <?php echo form_label('Comment', 'comment'); ?>
                            <?php echo form_textarea('comment', set_value('comment'), 'class=\'large-2\''); ?>
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
                            <th class="xsmall">Cost</th>
                            <th class="xsmall">Qty</th>
                            <th class="xsmall">Stock</th>
                            <th class="small">Comment</th>
                            <th class="xxsmall"></th>
                        </tr>
                    </thead>
                </table>
            <?php echo form_fieldset_close(); ?>
            
            <div class="btn-box">
                <ul>
                    <li><?php echo form_submit('purchase_create', '', 'class=\'btn-create\''); ?></li>
                </ul>
            </div>
        <?php echo form_close(); ?>
    </div>
</section>

<script>
    $(document).ready(function() {
        var selected_products = [];
        
        $(".product-remove").live("click", function(){
            var id = $(this).attr('href');
            // remove from local storage
            selected_products = $.grep(selected_products, function(value) {
                return value != id;
            });
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
                    // remove the highlight css from exsiting products
                    $.each(selected_products , function(index, value){
                        $('#row-' + value).removeClass('highlight');
                    });
                    
                    $.each(data, function(index, product) {
                        content += "<tr class='highlight' id=row-" + product.id + ">" +
                                        "<td>" + product.name + "</td>" +
                                        "<td>" + product.barcode + "</td>" +
                                        "<td>" + product.category + "</td>" +
                                        "<td>" + product.cost + "</td>" +
                                        "<td><input type='text' name='products[" + product.id + "][qty]' class='xxsmall' value='1' /></td>" +
                                        "<td>" + product.qty + "/" + product.unit + "</td>" +
                                        "<td><input type='text' name='products[" + product.id + "][comment]' class='small' /></td>" +
                                        "<td><a href='" + product.id + "' class='product-remove btn-remove'></a></td>" +
                                    "</tr>";
                        
                        selected_products.push(product.id);
                    });
                    
                    // add new data
                    $(content).hide().prependTo('table#search-products').fadeIn('slow');
                }
            });
            
            return false;
        });
    });
</script>