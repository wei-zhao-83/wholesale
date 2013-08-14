<section id="content">
    <div id="content-head">
        <h2>Sales</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/sale'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/sale/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        
        <form action="<?php echo site_url('admin/sale'); ?>" method="get" class="search">
            <ul class="filter-fields-list">
                <li>
                    <?php echo form_input('id', set_value('id'), 'class=\'small\' placeholder=\'Order #\''); ?>
                </li>
                
                <li>
                    <?php echo form_input('from', set_value('from'), 'class=\'small datepicker\' placeholder=\'From\''); ?>
                </li>
                
                <li>
                    <?php echo form_input('to', set_value('to'), 'class=\'small datepicker\' placeholder=\'To\''); ?>
                </li>
                
                <li>
                    <select name="status">
                        <option value="">Status</option>
                        <?php foreach($statuses as $status): ?>
                        <option value="<?php echo $status; ?>" <?php echo ($filter->getStatus() == $status) ? 'selected' : ''; ?>><?php echo get_full_name($status); ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                
                <li>
                    <select name="customer">
                        <option value="">Customer</option>
                        <?php foreach($customers as $customer): ?>
                        <option value="<?php echo $customer->getId(); ?>" <?php echo ($filter->getCustomer() == $customer->getId()) ? 'selected' : ''; ?>><?php echo $customer->getName(); ?></option>
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
            <?php echo form_submit('filter', '', 'class=\'btn-filter\''); ?>
        </form>
        
        <?php $this->load->view('admin/message'); ?>
        
        <table>
            <tr>
                <th class="xsmall">Order #</th>
                <th class="small">Order Date</th>
                <th class="medium">Customer</th>
                <th class="xsmall">Status</th>
                <th class="small">BOH Updated</th>
                <th class="small">Type</th>
                <!--<th class="small">Payment</th>-->
                <th class="xsmall">Discount</th>
                <th class="small">Total/Due</th>
                <th class="xsmall"></th>
            </tr>
            <?php foreach ($sales as $sale) { ?>
            <?php $_summary = $sale->getSummary(true); ?>
            <tr>
                <td><?php echo $sale->getId(); ?></td>
                <td><?php echo $sale->getCreatedAt(); ?></td>
                <td><?php echo ($sale->getCustomer())? $sale->getCustomer()->getName() : ''; ?></td>
                <td><?php echo $sale->getStatus(); ?></td>
                <td><?php echo ($sale->getBohUpdated() == 1) ? 'Yes' : 'No'; ?></td>
                <td><?php echo get_full_name($sale->getType()); ?></td>
                <!--<td><?php // echo get_full_name($sale->getPayment()); ?></td>-->
                <td><?php echo $sale->getDefaultDiscount() * 100; ?>%</td>
                <td>$<?php echo $_summary['total']; ?>/<span class="text-highlight">$<?php echo $_summary['total_due']; ?></span></td>
                <td>
                    <a class="btn-edit" href="<?php echo site_url('admin/sale/edit/' . $sale->getId()) ?>"></a>
                    <a class="btn-delete" href="<?php echo site_url('admin/sale/delete/' . $sale->getId()) ?>"></a>
                </td>
            </tr>
            <?php } ?>
        </table>
        
        <?php if(!empty($pagination['links'])) { ?>
            <?php echo $pagination['links'];?>
        <?php } ?>
    </div>
</section>