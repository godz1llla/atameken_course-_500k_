<?php include 'views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-box">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 64px; margin-bottom: 15px;">🎓</div>
            <h1 style="margin: 0;">✨ <?php echo __('auth.join_us'); ?></h1>
        </div>
        
        <!-- Language Switcher -->
        <div style="margin-bottom: 30px;">
            <select onchange="window.location.href='?lang='+this.value+'&url=auth/register'" style="padding: 12px 16px; border-radius: 10px; border: 2px solid #e5e7eb; background: white; cursor: pointer; font-weight: 600; width: 100%; font-size: 15px;">
                <?php foreach (Language::getLanguages() as $code => $name): ?>
                    <option value="<?php echo $code; ?>" <?php echo Language::getCurrentLanguage() === $code ? 'selected' : ''; ?>>
                        <?php echo $name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <p style="text-align: center; color: #6b7280; margin-bottom: 30px;">
            <?php echo __('auth.create_and_start'); ?>
        </p>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/index.php?url=auth/register">
            <div class="form-group">
                <label>👤 <?php echo __('auth.first_name'); ?></label>
                <input type="text" name="first_name" required>
            </div>
            
            <div class="form-group">
                <label>👤 <?php echo __('auth.last_name'); ?></label>
                <input type="text" name="last_name" required>
            </div>
            
            <div class="form-group">
                <label>📧 <?php echo __('auth.email'); ?></label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>🔑 <?php echo __('auth.password'); ?></label>
                <input type="password" name="password" required minlength="6">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                🎉 <?php echo __('auth.create_account'); ?>
            </button>
        </form>
        
        <p class="auth-link">
            <?php echo __('auth.already_have_account'); ?> 
            <a href="/index.php?url=auth/login"><?php echo __('auth.login'); ?> →</a>
        </p>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
