-- Migration script to add mood and tags columns to existing journal table
-- Run this ONLY if you already have a journal_db database and want to add new features

USE journal_db;

-- Add mood column if it doesn't exist
ALTER TABLE journal 
ADD COLUMN IF NOT EXISTS mood VARCHAR(50) DEFAULT 'neutral' AFTER entry_date;

-- Add tags column if it doesn't exist
ALTER TABLE journal 
ADD COLUMN IF NOT EXISTS tags TEXT AFTER mood;

-- Update existing entries with default mood if they don't have one
UPDATE journal SET mood = 'neutral' WHERE mood IS NULL OR mood = '';

-- Show updated table structure
DESCRIBE journal;

SELECT 'Migration completed successfully! Your journal table now has mood and tags columns.' AS Status;
