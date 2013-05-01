<section id="content">
    <div id="content-head">
        <h2>Role</h2>
        
        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/role'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/role/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php $this->load->view('admin/message'); ?>
        
        <table>
            <tr>
                <th class="medium">Role</th>
                <th class="small">Removable</th>
                <th class="medium">Description</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($roles as $role): ?>
                <tr>
                    <td><?php echo $role->getName(); ?></td>
                    <td><?php echo $role->getRemovable()?'Yes':'No'; ?></td>
                    <td><?php echo $role->getDescription(); ?></td>
                    <td>
                        <a class="btn-edit" href="role/edit/<?php echo $role->getId(); ?>"></a>
                        <a class="btn-delete" href="role/delete/<?php echo $role->getId(); ?>"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>