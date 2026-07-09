# Forge AI

Forge AI is an AI-powered software quality platform. It connects to your GitHub account, imports your repositories, and runs AI-driven analysis across code quality, security, performance, architecture, technical debt, and documentation — then tracks the results as reports you can revisit over time.

It's built as a classic Laravel + Blade + Tailwind app (no SPA framework, no Node.js required at runtime), backed by SQLite.

## Features

- **GitHub integration** — connect/disconnect a GitHub account (Laravel Socialite), import and sync repositories, branches, commits, and pull requests.
- **Team-scoped data** — every user gets a team on registration; repositories, analyses, and reports are scoped to the current team.
- **Repository details** — a per-repository page with repository information, statistics, branches, recent commits, languages, last analysis, and AI status, plus an "Analyze Repository" action.
- **AI analysis pipeline (in progress)** — analyses are queued per repository and scored across multiple categories (code quality, security, performance, architecture, technical debt, documentation).

## Requirements

- PHP 8.3+
- Composer
- SQLite (bundled with PHP's `pdo_sqlite` extension)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
```

### GitHub OAuth

Create a GitHub OAuth App (Settings → Developer settings → OAuth Apps) with the callback URL `http://localhost/github/callback` (or your `APP_URL` equivalent), then set:

```
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI="${APP_URL}/github/callback"
```

### Running the app

```bash
php artisan serve
```

Frontend assets (Tailwind/Alpine, via Vite) are built separately with `npm run dev` / `npm run build` when you need to change styles or JS — the Laravel app itself never runs Node.js.

## Testing

```bash
php artisan test
```

## Tech stack

- Laravel 13, Breeze (session auth)
- Laravel Socialite (GitHub OAuth)
- Blade + Tailwind CSS + Alpine.js
- Spatie Laravel Permission (team-scoped roles)
- SQLite
