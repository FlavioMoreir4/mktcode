# MKT Code – Website & Platform

Official repository of the website https://mktcode.digital.

This project was developed as a real application (not just a portfolio website), using Laravel 12, Vue 3, Inertia.js and a domain-oriented architecture focused on code quality and long-term sustainability.

---

## About the Project

MKT Code is the official website of **MC Marketing & Code** and works as:

- Professional portfolio platform
- Technical blog
- Lead generation website
- Public developer profile
- Project showcase system

The goal was not to create a simple institutional website, but to build a platform that reflects the same architectural quality used in real production systems.

---

## Tech Stack

### Backend
- Laravel 12
- PHP 8.2+
- Eloquent ORM
- Domain‑Driven Design (tactical)
- Pest
- PHPStan

### Frontend
- Vue 3
- Inertia.js
- Tailwind CSS
- Vite

### Admin Panel
- Filament

### Infrastructure
- MySQL (production)
- SQLite (development)
- Docker
- GitHub Actions
- Cloudflare

---

## Main Features

### Institutional Website
- Homepage focused on conversion
- Services pages
- About page
- Contact page with working form

### Portfolio
- Real projects with technical description
- Technologies used
- Project types (ERP, SaaS, CRM, etc.)

### Technical Blog
- Full blog system
- SEO structured posts
- Technical content (architecture, Laravel, DDD, etc.)

### Public Profile
```
/u/flaviomoreira
```

Includes:
- Technical stack
- Experience
- Recent articles
- Social links

### Admin Panel
The project includes a complete admin panel using Filament to manage:

- Blog posts
- Portfolio projects
- Institutional content
- Users
- SEO

---

## Architecture

The project uses a domain‑oriented architecture organized in:

```
app/
├── Domain/
├── Application/
├── Infrastructure/
└── Interfaces/
```

Full architecture documentation is available in:

```
docs/ARCHITECTURE.md
```

---

## Installation

```bash
git clone https://github.com/FlavioMoreir4/mktcode.git
cd mktcode
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate
npm run dev
```

---

## Philosophy

This project follows a few clear principles:

- Clean code is not perfectionism — it is respect for the future
- Architecture should be simple but explicit
- SEO is a system responsibility, not just meta tags
- Controllers must be thin
- Domain logic must not live inside models

---

## Author

Flávio Moreira

https://mktcode.digital
https://github.com/FlavioMoreir4
