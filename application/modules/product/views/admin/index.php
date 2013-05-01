<section id="content">
    <div id="content-head">
        <h2>Product</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/product'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/product/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php echo form_open('admin/product', array('class' => 'search')); ?>
            <ul id="filter-fields-list">
                <li>
                    <input placeholder="Name" class="medium" name="name" value="<?php echo !empty($filter['name']) ? $filter['name'] : ''; ?>" >
                </li>
                
                <li>
                    <input placeholder="Barcode" class="medium" name="barcode" value="<?php echo !empty($filter['barcode']) ? $filter['barcode'] : ''; ?>" >
                </li>
                
                <li>
                    <input placeholder="Section" class="xxsmall" name="section" value="<?php echo !empty($filter['section']) ? $filter['section'] : ''; ?>" >
                </li>
                
                <li>
                    <select name="category">
                        <option value="">Category</option>
                        <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category->getId(); ?>" <?php if(!empty($filter['category']) && $filter['category'] == $category->getId()) { ?> selected="selected" <?php } ?>><?php echo $category->getName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                
                <li>
                    <select name="vendor">
                        <option value="">Vendor</option>
                        <?php foreach($vendors as $vendor): ?>
                        <option value="<?php echo $vendor->getId(); ?>" <?php if(!empty($filter['vendor']) && $filter['vendor'] == $vendor->getId()) { ?> selected="selected" <?php } ?> ><?php echo $vendor->getName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                
                <li>
                    <select name="per_page">
                        <option value="12" <?php if(!empty($filter['per_page']) && $filter['per_page'] == 12) { ?> selected="selected" <?php } ?>>12</option>
                        <option value="24" <?php if(!empty($filter['per_page']) && $filter['per_page'] == 24) { ?> selected="selected" <?php } ?>>24</option>
                        <option value="48" <?php if(!empty($filter['per_page']) && $filter['per_page'] == 48) { ?> selected="selected" <?php } ?>>48</option>
                    </select>
                </li>
                
                <li>
                    <?php echo form_input('tags', set_value(''), 'class=\'large\' id=\'tags\''); ?>
                </li>
            </ul>
            <?php echo form_submit('filter', '', 'class=\'btn-filter\''); ?>
        <?php echo form_close(); ?>
        
        <?php $this->load->view('admin/message'); ?>
        <table>
            <tr>
                <th class="medium">SKU</th>
                <th class="medium">Name</th>
                <th class="small">Category</th>
                <th class="xxsmall">Section</th>
                <th class="xxsmall">Qty</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product->getSKU(); ?></td>
                <td><?php echo $product->getName(); ?></td>
                <td><?php echo $product->getCategory()->getName(); ?></td>
                <td><?php echo $product->getSection(); ?></td>
                <td><?php echo $product->getTotalQty(); ?></td>
                <td>
                    <a class="btn-edit" href="<?php echo base_url('admin/product/edit/' . $product->getId()); ?>"></a>
                    <a class="btn-delete" href="<?php echo base_url('admin/product/delete/' . $product->getId()); ?>"></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
        
        <?php if(!empty($pagination['links'])) { ?>
            <?php echo $pagination['links'];?>
        <?php } ?>
    </div>
</section>

<script>
    $(document).ready(function() {
        $("#tags").autoSuggest("<?php echo site_url('admin/tag/ajax_search');?>", {minChars: 2, startText: "Tags", asHtmlID: "tags"});
    });
</script>