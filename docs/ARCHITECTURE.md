# Architecture Documentation – MKT Code

This document explains the architecture of the project and the main technical decisions behind the implementation.

---

## Overview

Although this project is a website, it was built using a domain‑oriented architecture focused on scalability, maintainability and code quality.

The goal was to create a system that can grow without becoming difficult to maintain.

---

## Architecture Layers

The project is organized into four main layers:

```
Domain
Application
Infrastructure
Interfaces
```

---

## Folder Structure

```
app/
├── Domain/
├── Application/
├── Infrastructure/
└── Interfaces/
```

---

## Domain Layer

The Domain layer contains only business logic and rules.

It does NOT depend on:
- Laravel
- Controllers
- Eloquent
- Filament
- HTTP

It contains:
- Domain rules
- Enums
- Value Objects
- Repository contracts

---

## Application Layer

The Application layer is responsible for orchestrating use cases.

It contains:
- Commands
- Queries
- DTOs
- Application services

This layer connects the Domain with Infrastructure.

---

## Infrastructure Layer

The Infrastructure layer contains everything related to technology.

Examples:
- Eloquent repositories
- SEO builders
- Sitemap generator
- External integrations
- Persistence

---

## Interfaces Layer

The Interfaces layer connects the application with the outside world.

It contains:
- HTTP Controllers
- Filament resources
- Requests
- API resources
- Inertia responses

---

## Domain Contexts

The application is divided into multiple contexts:

### Content
Blog posts and SEO content.

### Portfolio
Projects and portfolio pages.

### Inquiry
Contact forms and lead generation.

### Identity
Users and public profile.

### Shared
Shared utilities (SEO, sitemap, helpers, etc.)

---

## Key Architecture Decisions

### 1. Thin Controllers
Controllers only receive requests and call commands/queries.

### 2. Business Logic Outside Models
Models are used only for persistence.

### 3. Domain‑Oriented Structure
The code is organized by domain, not by framework structure.

### 4. SEO as a System Responsibility
SEO is not handled manually in each page, but through structured logic.

---

## Goal of This Architecture

The main goal is to ensure:

- Scalability
- Maintainability
- Low coupling
- Clean code
- Long‑term sustainability
