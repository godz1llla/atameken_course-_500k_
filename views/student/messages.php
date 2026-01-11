<?php $title = __('messages.title'); include 'views/layouts/header.php'; ?>

<div class="row m-b-sm">
    <div class="col-sm-12">
        <h2 class="font-bold m-b-xs"><?php echo __('messages.title'); ?></h2>
    </div>
</div>

<?php if (count($conversations) > 0): ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom">
            <div class="table-responsive">
                <table class="table table-hover">
                    <tbody>
                        <?php foreach ($conversations as $conversation): ?>
                        <tr>
                            <td style="width: 50px;">
                                <?php if ($conversation['other_user_avatar']): ?>
                                    <img src="/public/uploads/<?php echo $conversation['other_user_avatar']; ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #f5f5f5; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #999;">
                                        <?php echo mb_substr($conversation['other_user_name'], 0, 1, 'UTF-8'); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($conversation['other_user_name']); ?></strong>
                                <?php if ($conversation['course_title']): ?>
                                    <br><small class="text-muted"><i class="ion-book"></i> <?php echo htmlspecialchars($conversation['course_title']); ?></small>
                                <?php endif; ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($conversation['last_message'], 0, 50)); ?>...</small>
                            </td>
                            <td style="text-align: right;">
                                <small class="text-muted"><?php echo date('M d, H:i', strtotime($conversation['last_message_time'])); ?></small>
                                <?php if ($conversation['unread_count'] > 0): ?>
                                    <br><span class="label label-danger"><?php echo $conversation['unread_count']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/index.php?url=student/conversation/<?php echo $conversation['other_user_id']; ?>" class="btn btn-xs btn-primary">
                                    <i class="ion-ios-eye"></i> <?php echo __('common.view'); ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-content border-bottom text-center" style="padding: 60px 20px;">
            <i class="ion-ios-email" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
            <p class="text-muted"><?php echo __('messages.no_messages'); ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>
