<section id="content">
    <div id="content-head">
        <h2>Message</h2>
        
        <ul>
            <li><a class="action-list" href="<?php echo base_url('admin/message'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/message/create'); ?>"></a></li>
        </ul>
    </div>

    <div id="white-bg-container">
        <div id="mail">
            <div id="mail-header">
                <h5><?php echo $message->getSubject(); ?></h5>
                <ul>
                    <li><span>From:</span> <?php echo $message->getSender()->getUsername(); ?></li>
                    <li><span>To: </span> <?php echo $message->getReceiver()->getUsername(); ?></li>
                </ul>
            </div>
            <div id="mail-body">
                <p><?php echo $message->getContent(); ?></p>
            </div>
        </div>
    </div>
</section>