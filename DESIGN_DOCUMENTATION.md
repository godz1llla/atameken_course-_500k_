# 🎨 ПОЛНАЯ ДОКУМЕНТАЦИЯ ПО ДИЗАЙНУ ATAMEKEN STUDY CRM

## 📋 ОГЛАВЛЕНИЕ

1. [Цветовая палитра](#цветовая-палитра)
2. [Типографика](#типографика)
3. [CSS Переменные](#css-переменные)
4. [Компоненты UI](#компоненты-ui)
5. [Адаптивность](#адаптивность)
6. [Анимации и эффекты](#анимации-и-эффекты)
7. [Структура стилей](#структура-стилей)
8. [Примеры использования](#примеры-использования)

---

## 🎨 ЦВЕТОВАЯ ПАЛИТРА

### Основные цвета

Все цвета определены в CSS переменных (`:root`) в файле `public/css/style.css`:

```css
:root {
    --primary: #6366f1;          /* Основной индиго цвет */
    --primary-dark: #4f46e5;    /* Темный индиго (для hover) */
    --primary-light: #818cf8;   /* Светлый индиго */
    --secondary: #ec4899;       /* Розовый цвет */
    --success: #10b981;          /* Зеленый (успех) */
    --warning: #f59e0b;          /* Оранжевый (предупреждение) */
    --danger: #ef4444;           /* Красный (опасность) */
    --dark: #1f2937;             /* Темно-серый текст */
    --gray: #6b7280;             /* Серый текст */
    --light-gray: #f3f4f6;       /* Светло-серый фон */
    --white: #ffffff;             /* Белый */
}
```

### Градиенты

**Основной градиент (Primary → Secondary):**
```css
background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
/* Используется для: кнопок, логотипа, акцентов */
```

**Градиент фона:**
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
/* Фон всей страницы, фиксированный */
```

### Цвета для статусов

- **Успех (Success):** `#10b981` - зеленый
- **Предупреждение (Warning):** `#f59e0b` - оранжевый
- **Опасность (Danger):** `#ef4444` - красный
- **Информация (Info):** `var(--primary)` - индиго

---

## 📝 ТИПОГРАФИКА

### Шрифт

**Основной шрифт:** `Inter` (Google Fonts)
```css
font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
```

**Подключение:**
```css
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
```

### Начертания шрифта

Доступные веса: `300`, `400`, `500`, `600`, `700`, `800`, `900`

### Размеры шрифтов

**Заголовки:**
```css
h1 {
    font-size: 2.5rem;     /* 40px */
    font-weight: 900;
}

h2 {
    font-size: 2rem;      /* 32px */
    font-weight: 800;
}

h3 {
    font-size: 1.5rem;    /* 24px */
    font-weight: 700;
}

h4 {
    font-size: 1.25rem;    /* 20px */
    font-weight: 700;
}
```

**Текст:**
```css
/* Основной текст */
font-size: 16px;
line-height: 1.6;

/* Мелкий текст */
font-size: 14px;

/* Крупный текст */
font-size: 18px;
```

### Градиентный текст

```css
.gradient-text {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
```

---

## 🔧 CSS ПЕРЕМЕННЫЕ

### Тени

```css
--shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
--shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
```

### Радиусы скругления

```css
--radius: 16px;  /* Основной радиус для карточек и элементов */
```

Использование:
- Маленькие элементы: `8px`, `10px`
- Карточки, формы: `12px`, `16px`
- Крупные элементы: `20px`, `24px`

### Переходы (Transitions)

```css
--transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
```

Стандартный плавный переход для всех интерактивных элементов.

---

## 🧩 КОМПОНЕНТЫ UI

### Кнопки (Buttons)

#### Основная кнопка (Primary)
```html
<a href="#" class="btn btn-primary">Текст кнопки</a>
```

```css
.btn-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    padding: 12px 28px;
    border-radius: 10px;
    font-weight: 700;
    text-decoration: none;
    transition: var(--transition);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(99, 102, 241, 0.3);
}
```

#### Варианты кнопок

- `.btn-primary` - основная (градиент)
- `.btn-success` - успех (зеленая)
- `.btn-warning` - предупреждение (оранжевая)
- `.btn-danger` - опасность (красная)
- `.btn-outline` - обводка (прозрачный фон)
- `.btn-sm` - маленькая
- `.btn-lg` - большая

#### Примеры использования

```html
<!-- Основная кнопка -->
<a href="#" class="btn btn-primary">Создать</a>

<!-- Кнопка успеха -->
<button class="btn btn-success">Сохранить</button>

<!-- Кнопка опасности -->
<a href="#" class="btn btn-danger">Удалить</a>

<!-- Кнопка с обводкой -->
<button class="btn btn-outline">Отмена</button>

<!-- Маленькая кнопка -->
<a href="#" class="btn btn-primary btn-sm">Редактировать</a>
```

### Карточки (Cards)

#### Основная карточка
```html
<div class="card">
    <div class="card-header">
        <h3>Заголовок</h3>
    </div>
    <div class="card-body">
        Содержимое карточки
    </div>
</div>
```

```css
.card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 30px;
    transition: var(--transition);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}
```

#### Варианты карточек

- `.card` - обычная карточка
- `.stat-card` - карточка статистики
- `.course-card` - карточка курса
- `.achievement-card` - карточка достижения
- `.message-card` - карточка сообщения

### Формы (Forms)

#### Группа полей
```html
<div class="form-group">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>
</div>
```

```css
.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark);
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 12px 18px;
    border: 2px solid var(--light-gray);
    border-radius: 10px;
    font-size: 15px;
    transition: var(--transition);
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}
```

### Таблицы (Tables)

```html
<table class="data-table">
    <thead>
        <tr>
            <th>Название</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Данные</td>
            <td>
                <div class="actions">
                    <a href="#" class="btn btn-sm btn-primary">Редактировать</a>
                </div>
            </td>
        </tr>
    </tbody>
</table>
```

**Адаптивная таблица (мобильная версия):**
```css
@media (max-width: 768px) {
    .data-table {
        display: block;
    }
    
    .data-table thead {
        display: none;
    }
    
    .data-table tbody tr {
        display: block;
        border: 2px solid var(--light-gray);
        border-radius: var(--radius);
        margin-bottom: 20px;
        padding: 20px;
    }
    
    .data-table td {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
    }
    
    .data-table td::before {
        content: attr(data-label);
        font-weight: 800;
        color: var(--primary);
    }
}
```

### Навигация (Navbar)

```html
<nav class="navbar">
    <div class="navbar-container">
        <a href="#" class="logo">🎓 LMS System</a>
        <div class="nav-links">
            <a href="#">🏠 <span>Главная</span></a>
            <a href="#">📚 <span>Курсы</span></a>
        </div>
    </div>
</nav>
```

**Стили:**
- Стеклянный морфизм (backdrop-filter blur)
- Sticky позиционирование (прилипает к верху)
- Градиентный логотип
- Hover эффекты на ссылках
- Мобильное меню с кнопкой-гамбургером

### Статусы (Badges)

```html
<span class="badge badge-success">Активен</span>
<span class="badge badge-warning">В ожидании</span>
<span class="badge badge-danger">Неактивен</span>
```

```css
.badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
}

.badge-success {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.badge-warning {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
}

.badge-danger {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
}
```

---

## 📱 АДАПТИВНОСТЬ

### Breakpoints (Точки перелома)

```css
/* Desktop (десктоп) */
/* По умолчанию, без медиа-запросов */

/* Tablet (планшет) */
@media (max-width: 1024px) {
    /* Стили для планшета */
}

/* Mobile Large (большой мобильный) */
@media (max-width: 768px) {
    /* Стили для большого мобильного */
}

/* Mobile (мобильный) */
@media (max-width: 480px) {
    /* Стили для мобильного */
}
```

### Адаптивная сетка

**Сетка статистики:**
```css
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
}

@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
```

**Сетка курсов:**
```css
.courses-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

@media (max-width: 1024px) {
    .courses-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .courses-grid {
        grid-template-columns: 1fr;
    }
}
```

### Мобильное меню

```javascript
// В footer.php
function toggleMobileMenu() {
    const navLinks = document.getElementById('navLinks');
    navLinks.classList.toggle('active');
}
```

**CSS для мобильного меню:**
```css
@media (max-width: 768px) {
    .mobile-menu-toggle {
        display: block;
    }
    
    .nav-links {
        position: fixed;
        top: 70px;
        left: -100%;
        width: 100%;
        flex-direction: column;
        background: white;
        box-shadow: var(--shadow-lg);
        transition: left 0.3s;
        padding: 20px;
    }
    
    .nav-links.active {
        left: 0;
    }
}
```

---

## ✨ АНИМАЦИИ И ЭФФЕКТЫ

### Hover эффекты

**Кнопки:**
```css
.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(99, 102, 241, 0.3);
}
```

**Карточки:**
```css
.card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}
```

**Ссылки навигации:**
```css
.nav-links a:hover {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}
```

### Плавные переходы

Все интерактивные элементы используют:
```css
transition: var(--transition);
/* Что равно: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) */
```

### Анимации появления

**Fade In:**
```css
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.5s ease-out;
}
```

**Bounce (для иконок):**
```css
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.bounce {
    animation: bounce 2s ease-in-out infinite;
}
```

### Стеклянный морфизм (Glassmorphism)

```css
.glass {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
}
```

**Использование:**
- Навбар
- Формы аутентификации
- Модальные окна
- Карточки

---

## 📐 СТРУКТУРА СТИЛЕЙ

### Организация CSS файлов

```
public/css/
├── style.css       # Основные стили приложения (1800+ строк)
└── landing.css     # Стили для landing page
```

### Структура style.css

1. **CSS Переменные** (`:root`)
2. **Базовые стили** (body, container, reset)
3. **Навигация** (navbar, nav-links, мобильное меню)
4. **Компоненты** (кнопки, карточки, формы)
5. **Страницы** (dashboard, course, lesson, test)
6. **Адаптивность** (media queries)
7. **Утилиты** (helpers, utilities)

### Соглашения по именованию

**Классы:**
- `.btn-*` - кнопки
- `.card-*` - карточки
- `.form-*` - формы
- `.nav-*` - навигация
- `.stat-*` - статистика
- `.course-*` - курсы
- `.lesson-*` - уроки
- `.message-*` - сообщения

**Модификаторы:**
- `-primary`, `-success`, `-warning`, `-danger` - цвета
- `-sm`, `-lg` - размеры
- `-outline` - обводка

---

## 💡 ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ

### Страница Dashboard

```html
<div class="container">
    <div class="page-header">
        <h1>📊 Дашборд</h1>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">150</div>
            <div class="stat-label">Студентов</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">25</div>
            <div class="stat-label">Курсов</div>
        </div>
    </div>
    
    <div class="courses-grid">
        <div class="course-card">
            <img src="..." alt="...">
            <div class="course-body">
                <h3>Название курса</h3>
                <p>Описание курса</p>
                <a href="#" class="btn btn-primary">Открыть</a>
            </div>
        </div>
    </div>
</div>
```

### Форма создания

```html
<div class="container">
    <div class="page-header">
        <h1>➕ Создать курс</h1>
    </div>
    
    <div class="card">
        <form class="form">
            <div class="form-group">
                <label for="title">Название</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="description">Описание</label>
                <textarea id="description" name="description" rows="5"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Создать</button>
                <a href="#" class="btn btn-outline">Отмена</a>
            </div>
        </form>
    </div>
</div>
```

### Карточка статистики

```html
<div class="stat-card">
    <div class="stat-icon">👥</div>
    <div class="stat-number">150</div>
    <div class="stat-label">Всего студентов</div>
    <div class="stat-change">
        <span class="badge badge-success">+12%</span>
    </div>
</div>
```

```css
.stat-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: var(--radius);
    padding: 30px;
    text-align: center;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.stat-number {
    font-size: 48px;
    font-weight: 900;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-label {
    color: var(--gray);
    font-size: 14px;
    margin-top: 10px;
}
```

### Чат интерфейс

```html
<div class="messages-container">
    <div class="message sent">
        <div class="message-content">Привет!</div>
        <div class="message-time">10:30</div>
    </div>
    <div class="message received">
        <div class="message-content">Здравствуйте!</div>
        <div class="message-time">10:31</div>
    </div>
</div>
```

```css
.message {
    padding: 15px 20px;
    border-radius: 18px;
    margin-bottom: 15px;
    max-width: 70%;
}

.message.sent {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    margin-left: auto;
}

.message.received {
    background: var(--light-gray);
    color: var(--dark);
}
```

---

## 🎯 ОСОБЕННОСТИ ДИЗАЙНА

### 1. Glassmorphism (Стеклянный морфизм)

Эффект прозрачного стекла с размытием:
- Навбар
- Формы аутентификации
- Карточки
- Модальные окна

### 2. Градиенты

Используются для:
- Кнопок
- Логотипа
- Фона страницы
- Текста
- Акцентов

### 3. Тени и глубина

Многоуровневые тени создают ощущение глубины:
- Карточки: `--shadow`
- Hover состояния: `--shadow-lg`
- Навбар: кастомная тень

### 4. Плавные переходы

Все интерактивные элементы имеют плавные переходы:
- Кнопки: `translateY` при hover
- Карточки: подъем при hover
- Ссылки: изменение цвета

### 5. Адаптивность

Полностью адаптивный дизайн для:
- Десктоп (1920px+)
- Планшет (1024px - 768px)
- Мобильный (768px - 480px)
- Маленький мобильный (480px и меньше)

### 6. Эмодзи как иконки

Используются эмодзи для навигации:
- 🏠 Главная
- 📚 Курсы
- 👥 Пользователи
- 📊 Статистика
- 💬 Сообщения
- 🎓 Сертификаты

---

## 📱 АДАПТИВНЫЕ ПРАВИЛА

### Таблицы становятся карточками

На мобильных устройствах таблицы преобразуются в карточки:
```css
@media (max-width: 768px) {
    .data-table tbody tr {
        display: block;
        border: 2px solid var(--light-gray);
        border-radius: var(--radius);
        padding: 20px;
    }
    
    .data-table td::before {
        content: attr(data-label);
    }
}
```

**Использование в HTML:**
```html
<td data-label="Название">Курс по PHP</td>
```

### Горизонтальные формы становятся вертикальными

```css
@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
```

### Сетка становится колонной

Все grid элементы на мобильных становятся одноколонными:
```css
@media (max-width: 768px) {
    .stats-grid,
    .courses-grid,
    .achievements-grid {
        grid-template-columns: 1fr;
    }
}
```

---

## 🚀 ПРОИЗВОДИТЕЛЬНОСТЬ

### Оптимизации CSS

1. **GPU ускорение анимаций:**
   - Используется `transform` вместо `top/left`
   - Используется `will-change` для анимируемых элементов

2. **Минимум перерисовок:**
   - Используются CSS transitions вместо JavaScript
   - Debounce для событий scroll/resize

3. **Ленивая загрузка:**
   - Изображения загружаются по требованию
   - Используется `loading="lazy"`

---

## 📋 ЧЕКЛИСТ ДЛЯ РАЗРАБОТЧИКОВ

При создании новых компонентов проверяй:

- [ ] Используются CSS переменные для цветов
- [ ] Добавлены hover эффекты
- [ ] Добавлены transition эффекты
- [ ] Адаптивность для мобильных (media queries)
- [ ] Соответствие существующему стилю
- [ ] Используются правильные размеры шрифтов
- [ ] Правильные радиусы скругления
- [ ] Правильные отступы и padding
- [ ] Тени соответствуют дизайн-системе
- [ ] Эмодзи используются для иконок (если подходит)

---

**Дата создания документации:** 2024  
**Версия дизайн-системы:** 1.0  
**Файлы стилей:** `public/css/style.css`, `public/css/landing.css`

---

## 💎 ДОПОЛНИТЕЛЬНЫЕ РЕСУРСЫ

### Полезные инструменты

- **Color Picker:** для выбора цветов
- **CSS Gradient Generator:** для создания градиентов
- **Box Shadow Generator:** для создания теней
- **Responsive Design Checker:** для проверки адаптивности

### Рекомендации

1. Всегда используй CSS переменные для цветов
2. Следуй существующей структуре классов
3. Тестируй на всех устройствах перед коммитом
4. Используй эмодзи для иконок в навигации
5. Поддерживай glassmorphism эффект для карточек и форм

