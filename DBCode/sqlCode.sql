-- Création de la base de données
CREATE DATABASE IF NOT EXISTS formationsDB;
USE formationsDB;

-- Table Pays
CREATE TABLE Pays (
    id INT AUTO_INCREMENT PRIMARY KEY,
    value VARCHAR(255) NOT NULL
);

-- Table Ville
CREATE TABLE Ville (
    id INT AUTO_INCREMENT PRIMARY KEY,
    value VARCHAR(255) NOT NULL,
    paysId INT NOT NULL,
    FOREIGN KEY (paysId) REFERENCES Pays(id)
);

-- Table Domaine
CREATE TABLE Domaine (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

-- Table Sujet
CREATE TABLE Sujet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    shortDescription TEXT,
    longDescription TEXT,
    individualBenefit TEXT,
    businessBenefit TEXT,
    logo LONGBLOB,
    domaineId INT NOT NULL,
    FOREIGN KEY (domaineId) REFERENCES Domaine(id)
);

-- Table Cours
CREATE TABLE Cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    content TEXT,
    description TEXT,
    audience TEXT,
    duration INT,
    testIncluded BOOLEAN,
    testContent TEXT,
    logo LONGBLOB,
    sujetId INT NOT NULL,
    FOREIGN KEY (sujetId) REFERENCES Sujet(id)
);

-- Table Formateur
CREATE TABLE Formateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    description TEXT,
    photo LONGBLOB
);

-- Table Formation
CREATE TABLE Formation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    price INT NOT NULL,
    mode ENUM('Présentiel', 'Distanciel') NOT NULL,
    coursId INT NOT NULL,
    formateurId INT NOT NULL,
    villeId INT NOT NULL,
    FOREIGN KEY (coursId) REFERENCES Cours(id),
    FOREIGN KEY (formateurId) REFERENCES Formateur(id),
    FOREIGN KEY (villeId) REFERENCES Ville(id)
);

-- Table FormationDate
CREATE TABLE FormationDate (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    formationId INT NOT NULL,
    FOREIGN KEY (formationId) REFERENCES Formation(id)
);

-- Table Inscription
CREATE TABLE Inscription (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255) NOT NULL,
    company VARCHAR(255),
    paid BOOLEAN DEFAULT FALSE,
    formationDateId INT NOT NULL,
    FOREIGN KEY (formationDateId) REFERENCES FormationDate(id)
);

-- 1. Table Role
CREATE TABLE Role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE  -- e.g. 'admin', 'client'
);

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,       -- store password_hash()
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    roleId INT NOT NULL,                  -- FK to Role
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (roleId) REFERENCES Role(id)
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL
);
