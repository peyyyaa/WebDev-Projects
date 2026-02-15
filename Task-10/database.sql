-- Create database
CREATE DATABASE IF NOT EXISTS journal_db;
USE journal_db;

-- Create journal table
CREATE TABLE IF NOT EXISTS journal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    entry_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    mood VARCHAR(50) DEFAULT 'neutral',
    tags TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data (optional)
INSERT INTO journal (title, content, mood, tags) VALUES
('My First Entry', 'This is my first journal entry. Today was a great day! The sun was shining and I felt so grateful for everything.', 'happy', 'gratitude, sunshine, first day'),
('Learning PHP', 'Started learning PHP and MySQL. CRUD operations are interesting and I am excited to build more projects!', 'excited', 'learning, coding, php');
