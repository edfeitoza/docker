-- Executado apenas na primeira inicialização (volume vazio)
USE app;
CREATE TABLE IF NOT EXISTS healthcheck (
  id INT AUTO_INCREMENT PRIMARY KEY,
  status VARCHAR(20) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO healthcheck (status) VALUES ('ok');
