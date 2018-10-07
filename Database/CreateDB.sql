DROP DATABASE IF EXISTS DevAndMore;

CREATE DATABASE DevAndMore;

USE DevAndMore;

CREATE TABLE User
(
	Id INT NOT NULL AUTO_INCREMENT
	, Username NVARCHAR(35) NOT NULL
	, Password NVARCHAR(100) NOT NULL
	, PRIMARY KEY (Id)
);

CREATE TABLE Category
(
	Id INT NOT NULL AUTO_INCREMENT
	, Name NVARCHAR(35) NOT NULL
	, Color BINARY(3) NOT NULL
	, IsVisible BOOLEAN NOT NULL
	, PRIMARY KEY (Id)
);

CREATE TABLE Image
(
	Id INT NOT NULL AUTO_INCREMENT
	, Name NVARCHAR(35) NOT NULL
	, Extension NVARCHAR(10) NOT NULL
	, Path NVARCHAR(255) NOT NULL
	, IsVisible BOOLEAN NOT NULL
	, PRIMARY KEY (Id)
);

CREATE TABLE Post
(
	Id INT NOT NULL AUTO_INCREMENT
	, Title NVARCHAR(100) NOT NULL
	, Slug NVARCHAR(100) NOT NULL
	, Description NVARCHAR(255) NOT NULL
	, Content TEXT NOT NULL
	, CreationDate DATETIME NOT NULL
	, LastUpdateDate DATETIME NOT NULL
	, IsPublished BOOLEAN NOT NULL
	, CategoryId INT NOT NULL
	, ImageId INT NOT NULL
	, PRIMARY KEY (Id)
	, FOREIGN KEY (CategoryId) REFERENCES Category(Id)
	, FOREIGN KEY (ImageId) REFERENCES Image(Id)
);

CREATE TABLE ExtendedProperty
(
	Id INT NOT NULL AUTO_INCREMENT
	, Name NVARCHAR(50) NOT NULL
	, Value NVARCHAR(255) NOT NULL
	, PRIMARY KEY (Id)
);

INSERT INTO User (Username, Password)
VALUES ('Admin', 'n756JWnb');

INSERT INTO ExtendedProperty (Name, Value)
VALUES ('DBVersion', '1.00.000');

INSERT INTO Category (Name, Color, IsVisible)
VALUES ('Test', UNHEX(123456), 1);

INSERT INTO Post (Title, Slug, Description, Content, CreationDate, LastUpdateDate, ImagePath, IsPublished, CategoryId)
VALUES ('Title', 'Slug', 'Description', 'Content', '2010-04-02 15:28:22', '2010-04-02 15:28:22', 'Path', 0, 1);