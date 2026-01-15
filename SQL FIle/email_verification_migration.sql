-- Email Verification Migration Script
-- This script adds email verification fields to the tblusers table

ALTER TABLE `tblusers` 
ADD COLUMN `EmailVerificationToken` VARCHAR(255) NULL DEFAULT NULL AFTER `Password`,
ADD COLUMN `IsEmailVerified` TINYINT(1) DEFAULT 0 AFTER `EmailVerificationToken`,
ADD INDEX `idx_verification_token` (`EmailVerificationToken`);

-- Update existing users to be marked as verified (optional - remove if you want existing users to verify)
-- UPDATE `tblusers` SET `IsEmailVerified` = 1 WHERE `IsEmailVerified` = 0;

