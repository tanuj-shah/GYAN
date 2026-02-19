-- ============================================
-- GYAN Performance Optimization: Database Indexes
-- Run this script in MySQL/phpMyAdmin
-- ============================================

-- Check if indexes already exist before creating (safe to run multiple times)

-- Index for events table - optimize date-based queries
SELECT 'Creating index on events.event_date...' as status;
CREATE INDEX IF NOT EXISTS idx_event_date ON events(event_date);

-- Index for blogs table - optimize status + created_at queries  
SELECT 'Creating index on blogs(status, created_at)...' as status;
CREATE INDEX IF NOT EXISTS idx_blog_status_created ON blogs(status, created_at);

-- Index for blogs user_id for JOIN optimization
SELECT 'Creating index on blogs.user_id...' as status;
CREATE INDEX IF NOT EXISTS idx_blog_user_id ON blogs(user_id);

-- Display all indexes on key tables
SELECT 'Verification: Showing indexes...' as status;
SHOW INDEX FROM events;
SHOW INDEX FROM blogs;
SHOW INDEX FROM profiles;

SELECT '✓ Database optimization indexes created successfully!' as status;
