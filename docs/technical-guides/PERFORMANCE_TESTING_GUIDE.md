# GYAN Performance Testing & Verification Guide

This guide provides step-by-step instructions for testing and verifying the performance optimizations implemented on the GYAN website.

---

## Quick Testing Checklist

- [ ] Run database indexes SQL script
- [ ] Verify browser caching is working
- [ ] Test lazy loading images
- [ ] Check Swiper/AOS conditional loading
- [ ] Run Lighthouse performance audit (desktop & mobile)
- [ ] Measure TTFB, FCP, LCP improvements
- [ ] Verify no JavaScript errors in console
- [ ] Test on mobile device (if available)

---

## 1. Database Optimization Verification

### Step 1.1: Run Performance Indexes

**IMPORTANT**: This must be done before measuring performance gains.

1. Open **phpMyAdmin**: http://localhost/phpmyadmin
2. Select database: `gyan_db`
3. Click **SQL** tab
4. Open file: `database/performance_indexes.sql`
5. Copy entire content and paste into SQL window
6. Click **Go**

**Expected Output**:
```
Creating index on events.event_date...
Creating index on blogs(status, created_at)...
Creating index on profiles.user_id...
✓ Database optimization indexes created successfully!
```

### Step 1.2: Verify Indexes Were Created

Run this query in phpMyAdmin:
```sql
SHOW INDEX FROM events;
SHOW INDEX FROM blogs;
SHOW INDEX FROM profiles;
```

**Expected**: You should see indexes named:
- `idx_event_date` on `events` table
- `idx_blog_status_created` on `blogs` table
- `idx_profile_user_id` on `profiles` table

---

## 2. Browser Caching Verification

### Step 2.1: Test Initial Load

1. Open Chrome (or Edge)
2. Press **F12** to open DevTools
3. Go to **Network** tab
4. Check "Disable cache" is **UNCHECKED**
5. Visit: http://localhost/IRD/public/index.php
6. Note the **total transferred size** (bottom right)

### Step 2.2: Test Cached Load

1. **Refresh the page** (F5 or Ctrl+R)
2. Check the Network tab

**Expected Results**:

| Resource | First Load | Cached Load |
|----------|-----------|-------------|
| `style.css` | 3 KB | (disk cache) |
| `person_1.png` | ~45 KB | (disk cache) |
| `person_2.png` | ~45 KB | (disk cache) |
| **Total Transferred** | 4-5 MB | **<200 KB** ✅ |

**Screenshot Example**:

Before (first load):
```
index.php         15 KB
style.css         3 KB   ← Downloaded
person_1.png     45 KB   ← Downloaded
```

After (refresh):
```
index.php         15 KB
style.css     (disk cache)   ← Not downloaded!
person_1.png  (disk cache)   ← Not downloaded!
```

### Step 2.3: Verify Cache Headers

1. In Network tab, click on `style.css`
2. Go to **Headers** tab
3. Look for **Response Headers**

**Expected Headers**:
```
Cache-Control: max-age=2592000  (30 days for CSS)
Expires: [date 30 days from now]
```

For images:
```
Cache-Control: max-age=31536000  (1 year)
Expires: [date 1 year from now]
```

---

## 3. Lazy Loading Verification

### Step 3.1: Test Image Lazy Loading

1. Open DevTools → **Network** tab
2. Filter by **Img**
3. Visit homepage: http://localhost/IRD/public/index.php
4. **DO NOT SCROLL** yet

**Expected**: Only 1-2 images should load initially (hero image)

### Step 3.2: Scroll Test

1. **Slowly scroll down** the page
2. Watch the Network tab

**Expected**: Images should load **ONLY** when they're about to enter the viewport:
- Event images load when you scroll to Events section
- Blog images load when you scroll to Blog section
- Testimonial images load when you scroll to Testimonials
- CTA background loads when you scroll to bottom

**Verification**: Check the **Waterfall** view in Network tab - images should load at different times, not all at once.

---

## 4. Conditional Script Loading Verification

### Step 4.1: Test Homepage (Swiper/AOS Should Load)

1. Open DevTools → **Network** tab → Filter **JS**
2. Visit: http://localhost/IRD/public/index.php
3. Look for these files being loaded:

**Expected to load**:
- ✅ `alpinejs` (cdn.min.js)
- ✅ `swiper-bundle.min.js` ← Should see this
- ✅ `aos.js` ← Should see this
- ✅ `script.js`

### Step 4.2: Test Non-Homepage (Swiper/AOS Should NOT Load)

1. Open DevTools → **Network** tab → Filter **JS**
2. Visit: http://localhost/IRD/public/about.php (or events.php, blog.php, contact.php)
3. Look for loaded scripts

**Expected to load**:
- ✅ `alpinejs` (cdn.min.js)
- ❌ `swiper-bundle.min.js` ← Should NOT see this
- ❌ `aos.js` ← Should NOT see this
- ✅ `script.js`

**Result**: About 200KB saved on non-homepage pages ✅

---

## 5. Lighthouse Performance Audit

### Method 1: Chrome DevTools (Easiest)

