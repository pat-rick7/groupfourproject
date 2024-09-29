-- Drop the existing DB, if it exists
DROP DATABASE IF EXISTS grades;

-- Create a new DB for storing Grades
CREATE DATABASE grades;

-- Switch to it
USE grades;

-- Create a user
create user gradesUser@'localhost' IDENTIFIED BY 'ICT312';

GRANT SELECT, INSERT, UPDATE, DELETE
	ON grades.*
	TO 'gradesUser'@'localhost'
	; 

-- DDL to create the tables
CREATE TABLE student (
	studentid VARCHAR(10) PRIMARY KEY,
	password VARCHAR(80)
);

CREATE TABLE course (
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(30) 
);

CREATE TABLE grade (
	code VARCHAR(2) PRIMARY KEY,
	description VARCHAR(50)
);

CREATE TABLE achievement (
	studentid VARCHAR(10),
	courseid INT,
	year INT,
	semester INT,
	gradecode VARCHAR(2),
	PRIMARY KEY (studentid, courseid, year, semester),
	FOREIGN KEY (studentid)
		REFERENCES student(studentid),
	FOREIGN KEY (courseid)
		REFERENCES course(id),
	FOREIGN KEY (gradecode)
		REFERENCES grade(code)
);

--
-- DML to create some records
--

-- Courses
INSERT INTO
	course (id, name)
VALUES
	(1, 'Web Development'),
	(2, 'Programming Fundamentals'),
	(3, 'Advanced Programming'),
	(4, 'Networking and Security');


-- Students
INSERT INTO student
	(studentid, password)
VALUES
	
	('admin', 'admin123'),
	('555001', 'password'),
	('555002', 'password'),
	('555003', 'password'),
	('555004', 'saiL');


-- Grades
INSERT INTO grade
	(code, description)
VALUES
	('XF', 'Didn\t show up'),
	('F', 'Failed'),
	('P', 'Passed'),
	('C', 'Credit'),
	('D', 'Distinction'),
	('HD', 'High Distinction');


-- Achievements / grades earned
INSERT INTO achievement
	(studentid, courseid, year, semester, gradecode)
VALUES
	('555001', 2, 2013, 1, 'D'),
	('555001', 3, 2013, 2, 'D'),
	('555001', 1, 2014, 1, 'F'),
	('555001', 1, 2014, 2, 'C'),
	('555001', 4, 2014, 2, 'HD'),

	('555002', 2, 2012, 2, 'XF'),
	('555002', 2, 2013, 1, 'C'),
	('555002', 3, 2013, 2, 'P'),
	('555002', 4, 2014, 2, 'P'),

	('555003', 2, 2012, 1, 'D'),
	('555003', 3, 2012, 2, 'HD'),
	('555003', 1, 2013, 1, 'HD'),
	('555003', 4, 2013, 2, 'C'),
	
	('555004', 2, 2012, 1, 'D'),
	('555004', 3, 2012, 2, 'HD'),
	('555004', 1, 2013, 1, 'HD'),
	('555004', 4, 2013, 2, 'C');

