# foreman-site

Landing page for **Foreman** (reliability layer for scheduled AI agents),
hosted at `foreman.grafisify.com`.

## How it works

- Source of truth is this repo. Edit `index.html` (copy, layout) or
  `subscribe.php` (waitlist endpoint), commit, push to `main`.
- GitHub Actions (`.github/workflows/deploy.yml`) uploads everything to
  the subdomain automatically via FTP. No manual uploads.
- Any AI agent with repo access can edit the page: change files, push,
  done. Design direction lives in `DESIGN.md`.

## One-time setup

1. Create the `foreman` subdomain in the hosting panel
   (document root e.g. `public_html/foreman/`).
2. Create an FTP account that can write to that folder.
3. Add 4 repo secrets (Settings -> Secrets and variables -> Actions):
   `FTP_HOST`, `FTP_USERNAME`, `FTP_PASSWORD`, `FTP_SERVER_DIR`
   (e.g. `/public_html/foreman/`).
4. Telegram alerts: upload `subscribe-config.php` once via File Manager
   (it holds the bot token — gitignored, never committed). Without it the
   form still saves to CSV, just without Telegram alerts.
5. `.htaccess` blocks public download of `waitlist.csv` and the config.

## Files

- `index.html` — the landing page (fonts bundled in `fonts/`)
- `subscribe.php` — waitlist endpoint: validates, dedups, appends to
  `waitlist.csv`, sends a Telegram alert
- `DESIGN.md` — design direction (antislop)
- `foreman-shift-report.html` — not committed; single-file preview build
