# Master Guide: InfinityFree Hosting (Zero to Expert)

This guide provides a comprehensive, professional workflow for deploying the GYAN website on InfinityFree.

## Phase 1: Zero - Infrastructure Setup
1. **Domain Selection**:
   * Go to [InfinityFree](https://infinityfree.com/) and register.
   * Choose a free subdomain (e.g., `gyan-ngo.hoste.com`) OR use a custom domain by pointing your Name Servers to `ns1.epizy.com` and `ns2.epizy.com`.
2. **Account Activation**:
   * Wait for the "Account Status" to show **Active** (usually 1-5 minutes).
   * Record your **vPanel Username** (e.g., `if0_12345678`) and **Password**.

## Phase 2: Beginner - File & Database Migration
1. **The Web Root**: All public files **must** go into the `htdocs` directory.
2. **Structuring for Security**:
   * Upload the `public/` folder's contents directly into `htdocs`.
   * Upload other folders (`config`, `includes`, `database`) and the `.env` file into `htdocs` as well (standard practice on restricted free hosts). 
   * **Expert Note**: Since your `config/database.php` looks for `.env` one level up (`../.env`), you must modify line 32 in `config/database.php` to `$envPath = __DIR__ . '/.env';` if you place everything in `htdocs`.
3. **Database Import**:
   * Go to **MySQL Databases** in vPanel.
   * Create a database: `gyan_db`. Note the **Full Database Name** and **Hostname** (e.g., `sql205.epizy.com`).
   * Open **phpMyAdmin**, click your database, and use the **Import** tab to upload `database/schema.sql`.

## Phase 3: Intermediate - Configuration & Security
1. **Environment Variables**:
   * Use the online File Editor to create a `.env` file in `htdocs`.
   * Use the **MySQL Details** from your InfinityFree client area:
   ```env
   DB_HOST=sqlXXX.infinityfree.com # Your assigned host
   DB_NAME=if0_XXXX_gyan_db
   DB_USER=if0_XXXX
   DB_PASS=YourVPanelPassword
   ENVIRONMENT=production
   ```
2. **SSL (HTTPS)**:
   * InfinityFree provides free SSL via the "Free SSL Certificates" tool in the client area.
   * Choose **Let's Encrypt**, follow the DNS verification steps, and click **Install Automatically**.

## Phase 4: Expert - Optimization & Troubleshooting
1. **Performance Tuning**:
   * **Browser Caching**: Add this to your `.htaccess` file in `htdocs`:
   ```apache
   <IfModule mod_expires.c>
       ExpiresActive On
       ExpiresByType image/webp "access plus 1 year"
       ExpiresByType text/css "access plus 1 month"
       ExpiresByType application/javascript "access plus 1 month"
   </IfModule>
   ```
2. **Solving Common "Free Hosting" Issues**:
   * **403 Forbidden**: Ensure your main file is named `index.php` and is in the root of `htdocs`.
   * **Database Connection Failed**: Double-check the DB Host. Do **not** use `localhost`.
   * **Image Uploads**: Ensure the `uploads/` folder is created and permissions are correct (InfinityFree handles most permissions automatically, but verify folder existence).

3. **Maintenance**:
   * InfinityFree has an automated system that suspends accounts with zero traffic. Visit your site at least once a month.
   * Back up your database frequently via phpMyAdmin > Export.

> [!IMPORTANT]
> InfinityFree has a strict **CPU/Memory limit**. If your site becomes very popular, you will need to upgrade to "iFastNet" or move to Oracle Cloud (covered in the next guide).
