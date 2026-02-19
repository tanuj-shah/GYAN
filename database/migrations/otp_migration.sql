-- Database migration for OTP System
-- Adds OTP columns and updates status enum

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Add OTP columns to users table
ALTER TABLE `users`
ADD COLUMN `otp_code` varchar(6) DEFAULT NULL AFTER `status`,
ADD COLUMN `otp_expiry` datetime DEFAULT NULL AFTER `otp_code`;

-- Update status enum to include 'pending' as default (if not already)
-- Note: 'pending' is already in the enum list from schema.sql, just ensuring it's the default for new registrations
ALTER TABLE `users`
MODIFY COLUMN `status` enum('pending','active','suspended') NOT NULL DEFAULT 'pending';

COMMIT;
