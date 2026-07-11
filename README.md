# Forge AI

Forge AI is an AI-powered software quality platform. It connects to GitHub, imports repositories (your own or any public one), and runs AI-driven analysis across code quality, security, performance, architecture, technical debt, and documentation — then tracks the results as reports and dashboards you can revisit over time.

It's a classic Laravel + Blade + Tailwind app (no SPA framework) backed by SQLite, with Alpine.js for interactivity and Chart.js for visualizations.

## Features

- **GitHub integration** — connect/disconnect a GitHub account (Laravel Socialite OAuth), sync every repository the account can see (owner, collaborator, or organization member).
- **Import public repositories** — pull in any public GitHub repository by `owner/repo` or URL, even ones outside your own connected account.
- **Team-scoped data** — every user gets a team on registration; repositories, analyses, and reports are scoped to the current team.
- **Repository details** — per-repository page with metadata, statistics, branches, recent commits, and AI analysis status.
- **AI analysis pipeline** — triggers a structured OpenAI review of a repository (metadata, commit history, and real source file contents when available), scored across code quality, security, performance, architecture, technical debt, and documentation.
- **Dashboard** — team-wide health/security/quality/technical-debt scores, recent activity, analysis history, and Chart.js visualizations.
- **Reports** — browsable history of past analyses per repository.
- **User preferences** — preferred AI model, theme (light/dark/system), and notification toggles.

## Requirements

- PHP 8.3+
- Composer
- Node.js (build-time only, for Tailwind/Alpine/Chart.js assets — never required at runtime)
- SQLite (bundled with PHP's `pdo_sqlite` extension)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run build
```

### GitHub OAuth

Create a GitHub OAuth App (GitHub → Settings → Developer settings → OAuth Apps) with the callback URL `http://localhost/github/callback` (or your `APP_URL` equivalent), then set:

```
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI="${APP_URL}/github/callback"
```

### OpenAI (AI analysis)

```
OPENAI_API_KEY=
OPENAI_MODEL=gpt-4o-mini
```

### Running the app

```bash
php artisan serve
```

Frontend assets (Tailwind/Alpine/Chart.js, via Vite) are built separately with `npm run dev` (watch mode) or `npm run build` (production) — the Laravel app itself never runs Node.js at request time.

## Testing

```bash
php artisan test
```

## Project structure

```
app/
├── Domain/
│   ├── Auth/Actions/          # User + team creation on registration
│   └── Github/Actions/        # GitHub sync/import/connection actions
├── Http/Controllers/          # Thin controllers, one per resource
├── Models/                    # Eloquent models
├── Services/
│   ├── Analysis/              # Builds repository context, orchestrates AI runs
│   └── OpenAi/                # OpenAI chat client wrapper
└── Support/                   # Small cross-cutting helpers (e.g. Toast)
```

## Tech stack

- Laravel 13, Breeze (session auth)
- Laravel Socialite (GitHub OAuth)
- OpenAI API (structured JSON analysis)
- Blade + Tailwind CSS + Alpine.js
- Chart.js (dashboard visualizations)
- Spatie Laravel Permission (team-scoped roles)
- SQLite
