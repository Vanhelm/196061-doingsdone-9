CREATE DATABASE affairs_order
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE affairs_order;

CREATE TABLE users 
(
	id_user INT AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	email VARCHAR(192) NOT NULL UNIQUE,
	name VARCHAR(128) NOT NULL,
	password VARCHAR(64) NOT NULL

);

CREATE TABLE projects 
(
	project_id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(68),
	id_user INT,
	FOREIGN KEY (id_user) REFERENCES users(id_user)
);

CREATE TABLE tasks
(
	id_task INT AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	status TINYINT DEFAULT 0,
	name VARCHAR(68) NOT NULL,
	file VARCHAR(100),
	term VARCHAR(20),
	project_id INT,
	id_user INT,
	FOREIGN KEY (id_user) REFERENCES users(id_user),
	FOREIGN KEY (project_id) REFERENCES projects(project_id)
);
CREATE FULLTEXT INDEX search
ON projects(name);

