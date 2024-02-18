CREATE TABLE tareas(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	titulo VARCHAR(200),
	descripcion VARCHAR(200),
	prioridad INT
);

INSERT INTO tareas (titulo, descripcion, prioridad)
VALUES ('tarea 1', 'tarea 11111', 1), ('tarea 2', 'tarea 22222', 3);