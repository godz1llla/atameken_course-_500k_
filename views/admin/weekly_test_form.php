<?php $title = __('admin.create_weekly_test'); include 'views/layouts/header.php'; ?>

<div class="container">
    <h1>📅 <?php echo __('admin.create_weekly_test'); ?> - <?php echo $course['title']; ?></h1>
    
    <form id="weeklyTestForm" method="POST" action="/index.php?url=test/createWeeklyTest/<?php echo $course['id']; ?>" class="form">
        <div class="form-group">
            <label>📖 <?php echo __('course.title'); ?></label>
            <input type="text" name="title" required>
        </div>
        
        <div class="form-group">
            <label>📝 <?php echo __('course.description'); ?></label>
            <textarea name="description" rows="3"></textarea>
        </div>
        
        <div class="form-group">
            <label>📆 <?php echo __('admin.week_number'); ?></label>
            <input type="number" name="week_number" min="1" required>
        </div>
        
        <div class="form-group">
            <label>💯 <?php echo __('test.passing_score'); ?> (%)</label>
            <input type="number" name="passing_score" value="70" min="0" max="100" required>
        </div>
        
        <div class="form-group">
            <label>⏱ <?php echo __('test.time_limit'); ?> (<?php echo __('time.minutes'); ?>)</label>
            <input type="number" name="time_limit" min="0">
        </div>
        
        <div class="form-group">
            <label>📅 <?php echo __('admin.deadline'); ?></label>
            <input type="datetime-local" name="deadline">
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_mandatory" checked>
                ⚠️ <?php echo __('admin.mandatory'); ?>
            </label>
        </div>
        
        <hr style="margin: 40px 0; border: none; border-top: 2px solid var(--light-gray);">
        
        <h2>📋 <?php echo __('admin.questions'); ?></h2>
        
        <div id="questionsContainer"></div>
        
        <button type="button" onclick="addQuestion()" class="btn btn-success">➕ <?php echo __('admin.add_question'); ?></button>
        
        <div class="form-actions" style="margin-top: 40px;">
            <button type="submit" class="btn btn-primary">💾 <?php echo __('common.save'); ?></button>
            <a href="/index.php?url=admin/manageCourse/<?php echo $course['id']; ?>" class="btn btn-secondary">❌ <?php echo __('common.cancel'); ?></a>
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
                <button type="button" onclick="removeQuestion(${questionCount})" class="btn btn-danger btn-small">🗑️</button>
            </div>
            
            <div class="form-group">
                <label><?php echo __('admin.question_text'); ?></label>
                <textarea name="questions[${questionCount}][text]" rows="3" required></textarea>
            </div>
            
            <div class="form-group">
                <label><?php echo __('admin.question_type'); ?></label>
                <select name="questions[${questionCount}][type]">
                    <option value="single_choice">☝️ <?php echo __('admin.single_choice'); ?></option>
                </select>
            </div>
            
            <div class="answers-container" id="answers_${questionCount}">
                <h4><?php echo __('admin.answers'); ?></h4>
            </div>
            
            <button type="button" onclick="addAnswer(${questionCount})" class="btn btn-secondary btn-small">➕ <?php echo __('admin.add_answer'); ?></button>
        </div>
    `;
    
    container.appendChild(questionDiv);
    
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
    answerDiv.id = `answer_${questionId}_${answerCount}`;
    answerDiv.innerHTML = `
        <div style="display: flex; gap: 10px; align-items: center; margin: 10px 0; padding: 15px; background: white; border-radius: 8px;">
            <input type="text" name="questions[${questionId}][answers][${answerCount}][text]" placeholder="<?php echo __('admin.answer_text'); ?>" style="flex: 1; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 15px;" required>
            <label style="display: flex; align-items: center; gap: 8px; white-space: nowrap; font-weight: 700;">
                <input type="checkbox" name="questions[${questionId}][answers][${answerCount}][is_correct]" value="1" style="width: 20px; height: 20px;">
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

addQuestion();
</script>

<style>
.question-builder {
    animation: fadeIn 0.4s ease;
}
</style>

<?php include 'views/layouts/footer.php'; ?>

