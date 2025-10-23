# Laravel 12 Backend Template

Шаблон для быстрого старта разработки бэкенд-приложений на **Laravel 12**.

---

## 📦 Требования

Для запуска проекта необходимо установить:

- **PHP** ≥ 8.3
- **Node.js** ≥ 20
- **MySQL** ≥ 8.0 или **PostgreSQL** ≥ 15

---

## ⚙️ Настройка OpenServer

Для запуска проекта в среде OpenServer выполните следующие шаги:

1. Скопируйте папку `.osp.example` в `.osp`.
2. В файлах `.osp/project.ini`, `.osp/tasks.ini` и в имени конфигурационного файла `.osp/Nginx/domain.loc.conf` замените `domain.loc` на ваш локальный домен.
3. В настройках OpenServer активируйте модули **Nginx** и **PHP-FCGI**.
4. Перезапустите OpenServer для применения изменений.

---

## 🚀 Установка и запуск проекта

### 1. Клонирование репозитория

```bash
git clone [URL репозитория]
cd [название-папки-проекта]
```

### 2. Установка зависимостей PHP

```bash
composer install
```

### 3. Установка зависимостей JavaScript

```bash
npm install
```

### 4. Настройка окружения

```bash
cp .env.example .env
```

- Укажите параметры подключения к БД в файле `.env`.
- Сгенерируйте ключ приложения:

```bash
php artisan key:generate
```

- Создайте символьную ссылку для публичного доступа к хранилищу:

```bash
php artisan storage:link
```

### 5. Запуск миграций базы данных

```bash
php artisan migrate
```

### 6. Создание администратора Moonshine

```bash
php artisan moonshine:create-user
```

Команда использует переменные окружения:

- `MOONSHINE_USERNAME`  
- `MOONSHINE_NAME`  
- `MOONSHINE_PASSWORD`  

Или вы можете передать параметры вручную:

```bash
php artisan moonshine:create-user --u=admin --N="Admin Name" --p=secret
```

---

## 🖥 Локальный запуск

1. Запуск Laravel-сервера:
```bash
php artisan serve
```

2. Запуск Vite-сборщика:
```bash
npm run dev
```

Moonshine Admin Panel будет доступна по адресу:  
[http://localhost:8000/admin](http://localhost:8000/admin)

---

## ⚡️ Оптимизация и очереди

### Оптимизация приложения:

```bash
php artisan optimize
```

### Запуск обработчика очередей:

```bash
php artisan queue:work
```

---

## 🛠 Полезные команды

- Сборка фронтенда для production:
```bash
npm run build
```

- Проверка кода линтером:
```bash
npm run lint
```

- Автоматическое форматирование кода:
```bash
npm run format
```

---