1. Open homepage in **Chrome**: http://localhost/IRD/public/index.php
2. Open DevTools (F12)
3. Go to **Lighthouse** tab (may be under >> menu)
4. Configuration:
   - Mode: **Desktop** (test this first)
   - Categories: Check **Performance** only
5. Click **Analyze page load**
6. Wait 30-60 seconds

**Desktop Score Expectations**:

| Metric | Before | After | Target |
|--------|--------|-------|--------|
| **Performance Score** | 60-70 | **85-95** | ≥90 ✅ |
| **FCP** (First Contentful Paint) | 2-3s | 1.2-1.8s | <1.8s |
| **LCP** (Largest Contentful Paint) | 3-5s | 2-2.8s | <2.5s |
| **TBT** (Total Blocking Time) | 500-800ms | 100-300ms | <300ms |
| **CLS** (Cumulative Layout Shift) | 0.1-0.2 | <0.1 | <0.1 |
| **Speed Index** | 3-4s | 1.5-2.5s | <3.4s |

7. **Repeat with Mobile mode**:
   - Mode: **Mobile**
   - Run again

**Mobile Score Expectations**:

| Metric | Before | After | Target |
|--------|--------|-------|--------|
| **Performance Score** | 40-55 | **70-85** | ≥85 |
| **FCP** | 3-5s | 2-3s | <3s |
| **LCP** | 5-8s | 3-4.5s | <4s |

### Method 2: Lighthouse CLI (Advanced)

If you have Node.js installed:

```bash
# Install Lighthouse
npm install -g lighthouse

# Run desktop test
lighthouse http://localhost/IRD/public/index.php --only-categories=performance --preset=desktop --output=html --output-path=./lighthouse-desktop.html --view

# Run mobile test
lighthouse http://localhost/IRD/public/index.php --only-categories=performance --preset=mobile --output=html --output-path=./lighthouse-mobile.html --view
```

---

## 6. TTFB (Time To First Byte) Measurement

### Method 1: Chrome DevTools

1. Open DevTools → **Network** tab
2. Visit homepage
3. Click on the first request (`index.php`)
4. Look at **Timing** tab
5. Find "**Waiting (TTFB)**" value

**Expected**:
- **Before optimizations**: 800-1200ms
- **After database + persistent connections**: 400-700ms
- **After page caching (if implemented)**: 40-100ms

### Method 2: WebPageTest.org (Most Accurate)

1. Go to https://webpagetest.org
2. Enter your URL (if publicly accessible, otherwise skip)
3. Location: Choose nearest server
4. Browser: Chrome
5. Connection: Cable (desktop) or 3G Fast (mobile)
6. Click **Start Test**
7. Wait 2-3 minutes

**Key Metrics to Check**:
- **TTFB**: Should be <500ms ✅
- **Start Render**: < 1.5s
- **Speed Index**: < 2.5s
- **Fully Loaded**: < 5s

### Method 3: Command Line (curl)

```bash
curl -w "@curl-format.txt" -o /dev/null -s http://localhost/IRD/public/index.php
```

**Create `curl-format.txt`**:
```
\nTime Measurements:\n
time_namelookup:  %{time_namelookup}s\n
time_connect:  %{time_connect}s\n
time_appconnect:  %{time_appconnect}s\n
time_pretransfer:  %{time_pretransfer}s\n
time_redirect:  %{time_redirect}s\n
time_starttransfer (TTFB):  %{time_starttransfer}s\n
time_total:  %{time_total}s\n
```

**Expected Output**:
```
time_namelookup:  0.001s
time_connect:  0.001s
time_starttransfer (TTFB):  0.450s  ← Should be <0.5s
time_total:  0.480s
```

---

## 7. Canvas Animation Performance Test

### Step 7.1: Test Animation Pausing

1. Open homepage
2. Open DevTools → **Performance** tab
3. Click **Record** (circle icon)
4. **Scroll down** past the hero section
5. **Scroll back up** to hero section
6. Stop recording

**Expected**: 
- CPU usage should **drop** when hero scrolls off-screen
- CPU usage should **resume** when hero scrolls back into view
- This proves Intersection Observer is working ✅

### Step 7.2: Mobile Performance

Open Chrome DevTools:
1. Click **Toggle device toolbar** (Ctrl+Shift+M)
2. Select device: **iPhone 12 Pro**
3. Reload homepage

**Expected**:
- Animation should be smoother (fewer particles on mobile)
- No lag or stuttering
- 60 FPS maintained

---

## 8. Database Query Performance Test

### Step 8.1: Enable MySQL Slow Query Log

In phpMyAdmin, run:

```sql
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 0.1; -- Log queries >100ms
```

### Step 8.2: Test Page Load

1. Visit homepage: http://localhost/IRD/public/index.php
2. Check slow query log

**In phpMyAdmin**:
```sql
SELECT * FROM mysql.slow_log ORDER BY start_time DESC LIMIT 10;
```

**Expected**: 
- Homepage queries should be <100ms each
- **Events query**: ~20-50ms (with index)
- **Blogs query**: ~20-50ms (with index)

**Without indexes, these would be 200-500ms each!**

---

## 9. Console Error Check

### Step 9.1: JavaScript Console

