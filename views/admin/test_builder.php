<?php $title = __('admin.create_test'); include 'views/layouts/header.php'; ?>

<div class="container">
    <h1>📝 <?php echo __('admin.create_test'); ?> - <?php echo $lesson['title'] ?? $course['title']; ?></h1>
    
    <form id="testForm" method="POST" class="form">
        <div class="form-group">
            <label>📖 <?php echo __('course.title'); ?></label>
            <input type="text" name="title" required>
        </div>
        
        <div class="form-group">
            <label>📝 <?php echo __('course.description'); ?></label>
            <textarea name="description" rows="3"></textarea>
        </div>
        
        <div class="form-group">
            <label>💯 <?php echo __('test.passing_score'); ?> (%)</label>
            <input type="number" name="passing_score" value="70" min="0" max="100" required>
        </div>
        
        <div class="form-group">
            <label>⏱ <?php echo __('test.time_limit'); ?> (<?php echo __('time.minutes'); ?>)</label>
            <input type="number" name="time_limit" min="0">
            <small style="color: #6b7280;"><?php echo __('admin.leave_empty_no_limit'); ?></small>
        </div>
        
        <div class="form-group">
            <label>🔄 <?php echo __('test.max_attempts'); ?></label>
            <input type="number" name="max_attempts" min="1">
            <small style="color: #6b7280;"><?php echo __('admin.leave_empty_unlimited'); ?></small>
        </div>
        
        <hr style="margin: 40px 0; border: none; border-top: 2px solid var(--light-gray);">
        
        <h2>📋 <?php echo __('admin.questions'); ?></h2>
        
        <div id="questionsContainer"></div>
        
        <button type="button" onclick="addQuestion()" class="btn btn-success">➕ <?php echo __('admin.add_question'); ?></button>
        
        <div class="form-actions" style="margin-top: 40px;">
            <button type="submit" class="btn btn-primary">💾 <?php echo __('common.save'); ?></button>
            <a href="/index.php?url=admin/manageCourse/<?php echo $course['id'] ?? ''; ?>" class="btn btn-secondary">❌ <?php echo __('common.cancel'); ?></a>
        </div>
    </form>
</div>

<script>
let questionCount = 0;

function addQuestion() {
    questionCount++;
    const container = document.getElementById('questionsContainer');
    
    const questionDiv = document.createElement('div');
    questionDiv.className = 'question-builder';
    questionDiv.id = 'question_' + questionCount;
    questionDiv.innerHTML = `
        <div style="background: #f9fafb; padding: 30px; border-radius: 12px; margin-bottom: 25px; border-left: 5px solid var(--primary);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>❓ <?php echo __('test.question'); ?> ${questionCount}</h3>
                <button type="button" onclick="removeQuestion(${questionCount})" class="btn btn-danger btn-small">🗑️ <?php echo __('common.delete'); ?></button>
            </div>
            
            <div class="form-group">
                <label><?php echo __('admin.question_text'); ?></label>
                <textarea name="questions[${questionCount}][text]" rows="3" required></textarea>
            </div>
            
            <div class="form-group">
                <label><?php echo __('admin.question_type'); ?></label>
                <select name="questions[${questionCount}][type]">
                    <option value="single_choice">☝️ <?php echo __('admin.single_choice'); ?></option>
                    <option value="multiple_choice">✋ <?php echo __('admin.multiple_choice'); ?></option>
                </select>
            </div>
            
            <div class="answers-container" id="answers_${questionCount}">
                <h4><?php echo __('admin.answers'); ?></h4>
            </div>
            
            <button type="button" onclick="addAnswer(${questionCount})" class="btn btn-secondary btn-small">➕ <?php echo __('admin.add_answer'); ?></button>
        </div>
    `;
    
    container.appendChild(questionDiv);
    
    // Добавляем 2 ответа по умолчанию
    addAnswer(questionCount);
    addAnswer(questionCount);
}

function removeQuestion(questionId) {
    document.getElementById('question_' + questionId).remove();
}

let answerCounts = {};

function addAnswer(questionId) {
    if (!answerCounts[questionId]) answerCounts[questionId] = 0;
    answerCounts[questionId]++;
    
    const container = document.getElementById('answers_' + questionId);
    const answerCount = answerCounts[questionId];
    
    const answerDiv = document.createElement('div');
    answerDiv.className = 'answer-item';
    answerDiv.id = `answer_${questionId}_${answerCount}`;
    answerDiv.innerHTML = `
        <div style="display: flex; gap: 10px; align-items: center; margin: 10px 0; padding: 15px; background: white; border-radius: 8px;">
            <input type="text" name="questions[${questionId}][answers][${answerCount}][text]" placeholder="<?php echo __('admin.answer_text'); ?>" style="flex: 1;" required>
            <label style="display: flex; align-items: center; gap: 5px; white-space: nowrap;">
                <input type="checkbox" name="questions[${questionId}][answers][${answerCount}][is_correct]" value="1">
                ✅ <?php echo __('admin.correct'); ?>
            </label>
            <button type="button" onclick="removeAnswer(${questionId}, ${answerCount})" class="btn btn-danger btn-small">🗑️</button>
        </div>
    `;
    
    container.appendChild(answerDiv);
}

function removeAnswer(questionId, answerId) {
    document.getElementById(`answer_${questionId}_${answerId}`).remove();
}

// Добавляем первый вопрос автоматически
addQuestion();
</script>

<?php include 'views/layouts/footer.php'; ?>

