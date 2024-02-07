CREATE TABLE IF NOT EXISTS material
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) not null ,
    engName VARCHAR(50) not null
);

CREATE TABLE IF NOT EXISTS color
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) not null ,
    engName VARCHAR(50) not null
);

CREATE TABLE IF NOT EXISTS target_audience
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) not null ,
    engName VARCHAR(50) not null
);