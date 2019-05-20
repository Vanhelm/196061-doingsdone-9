USE affairs_order;

INSERT INTO users
(email, name, password) 
VALUES 
('mpilchuk@gmail.com', 'vanhelm', 'pass'),
('m@gmail.com', 'veah', 'pass');

INSERT INTO projects
(name, id_user) VALUES ('Входящие', 1), ('Учёба', 1), ('Работа', 2), ('Домашние дела', 2), ('Авто', 1);

INSERT INTO tasks
(name, term, project_id) 
VALUES 
('Собеседование в IT компании', '2019.04.21', 3), 
('Выполнить тестовое задание', '2019.03.25', 3),
('Сделать задание 1 раздела', '2019.03.21', 2),
('Встреча с другом', '2018.03.22', 1),
('Купить корм для кота', NULL, 4),
('Заказать пиццу', NULL, 4);


#показать задачи по пользователю
SELECT p.project_id, p.name, COUNT(t.project_id) 
AS Количество 
FROM projects p 
LEFT JOIN tasks t ON t.project_id = p.project_id 
WHERE p.id_user=2
GROUP BY p.project_id ORDER BY t.project_id ASC;

SELECT *  FROM tasks
WHERE project_id = 4;

UPDATE tasks SET status = 1
WHERE status=0 AND id_task=1;  

UPDATE tasks SET name='Зачем-то обновил название'

WHERE id_task=1;

