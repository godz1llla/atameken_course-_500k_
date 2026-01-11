# Инструкция по установке LMS System

## Шаг 1: Требования

- PHP 7.4 или выше
- MySQL 5.7 или выше  
- Веб-сервер (Apache, Nginx или встроенный PHP сервер)
- Расширения PHP: PDO, pdo_mysql, mbstring, json

## Шаг 2: Установка базы данных

1. Создайте базу данных MySQL:
```sql
CREATE DATABASE lms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Импортируйте схему базы данных:
```bash
mysql -u root -p lms_db < database/schema.sql
```

Или через phpMyAdmin:
- Откройте phpMyAdmin
- Выберите базу данных `lms_db`
- Перейдите во вкладку "Импорт"
- Выберите файл `database/schema.sql`
- Нажмите "Вперед"

## Шаг 3: Настройка конфигурации

Отредактируйте файл `config/config.php`:

```php
define('BASE_URL', 'http://localhost'); // Ваш URL
define('DB_HOST', 'localhost');
define('DB_NAME', 'lms_db');
define('DB_USER', 'root'); // Ваш пользователь MySQL
define('DB_PASS', ''); // Ваш пароль MySQL
```

## Шаг 4: Права доступа

Убедитесь, что директория `public/uploads/` доступна для записи:

```bash
chmod -R 755 public/uploads/
```

## Шаг 5: Запуск

### Вариант 1: Встроенный PHP сервер (для разработки)

```bash
cd /path/to/LMSSS_AKUUU
php -S localhost:8000
```

Откройте браузер: `http://localhost:8000/index.php?url=auth/login`

### Вариант 2: Apache/Nginx

Разместите проект в корневой директории вашего веб-сервера:
- Apache: `/var/www/html/` или `htdocs/`
- Nginx: настройте root на директорию проекта

Откройте браузер: `http://localhost/index.php?url=auth/login`

## Шаг 6: Вход в систему

**Аккаунт администратора по умолчанию:**
- Email: `admin@lms.com`
- Password: `password`

⚠️ **ВАЖНО:** Сразу после входа смените пароль администратора!

## Шаг 7: Настройка (опционально)

### Создание преподавателей
1. Войдите как администратор
2. Перейдите в "Users"
3. Нажмите "Create User"
4. Выберите роль "Teacher"

### Создание курсов
1. Перейдите в "Courses"
2. Нажмите "Create Course"
3. Заполните информацию о курсе
4. Нажмите "Manage" для добавления модулей и уроков

### Добавление YouTube видео
При создании урока вставьте полную ссылку YouTube:
- `https://www.youtube.com/watch?v=VIDEO_ID`
- `https://youtu.be/VIDEO_ID`

Система автоматически создаст embed плеер.

### Создание достижений
1. Перейдите в "Achievements"
2. Нажмите "Create Achievement"
3. Выберите тип условия:
   - `course_complete` - завершение конкретного курса (укажите ID курса)
   - `courses_count` - количество завершенных курсов (укажите число)
   - `test_score` - минимальный балл (укажите процент)
   - `perfect_test` - идеальный результат теста (100%)

### Назначение курсов студентам
1. Перейдите в "Enrollments"
2. Выберите студента
3. Выберите курс
4. Нажмите "Enroll"

## Устранение неполадок

### Ошибка подключения к базе данных
- Проверьте параметры в `config/config.php`
- Убедитесь, что MySQL сервер запущен
- Проверьте права пользователя БД

### Ошибка загрузки файлов
- Проверьте права доступа к `public/uploads/`
- Проверьте настройки `upload_max_filesize` в php.ini

### Видео YouTube не отображается
- Проверьте формат ссылки
- Убедитесь, что видео доступно и не ограничено

### Страница не загружается
- Проверьте правильность URL
- Формат: `/index.php?url=controller/method`
- Проверьте логи PHP на наличие ошибок

## Структура URL

Система использует GET параметры для роутинга:

```
/index.php?url=controller/method/param
```

Примеры:
- Авторизация: `/index.php?url=auth/login`
- Регистрация: `/index.php?url=auth/register`
- Дашборд студента: `/index.php?url=student/dashboard`
- Дашборд преподавателя: `/index.php?url=teacher/dashboard`
- Дашборд администратора: `/index.php?url=admin/dashboard`
- Курс: `/index.php?url=student/course/1`
- Урок: `/index.php?url=student/lesson/1`

## Дополнительная настройка

### Изменение максимального размера загружаемых файлов

В `config/config.php`:
```php
define('MAX_FILE_SIZE', 10485760); // 10MB в байтах
```

В `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Настройка временной зоны

В `config/config.php`:
```php
date_default_timezone_set('Europe/Moscow'); // Ваша временная зона
```

## Безопасность

После установки рекомендуется:

1. Сменить пароль администратора
2. Создать нового администратора и удалить дефолтного
3. Настроить HTTPS (SSL сертификат)
4. Ограничить доступ к `config/` через веб-сервер
5. Регулярно делать резервные копии базы данных

## Резервное копирование

### База данных
```bash
mysqldump -u root -p lms_db > backup_$(date +%Y%m%d).sql
```

### Файлы
```bash
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz public/uploads/
```

## Поддержка

При возникновении проблем:
1. Проверьте логи PHP
2. Проверьте логи MySQL
3. Убедитесь, что все требования выполнены
4. Создайте issue в репозитории с описанием проблемы

