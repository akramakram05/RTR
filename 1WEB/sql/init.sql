CREATE DATABASE rtr CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE rtr;

-- users
CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom        VARCHAR(100)  NOT NULL,
  prenom     VARCHAR(100)  NOT NULL,
  email      VARCHAR(255)  NOT NULL UNIQUE,
  password   VARCHAR(255)  NOT NULL,
  created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- actualites
CREATE TABLE actualites (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre        VARCHAR(255) NOT NULL,
  contenu      TEXT         NOT NULL,
  image        VARCHAR(255),
  published_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  author_id    INT UNSIGNED,
  CONSTRAINT fk_actualites_user
    FOREIGN KEY (author_id) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- chantiers
CREATE TABLE chantiers (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lieu         VARCHAR(255) NOT NULL,
  description  TEXT         NOT NULL,
  image        VARCHAR(255),
  date_debut   DATE         NOT NULL,
  date_fin     DATE,
  created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- contacts
CREATE TABLE contacts (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom        VARCHAR(100) NOT NULL,
  prenom     VARCHAR(100) NOT NULL,
  email      VARCHAR(255) NOT NULL,
  sujet      ENUM('Demande infos','Demande Rendez-vous','autre') NOT NULL,
  message    TEXT         NOT NULL,
  created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

