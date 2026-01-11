<?php $title = __('admin.student_finances'); include 'views/layouts/header.php'; ?>

<div class="content-area">
    <div class="page-header">
        <h1>💳 <?php echo __('admin.student_finances'); ?>: <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h1>
        <a href="/index.php?url=admin/users" class="btn btn-outline">← <?php echo __('common.back'); ?></a>
    </div>
    
    <div style="display: grid; gap: 30px;">
        <!-- Первая карточка: Текущий абонемент -->
        <div class="card">
            <h2>📋 <?php echo __('admin.current_subscription'); ?></h2>
            
            <?php if ($activeSubscription): ?>
            <div style="margin-top: 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <strong style="color: var(--gray); font-size: 14px;"><?php echo __('admin.plan_name'); ?>:</strong>
                        <p style="font-size: 18px; font-weight: 600; margin-top: 5px;"><?php echo htmlspecialchars($activeSubscription['plan_name']); ?></p>
                    </div>
                    <div>
                        <strong style="color: var(--gray); font-size: 14px;"><?php echo __('admin.start_date'); ?>:</strong>
                        <p style="font-size: 18px; font-weight: 600; margin-top: 5px;"><?php echo date('d.m.Y', strtotime($activeSubscription['start_date'])); ?></p>
                    </div>
                    <div>
                        <strong style="color: var(--gray); font-size: 14px;"><?php echo __('admin.end_date'); ?>:</strong>
                        <p style="font-size: 18px; font-weight: 600; margin-top: 5px;"><?php echo date('d.m.Y', strtotime($activeSubscription['end_date'])); ?></p>
                    </div>
                    <div>
                        <strong style="color: var(--gray); font-size: 14px;"><?php echo __('admin.lessons_remaining'); ?>:</strong>
                        <p style="font-size: 18px; font-weight: 600; margin-top: 5px; color: var(--primary);">
                            <?php echo htmlspecialchars($activeSubscription['lessons_remaining']); ?> <?php echo __('admin.lessons'); ?>
                        </p>
                    </div>
                </div>
                
                <?php if (!empty($activeSubscription['plan_description'])): ?>
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--light-gray);">
                    <strong style="color: var(--gray); font-size: 14px;"><?php echo __('course.description'); ?>:</strong>
                    <p style="margin-top: 5px; color: var(--dark);"><?php echo nl2br(htmlspecialchars($activeSubscription['plan_description'])); ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 40px 20px; margin-top: 20px;">
                <div style="font-size: 48px; margin-bottom: 15px;">💳</div>
                <p style="color: var(--gray);"><?php echo __('admin.no_active_subscription'); ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Вторая карточка: Назначить новый абонемент -->
        <div class="card">
            <h2>➕ <?php echo __('admin.assign_new_subscription'); ?></h2>
            
            <?php if (!empty($plans)): ?>
            <form method="POST" action="/index.php?url=admin/assignSubscription" class="form" style="margin-top: 20px;">
                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                
                <div class="form-group">
                    <label><?php echo __('admin.select_plan'); ?> *</label>
                    <select name="plan_id" required>
                        <option value=""><?php echo __('admin.select_plan'); ?></option>
                        <?php foreach ($plans as $plan): ?>
                            <option value="<?php echo $plan['id']; ?>">
                                <?php echo htmlspecialchars($plan['name']); ?> 
                                - <?php echo number_format($plan['price'], 2, '.', ' '); ?> ₸ 
                                (<?php echo $plan['duration_days']; ?> <?php echo __('admin.days'); ?>, 
                                <?php echo $plan['lesson_count']; ?> <?php echo __('admin.lessons'); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><?php echo __('admin.start_date'); ?> *</label>
                    <input type="date" name="start_date" value="<?php echo date('Y-m-d'); ?>" required min="<?php echo date('Y-m-d'); ?>">
                    <small style="color: var(--gray); margin-top: 5px; display: block;"><?php echo __('admin.start_date_description'); ?></small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💳 <?php echo __('admin.assign_subscription'); ?></button>
                    <a href="/index.php?url=admin/users" class="btn btn-outline">❌ <?php echo __('common.cancel'); ?></a>
                </div>
            </form>
            <?php else: ?>
            <div style="text-align: center; padding: 40px 20px; margin-top: 20px;">
                <div style="font-size: 48px; margin-bottom: 15px;">📋</div>
                <p style="color: var(--gray);"><?php echo __('admin.no_plans_available'); ?></p>
                <a href="/index.php?url=admin/createPlan" class="btn btn-primary" style="margin-top: 20px;">➕ <?php echo __('admin.create_plan'); ?></a>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Третья карточка: Добавить новый платеж -->
        <?php if ($activeSubscription): ?>
        <div class="card">
            <h2>💰 <?php echo __('admin.add_new_payment'); ?></h2>
            
            <form method="POST" action="/index.php?url=admin/addPayment" class="form" style="margin-top: 20px;">
                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                <input type="hidden" name="student_subscription_id" value="<?php echo $activeSubscription['id']; ?>">
                
                <div class="form-group">
                    <label><?php echo __('admin.amount'); ?> *</label>
                    <input type="number" name="amount" required min="0" step="0.01" placeholder="0.00">
                    <small style="color: var(--gray); margin-top: 5px; display: block;"><?php echo __('admin.amount_description'); ?></small>
                </div>
                
                <div class="form-group">
                    <label><?php echo __('admin.payment_method'); ?> *</label>
                    <select name="payment_method" required>
                        <option value="cash">💵 <?php echo __('admin.payment_method_cash'); ?></option>
                        <option value="card">💳 <?php echo __('admin.payment_method_card'); ?></option>
                        <option value="transfer">🏦 <?php echo __('admin.payment_method_transfer'); ?></option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><?php echo __('admin.payment_date'); ?> *</label>
                    <input type="date" name="payment_date" value="<?php echo date('Y-m-d'); ?>" required max="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label><?php echo __('admin.note'); ?> (<?php echo __('common.optional'); ?>)</label>
                    <textarea name="note" rows="3" placeholder="<?php echo __('admin.note_placeholder'); ?>"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">➕ <?php echo __('admin.add_payment'); ?></button>
                    <a href="/index.php?url=admin/users" class="btn btn-outline">❌ <?php echo __('common.cancel'); ?></a>
                </div>
            </form>
        </div>
        <?php endif; ?>
        
        <!-- Четвертая карточка: История платежей -->
        <div class="card">
            <h2>📊 <?php echo __('admin.payment_history'); ?></h2>
            
            <?php if (!empty($payments)): ?>
            <div style="margin-top: 20px;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><?php echo __('admin.payment_date'); ?></th>
                            <th><?php echo __('admin.amount'); ?></th>
                            <th><?php echo __('admin.payment_method'); ?></th>
                            <th><?php echo __('admin.plan_name'); ?></th>
                            <th><?php echo __('admin.note'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td data-label="<?php echo __('admin.payment_date'); ?>">
                                <?php echo date('d.m.Y', strtotime($payment['payment_date'])); ?>
                            </td>
                            <td data-label="<?php echo __('admin.amount'); ?>">
                                <strong style="color: var(--success);"><?php echo number_format($payment['amount'], 2, '.', ' '); ?> ₸</strong>
                            </td>
                            <td data-label="<?php echo __('admin.payment_method'); ?>">
                                <?php 
                                $methodIcons = [
                                    'cash' => '💵 ' . __('admin.payment_method_cash'),
                                    'card' => '💳 ' . __('admin.payment_method_card'),
                                    'transfer' => '🏦 ' . __('admin.payment_method_transfer')
                                ];
                                echo $methodIcons[$payment['payment_method']] ?? $payment['payment_method'];
                                ?>
                            </td>
                            <td data-label="<?php echo __('admin.plan_name'); ?>">
                                <?php echo htmlspecialchars($payment['plan_name']); ?>
                            </td>
                            <td data-label="<?php echo __('admin.note'); ?>">
                                <?php echo $payment['note'] ? htmlspecialchars($payment['note']) : '<span style="color: var(--gray);">—</span>'; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 40px 20px; margin-top: 20px;">
                <div style="font-size: 48px; margin-bottom: 15px;">💰</div>
                <p style="color: var(--gray);"><?php echo __('admin.no_payments'); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>

