<section id="content">
    <div id="content-head">
        <h2>Transaction status</h2>
        
        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/transaction_status'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/transaction_status/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container" class="clearfix">
        <?php $this->load->view('admin/message'); ?>
        <table>
            <tr>
                <th class="large">Transaction Status</th>
                <th class="medium">Core</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($statuses as $status): ?>
                <tr>
                    <td><?php echo $status->getName(); ?></td>
                    <td><?php echo ($status->getCore()) ? 'Yes' : 'No'; ?></td>
                    <td>
                        <?php if(!$status->getCore()) { ?>
                        <a class="btn-edit" href="transaction_status/edit/<?php echo $status->getId(); ?>"></a>
                        <a class="btn-delete" href="transaction_status/delete/<?php echo $status->getId(); ?>"></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>