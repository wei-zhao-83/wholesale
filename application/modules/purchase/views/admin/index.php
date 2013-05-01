<section id="content">
    <div id="content-head">
        <h2>Purchase</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/purchase'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/purchase/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php echo form_open('admin/purchase', array('class' => 'search')); ?>
            <ul id="filter-fields-list">
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
                    <select name="vendor">
                        <option value="">Vendor</option>
                        <?php foreach($vendors as $vendor): ?>
                        <option value="<?php echo $vendor->getId(); ?>" <?php echo set_select('vendor'); ?>><?php echo $vendor->getName(); ?></option>
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
                <th class="medium">Vendor</th>
                <th class="small">Status</th>
                <th class="small">Total</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($purchases as $purchase) { ?>
            <tr>
                <td><?php echo $purchase->getId(); ?></td>
                <td><?php echo $purchase->getCreatedAt(); ?></td>
                <td><?php echo ($purchase->getVendor())? $purchase->getVendor()->getName() : ''; ?></td>
                <td><?php echo $purchase->getStatus()->getName(); ?></td>
                <td><?php echo $purchase->getTotal(); ?></td>
                <td>
                    <a class="btn-edit" href="purchase/edit/<?php echo $purchase->getId(); ?>"></a>
                    <a class="btn-delete" href="purchase/delete/<?php echo $purchase->getId(); ?>"></a>
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