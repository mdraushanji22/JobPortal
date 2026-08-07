<p align="center">
    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" width="80" height="80">
        <rect x="4" y="4" width="56" height="56" rx="14" fill="#2563eb"/>
        <path d="M20 25V22.5C20 20.57 21.57 19 23.5 19H40.5C42.43 19 44 20.57 44 22.5V25" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" fill="none"/>
        <rect x="13.5" y="25" width="37" height="22.5" rx="5.5" fill="#ffffff"/>
        <rect x="29.5" y="30.5" width="5" height="7" rx="2" fill="#2563eb"/>
        <path d="M21 36h7" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round"/>
    </svg>
</p>

# Job Portal

A full-featured job portal built with **Laravel 12**, **Tailwind CSS**, and **Alpine.js** where candidates can search and apply for jobs, employers can post and manage vacancies, and admins can oversee the entire platform.

## Screenshots

> Create a `screenshots/` folder in the project root and put your images there (PNG/JPG). The examples below show the most useful pages to capture.

| Page | Screenshot |
| ---- | ---------- |

| Page     | Screenshot                                                                                                                                  |
| -------- | ------------------------------------------------------------------------------------------------------------------------------------------- |
| Homepage | <img width="960" height="540" alt="Screenshot (4)" src="https://github.com/user-attachments/assets/0c8d9383-3c46-42cd-b2b7-f3de3ad373fc" /> |

|
| Job Listings | <img width="1920" height="1080" alt="Screenshot (5)" src="https://github.com/user-attachments/assets/ec87fdbf-c953-4a72-9d0c-c385fe87266f" />
|
| Job Detail | <img width="1912" height="889" alt="Screenshot 2026-08-03 133212" src="https://github.com/user-attachments/assets/b771f1dc-19be-419d-a777-37fcf2e46031" />
|
| Login | <img width="1920" height="1080" alt="Screenshot (6)" src="https://github.com/user-attachments/assets/25f97e67-fe2b-4b07-b7ef-0e0c81676bf6" />
|
| Register | <img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/8caf7bdc-6a4c-458d-9788-a91c920c5e83" />
|
| Candidate Dashboard | <img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/fa33efcb-f043-481e-a8d9-f9e76037adec" />
|
| Employer Dashboard | <img width="1920" height="1080" alt="Screenshot (14)" src="https://github.com/user-attachments/assets/9711f869-5649-4d7a-ac37-1ae5e7c886b4" />
|
| Admin Dashboard | <img width="1920" height="1080" alt="Screenshot (15)" src="https://github.com/user-attachments/assets/9e122422-c960-451a-987a-e20e1601da7e" /> |

## Tech Stack

| Layer    | Technology                                                  |
| -------- | ----------------------------------------------------------- |
| Backend  | PHP 8.2+, Laravel 12, MySQL                                 |
| Frontend | Blade, Tailwind CSS 4, Alpine.js, Font Awesome 6            |
| Build    | Vite (Laravel Vite Plugin)                                  |
| PDF      | barryvdh/laravel-dompdf                                     |
| Mail     | Laravel Mail (Mailable), SMTP                               |
| Testing  | PHPUnit (Feature tests)                                     |
| Extras   | Laravel Tinker, Laravel Breeze (authentication scaffolding) |

## Implemented Features

### Public / Guest

- Homepage with platform stats (jobs, employers, candidates), featured jobs, and job categories with live counts.
- Public job listing page with **search** (title, description, skills) and **filters** (category, employment type, workplace type, experience level, location, salary) plus **sorting**.
- Public job detail pages (only active + approved jobs are visible; inactive/unapproved return 404).
- Custom JobPortal logo (briefcase mark) across the site, with full **dark mode** support.
- Authentication, registration (candidate / employer roles), email verification, password reset.

### Candidate

- Personal dashboard and profile management (bio, skills, education, experience).
- Browse & view jobs, apply to jobs, and **withdraw** applications.
- **Resume management**: upload multiple resumes, mark one as default, download, and delete.
- **Saved Jobs**: bookmark jobs and manage the list.
- Track applications and view **offer / joining letters** issued by employers (with PDF download).
- Notifications center (read individual or all notifications).

### Employer

- Company profile management (logo, description, industry, website, contact details).
- **Job management**: create, edit, close/reopen, and delete jobs; view job status (pending / approved / rejected).
- Application inbox: view applicants, update application status, and **schedule interviews**.
- **Interview management**: track and update interview status.
- **Offer & Joining Letters**: generate offer/joining letters for selected candidates, edit, send by email, download as PDF, regenerate, and archive/delete.
- Receive notifications when messages/letters are issued.

