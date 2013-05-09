<section id="content">
    <div id="content-head">
        <h2>Permission</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/permission'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/permission/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php echo form_open('admin/permission', array('class' => 'search')); ?>
            <ul class="filter-fields-list">
                <li>
                    <?php echo form_input('name', set_value('name'), 'class=\'medium\' placeholder=\'name\''); ?>
                </li>
                <li>
                    <select class="medium" name="module">
                        <option value="">Module</option>
                        <?php foreach($modules as $module): ?>
                        <option value="<?php echo $module->getSlug(); ?>" <?php echo set_select('module', $module->getSlug()); ?>><?php echo $module->getName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
                <li>
                    <select class="medium" name="removable">
                        <option value="">Removable</option>
                        <option value="1" <?php echo set_select('removable"', '1'); ?>>Yes</option>
                        <option value="0" <?php echo set_select('removable', '0'); ?>>No</option>
                    </select>
                </li>
            </ul>
            <?php echo form_submit('filter', '', 'class=\'btn-filter\''); ?>
        <?php echo form_close(); ?>
        
        <?php $this->load->view('admin/message'); ?>
        
        <table>
            <tr>
                <th class="medium">Module</th>
                <th class="medium">Name</th>
                <th class="small">Removable</th>
                <th class="medium">Description</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($permissions as $permission): ?>
            <tr>
                <td><?php echo $permission->getModule(); ?></td>
                <td><?php echo $permission->getName(); ?></td>
                <td><?php echo $permission->getRemovable()?'Yes':'No'; ?></td>
                <td><?php echo $permission->getDescription(); ?></td>
                <td>
                    <a class="btn-edit" href="permission/edit/<?php echo $permission->getId(); ?>"></a>
                    <a class="btn-delete" href="permission/delete/<?php echo $permission->getId(); ?>"></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</section>