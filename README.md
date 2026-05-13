# TechNova - Dynamic Store Setup

Your website has been successfully converted from static HTML to dynamic PHP with an Admin Panel!

## Prerequisites
1.  **Local Server**: Install [XAMPP](https://www.apachefriends.org/) or [WAMPServer](https://www.wampserver.com/en/).
2.  **Database**: Make sure MySQL is running.

## Setup Instructions
1.  **Import Database**: 
    - Open `phpMyAdmin` (usually `http://localhost/phpmyadmin`).
    - Click "Import" and select the `db_setup.sql` file from this project.
    - This will create the `technova_db` database, tables, and a default admin account.

2.  **Configuration**: 
    - Open `config.php`.
    - Ensure `$username` (default: `root`) and `$password` (default: empty) match your MySQL credentials.

3.  **Run the Site**:
    - Open your browser and go to `http://localhost/your-project-folder/index.php`.

## Admin Credentials
- **Username**: `admin`
- **Password**: `$2y$10$vO.Nl2I5X9vH3ez0F6Q0tY6J8P7F3I2G1A5B4C3D2E1F` (Note: In a production environment, change this via the database).

## Features
- **Dynamic Frontend**: The `index.php` page now pulls all products directly from the database.
- **Admin Dashboard**: Access via `admin_login.php`.
- **CRUD Operations**: Admin can Add, Edit, and Delete products.
- **Image Uploads**: Images uploaded in the dashboard are saved to the `/image` folder.
