# Event Management System

## Overview
The Event Management System is a web-based application designed to help users manage events, register attendees, and generate reports. The system includes features for event creation, attendee registration, authentication, and data reporting.

## Features
- **User Authentication**: Secure login and registration system.
- **Event Management**: Create, update, delete, and view events.
- **Attendee Registration**: Guest can register for events, with validation to prevent duplicate registrations.
- **Event Reports**: Authenticate can download event attendee lists in CSV format.
- **JSON API**: Fetch event details programmatically.

## Installation
### 1. Clone the Repository
```sh
$ git clone https://github.com/yourusername/event-management-system.git
$ cd event-management-system
```

### 2. Set Up Database
- Create a MySQL database named `event_management`.
- Import the database schema from `database.sql`:
  ```sh
  $ mysql -u root -p event_management < database.sql
  ```

### 3. Configure Application
- Rename `config.example.php` to `config.php` and update database credentials:
  ```php
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', '');
  define('DB_NAME', 'event_management');
  ```
- Ensure the `config.php` file is writable:
  ```sh
  $ chmod 664 config.php
  ```

### 4. Set Up Permissions
- If running on Linux, set write permissions for required directories:
  ```sh
  $ chmod -R 775 config.php
  ```

### 5. Run the Application
- Start a local server:
  ```sh
  $ php -S localhost:8000
  ```
- Open in the browser: `http:domain.com`

## API Endpoints
- **Get all events**: `GET /api/events`
- **Get event by ID**: `GET /api/events/{id}`

## Troubleshooting
- **Permission Errors**: Ensure correct ownership and file permissions.
- **Database Connection Issues**: Verify credentials in `config.php`.


