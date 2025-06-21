-- Create user "yourname" with full access to all databases
CREATE USER IF NOT EXISTS 'yourname'@'%' IDENTIFIED BY '12345';

-- Grant all privileges to "yourname" for all current and future databases
GRANT ALL PRIVILEGES ON *.* TO 'yourname'@'%' WITH GRANT OPTION;

-- Apply changes immediately
FLUSH PRIVILEGES;
