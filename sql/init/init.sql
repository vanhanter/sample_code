-- Создаем БД для тестирования и привязываем к пользователю из /../../compose.yml
CREATE DATABASE sample_db_test;
GRANT ALL PRIVILEGES ON DATABASE sample_db_test TO admin_db;
