-- Сидинг: категории, статьи и связь многие-ко-многим (соответствует scripts/seed.php)

USE blog;

INSERT INTO categories (name, slug, description) VALUES
('Автомобили', 'avtomobili', 'Статьи о авто'),
('Лес', 'les', 'Статьи о лесе'),
('Дом', 'dom', 'Статьи о доме'),
('Одинадцатиклассница', 'odinadcatiklassnica', 'Проверяем очень длинное название');

INSERT INTO articles (slug, image, name, description, text, view_count, published_at, created_at) VALUES
('lada-sedan', NULL, 'Лада седан', 'Баклажан', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Culpa ut velit proident consectetur dolor consectetur elit incididunt laboris occaecat qui id mollit elit do. Reprehenderit exercitation amet irure sunt exercitation aute adipiscing nulla commodo pariatur laboris pariatur cupidatat.', 0, NOW(), NOW()),
('sluchaj-v-lesu', NULL, 'Случай в лесу', 'Интересный случай в лесу', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Culpa ut velit proident consectetur dolor consectetur elit incididunt laboris occaecat qui id mollit elit do. Reprehenderit exercitation amet irure sunt exercitation aute adipiscing nulla commodo pariatur laboris pariatur cupidatat.', 0, NOW(), NOW()),
('domashnee', NULL, 'Домашнее', 'Секреты домашнего уюта. Очень длинное описание. Уже фантазия кончилась. Хоть Lorem вставляй. Ужас какое длинное описание', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Culpa ut velit proident consectetur dolor consectetur elit incididunt laboris occaecat qui id mollit elit do. Reprehenderit exercitation amet irure sunt exercitation aute adipiscing nulla commodo pariatur laboris pariatur cupidatat.', 0, NOW(), NOW());

INSERT INTO article_category (article_id, category_id) VALUES
(1, 1),
(2, 2),
(2, 3),
(3, 1),
(3, 4);
