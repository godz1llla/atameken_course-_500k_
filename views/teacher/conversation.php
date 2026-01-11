<?php $title = __('messages.conversation_with'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs">
            <i class="ion-ios-email"></i> <?php echo __('messages.conversation_with'); ?> <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom" style="max-height: 500px; overflow-y: auto; margin-bottom: 20px;">
            <?php foreach ($messages as $message): ?>
            <div class="m-b-sm" style="padding: 10px; background: <?php echo $message['sender_id'] == Session::getUserId() ? '#e3f2fd' : '#f5f5f5'; ?>; border-radius: 5px; margin-bottom: 10px;">
                <div class="m-b-xs">
                    <strong><?php echo htmlspecialchars($message['sender_name']); ?></strong>
                    <small class="text-muted pull-right"><?php echo date('M d, H:i', strtotime($message['created_at'])); ?></small>
                </div>
                <p style="margin: 0;"><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="ibox-content border-bottom">
            <form id="messageForm" action="/index.php?url=teacher/sendMessage" method="POST">
                <input type="hidden" name="receiver_id" value="<?php echo $student['id']; ?>">
                <div class="form-group">
                    <textarea name="message" class="form-control" rows="3" placeholder="<?php echo __('messages.type_message'); ?>" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="ion-paper-airplane"></i> <?php echo __('messages.send'); ?>
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
