<section id="content">
    <div id="content-head">
        <h2>Sales Report</h2>
        
        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/sale'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/sale/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <section>
            <form action="<?php echo base_url('admin/sale/report'); ?>" method="GET" class="search dashboard">
                <ul class="filter-fields-list">
                    <li>
                        <input name="from" class="small datepicker" value="<?php echo !empty($_GET['from']) ? $_GET['from'] : ''; ?>" placeholder="From">
                    </li>
                    <li>
                        <input name="to" class="small datepicker" value="<?php echo !empty($_GET['to']) ? $_GET['to'] : ''; ?>" placeholder="To">
                    </li>
                    
                    <li>
                        <select name="category">
                            <?php if (!empty($categories)) { ?>
                                <option value=""> - Category - </option>
                                <?php foreach ($categories as $category) { ?>
                                    <option value="<?php echo $category->getId() ?>" <?php echo (!empty($_GET['category']) && $_GET['category'] == $category->getId()) ? 'selected' : ''; ?>><?php echo $category->getName(); ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </li>
                </ul>
                <input type="submit" value="" class="btn-filter">
            </form>
            
            <div id="sales-flowchart" data-sales-by-date='<?php echo json_encode($items); ?>'></div>
        </section>
        
        <section class="report-detail">
            <div class="left half report-wrap">
                <h4>Products</h4>
                <table class="report">
                    <tr>
                        <th class="text-lft">Name</th>
                        <th>Qty.</th>
                        <th class="text-rgt">Total</th>
                    </tr>
                    <?php if (!empty($products)) { ?>
                        <?php foreach($products as $product) { ?>
                            <tr>
                                <td class="text-lft"><?php echo $product['obj']->getName(); ?></td>
                                <td><?php echo $product['qty'] ?></td>
                                <td class="text-rgt">$<?php echo number_format($product['total'], 2, '.', ''); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </div>
            
            <div class="left half report-wrap">
                <h4>Sales by Date</h4>
                <table class="report">
                    <tr>
                        <th class="text-lft">Date</th>
                        <th class="text-rgt">Total Sales</th>
                    </tr>
                    <?php if (!empty($items)) { ?>
                        <?php foreach($items as $date => $sale) { ?>
                            <tr>
                                <td class="text-lft"><?php echo date('M d, Y', substr($date, 0, -3)); ?></td>
                                <td class="text-rgt">$<?php echo number_format($sale, 2, '.', ''); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </div>
        </section>
    </div>
</section>