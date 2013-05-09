<section id="content">
    <div id="content-head">
        <h2>User</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/user'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/user/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php echo form_open('admin/user', array('class' => 'search')); ?>
            <ul class="filter-fields-list">
                <li>
                    <?php echo form_input('username', set_value('username'), 'class=\'small\' placeholder=\'Username\''); ?>
                </li>
                <li>
                    <?php echo form_input('phone', set_value('phone'), 'class=\'small\' placeholder=\'Phone\''); ?>
                </li>
                <li>
                    <?php echo form_input('email', set_value('email'), 'class=\'small\' placeholder=\'Email\''); ?>
                </li>
                <li>
                    <select class="medium" name="role">
                        <option value="">Role</option>
                        <?php foreach($roles as $role): ?>
                        <option value="<?php echo $role->getId(); ?>" <?php echo set_select('role', $role->getId()); ?>><?php echo $role->getName(); ?></option>
                        <?php endforeach; ?>
                    </select>                
                </li>
                <li>
                    <select class="xsmall" name="active">
                        <option value="">Active</option>
                        <option value="1" <?php echo set_select('active"', '1'); ?>>Yes</option>
                        <option value="0" <?php echo set_select('active', '0'); ?>>No</option>
                    </select>
                </li>
            </ul>
            <?php echo form_submit('filter', '', 'class=\'btn-filter\''); ?>
        <?php echo form_close(); ?>
        
        <?php $this->load->view('admin/message'); ?>
        
        <table>
            <tr>
                <th class="small">Username</th>
                <th class="small">Role</th>
                <th class="xxsmall">Active</th>
                <th class="medium">Email</th>
                <th class="small">Phone</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user->getUserName(); ?></td>
                    <td><?php echo $user->getRole()->getName(); ?></td>
                    <td><?php echo $user->getActive()?'Yes':'No'; ?></td>
                    <td><?php echo $user->getEmail(); ?></td>
                    <td><?php echo $user->getPhone(); ?></td>
                    <td>
                        <a class="btn-edit" href="user/edit/<?php echo $user->getId(); ?>"></a>
                        <a class="btn-delete" href="user/delete/<?php echo $user->getId(); ?>"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>