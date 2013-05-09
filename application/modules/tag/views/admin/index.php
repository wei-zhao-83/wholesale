<section id="content">
    <div id="content-head">
        <h2>Tag</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/tag'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/tag/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php echo form_open('admin/tag', array('class' => 'search')); ?>
            <ul class="filter-fields-list">
                <li>
                    <?php echo form_input('name', set_value('name'), 'class=\'large\' placeholder=\'Name\''); ?>
                </li>
            </ul>
            <?php echo form_submit('filter', '', 'class=\'btn-filter\''); ?>
        <?php echo form_close(); ?>
        
        <?php $this->load->view('admin/message'); ?>
        
        <table>
            <tr>
                <th class="large">Name</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($tags as $tag): ?>
            <tr>
                <td><?php echo $tag->getName(); ?></td>
                <td>
                    <a class="btn-edit" href="tag/edit/<?php echo $tag->getId(); ?>"></a>
                    <a class="btn-delete" href="tag/delete/<?php echo $tag->getId(); ?>"></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</section>