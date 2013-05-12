<section id="content">
    <div id="content-head">
        <h2>Customer</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/customer'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/customer/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php echo form_open('admin/customer', array('class' => 'search')); ?>
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
                <th class="medium">Location</th>
                <th class="small">Last Order</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?php echo $customer->getName(); ?></td>
                <td><?php echo $customer->getPhone(); ?></td>
                <td><?php echo $customer->getBillingAddress(); ?> <?php echo $customer->getBillingCity(); ?> <?php echo $customer->getBillingProvinceAbbr(); ?>, <?php echo $customer->getBillingPostal(); ?></td>
                <td></td>
                <td>
                    <a class="btn-edit" href="customer/edit/<?php echo $customer->getId(); ?>"></a>
                    <a class="btn-delete" href="customer/delete/<?php echo $customer->getId(); ?>"></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</section>