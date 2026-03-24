# Domain Documentation – MKT Code

This document explains the domain structure of the project and the responsibility of each context.

---

## Why Domain‑Oriented?

Even though this project is a website, it was designed using domain‑oriented architecture to ensure long‑term maintainability and scalability.

---

## Domain Contexts

The project is divided into the following contexts:

### 1. Content
Responsible for blog posts and SEO content.

Includes:
- Post publication
- Visibility rules
- SEO metadata
- Blog structure

---

### 2. Portfolio
Responsible for projects displayed on the website.

Includes:
- Portfolio projects
- Project visibility
- Featured projects
- Public project pages

---

### 3. Inquiry
Responsible for contact forms and lead generation.

Includes:
- Contact requests
- Notifications
- Lead tracking

---

### 4. Identity
Responsible for users and the public developer profile.

Includes:
- Public profile pages
- Authentication
- Admin access rules

---

### 5. Shared
Shared logic used across multiple contexts.

Includes:
- SEO builders
- Sitemap generation
- Shared contracts

---

## Goal of This Structure

The goal is to keep the code organized by business responsibility rather than by framework structure.