### Admin

- Admin dashboard with stats (total employers, candidates, jobs, applications, active jobs, pending jobs, today's applications).
- **Employer management**: list, view, verify, suspend.
- **Candidate management**: list and view profiles.
- **Job management**: list all jobs, edit, approve/reject, mark as featured, delete.
- **Application management**: view all applications across the platform.
- **Letter management**: view, archive, and delete all letters.
- **Reports** page and **Site Settings** management.

### Shared / Cross-Cutting

- **Role-based access control** via `RoleMiddleware` (`role:admin`, `role:employer`, `role:candidate`).
- **Conversation-based messaging** between candidates and employers per application, including file **attachments** (preview + download).
- **In-app notifications** for candidates (letters issued, etc.).
- **Email notifications** (offer/joining letters) via `LetterMail`.
- **PDF generation** for offer/joining letters via `LetterPdfService` (dompdf).
- **Activity logs** and **login logs** tracked per user.
- Dark/light theme toggle persisted per user.
- Custom 404/403 handling for inactive jobs and unauthorized access.

## Database Structure

Migrations (`database/migrations/`) cover:

- `users`, `employers`, `candidates` (with `role` on users)
- `categories`, `skills`, `job_listings`, `job_skills` (many-to-many)
- `resumes`, `applications`, `interviews`, `saved_jobs`
- `notifications`, `conversations`, `messages` (with attachments)
- `letters` (offer/joining letters with PDFs)
- `settings`, `activity_logs`, `login_logs`

Key models: `User`, `Employer`, `Candidate`, `JobListing`, `Category`, `Skill`, `Application`, `Resume`, `Interview`, `SavedJob`, `Message`, `Conversation`, `Letter`, `Notification`, `Setting`, `ActivityLog`, `LoginLog`.

## Demo Accounts

Seeded by `DatabaseSeeder` (`AdminSeeder` + `CategorySeeder`):

| Role      | Email                 | Password |
| --------- | --------------------- | -------- |
| Admin     | admin@example.com     | password |
| Employer  | employer@example.com  | password |
| Candidate | candidate@example.com | password |

The employer belongs to **Tech Corp Inc.** (verified).

## Demo Jobs

`JobDemoSeeder` populates the portal with **100 realistic demo jobs** — exactly **10 per category** across 10 categories (Information Technology, Healthcare, Finance, Education, Marketing, Sales, Engineering, Design, Human Resources, Legal). It also creates 21 additional demo employer accounts.

The seeder is **idempotent** (re-running does not create duplicates) and is not registered in `DatabaseSeeder`, so it runs standalone:

```bash
php artisan db:seed --class=JobDemoSeeder
```

## Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Install frontend dependencies
npm install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Configure database (MySQL) in .env, then migrate
php artisan migrate

# 5. Seed roles, demo accounts, categories and (optionally) demo jobs
php artisan db:seed
php artisan db:seed --class=JobDemoSeeder   # optional: 100 demo jobs

# 6. Link storage (for logo/resume/PDF uploads)
php artisan storage:link

# 7. Build assets & run
npm run dev        # or: npm run build
php artisan serve
```

> Note: `php artisan test` runs against an in-memory SQLite DB (see `phpunit.xml`). Some Breeze default tests (`ExampleTest`, `ProfileTest`, `RegistrationTest`) fail because this app uses a custom `role`-based registration and does not define the `/profile` route; the project-specific suites (`LetterTest`, `MessagingConversationTest`, `MessagingAttachmentTest`, `Auth`) pass.

## Running Tests

```bash
php artisan test
```

## Project Structure Highlights

```
app/
  Http/Controllers/        # Admin, Employer, Candidate, Auth + public controllers
  Http/Middleware/         # RoleMiddleware (role-based access)
  Mail/                    # LetterMail (offer/joining letter emails)
  Models/                  # Eloquent models
  Services/                # LetterPdfService (dompdf generation)
database/
  migrations/              # Full schema
  seeders/                 # AdminSeeder, CategorySeeder, JobDemoSeeder
resources/views/
  layouts/                 # public, guest, admin, employer, candidate layouts
  components/              # sidebars, headers, buttons, logo, etc.
  jobs/ auth/ admin/ employer/ candidate/ messages/ letters/ emails/ profile/
tests/Feature/             # Feature tests (letters, messaging, auth, profile)
```

## License

This is a demo/learning project built on the Laravel framework, which is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
