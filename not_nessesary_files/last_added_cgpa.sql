-- 1. Add new columns
ALTER TABLE users 
ADD COLUMN cgpa DECIMAL(3,2) DEFAULT 0.00,
ADD COLUMN credits_completed INT DEFAULT 0;

-- 2. Update existing dummy students with random academic data
UPDATE users 
SET cgpa = 3.50, credits_completed = 45 
WHERE role = 'student' AND email = 'student1@stms.com';

UPDATE users 
SET cgpa = 3.85, credits_completed = 90 
WHERE role = 'student' AND email = 'student2@stms.com';

UPDATE users 
SET cgpa = 2.90, credits_completed = 30 
WHERE role = 'student' AND email = 'student3@stms.com';

-- Set a default for others
UPDATE users SET cgpa = 3.00, credits_completed = 15 WHERE role = 'student' AND cgpa = 0.00;