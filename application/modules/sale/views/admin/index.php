<section id="content">
    <div id="content-head">
        <h2>Sales</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/sale'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/sale/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php echo form_open('admin/sale', array('class' => 'search')); ?>
            <ul class="filter-fields-list">
                <li>
                    <?php echo form_input('id', set_value('id'), 'class=\'small\' placeholder=\'Order #\''); ?>
                </li>
                
                <li>
                    <?php echo form_input('from', set_value('from'), 'class=\'small\' id=\'date-picker\' placeholder=\'From\''); ?>
                </li>
                
                <li>
                    <?php echo form_input('to', set_value('to'), 'class=\'small\' id=\'date-picker\' placeholder=\'To\''); ?>
                </li>
                
                <li>
                    <select name="status">
                        <option value="">Status</option>
                        <?php foreach($statuses as $status): ?>
                        <option value="<?php echo $status->getId(); ?>" <?php echo set_select('status'); ?>><?php echo $status->getName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                
                <li>
                    <select name="customer">
                        <option value="">Customer</option>
                        <?php foreach($customers as $customer): ?>
                        <option value="<?php echo $customer->getId(); ?>" <?php echo set_select('customer'); ?>><?php echo $customer->getName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
            </ul>
            <?php echo form_submit('filter', '', 'class=\'btn-filter\''); ?>
        <?php echo form_close(); ?>
        
        <?php $this->load->view('admin/message'); ?>
        
        <table>
            <tr>
                <th class="xsmall">Order #</th>
                <th class="small">Order Date</th>
                <th class="medium">Customer</th>
                <th class="xsmall">Status</th>
                <th class="small">Type</th>
                <!--<th class="small">Payment</th>-->
                <th class="xsmall">Discount</th>
                <th class="small">Total</th>
                <th class="small">Due</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($sales as $sale) { ?>
            <?php $_summary = $sale->getSummary(true); ?>
            <tr>
                <td><?php echo $sale->getId(); ?></td>
                <td><?php echo $sale->getCreatedAt(); ?></td>
                <td><?php echo ($sale->getCustomer())? $sale->getCustomer()->getName() : ''; ?></td>
                <td><?php echo $sale->getStatus()->getName(); ?></td>
                <td><?php echo get_full_name($sale->getType()); ?></td>
                <!--<td><?php // echo get_full_name($sale->getPayment()); ?></td>-->
                <td><?php echo $sale->getDefaultDiscount() * 100; ?>%</td>
                <td>$<?php echo $_summary['total']; ?></td>
                <td>$<?php echo $_summary['total_due']; ?></td>
                <td>
                    <a class="btn-edit" href="sale/edit/<?php echo $sale->getId(); ?>"></a>
                    <a class="btn-delete" href="sale/delete/<?php echo $sale->getId(); ?>"></a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</section>