<!DOCTYPE html>
<html lang="<?php echo Language::getCurrentLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('landing.title'); ?> - atameken course</title>
    <link rel="stylesheet" href="/public/css/landing.css">
</head>
<body>
    
    <!-- Hero Section -->
    <section class="hero">
        <nav class="landing-nav">
            <div class="container">
                <div class="nav-content">
                    <a href="/" class="logo" aria-label="atameken course">
                        <img src="/public/logo.png" alt="atameken course logo">
                        <span>atameken course</span>
                    </a>
                    <div class="nav-right">
                        <select class="lang-select" onchange="window.location.href='?lang='+this.value">
                            <?php foreach (Language::getLanguages() as $code => $name): ?>
                                <option value="<?php echo $code; ?>" <?php echo Language::getCurrentLanguage() === $code ? 'selected' : ''; ?>>
                                    <?php echo $name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <a href="/index.php?url=auth/login" class="btn-nav"><?php echo __('auth.login'); ?></a>
                        <a href="/index.php?url=auth/register" class="btn-primary"><?php echo __('auth.register'); ?></a>
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1><?php echo __('landing.hero_title'); ?></h1>
                    <p><?php echo __('landing.hero_subtitle'); ?></p>
                    <div class="hero-buttons">
                        <a href="/index.php?url=auth/register" class="btn-hero"><?php echo __('landing.get_started'); ?> →</a>
                        <a href="#features" class="btn-hero-outline"><?php echo __('landing.learn_more'); ?></a>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="floating-card">
                        <div class="card-icon">📚</div>
                        <div class="card-text">
                            <div class="card-title"><?php echo __('landing.online_learning'); ?></div>
                            <div class="card-subtitle"><?php echo __('landing.anytime_anywhere'); ?></div>
                        </div>
                    </div>
                    <div class="floating-card" style="animation-delay: 0.2s;">
                        <div class="card-icon">🏆</div>
                        <div class="card-text">
                            <div class="card-title"><?php echo __('landing.achievements'); ?></div>
                            <div class="card-subtitle"><?php echo __('landing.earn_badges'); ?></div>
                        </div>
                    </div>
                    <div class="floating-card" style="animation-delay: 0.4s;">
                        <div class="card-icon">🎓</div>
                        <div class="card-text">
                            <div class="card-title"><?php echo __('landing.practice_test_title'); ?></div>
                            <div class="card-subtitle"><?php echo __('landing.practice_test_subtitle'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-header">
                <h2><?php echo __('landing.why_choose'); ?></h2>
                <p><?php echo __('landing.features_subtitle'); ?></p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3><?php echo __('landing.quality_courses'); ?></h3>
                    <p><?php echo __('landing.quality_courses_desc'); ?></p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">👨‍🏫</div>
                    <h3><?php echo __('landing.expert_teachers'); ?></h3>
                    <p><?php echo __('landing.expert_teachers_desc'); ?></p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3><?php echo __('landing.mobile_learning'); ?></h3>
                    <p><?php echo __('landing.mobile_learning_desc'); ?></p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3><?php echo __('landing.progress_tracking'); ?></h3>
                    <p><?php echo __('landing.progress_tracking_desc'); ?></p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💬</div>
                    <h3><?php echo __('landing.direct_messaging'); ?></h3>
                    <p><?php echo __('landing.direct_messaging_desc'); ?></p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🏆</div>
                    <h3><?php echo __('landing.achievements_system'); ?></h3>
                    <p><?php echo __('landing.achievements_system_desc'); ?></p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-container">
                <div class="stat-item">
                    <div class="stat-number" data-count="500">0</div>
                    <div class="stat-label"><?php echo __('landing.students'); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="50">0</div>
                    <div class="stat-label"><?php echo __('landing.courses_available'); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="30">0</div>
                    <div class="stat-label"><?php echo __('landing.expert_instructors'); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="95">0</div>
                    <div class="stat-label"><?php echo __('landing.success_rate'); ?>%</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- How It Works -->
    <section class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2><?php echo __('landing.how_it_works'); ?></h2>
                <p><?php echo __('landing.how_it_works_subtitle'); ?></p>
            </div>
            
            <div class="steps-container">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-icon">📝</div>
                    <h3><?php echo __('landing.step1_title'); ?></h3>
                    <p><?php echo __('landing.step1_desc'); ?></p>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-icon">📚</div>
                    <h3><?php echo __('landing.step2_title'); ?></h3>
                    <p><?php echo __('landing.step2_desc'); ?></p>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-icon">📈</div>
                    <h3><?php echo __('landing.step3_title'); ?></h3>
                    <p><?php echo __('landing.step3_desc'); ?></p>
                </div>
                
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-icon">🎓</div>
                    <h3><?php echo __('landing.step4_title'); ?></h3>
                    <p><?php echo __('landing.step4_desc'); ?></p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2><?php echo __('landing.ready_to_start'); ?></h2>
                <p><?php echo __('landing.join_thousands'); ?></p>
                <a href="/index.php?url=auth/register" class="btn-cta"><?php echo __('landing.start_learning_free'); ?> 🚀</a>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>atameken course</h3>
                    <p><?php echo __('landing.footer_about'); ?></p>
                </div>
                
                <div class="footer-section">
                    <h4><?php echo __('landing.quick_links'); ?></h4>
                    <a href="/index.php?url=auth/login"><?php echo __('auth.login'); ?></a>
                    <a href="/index.php?url=auth/register"><?php echo __('auth.register'); ?></a>
                </div>
                
                <div class="footer-section">
                    <h4><?php echo __('landing.language'); ?></h4>
                    <?php foreach (Language::getLanguages() as $code => $name): ?>
                        <a href="?lang=<?php echo $code; ?>"><?php echo $name; ?></a>
                    <?php endforeach; ?>
                </div>
                
                <div class="footer-section">
                    <h4><?php echo __('landing.contact_title'); ?></h4>
                    <a href="tel:<?php echo __('landing.phone_number_link'); ?>">📞 <?php echo __('landing.contact_phone_label'); ?>: <?php echo __('landing.phone_number'); ?></a>
                    <a href="https://www.instagram.com/atameken.course/" target="_blank" rel="noopener">📸 <?php echo __('landing.contact_instagram_label'); ?>: <?php echo __('landing.instagram_handle'); ?></a>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> atameken course. <?php echo __('landing.all_rights'); ?></p>
            </div>
        </div>
    </footer>
    
    <script>
    // Counter Animation
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200;
    
    const observerOptions = {
        threshold: 0.5
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = +counter.getAttribute('data-count');
                const increment = target / speed;
                let count = 0;
                
                const updateCounter = () => {
                    count += increment;
                    if (count < target) {
                        counter.textContent = Math.ceil(count);
                        setTimeout(updateCounter, 10);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                updateCounter();
                observer.unobserve(counter);
            }
        });
    }, observerOptions);
    
    counters.forEach(counter => observer.observe(counter));
    
    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    </script>
</body>
</html>

