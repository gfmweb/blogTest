# Blog Test

PHP-блог на нативном PHP 8.3 с Smarty, MySQL и маршрутизацией. Запуск только через Docker.

## Требования

- **Docker** и **Docker Compose**
- Git

Проверка установки:

```bash
docker --version
docker compose version
```

---

## 1. Клонирование репозитория

```bash
git clone https://github.com/gfmweb/blogTest.git blogTest
cd blogTest
```

---

## 2. Настройка окружения

Создайте файл `.env` на основе примера:

```bash
cp .env.example .env
```

При необходимости отредактируйте `.env`. Минимально нужны переменные:

| Переменная | Описание | Пример |
|------------|----------|--------|
| `MYSQL_ROOT_PASSWORD` | Пароль root MySQL | `root` |
| `MYSQL_DATABASE` | Имя базы данных | `blog` |
| `MYSQL_USER` | Пользователь БД | `blog` |
| `MYSQL_PASSWORD` | Пароль пользователя БД | `blog` |
| `DB_HOST` | Хост БД (в Docker — имя сервиса) | `mysql` |
| `DB_PORT` | Порт MySQL | `3306` |
| `DB_NAME` | Имя БД для приложения | `blog` |
| `DB_USER` | Пользователь для приложения | `blog` |
| `DB_PASSWORD` | Пароль для приложения | `blog` |

Для локальной разработки значений из `.env.example` обычно достаточно.

---

## 3. Запуск контейнеров

Соберите образ и запустите сервисы:

```bash
docker compose up -d --build
```

Будут подняты:

- **php** — приложение (PHP 8.3, встроенный сервер на порту 8000)
- **mysql** — MySQL 8.0 на порту 3306

Проверка, что контейнеры работают:

```bash
docker compose ps
```

Дождитесь, пока MySQL полностью запустится (обычно 10–20 секунд), прежде чем выполнять настройку БД (db-setup).

---

## 4. Установка зависимостей PHP

Установите зависимости Composer **внутри контейнера**:

```bash
docker compose exec php composer install
```

---

## 5. Настройка БД (db-setup)

Применить схему из `sql/schema.sql` (создание БД `blog` и таблиц) и заполнить БД тестовыми данными (категории, статьи, заглушки изображений):

```bash
docker compose exec php php scripts/db-setup.php
```

Выполнять один раз после первого запуска или после сброса БД. При повторном запуске возможны дубликаты или ошибки уникальности — db-setup рассчитан на пустую БД.

---

## 6. Сборка CSS (опционально)

Стили собираются из SCSS. На хосте (при установленном Node.js):

```bash
npm install
npm run build:css
```

Либо в контейнере, если там есть Node/npm:

```bash
docker compose exec php npm install
docker compose exec php npm run build:css
```

Если Node в контейнере не установлен, выполняйте `npm run build:css` локально; скомпилированный `public/css/main.css` будет доступен приложению через volume.

---

## 7. Запуск приложения и проверка

- Приложение уже запущено сервисом **php** (шаг 3).
- Сайт доступен по адресу: **http://localhost:8000**

Откройте этот URL в браузере. Должна отображаться главная страница блога со списком статей и категориями.

---

## Полезные команды

| Действие | Команда |
|----------|---------|
| Остановить контейнеры | `docker compose down` |
| Остановить и удалить данные БД | `docker compose down -v` |
| Просмотр логов | `docker compose logs -f` |
| Логи только PHP | `docker compose logs -f php` |
| Зайти в shell контейнера PHP | `docker compose exec php sh` |
| Подключиться к MySQL | `docker compose exec mysql mysql -u blog -p blog` (пароль из `MYSQL_PASSWORD`) |

---

## Структура проекта (кратко)

- `public/` — точка входа (`index.php`), статика (CSS, изображения)
- `src/` — код приложения (контроллеры, репозитории, конфиг, DTO, мапперы)
- `templates/` — шаблоны Smarty
- `sql/` — `schema.sql` (схема БД), `seed.sql` (альтернативное сидирование SQL)
- `scripts/` — `db-setup.php` (миграция + сидирование), `migrate.php`, `seed.php` (отдельно при необходимости)
- `scss/` — исходники стилей

Все команды установки зависимостей и настройки БД выполняются **внутри контейнера** (`docker compose exec php ...`), как принято в проекте.
