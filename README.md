# GYAN: Global Youth Alliance for Nepal

> **"Connecting, Inspiring, & Channeling the global community of Nepali youth directly into the mainstream development of Nepal."**

![GYAN Platform Backdrop](public/img/mountain.webp)

GYAN (Global Youth Alliance for Nepal) is a premium digital ecosystem structured to unite the global Nepali diaspora. It serves as a bridge connecting professionals, visionaries, and leaders to foster national growth through collaboration and structured initiatives.

---

## 🏛️ Platform Ecosystem

### 👤 Member Hub
Curated professional directory with role-based access control.
- **Global Networking**: Connecting professionals across 45+ countries.
- **Dynamic Profiles**: Showcase bio, professional skills, and social presence.
- **RBAC**: Tiered access for Admins, Chapter Leads, and Verified Members.

### 💡 Vision 2035 Engine
The platform's "Think Tank" for developmental proposals.
- **Proposal Lifecycle**: Structured submission and review for nation-building ideas.
- **Impact Tracking**: Categorized by sectors (Tech, Education, Infrastructure).

### 📅 Strategic Events & Gallery
Robust management for summits and archives.
- **Event Lifecycle**: Automatic transitions between Upcoming and Past events.
- **Cinematic Gallery**: Masonry-style visual archives with optimized image delivery.

### ✍️ Insights Engine
Professional publishing platform for GYAN's collective wisdom.
- **Editorial Workflow**: Multi-stage approval for ensuring high-quality content.

---

## 🛠️ Tech Stack & Architecture

Built with a **"High-Impact, Low-Cost"** philosophy—achieving agency-level quality through surgical use of Vanilla PHP and modern utility frameworks.

- **Backend**: Vanilla PHP 8.1+ (PDO with Prepared Statements)
- **Database**: MySQL 5.7+ (Performance-indexed schema)
- **Frontend**: Tailwind CSS & Alpine.js for a responsive, reactive UI.
- **Mailing**: PHPMailer (SMTP-secured OTP & Alert system)
- **Routing**: Optimized `.htaccess` for clean URLs and POST data integrity.

---

## 🚀 Getting Started

### 1. Requirements
- PHP 8.1+
- MySQL 5.7+
- Apache (with `mod_rewrite` enabled)

### 2. Installation
1. Clone the repository to your web root.
2. **Environment**: Copy `.env.example` to `.env` and configure your database and SMTP credentials.
3. **Database**: Import the schema and initial migrations from `/database/migrations/`.
4. **Permissions**: Ensure `/public/uploads` is writable by the web server.

### 3. Routing Configuration
The platform uses custom URL rewriting. Ensure Apache allows overrides:
```apache
<Directory "path/to/gyan">
    AllowOverride All
</Directory>
```

---

## 🔐 Security & Stability

We implement a multi-layer security approach:
- **Environment Isolation**: Sensitive credentials are kept out of the codebase via `.env`.
- **SQLi Protection**: 100% PDO-based prepared statements.
- **Brute-Force Defense**: IP-based rate limiting on authentication gateways.
- **CSRF Fortification**: Session-bound tokens for all state-changing actions.
- **POST Integrity**: Custom routing rules to prevent data loss during URL cleaning.

---

## 📂 Organizational Structure
- `/public`: Web root and secure entry point.
- `/includes`: Core modular engine and logic layers.
- `/config`: Centralized connection and environment settings.
- `/database`: Migrations and performance optimization scripts.
- `/docs`: Technical guides, roadmaps, and maintenance handbooks.

---

*“The Global Youth Alliance is not just a community; it is a movement dedicated to bridging our roots with our global professional potential.”*

**© 2026 GYAN - Built for National Impact.**
