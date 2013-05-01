<?php $message = !empty($message)? $message : $this->session->flashdata('message'); ?>

<?php if(isset($message)) { ?>
    <div class="<?php echo $message['type'] ?>">
        <?php if(is_array($message['content'])) { ?>
        <ul>
            <?php foreach($message['content'] as $field_name => $error_msg) { ?>
                <li><?php echo $error_msg; ?></li>
            <?php } ?>
        </ul>
        <?php } else { ?>
            <?php echo $message['content']; ?>
        <?php } ?>
    </div>
<?php } ?>