CREATE DATABASE archeo CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE archeo;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100),
  prenom VARCHAR(100),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE actualites (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(255),
  contenu TEXT,
  image VARCHAR(255),
  date DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE chantiers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lieu VARCHAR(255),
  description TEXT,
  image VARCHAR(255),
  date_debut DATE,
  date_fin DATE
);

CREATE TABLE contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100),
  prenom VARCHAR(100),
  email VARCHAR(255),
  sujet ENUM('Demande d\'infos','Demande de Rendez-vous','autre'),
  message TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
