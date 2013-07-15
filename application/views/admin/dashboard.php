<section id="content">
    <div id="content-head">
        <h2>Dashboard</h2>
    </div>
    
    <?php echo Modules::run('modules/report/controllers/admin/sales'); ?>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
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
    </div>
</section>