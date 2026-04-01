CREATE DATABASE surfSchoolManager;

USE  surfSchoolManager

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR (150)UNIQUE,
	password varchar (255)NOT NULL, 
    role ENUM('student', 'admin') NOT NULL DEFAULT 'student'
);

CREATE TABLE student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL UNIQUE,
    name VARCHAR (100)UNIQUE,
	country varchar (150)NOT NULL, 
   level ENUM('Beginner', 'Intermediate','Advanced') NOT NULL DEFAULT 'Beginner',
    FOREIGN KEY (user_id) REFERENCES users(id)
);


CREATE TABLE lessons (
    id          INT           NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    title       VARCHAR(255)  NOT NULL ,
    coach       VARCHAR(50)   NOT NULL ,
    datetime    DATETIME      NOT NULL ,
    price       DECIMAL(5,2)  NOT NULL 
);


CREATE TABLE lesson_student (
     id INT AUTO_INCREMENT PRIMARY KEY ,
     student_id INT NOt null UNIQUE,
     lesson_id INT NOt null UNIQUE,
     pay_status ENUM('payed','notpayd') DEFAULT 'notpayd',
    FOREIGN KEY (student_id)REFERENCES student (id),
    FOREIGN KEY (lesson_id) REFERENCES lessons (id)
   
   