<section id="content">
    <div id="content-head">
        <h2>Purchase</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/purchase'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <form action="<?php echo site_url('admin/purchase'); ?>" method="get" class="search">
            <ul class="filter-fields-list">
                <li>
                    <?php echo form_input('id', set_value('id'), 'class=\'xsmall\' placeholder=\'Order #\''); ?>
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
            <?php echo form_submit('filter', '', 'class=\'btn-filter\''); ?>
        </form>
        
        <?php $this->load->view('admin/message'); ?>
        
        <table>
            <tr>
                <th class="small">Order #</th>
                <th class="small">Order Date</th>
                <th class="medium">Vendor</th>
                <th class="small">Status</th>
                <th class="small">BOH Updated</th>
                <th class="small">Total/Due</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($purchases as $purchase) { ?>            
            <?php $_summary = $purchase->getSummary(); ?>
            <tr>
                <td><?php echo $purchase->getId(); ?></td>
                <td><?php echo $purchase->getCreatedAt(); ?></td>
                <td><?php echo ($purchase->getVendor())? $purchase->getVendor()->getName() : ''; ?></td>
                <td><?php echo get_full_name($purchase->getStatus()); ?></td>
                <td><?php echo ($purchase->getBohUpdated() == 1) ? 'Yes' : 'No'; ?></td>
                <td>$<?php echo $_summary['total']; ?>/<span class="text-highlight">$<?php echo $_summary['total_due']; ?></span></td>
                <td>
                    <a class="btn-edit" href="purchase/edit/<?php echo $purchase->getId(); ?>"></a>
                    <a class="btn-delete" href="purchase/delete/<?php echo $purchase->getId(); ?>"></a>
                </td>
            </tr>
            <?php } ?>
        </table>
        
        <?php if(!empty($pagination['links'])) { ?>
            <?php echo $pagination['links'];?>
        <?php } ?>
    </div>
</section>