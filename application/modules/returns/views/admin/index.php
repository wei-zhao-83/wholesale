<section id="content">
    <div id="content-head">
        <h2>Return</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/returns'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/returns/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php echo form_open('admin/returns', array('class' => 'search')); ?>
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
                <th class="small">Order #</th>
                <th class="medium">Order Date</th>
                <th class="medium">Customer</th>
                <th class="small">Status</th>
                <th class="small">Total</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($returns as $return) { ?>
            <tr>
                <td><?php echo $return->getId(); ?></td>
                <td><?php echo $return->getCreatedAt(); ?></td>
                <td><?php echo ($return->getCustomer())? $return->getCustomer()->getName() : ''; ?></td>
                <td><?php echo $return->getStatus()->getName(); ?></td>
                <td><?php echo $return->getTotal(); ?></td>
                <td>
                    <a class="btn-edit" href="returns/edit/<?php echo $return->getId(); ?>"></a>
                    <a class="btn-delete" href="returns/delete/<?php echo $return->getId(); ?>"></a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</section>

<script>
    $(document).ready(function() {

    });
</script>