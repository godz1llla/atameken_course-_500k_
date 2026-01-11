# 🚀 БЫСТРЫЙ СТАРТ

## 1️⃣ ИМПОРТ БАЗЫ ДАННЫХ
```bash
mysql -u root -p
```
Затем:
```sql
CREATE DATABASE lms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lms_db;
SOURCE /home/hubtech/Documents/LMSSS_AKUUU/database/schema.sql;
EXIT;
```

## 2️⃣ НАСТРОЙКА
Откройте `config/config.php` и настройте:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'lms_db');
define('DB_USER', 'root');          // ← ваш пользователь
define('DB_PASS', '');              // ← ваш пароль
```

## 3️⃣ ЗАПУСК
```bash
cd /home/hubtech/Documents/LMSSS_AKUUU
php -S localhost:8000
```

## 4️⃣ ОТКРЫТЬ В БРАУЗЕРЕ
```
http://localhost:8000/index.php?url=auth/login
```

## 5️⃣ ВОЙТИ

**Аккаунт 1 (Основной админ):**
```
Email: admin@lms.com
Password: password
```

**Аккаунт 2 (Админ Габи):**
```
Email: gabi@lms.com
Password: password
```

## ✅ ГОТОВО!

---

## 📌 ВАЖНЫЕ URL:

**Авторизация:**
- Вход: `/index.php?url=auth/login`
- Регистрация: `/index.php?url=auth/register`

**Администратор:**
- Dashboard: `/index.php?url=admin/dashboard`
- Пользователи: `/index.php?url=admin/users`
- Курсы: `/index.php?url=admin/courses`

**Студент:**
- Dashboard: `/index.php?url=student/dashboard`
- Достижения: `/index.php?url=student/achievements`
- Сертификаты: `/index.php?url=student/certificates`

**Преподаватель:**
- Dashboard: `/index.php?url=teacher/dashboard`
- Сообщения: `/index.php?url=teacher/messages`

---

## 🔧 ЕСЛИ НЕ РАБОТАЕТ:

1. **Ошибка подключения к БД:**
   - Проверьте `config/config.php`
   - Убедитесь что MySQL запущен

2. **404 ошибка:**
   - Проверьте URL (должен быть `/index.php?url=...`)
   - Убедитесь что запустили из корня проекта

3. **Картинки не грузятся:**
   - Убедитесь что папки созданы: `public/uploads/`
   - Дайте права: `chmod -R 755 public/uploads/`

4. **Стили не работают:**
   - Проверьте что есть файл `public/css/style.css`
   - Откройте в браузере: `http://localhost:8000/public/css/style.css`

