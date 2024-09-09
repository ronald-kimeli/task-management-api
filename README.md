Task Management System API 
==========================

This is a task management API based on laravel API, basically a user can add category (e.g New Feature) and Task for management.

Table of Contents
-----------------

* [Requirements](#requirements)
* [Features](#features)
* [Installation](#installation)
* [Usage](#usage)

Requirements
------------

* [PHP 8.2+][php]
* [Laravel 11][laravel]
* [PostgreSQL][postgresql]
* [Node.js][node] 16+
* [Vite][vite]
* [Postman][postman]

[php]: https://www.php.net/releases/
[laravel]: https://laravel.com/docs/11.x/installation
[postgresql]: https://www.postgresql.org/
[node]: https://nodejs.org
[vite]: https://vitejs.dev
[postman]: https://www.postman.com/

Features
--------

- Create, get all, get a specific, update, delete a Task(s).
- Datavaridation from the incoming requests.
- Task search with their titles, status, due_date.
- Paginated data in pages of 10 each.
- Eloquent relationships and controller responses

Installation
------------

Create .env file through copy

```bash
cp .env.example .env
```

Provide database credentials below in .env file for postgres.

```php
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=Enter_database_name
    DB_USERNAME=Enter_database_user_name
    DB_PASSWORD=Enter_database_password
```
  
Install project dependencies, key generate and clear cached config

```php
composer install && php artisan key:generate && php artisan optimize
```
Now seed db with dummy data and start the server.

```php
php artisan migrate --seed && php artisan serve 
```

Install nodejs dependencies and run the server

```javascript
npm install && npm run dev
```

Laravel will run on port 8000 and node most likely 5173 or 3000

```php
localhost:8000 || localhost:5173
```

Usage
------------

Use postman for testing the api endpoints, once installed navigate to postman and import the provided `Task Management Assignment API.postman_collection.json` inside this project then follow next steps.

## Postman configuration

  all api endpoints should have /api/v1/. [http://127.0.0.1:8000/api/v1] !> Note that there is two main folders (public routes - no authentication needed and protected routes)

    For Protected routes start with either Login or Register then copy token for authorizing these requests

![alt text](https://github.com/ronald-kimeli/task-management-api/blob/master/public/images/login.png?raw=true)

    Paste the token as Bearer token for usage as shown below

![alt text](https://github.com/ronald-kimeli/task-management-api/blob/master/public/images/highlighted.png?raw=true)

    Finaly, make a request

![alt text](https://github.com/ronald-kimeli/task-management-api/blob/master/public/images/final.png?raw=true)













