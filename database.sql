USE if0_40019741_DBDB;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(120) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL
);

-- Default admin: admin@example.com / admin123
INSERT IGNORE INTO admins (email, password_hash)
VALUES ('admin@example.com', '$2y$10$Nnc.OzYB2cLw6uihQhjP.e8umJu1VAFUebeQDGDfQjPIkELIv8.Ly');

CREATE TABLE IF NOT EXISTS notices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  body TEXT NOT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
