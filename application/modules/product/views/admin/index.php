<section id="content">
    <div id="content-head">
        <h2>Product</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/product'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/product/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <form class="search" method="GET" action="<?php echo site_url('admin/product'); ?>">
            <ul class="filter-fields-list">
                <li>
                    <input placeholder="Name" class="small" name="name" value="<?php echo $filter->getName(); ?>" >
                </li>
                
                <li>
                    <input placeholder="Barcode" class="small" name="barcode" value="<?php echo $filter->getBarcode(); ?>" >
                </li>
                
                <li>
                    <input placeholder="Section" class="xxsmall" name="section" value="<?php echo $filter->getSection(); ?>" >
                </li>
                
                <li>
                    <select name="category">
                        <option value="">Category</option>
                        <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category->getId(); ?>" <?php echo ($filter->getCategory() == $category->getId()) ? 'selected' : ''; ?>><?php echo $category->getName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                
                <li>
                    <select name="vendor">
                        <option value="">Vendor</option>
                        <?php foreach($vendors as $vendor): ?>
                        <option value="<?php echo $vendor->getId(); ?>" <?php echo ($filter->getVendor() == $vendor->getId()) ? 'selected' : ''; ?>><?php echo $vendor->getName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                
                <li>
                    <select name="per_page">
                        <option value="">Per Page</option>
                        <option value="5" <?php echo ($filter->getPerPage() == 5) ? 'selected' : ''; ?>>5</option>
                        <option value="10" <?php echo ($filter->getPerPage() == 10) ? 'selected' : ''; ?>>10</option>
                        <option value="50" <?php echo ($filter->getPerPage() == 50) ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?php echo ($filter->getPerPage() == 100) ? 'selected' : ''; ?>>100</option>
                    </select>
                </li>
            </ul>
            <ul class="filter-fields-list">
                <li>
                    <input name="tags" class="large" id="tags" data-url="<?php echo site_url('admin/tag/ajax_search/'); ?>" data-never-submit="false" data-prefill="<?php echo $filter->getTags(); ?>" >
                </li>
            </ul>
            <?php echo form_submit('filter', '', 'class=\'btn-filter\''); ?>
        </form>
        
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