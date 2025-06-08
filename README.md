# Simple PHP File Hosting Demo

This repository contains a minimal PHP application that demonstrates a basic cloud storage system. Users can register, log in, upload files, and download them later. Uploaded files are stored on the server, and a small API endpoint returns a JSON list of a user's files. Additional metadata such as file size and type are tracked, and files can be deleted from the dashboard.

## Features
- User registration and login using SQLite
- File uploads of any type
- Dashboard listing uploaded files with download links and delete actions
- JSON API listing user files with metadata

## Setup
1. Ensure PHP with SQLite support is installed.
2. Run `php src/init_db.php` once to create the SQLite database.
3. Place the contents of the `src` directory in a web-accessible location.
4. Access `index.php` via your web server.

This is a demo and lacks production security features. Use at your own risk.
