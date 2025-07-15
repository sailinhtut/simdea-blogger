[![Simdea Blogger Preview](https://github.com/sailinhtut/simdea-blogger/blob/main/preview/1.png?raw=true)](https://github.com/sailinhtut/simdea-blogger/blob/main/preview/1.png?raw=true)

Simdea Blogger is a modern, clean, and fully responsive blogging platform built with Laravel, using Tailwind CSS and Bootstrap for UI styling. It features stateless authentication via API tokens, full CRUD operations for blog posts, user management, password reset, email verification, and pagination.

## Features

- User registration, login, logout, and profile management
- Password reset and email verification flows
- Create, read, update, and delete (CRUD) blog posts with image upload support
- Responsive design using Tailwind CSS and Bootstrap
- Stateless API authentication using Laravel Sanctum tokens stored in browser storage
- Blog listing with pagination (10 posts per page)
- Detailed blog post view
- Secure and clean code structure following Laravel best practices

## Preview

Here are some screenshots showcasing the app UI:

![Landing Page](https://github.com/sailinhtut/simdea-blogger/blob/main/preview/1.png?raw=true)

![Landing Page](https://github.com/sailinhtut/simdea-blogger/blob/main/preview/2.png?raw=true)

![Landing Page](https://github.com/sailinhtut/simdea-blogger/blob/main/preview/3.png?raw=true)

![Landing Page](https://github.com/sailinhtut/simdea-blogger/blob/main/preview/4.png?raw=true)

![Landing Page](https://github.com/sailinhtut/simdea-blogger/blob/main/preview/5.png?raw=true)

![Landing Page](https://github.com/sailinhtut/simdea-blogger/blob/main/preview/6.png?raw=true)

![Landing Page](https://github.com/sailinhtut/simdea-blogger/blob/main/preview/7.png?raw=true)

## Installation

### Clone the repository  
   ```bash
   git clone https://github.com/sailinhtut/simdea-blogger.git
   cd simdea-blogger
   ```

### Install dependencies

```bash
composer install
npm install
```
Edit database and smtp configration in .env and configure your environment variables (database, mail, etc.).

Run migrations.

```bash
php artisan migrate 
```

Build assets with Vite

```bash
npm run dev
```

Start the Laravel development server

```bash
php artisan serve
```

Access the app at http://localhost:8000

### Usage
Register or login with your credentials in Postman interface. I put postman export file in repository.

Create new blog posts with optional heading images

View, edit, or delete your posts

Manage your profile and update password/email

Use the forgot password and reset password flows for account recovery

### Contributing
Feel free to submit issues or pull requests. Please adhere to the existing code style and ensure all tests pass before submitting.