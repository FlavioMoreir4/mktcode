# Project Structure – MKT Code

This document explains the structure of the repository.

---

## Root Structure

```
app/
bootstrap/
config/
database/
docs/
public/
resources/
routes/
storage/
tests/
```

---

## app/

The application is organized by architecture layers.

```
app/
├── Domain/
├── Application/
├── Infrastructure/
└── Interfaces/
```

---

## resources/

```
resources/
├── js/
├── views/
└── css/
```

---

## routes/

```
routes/
├── web.php
├── api.php
```

---

## docs/

```
docs/
├── ARCHITECTURE.md
├── DOMAIN.md
├── CODE-STYLE.md
├── PROJECT-STRUCTURE.md
```

---

## Goal

This structure is designed to support long‑term scalability and maintainability.
