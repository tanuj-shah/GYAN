# GYAN Project Documentation

## 1. Executive Summary
**Project Name:** GYAN (Global Youth Alliance for Nepal)
**Vision:** A premium, "elite-tier" digital platform designed to unite Nepali youth worldwide. The platform focuses on professional networking, mentorship, and national development.
**Core Philosophy:** "Connecting, Inspiring, & Channeling global community of Nepali directly into mainstream development of Nepal."

## 2. Technical Architecture
*   **Backend:** Vanilla PHP 8+ (No framework).
*   **Database:** MySQL (Relational schema).
*   **Frontend:** HTML5, Tailwind CSS (Utility-first), Alpine.js (Interactivity).
*   **Libraries:** AOS (Scroll animations), Swiper (Carousels), Custom Canvas Fog Effect.
*   **Server:** Apache (XAMPP environment).

## 3. Directory Structure
*   **`config/`**: Database credentials and connection settings.
*   **`database/`**: SQL migration files vs `schema.sql` (single source of truth).
*   **`includes/`**: Core backend logic and reusable components.
    *   `auth.php`: Registration, Login, Session handling.
    *   `events.php`: Event fetching, filtering, and status logic.
    *   `admin.php`: Admin dashboard stats, CRUD operations.
    *   `functions.php`: Global helper functions.
*   **`public/`**: Web root.
    *   `api/`: Internal API endpoints for AJAX requests.
    *   `admin/`: Admin dashboard pages (protected).
    *   `css/` & `js/`: Asset files.
    *   `uploads/`: User-uploaded content (images).

## 4. Database Schema
The database consists of 7 main tables:

1.  **`users`**: Authentication data (`email`, `password_hash`, `role` [user/admin], `status`).
2.  **`profiles`**: Extended user info (`full_name`, `bio`, `profession`, `social_links`).
3.  **`events`**: Event management (`title`, `slug`, `event_date`, `location`, `status` [upcoming/past], `registration_enabled`).
4.  **`gallery_images`**: Images linked to specific events.
5.  **`blogs`**: User-submitted content (pending approval).
6.  **`vision_2035_proposals`**: Community development proposals (`title`, `impact_area`, `description`, `status`).
7.  **`contact_messages`**: Inquiries from the contact form.

## 5. Core Modules & Logic

### Authentication (`includes/auth.php`)
*   **Register:** Creates User + Profile record transactionally.
*   **Login:** Verifies password hash and checks `active` status.
*   **RBAC:** Simple Role-Based Access Control (`user` vs `admin`).

### Event System (`includes/events.php`)
*   **Logic:** Events automatically transition from `upcoming` to `past` based on date comparison.
*   **Features:** Registration links, customizable deadlines, and gallery associations.

### Vision 2035 Engine (`includes/vision2035.php`)
*   **Purpose**: A dedicated module for gathering high-impact national development proposals.
*   **Features**: Multi-stage submission forms, impact categorization, and profile-linked authorship.

### Communication System (`includes/mail.php`)
*   **Provider**: PHPMailer integration for reliable email delivery.
*   **Use-cases**: OTP verification, welcome emails, and contact form notifications.

### Admin Dashboard (`includes/admin.php`)
*   **Stats:** Real-time counters for Users, Events, Messages.
*   **Management:**
    *   **User Management:** Approve/Suspend users.
    *   **Content:** Approve Blogs, Manage Vision 2035 Proposals, Create Events.

## 6. Internal API Reference
Located in `public/api/`. These endpoints return JSON.

*   **Auth**:
    *   `POST /api/auth/login.php`
    *   `POST /api/auth/register.php`
*   **Admin**:
    *   `POST /api/admin/create_event.php`
    *   `POST /api/admin/update_event.php`
    *   `POST /api/admin/blog-status.php` (Approve/Reject)
    *   `POST /api/admin/upload_gallery.php`

## 7. Frontend System (`public/css/style.css`, `public/js/script.js`)

### Design Design Tokens
*   **Colors:** Crimson (#DC143C), Royal Blue (#003893), Gold (#D4AF37).
*   **Typography:** Montserrat (Headings), Inter (Body), Playfair Display (Serif accents).

### Visual Effects
*   **Fog Overlay:** HTML5 Canvas animation for a cinematic hero effect.
*   **Glassmorphism:** CSS `backdrop-filter: blur(20px)` used in Nav and Cards.
*   **Micro-interactions:** Hover states on "Pillar Cards" and "News Grid".

## 8. Setup & Deployment
1.  **Environment:** PHP 8.0+, MySQL 5.7+.
2.  **Installation:**
    *   Import `database/schema.sql` into MySQL.
    *   Configure `config/database.php` with DB credentials.
    *   Serve `public/` directory.

## 10. Strategic Roadmaps (2026 Audit)
A series of comprehensive strategic audits and improvement roadmaps were completed in February 2026. Refer to the specific documents in `docs/` for implementation details:

*   **Dashboards**: [Dashboard](roadmaps/dashboard_analysis.md), [Admin Dashboard](roadmaps/admin_dashboard_analysis.md)
*   **Core Pages**: [Homepage](roadmaps/homepage_analysis.md), [About Page](roadmaps/about_analysis.md), [Contact Hub](roadmaps/contact_analysis.md)
*   **Engagement**: [Member Directory](roadmaps/directory_analysis.md), [Events & Initiatives](roadmaps/events_analysis.md), [Insights Blog](roadmaps/blog_analysis.md)
*   **Onboarding**: [Login Security](roadmaps/login_analysis.md), [Registration Flow](roadmaps/register_analysis.md)

## 11. Key Workflows for LLMs
To assist an LLM in generating code:
*   **Aesthetics**: Always refer to the [Strategic Roadmaps](docs/) for the latest design intentions and recommended feature improvements.
*   **Styling:** Always use the defined CSS variables (`--crimson`, `--gold`) or Tailwind utility classes that match the "Elite" aesthetic.
*   **Database:** Refer to `schema.sql` for exact column names.
*   **Security:** Ensure all new pages include `require_once __DIR__ . '/../includes/auth.php';` if protection is needed.
