# SEO Architecture – MKT Code

This document explains how SEO is implemented in the project and why it is treated as a system responsibility instead of manual page configuration.

---

## Philosophy

SEO is not handled using static meta tags inside templates.

Instead, the project uses a structured architecture that allows every public page to generate its own SEO data dynamically and consistently.

---

## Goals

The SEO system was designed to:

- Keep SEO logic outside views
- Avoid duplicated meta tag logic
- Allow dynamic pages (posts, projects, profiles) to generate SEO automatically
- Ensure consistency across the entire site

---

## Architecture

The SEO system is divided into:

### 1. SEO Builders
Responsible for building SEO data for a specific domain context.

Examples:

- Post SEO Builder
- Project SEO Builder
- User Profile SEO Builder

---

### 2. SEO Registry

The registry is responsible for selecting the correct SEO builder for each page.

This avoids:
- conditionals in controllers
- duplicated SEO logic

---

### 3. SEO DTO

Instead of passing raw arrays to views, the system uses a structured data object.

This ensures consistency and type safety.

---

## Result

The result is a predictable and scalable SEO architecture that works for both static and dynamic pages.
