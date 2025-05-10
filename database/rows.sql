-- Data to insert into tables

-- users have same hashed password: password123
INSERT INTO users (username, email, password, created_at)
VALUES 
  ('alice', 'alice@example.com', '$2y$10$Yz6MwYjvYB0fWqTduhJ46.Lg5oVYG/oMc6lT0SyOfB52e9xmynHIMAm', NOW()),
  ('bob', 'bob@example.com', '$2y$10$zdlIaHAF7PRHDZcqptvJ5e20cTnPbnQ1/VCu7aYl/XCVy9ZdrZDiO', NOW()), 
  ('charlie', 'charlie@example.com', '$2y$10$YkTL8hG90hEyE4wnOiXl6u4EwqLy7KDZGx/h7v.lQ2pZ7G/POp6A.', NOW()),
  ('diana', 'diana@example.com', '$2y$10$EpMaO3UbiH34RUEBjWzLquCmzv6YemOhAMi7UFGFiqE1i1lQa4mzm', NOW()),
  ('eve', 'eve@example.com', '$2y$10$YB1HgM3tEK7qEF5CAvX7ZOw3T2MwDxYHpN5E3XtkpZrheHhGCEiP2', NOW());
