# Simple PHP File Hosting Demo

This repository contains a minimal PHP application that demonstrates a basic cloud storage system. Users can register, log in, upload files, and download them later. Each account can store up to **30 GiB** of data. Uploaded files are stored on the server, and a small API endpoint returns a JSON list of a user's files. Additional metadata such as file size and type are tracked, and files can be deleted from the dashboard. A profile page allows password changes and a simple API provides programmatic upload and delete capabilities. Statistics and activity logs are available and the interface uses a simple cloud-themed style. Files may optionally be shared via unique links.

## Features
- User registration and login using SQLite
- File uploads of any type
- Dashboard listing uploaded files with download links and delete actions
- JSON API listing user files with metadata
- Profile page with password change form
- API endpoints for uploading and deleting files
- Server statistics page with disk and memory usage
- Activity log page showing recent uploads and deletions
- Optional public share links for files
- Each account includes up to **30 GiB** of storage
- Clean cloud-themed CSS styling

### APIs
* `GET /api/files.php` - list your files
* `POST /api/upload.php` with `file` field - upload new file
* `POST /api/delete.php` with `id` - delete a file
* `GET /api/profile.php` - current user details
* `GET /api/stats.php` - server statistics
* `GET /api/activity.php` - recent upload/delete events
* `POST /api/share.php` with `id` to create a share link or `unshare=1` to revoke
* `GET /public.php?token=...` - download a shared file

## Setup
1. Ensure PHP with SQLite support is installed.
2. Run `php src/init_db.php` once to create the SQLite database.
3. Place the contents of the `src` directory in a web-accessible location.
4. Access `index.php` via your web server.
5. The default per-user storage quota is set in `config.php` as `MAX_STORAGE_BYTES`.
6. Shared files can be downloaded via `public.php?token=TOKEN`.

This is a demo and lacks production security features. Use at your own risk.
