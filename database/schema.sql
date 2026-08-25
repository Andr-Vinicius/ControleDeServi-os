CREATE DATABASE IF NOT EXISTS titan_teste CHARACTER SET utf8mb4;
USE titan_teste;

CREATE TABLE user (
  id_user BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  update_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  ativo TINYINT(1) DEFAULT 1
);

CREATE TABLE service (
  id_service BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  description VARCHAR(255) NOT NULL,
  price DECIMAL(11,3) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  update_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  finished_at DATETIME NULL,
  commission_user DECIMAL(11,3) NULL,
  user_id_user BIGINT(20) UNSIGNED NOT NULL,
  FOREIGN KEY (user_id_user) REFERENCES user(id_user)
);

-- Seed
INSERT INTO user (name, email, password, ativo) VALUES
('José Silva', 'jose.silva@teste.com', '$2b$10$ij5JUZccmh.Pt6kRFMsqq.AoTvIlJOJ8fZ8uU4gHtLR414pYO.RB6', 1),
('Maria Souza', 'maria.souza@teste.com', '$2b$10$ij5JUZccmh.Pt6kRFMsqq.AoTvIlJOJ8fZ8uU4gHtLR414pYO.RB6', 1);

INSERT INTO service (description, price, created_at, finished_at, commission_user, user_id_user) VALUES
('Troca de Tela de Notebook', 425.00, NOW() - INTERVAL 5 DAY, NULL, NULL, 1),
('Conserto de carregador', 120.00, NOW() - INTERVAL 4 DAY, NULL, NULL, 1),
('Instalação de Office 2016', 80.00, NOW() - INTERVAL 3 DAY, NULL, NULL, 2),
('Limpeza de Computador', 100.00, NOW() - INTERVAL 10 DAY, NOW() - INTERVAL 9 DAY, 5.00, 1),
('Troca de Memória RAM', 1500.00, NOW() - INTERVAL 8 DAY, NOW() - INTERVAL 7 DAY, 150.00, 2);
