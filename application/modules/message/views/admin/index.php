<section id="content">
    <div id="content-head">
        <h2>Message</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/message'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/message/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php echo form_open('admin/message', array('class' => 'search')); ?>
            <ul class="filter-fields-list">
                <li>
                    <?php echo form_input('subject', set_value('subject'), 'class=\'large\' placeholder=\'Subject\''); ?>
                </li>
                
                <li>
                    <select class="medium" name="from">
                        <option value="">From</option>
                        <?php foreach($users as $user) { ?>
                        <?php if ($this->session->userdata('username') != $user->getUsername()) { ?>
                            <option value="<?php echo $user->getId(); ?>"><?php echo $user->getUsername(); ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </li>
            </ul>
            <?php echo form_submit('filter', '', 'class=\'btn-filter\''); ?>
        <?php echo form_close(); ?>
    
        <?php $this->load->view('admin/message'); ?>
        
        <table>
            <tr>
                <th class="medium">Subject</th>
                <th class="medium">From</th>
                <th class="medium">Date</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($messages as $msg): ?>
            <tr>
                <td><?php echo $msg->getSubject(); ?></td>
                <td><?php echo $msg->getSender()->getUsername(); ?></td>
                <td><?php echo $msg->getCreatedAt(); ?></td>
                <td>
                    <a class="btn-view" href="message/view/<?php echo $msg->getId(); ?>"></a>
                    <a class="btn-delete" href="message/delete/<?php echo $msg->getId(); ?>"></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</section>