# BOCRA Digital Services Platform

A comprehensive digital platform for the Botswana Communications Regulatory Authority (BOCRA) that digitizes regulatory services including complaint management, license applications, domain registrations, and public information access.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [System Architecture](#system-architecture)
- [API Subsystems](#api-subsystems)
- [Getting Started](#getting-started)
- [Environment Variables](#environment-variables)
- [Running with Docker](#running-with-docker)
- [API Documentation](#api-documentation)
- [User Roles](#user-roles)
- [Security](#security)
- [Deployment](#deployment)

---

## Overview

The BOCRA Digital Services Platform transforms manual regulatory processes into a unified digital ecosystem. Citizens can submit complaints, track applications, and verify licenses without visiting a physical office. BOCRA staff manage the full lifecycle of complaints, licenses, and domain registrations through a role-based administrative interface.

---

## Features

### Public Access (No Login Required)
- Submit complaints anonymously
- Track complaint status by reference number
- Search and browse published regulations and advisories
- Verify business license legitimacy
- Check domain name availability
- Public domain ownership lookup

### Citizen & Business Portal
- Submit and track complaints
- Apply for telecommunications licenses
- Register `.bw` domain names
- Real-time notifications on application status
- Full profile management

### Staff & Admin Dashboard
- Review and process complaints with status timeline
- Approve or reject license applications
- Manage domain registrations
- Publish regulatory content and cybersecurity advisories
- Broadcast system-wide notifications
- User management with role-based access control

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 11 (PHP 8.4) |
| Database | PostgreSQL (production) / MySQL (local) |
| Authentication | Laravel Sanctum (token-based) |
| Containerization | Docker + Apache |
| Hosting | Render |
| API Testing | Custom PHP test runner + Postman |

---

## System Architecture

```
Client (React / Postman / Mobile)
        ↓
Laravel API (Sanctum Auth)
        ↓
Role Middleware → Controllers → Services
        ↓
PostgreSQL Database (Render)
```

---

## API Subsystems

| Subsystem | Description |
|-----------|-------------|
| **Auth** | Registration, login, profile, role management |
| **Complaints** | Full lifecycle — submit, track, review, resolve |
| **Licensing** | Application submission, review, public verification |
| **Domain Registration** | `.bw` domain availability, registration, approval |
| **Notifications** | In-app alerts, unread tracking, admin broadcast |
| **CMS** | Content creation, publishing, archiving, search |
| **User Management** | Admin CRUD, role assignment, account activation |

---

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- MySQL 8.0+ or PostgreSQL 14+
- Docker Desktop (for containerized setup)

### Local Setup (without Docker)

```bash
# Clone the repository
git clone https://github.com/your-username/bocra-system.git
cd bocra-system

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env then run migrations
php artisan migrate

# Seed test data
php artisan db:seed

# Start the development server
php artisan serve
```

---

## Environment Variables

Create a `.env` file in the project root with the following variables:

```env
APP_NAME="BOCRA System"
APP_ENV=local
APP_DEBUG=true
APP_KEY=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bocra_db
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
CACHE_DRIVER=array
```

For production (PostgreSQL on Render):

```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=pgsql
DB_HOST=your-render-postgres-host
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

## Running with Docker

The project includes a full Docker Compose setup with Laravel and MySQL running together.

```bash
# Build and start all containers
docker-compose up --build

# Run migrations (in a new terminal)
docker-compose exec app php artisan migrate --force

# Seed the database
docker-compose exec app php artisan db:seed

# Stop containers
docker-compose down
```

The API will be available at `http://localhost:8080/api`

---

## API Documentation

### Base URL
```
Production: https://bocra-2.onrender.com/api
Local:      http://localhost:8080/api
```

### Authentication

All protected routes require a Bearer token in the Authorization header:

```
Authorization: Bearer {your_token}
```

Obtain a token via `POST /api/auth/login`.

### Key Endpoints

#### Auth
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/auth/register` | None | Register new account |
| POST | `/auth/login` | None | Login and get token |
| GET | `/auth/me` | Required | Get own profile |
| POST | `/auth/logout` | Required | Logout current device |

#### Complaints
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/complaints` | None | Submit complaint (guest or user) |
| GET | `/complaints/track/{id}` | None | Track complaint by reference |
| GET | `/complaints` | Required | List complaints |
| PUT | `/complaints/{id}/status` | Staff/Admin | Update complaint status |

#### Licensing
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/licenses/verify?search=` | None | Public license verification |
| POST | `/licenses` | Business | Submit license application |
| PUT | `/licenses/{id}/status` | Staff/Admin | Approve or reject |

#### Domains
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/domains/check` | None | Check domain availability |
| GET | `/domains/lookup` | None | Public domain lookup |
| POST | `/domains` | Required | Register domain |
| PUT | `/domains/{id}/status` | Staff/Admin | Approve or reject |

#### CMS
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/contents` | None | List published content |
| GET | `/contents?search=` | None | Search content |
| POST | `/contents` | Staff/Admin | Create content |
| PUT | `/contents/{id}/publish` | Staff/Admin | Publish draft |

---

## User Roles

| Role | Access Level | Capabilities |
|------|-------------|--------------|
| **Citizen** | Basic | Submit complaints, track applications, view public data |
| **Business** | Standard | Everything citizen can do + apply for licenses and domains |
| **Staff** | Medium | Review and process complaints, licenses, domains, manage CMS |
| **Admin** | Full | All staff capabilities + user management, system configuration |

### Test Credentials (seeded)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@bocra.bw | Admin@1234 |
| Staff | staff1@bocra.bw | Staff@1234 |
| Business | rep@mascom.bw | Business@1234 |
| Citizen | john@gmail.com | Citizen@1234 |

---

## Security

- **Authentication**: Laravel Sanctum token-based API authentication
- **Authorization**: Role-based access control via custom `RoleMiddleware`
- **Input Sanitization**: XSS prevention via `SanitizeInputMiddleware`
- **SQL Injection Prevention**: `PreventSqlInjectionMiddleware` + Eloquent ORM parameterized queries
- **Security Headers**: `SecurityHeadersMiddleware` adds CSP, X-Frame-Options, X-Content-Type-Options
- **Account Protection**: Inactive account blocking, token revocation on password change
- **Rate Limiting**: Laravel throttle middleware on sensitive routes

---

## Deployment

The application is containerized with Docker and deployed on Render.

### Live URL
```
https://bocra-2.onrender.com/api
```

### Deploy Steps

1. Push to GitHub (`feature/Backend-hosting` branch)
2. Render automatically detects the Dockerfile and builds the image
3. On startup, the container runs `php artisan migrate --force` then starts Apache
4. Environment variables are managed via Render's dashboard

> **Note:** Render free tier spins down after 15 minutes of inactivity. The first request after sleep may take 20–30 seconds.

---

## Running API Tests

A full automated test suite is included:

```bash
# Make sure the server is running first, then:
php test_api.php
```

The test runner covers 60+ endpoints across all subsystems including security tests, role enforcement, and edge cases. It outputs a pass/fail summary with a score percentage.

---

## License

This project was built for the BOCRA Digital Transformation Hackathon 2025.
