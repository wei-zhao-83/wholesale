<section id="content">
    <div id="content-head">
        <h2>Module</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/module'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <table>
            <tr>
                <th class="medium">Name</th>
                <th class="xsmall">Version</th>
                <th class="xxsmall">Core</th>
                <th class="xxsmall">Active</th>
                <th class="medium">Description</th>
                <th class="medium">Last Update</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($modules as $module): ?>
                <tr>
                    <td><?php echo $module->getName(); ?></td>
                    <td><?php echo $module->getVersion(); ?></td>
                    <td><?php echo $module->getCore()?'Yes':'No'; ?></td>
                    <td><?php echo $module->getActive()?'Yes':'No'; ?></td>
                    <td><?php echo $module->getDescription(); ?></td>
                    <td><?php echo $module->getLastUpdatedAt(); ?></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>