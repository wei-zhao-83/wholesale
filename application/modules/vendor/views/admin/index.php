<section id="content">
    <div id="content-head">
        <h2>Vendor</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/vendor'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/vendor/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php echo form_open('admin/vendor', array('class' => 'search')); ?>
            <ul class="filter-fields-list">
                <li>
                    <?php echo form_input('name', set_value('name'), 'class=\'small\' placeholder=\'Username\''); ?>
                </li>
                
                <li>
                    <?php echo form_input('phone', set_value('phone'), 'class=\'small\' placeholder=\'Phone\''); ?>
                </li>
            </ul>
            <ul class="filter-fields-list">
                <li>
                    <input name="tags" class="large" id="tags" data-url="<?php echo site_url('admin/tag/ajax_search/'); ?>" data-never-submit="false" data-prefill="<?php echo $this->input->post('as_values_tags'); ?>" >
                </li>
            </ul>
            <?php echo form_submit('filter', '', 'class=\'btn-filter\''); ?>
        <?php echo form_close(); ?>
        
        <?php $this->load->view('admin/message'); ?>
        
        <table>
            <tr>
                <th class="medium">Name</th>
                <th class="small">Phone</th>
                <th class="medium">Email</th>
                <th class="small">Order Frequency</th>
                <th class="small"></th>
            </tr>
            <?php foreach ($vendors as $vendor): ?>
            <tr>
                <td><?php echo $vendor->getName(); ?></td>
                <td><?php echo $vendor->getPhone(); ?></td>
                <td><?php echo $vendor->getEmail(); ?></td>
                <td><?php echo $vendor->getOrderFrequency(); ?></td>
                <td>
                    <a class="btn-add" href="<?php echo site_url('admin/purchase/create/' . $vendor->getId()); ?>"></a>
                    <a class="btn-edit" href="vendor/edit/<?php echo $vendor->getId(); ?>"></a>
                    <a class="btn-delete" href="vendor/delete/<?php echo $vendor->getId(); ?>"></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</section>