1. Open DevTools → **Console** tab
2. Visit homepage
3. Scroll through entire page
4. Check all other pages (About, Events, Blog, Contact, Dashboard)

**Expected**: 
- ✅ **0 errors**
- ⚠️ Warnings are OK (e.g., "Tailwind CSS loaded")
- ❌ Any red errors = something broke

Common errors to watch for:
- `Uncaught ReferenceError: Swiper is not defined` (would mean conditional loading broke)
- `Failed to load resource` (would mean file path is wrong)

---

## 10. Page Caching Test (Optional)

If you implemented page caching on any pages:

### Step 10.1: Test Cache Creation

1. Visit a cached page (e.g., events.php)
2. Check if cache file was created:
   ```
   c:\xampp\htdocs\IRD\cache\pages\
   ```
3. You should see `.html` files

### Step 10.2: Test Cache Performance

**First load (uncached)**:
```bash
curl -w "TTFB: %{time_starttransfer}s\n" -o /dev/null -s http://localhost/IRD/public/events.php
# TTFB: 0.650s
```

**Second load (cached)**:
```bash
curl -w "TTFB: %{time_starttransfer}s\n" -o /dev/null -s http://localhost/IRD/public/events.php
# TTFB: 0.045s  ← 93% faster! ✅
```

### Step 10.3: Test Cache Manager

1. Visit: http://localhost/IRD/public/admin/cache_manager.php
2. Login as admin
3. Check cache statistics
4. Click "Clear All Cache"
5. Verify cache was cleared (count should be 0)

---

## 11. Mobile Device Testing (Optional but Recommended)

If you have a smartphone on the same network:

1. **Find your computer's local IP**:
   ```bash
   # Windows
   ipconfig

   # Look for IPv4 Address (e.g., 192.168.1.100)
   ```

2. **Visit on phone**:
   ```
   http://192.168.1.100/IRD/public/index.php
   ```

3. **Test**:
   - Page loads quickly
   - Images lazy load as you scroll
   - Animations are smooth
   - No horizontal scrolling
   - Buttons/links work

---

## 12. Cross-Browser Testing

Test in multiple browsers to ensure compatibility:

- [x] **Chrome** (primary)
- [ ] **Firefox**
- [ ] **Edge**
- [ ] **Safari** (if on Mac/iPhone)

**Focus on**:
- Lazy loading works (all browsers support it natively now)
- Animations play smoothly
- No console errors

---

## Performance Gains Summary

After completing all optimizations, you should see:

| Metric | Before | After | Improvement |
|--------|--------|-------|-------------|
| **Lighthouse Desktop** | 60-70 | 85-95 | +35% |
| **Lighthouse Mobile** | 40-55 | 70-85 | +75% |
| **TTFB** | 800-1200ms | 400-700ms | -50% |
| **Repeat Page Load Size** | 4-5 MB | <200 KB | -95% |
| **FCP (Desktop)** | 2-3s | 1.2-1.8s | -40% |
| **LCP (Desktop)** | 3-5s | 2-2.8s | -45% |

---

## Troubleshooting

### Issue: Lighthouse score not improving

**Check**:
1. Clear browser cache completely (Ctrl+Shift+Del)
2. Run Lighthouse in **Incognito mode**
3. Disable browser extensions
4. Ensure database indexes were created
5. Verify .htaccess is working (check response headers)

### Issue: Images not lazy loading

**Check**:
1. Open Network tab → filter Img
2. Verify `loading="lazy"` attribute is present in HTML source
3. Try in Chrome (best lazy loading support)
4. Clear cache and reload

### Issue: Swiper not working on homepage

**Check**:
1. Console for errors
2. Verify script loaded (Network tab → JS → swiper-bundle.min.js)
3. Check if `basename($_SERVER['PHP_SELF']) === 'index.php'` condition is correct

### Issue: Cache not saving

**Check**:
1. Verify `cache/pages/` directory exists and is writable
2. Check PHP error log for permission issues
3. Run: `chmod 755 cache/`

---

## Next Steps After Verification

Once all tests pass:

1. ✅ **Document your results**
2. ✅ **Deploy to production** (upload changed files)
3. ✅ **Set up Cloudflare** (if using CDN)
4. ✅ **Monitor performance** with weekly Lighthouse reports
5. ✅ **Clear cache after content updates**

---

## Performance Monitoring (Ongoing)

**Weekly**:
- Run Lighthouse audit
- Check cache hit rate (if using page caching)
- Monitor TTFB trends

**Monthly**:
- Review database slow query log
- Check for unused CSS/JS
- Update dependencies (Tailwind, Alpine, etc.)

**After Major Updates**:
- Clear cache
- Re-run full verification suite
- Compare performance before/after

---

## Success Metrics ✅

Your optimization is **successful** if:

- ✅ Lighthouse Desktop score ≥ 85
- ✅ Lighthouse Mobile score ≥ 70  
- ✅ TTFB < 700ms
- ✅ Repeat page loads < 200KB
- ✅ No JavaScript console errors
- ✅ All pages load correctly on mobile

**If all checks pass, congratulations! Your GYAN website is now significantly faster! 🎉**
