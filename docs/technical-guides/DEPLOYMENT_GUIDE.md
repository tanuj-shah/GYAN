# GYAN Website - Production Deployment Guide

This guide provides step-by-step instructions for deploying the optimized GYAN website to production.

---

## Pre-Deployment Checklist

- [ ] All performance optimizations tested locally
- [ ] Database indexes created and tested
- [ ] No console errors on any page
- [ ] Lighthouse score ≥ 85 (desktop) on local
- [ ] Backup existing production site
- [ ] Backup production database

---

## Deployment Method

### Option A: FTP/cPanel (Most Common for Shared Hosting)

**Step 1: Backup Production**

1. Login to cPanel
2. File Manager → Select `public_html/` 
3. Click **Compress** → Create `backup_YYYY-MM-DD.zip`
4. Download backup to local computer

**Step 2: Backup Database**

1. cPanel → phpMyAdmin
2. Select `gyan_db`
3. Click **Export** → Quick export → Go
4. Save `.sql` file locally

**Step 3: Upload Changed Files**

Upload these files via FTP or cPanel File Manager:

```
includes/header.php          ← Modified
includes/footer.php          ← Modified  
includes/cache_helper.php    ← NEW
config/database.php          ← Modified
public/index.php             ← Modified
public/js/script.js          ← Modified
public/.htaccess             ← NEW
public/php.ini               ← NEW (if supported)
public/admin/cache_manager.php ← NEW
database/performance_indexes.sql ← NEW (run in phpMyAdmin)
docs/CACHING_GUIDE.md        ← NEW (optional)
docs/PERFORMANCE_TESTING_GUIDE.md ← NEW (optional)
```

**File transfer checklist**:
- [ ] `includes/header.php`
- [ ] `includes/footer.php`
- [ ] `includes/cache_helper.php` (NEW)
- [ ] `config/database.php`
- [ ] `public/index.php`
- [ ] `public/js/script.js`
- [ ] `public/.htaccess` (NEW)
- [ ] `public/php.ini` (NEW - may not work on all hosts)
- [ ] `public/admin/cache_manager.php` (NEW)

**Step 4: Create Cache Directory**

Via cPanel File Manager or FTP:
1. Create folder: `cache/`
2. Create folder: `cache/pages/`
3. Set permissions:
   - Right-click `cache/` → Change Permissions → `755`
   - Right-click `cache/pages/` → Change Permissions → `755`

**Step 5: Run Database Indexes**

1. cPanel → phpMyAdmin
2. Select your database
3. Click **SQL** tab
4. Open `database/performance_indexes.sql` from local
5. Copy entire content, paste into SQL window
6. Click **Go**
7. Verify success message

**Step 6: Verify .htaccess**

1. Visit your site: https://yoursite.com
2. Check if site loads correctly
3. If you see "500 Internal Server Error":
   - Your server may not support some directives
   - See troubleshooting below

**Step 7: Test Production Site**

- [ ] Homepage loads correctly
- [ ] All pages load without errors
- [ ] Images lazy load on scroll
- [ ] Browser caching works (check Network tab)
- [ ] No JavaScript console errors
- [ ] Mobile version works

---

### Option B: Git Deployment (VPS/Cloud)

**Step 1: Commit Changes**

```bash
git add .
git commit -m "Performance optimizations: caching, lazy loading, database indexes"
git push origin main
```

**Step 2: Pull on Server**

```bash
ssh user@your-server.com
cd /var/www/html/gyan
git pull origin main
```

**Step 3: Create Cache Directory**

```bash
mkdir -p cache/pages
chmod 755 cache
chmod 755 cache/pages
```

**Step 4: Run Database Indexes**

```bash
mysql -u username -p gyan_db < database/performance_indexes.sql
```

**Step 5: Restart Server** (if needed)

```bash
sudo systemctl restart apache2
# or
sudo systemctl restart nginx
sudo systemctl restart php-fpm
```

---

## Post-Deployment Verification

### 1. Test Production Performance

**Run Lighthouse on production URL**:
1. Open https://yoursite.com in Chrome
2. DevTools (F12) → Lighthouse
3. Mode: Desktop → Performance only
4. Click "Analyze page load"

**Expected**:
- Desktop score ≥ 85 ✅
- Mobile score ≥ 70 ✅

### 2. Verify Browser Caching

1. Open https://yoursite.com
2. DevTools → Network tab
3. Load page, check size
4. **Refresh** page
5. **Expected**: Most assets show "(disk cache)"

### 3. Check Response Headers

1. DevTools → Network → Click `style.css`
2. Headers tab → Response Headers

**Should see**:
```
cache-control: max-age=2592000
expires: [30 days from now]
```

### 4. Test TTFB

```bash
curl -w "TTFB: %{time_starttransfer}s\n" -o /dev/null -s https://yoursite.com
```

**Expected**: <700ms (without page caching), <100ms (with page caching)

---

## Cloudflare Setup (Recommended)

Free CDN that adds 20-30 Lighthouse points and global acceleration.

### Step 1: Sign Up

1. Go to https://cloudflare.com/plans
2. Click "Free" plan → "Get started"
3. Enter your domain (e.g., `gyannepal.org`)

### Step 2: Add DNS Records

Cloudflare will scan your existing DNS. Review and confirm.

### Step 3: Update Nameservers

1. Login to your **domain registrar** (GoDaddy, Namecheap, etc.)
2. Find "DNS Management" or "Nameservers"
3. Change nameservers to Cloudflare's:
   ```
   NS1: audrey.ns.cloudflare.com
   NS2: bob.ns.cloudflare.com
   (exact values shown in Cloudflare dashboard)
   ```
