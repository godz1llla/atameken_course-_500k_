<?php $title = __('admin.create_achievement'); include 'views/layouts/header.php'; ?>

<div class="container">
    <h1>🏆 <?php echo __('admin.create_achievement'); ?></h1>
    
    <form method="POST" enctype="multipart/form-data" class="form">
        <div class="form-group">
            <label>📝 <?php echo __('course.title'); ?></label>
            <input type="text" name="title" required>
        </div>
        
        <div class="form-group">
            <label>📄 <?php echo __('course.description'); ?></label>
            <textarea name="description" rows="3" required></textarea>
        </div>
        
        <div class="form-group">
            <label>🖼️ Icon</label>
            <input type="file" name="icon" accept="image/*">
        </div>
        
        <div class="form-group">
            <label>📋 Condition Type</label>
            <select name="condition_type" required>
                <option value="course_complete">✅ Course Complete</option>
                <option value="courses_count">📊 Courses Count</option>
                <option value="test_score">💯 Test Score</option>
                <option value="perfect_test">⭐ Perfect Test</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>🎯 Condition Value</label>
            <input type="text" name="condition_value" placeholder="e.g., course_id or score number" required>
            <small style="color: #6b7280; display: block; margin-top: 5px;">
                📌 For course_complete: enter course ID<br>
                📌 For courses_count: enter number of courses<br>
                📌 For test_score: enter minimum score (0-100)<br>
                📌 For perfect_test: leave any value
            </small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 <?php echo __('common.create'); ?></button>
            <a href="/index.php?url=admin/achievements" class="btn btn-secondary">❌ <?php echo __('common.cancel'); ?></a>
        </div>
    </form>
</div>

<?php include 'views/layouts/footer.php'; ?>
