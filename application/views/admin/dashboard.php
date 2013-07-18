<section id="content">
    <div id="content-head">
        <h2>Dashboard</h2>
    </div>
    
    <?php echo Modules::run('modules/report/controllers/admin/sales'); ?>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <section>
            <form action="<?php echo base_url('admin'); ?>" method="GET" class="search dashboard">
                <ul class="filter-fields-list">
                    <li>
                        <input name="from" class="small datepicker" value="<?php echo !empty($_GET['from']) ? $_GET['from'] : ''; ?>" placeholder="From">
                    </li>
                    <li>
                        <input name="to" class="small datepicker" value="<?php echo !empty($_GET['to']) ? $_GET['to'] : ''; ?>" placeholder="To">
                    </li>
                </ul>
                <input type="submit" value="" class="btn-filter">
                
                <ul id="sales-date-range">
                    <li><a href="<?php echo base_url(); ?>admin/?range=7days">Last 7 Days</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/?range=month">Current Month</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/?range=ytd">YTD</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/?range=2ytd">2YTD</a></li>
                </ul>
            </form>
            
            <div id="sales-flowchart" data-sales-by-date='<?php echo $sales_matrix; ?>'></div>
        </section>
        
        <section>
            <h4>Last 5 Sales</h4>
                <table>
                    <tr>
                        <th class="xsmall">Order #</th>
                        <th class="small">Order Date</th>
                        <th class="medium">Customer</th>
                        <th class="xsmall">Status</th>
                        <th class="small">Type</th>
                        <th class="small">Total/Due</th>
                    </tr>
                    <?php if(count($last_sales) > 0) { ?>
                        <?php foreach ($last_sales as $sale) { ?>
                            <?php $_summary = $sale->getSummary(true); ?>
                            <tr>
                                <td><a href="<?php echo site_url('admin/sale/edit/' . $sale->getId()); ?>"><?php echo $sale->getId(); ?></a></td>
                                <td><?php echo $sale->getCreatedAt(); ?></td>
                                <td><?php echo ($sale->getCustomer())? $sale->getCustomer()->getName() : ''; ?></td>
                                <td><?php echo $sale->getStatus(); ?></td>
                                <td><?php echo get_full_name($sale->getType()); ?></td>
                                <td>$<?php echo $_summary['total']; ?>/<span class="text-highlight">$<?php echo $_summary['total_due']; ?></span></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
        </section>
    </div>
</section>