4. Wait 24-48 hours for propagation

### Step 4: Configure Cloudflare

**Speed Optimizations**:
1. Speed → Optimization → **Auto Minify**: Enable JS, CSS, HTML
2. Speed → Optimization → **Brotli**: Enable
3. Speed → Optimization → **Rocket Loader**: OFF (can break Alpine.js)

**Caching**:
1. Caching → Configuration → **Browser Cache TTL**: 1 year
2. Caching → Configuration → **Caching Level**: Standard

**Network**:
1. Network → **HTTP/3**: Enable
2. Network → **WebSockets**: Enable (if using)

**SSL/TLS**:
1. SSL/TLS → Overview → **Full (strict)**

**Performance Impact**:
- Additional +20-30 Lighthouse points ✅
- TTFB -200-500ms globally ✅
- Free SSL certificate included ✅

---

## Rollback Procedure

If something breaks after deployment:

### Quick Rollback

**Via cPanel**:
1. File Manager
2. Locate backup ZIP (e.g., `backup_2026-02-10.zip`)
3. Select → Extract
4. Overwrite all files

**Via FTP**:
1. Download backup from local
2. Upload original files, overwriting new ones

### Database Rollback

1. phpMyAdmin → Import
2. Select backup `.sql` file
3. Go

### Selective Rollback

Only restore specific files if you know which caused the issue:
```
# Restore header.php only
Upload original header.php → overwrite
```

---

## Performance Monitoring (Ongoing)

### Weekly Tasks

**Run Lighthouse audit**:
- Desktop score should stay ≥ 85
- Mobile score should stay ≥ 70
- If scores drop, investigate what changed

**Check error logs**:
```bash
# cPanel → Error logs
# or via SSH:
tail -f /var/log/apache2/error.log
```

### Monthly Tasks

**Review cache effectiveness**:
1. Visit `/admin/cache_manager.php`
2. Check cache stats
3. Expected: 10-50 cached pages, 1-5 MB

**Update dependencies**:
- Check for Tailwind CSS updates
- Check for Alpine.js updates
- Check for Swiper updates

### After Content Updates

**Always clear cache after**:
- Publishing new blog post
- Creating new event
- Updating About page
- Changing CSS (increment version number too!)

---

## Troubleshooting Production Issues

### Issue: 500 Internal Server Error

**Cause**: `.htaccess` directive not supported by server

**Fix**:
1. Rename `.htaccess` to `.htaccess.bak`
2. Create new `.htaccess` with minimal rules:

```apache
# Minimal .htaccess for compatibility
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
</IfModule>
```

3. Test again
4. Gradually add back directives one at a time

### Issue: Images showing broken

**Cause**: File permissions

**Fix**:
```bash
chmod 755 public/img
chmod 644 public/img/*
```

### Issue: Cache not working

**Cause**: `cache/` directory not writable

**Fix**:
```bash
chmod 755 cache
chmod 755 cache/pages
```

### Issue: Database queries still slow

**Cause**: Indexes not created

**Fix**:
1. Re-run `performance_indexes.sql` in phpMyAdmin
2. Verify with: `SHOW INDEX FROM events;`

### Issue: Cloudflare breaks styling

**Cause**: Rocket Loader or Minification

**Fix**:
1. Cloudflare Dashboard → Speed → Optimization
2. **Rocket Loader**: OFF
3. **Auto Minify HTML**: OFF (keep CSS/JS ON)
4. Purge cache: Caching → Clear cache → Purge everything

---

## Production Configuration Differences

### Development vs Production

| Setting | Development | Production |
|---------|-------------|------------|
| **Cache TTL** | 300s (5 min) | 3600s (1 hour) |
| **Error Display** | ON | OFF |
| **OPcache revalidate_freq** | 2s | 60s |
| **Database Charset** | utf8mb4 | utf8mb4 |
| **HTTPS** | OFF | ON (via Cloudflare) |

### Update Production php.ini

```ini
; Production settings
display_errors=Off
error_reporting=E_ALL
opcache.revalidate_freq=60
opcache.validate_timestamps=1
```

---

## Security Checklist

Before going live:

- [ ] Remove any test/debug code
- [ ] Ensure `.env` or config files are not publicly accessible
- [ ] Verify `database.php` is outside public_html
- [ ] Check file permissions (755 for directories, 644 for files)
- [ ] Enable HTTPS (via Cloudflare or cPanel SSL)
- [ ] Update `register_globals` = OFF
- [ ] Disable PHP version display
- [ ] Set secure session cookie flags

---

## Success! 🎉

Your optimized GYAN website is now live and running at peak performance.

**Final Verification**:
- ✅ All pages load correctly
- ✅ Lighthouse score ≥ 85 (desktop)
- ✅ Browser caching working
- ✅ No console errors
- ✅ TTFB < 700ms
- ✅ Mobile responsive
- ✅ Cloudflare active (if configured)

**Performance Gains Achieved**:
- 🚀 +30-50 Lighthouse points
- ⚡ 40-60% faster repeat page loads
- 📉 50-70% reduction in TTFB
- 🌍 Global CDN acceleration (if using Cloudflare)

---

## Support & Maintenance

**Need Help?**
- Review `docs/PERFORMANCE_TESTING_GUIDE.md`
- Review `docs/CACHING_GUIDE.md`
- Check server error logs
- Test locally first, then debug production

**Recommended Maintenance Schedule**:
- **Daily**: Check error logs
- **Weekly**: Run Lighthouse audit
- **Monthly**: Review cache stats, update dependencies
- **Quarterly**: Full security audit

Congratulations on deploying your optimized GYAN website! 🚀
