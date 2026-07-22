<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<h1 align="center">Laravel 12 Backend Assessment API</h1>

<p align="center">
  A secure, modular, and scalable RESTful API built with <strong>Laravel 12</strong> for managing user profiles and simulating dynamic delivery slot allocation logic.
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <img src="https://img.shields.io/badge/PHP-%3E%3D8.2-777BB4?logo=php&logoColor=white" alt="PHP Version">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white" alt="Laravel Version">
  <img src="https://img.shields.io/badge/Database-MongoDB-47A248?logo=mongodb&logoColor=white" alt="MongoDB">
  <img src="https://img.shields.io/badge/Auth-JWT-000000?logo=jsonwebtokens&logoColor=white" alt="JWT Auth">
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License: MIT"></a>
</p>

---

## 📖 About The Project

This project was developed as part of a **Backend Developer Technical Assessment**. It implements two core deliverables:

1. **User Profile Management API** — a complete, secure, and production-style RESTful CRUD service backed by MongoDB, protected by JWT authentication.
2. **Delivery Slot Allocation Logic** — a documented pseudocode design (see [`delivery-slot-allocation.md`](./delivery-slot-allocation.md)) describing how a real backend would dynamically assign delivery slots, prevent overbooking, and suggest alternatives in real time.

The project emphasizes clean architecture, request validation, centralized error handling, and a maintainable, layered folder structure (Controllers → Services → Repositories → Models), rather than putting business logic directly in controllers.

---

## 🚀 Features

### User Profile Management
- Full CRUD operations for user profiles.
- MongoDB as the primary datastore (via `mongodb/laravel-mongodb`).
- Auto-generated ID and `created_at` / `updated_at` timestamps.
- Enforced **unique email** validation.
- Optional **age filtering** on the listing endpoint.
- **Pagination** support out of the box.
- Centralized Form Request validation (`app/Http/Requests`).
- Consistent API responses via API Resources (`app/Http/Resources`).

### Authentication & Security
- Token-based authentication using **JWT** (`php-open-source-saver/jwt-auth`).
- Access token + refresh token flow.
- Route protection via middleware — every sensitive endpoint requires a valid Bearer token.
- Centralized exception handling for clean, predictable error responses.
- Input sanitization and strict validation rules to prevent malformed or malicious input.

### Architecture & Code Quality
- Modular, layered structure: `Controllers`, `Services`, `Repositories`, `DTOs`, `Filters`, `Requests`, `Resources`, `Traits`.
- Business logic kept out of controllers and pushed into dedicated Service/Repository classes.
- Code style enforced with **Laravel Pint**.
- Test suite scaffolded with **PHPUnit**.

### Documentation & DX
- Auto-generated, interactive API documentation via **Laravel Scramble** (`/docs/api`).
- Local development stack orchestration via `composer dev` (server, queue listener, log tailing via **Laravel Pail**, and Vite, all running concurrently).

---

## 🛠 Tech Stack

| Layer | Technology |
| :--- | :--- |
| Framework | Laravel 12 |
| Language | PHP ^8.2 |
| Database | MongoDB (`mongodb/laravel-mongodb`) |
| Authentication | JWT (`php-open-source-saver/jwt-auth`) + Laravel Sanctum |
| API Docs | Laravel Scramble |
| Testing | PHPUnit, Mockery, Faker |
| Code Style | Laravel Pint |
| Dev Tooling | Laravel Sail, Laravel Pail, Laravel Tinker |

---

## 📂 Project Structure

```
app/
├── DTOs/                 # Data Transfer Objects
├── Filters/               # Query filters (e.g. filtering users by age)
├── Http/
│   ├── Controllers/       # Thin controllers — delegate to Services
│   ├── Middleware/        # JWT auth guard, etc.
│   ├── Requests/          # Form Request validation classes
│   └── Resources/         # API Resource transformers
├── Models/                 # Eloquent / MongoDB models
├── Repositories/          # Data access layer
├── Services/                # Business logic layer
└── Traits/

routes/
└── api.php                 # All API route definitions

database/
├── migrations/
└── seeders/

tests/
├── Feature/
└── Unit/

delivery-slot-allocation.md   # Task 2 — pseudocode & design write-up

postman/
├── Backend-Assessment.postman_collection.json
└── Backend-Assessment.postman_environment.json
```

This separation keeps controllers thin, business logic testable, and data access swappable — a Service can be reused across multiple controllers, and Repositories abstract away the underlying MongoDB queries.

---

## 🔌 API Endpoints

> All endpoints are prefixed with `/api`. Except where marked **Public**, every request must include:
> ```
> Authorization: Bearer <your_access_token>
> ```

### 🔐 Authentication

| Method | Endpoint | Description | Access |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/register` | Register a new user account | Public |
| `POST` | `/api/login` | Authenticate and receive access/refresh tokens | Public |
| `GET` | `/api/me` | Get the currently authenticated user's profile | Protected |
| `POST` | `/api/refresh` | Exchange a refresh token for a new access token | Protected |
| `POST` | `/api/logout` | Revoke the current session/token | Protected |

### 👤 Users

| Method | Endpoint | Description | Access |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/users` | List all users (supports pagination & `?age=` filter) | Protected / Admin |
| `GET` | `/api/users/{id}` | Get a single user by ID | Protected |
| `POST` | `/api/users` | Create a new user | Protected / Admin |
| `PUT` | `/api/users/{id}` | Update a user by ID | Protected |
| `DELETE` | `/api/users/{id}` | Delete a user by ID | Protected / Admin |

#### Example — Register

```http
POST /api/register
Content-Type: application/json

{
  "name": "Ziad Shalaby",
  "email": "ziad@example.com",
  "password": "SecurePass123!",
  "age": 25
}
```

#### Example — List users with filtering & pagination

```http
GET /api/users?age=25&page=1&per_page=15
Authorization: Bearer <token>
```

Full request/response schemas, including validation error formats, are available in the interactive documentation at **`/docs/api`** once the server is running.

---

# 📦 Delivery Slot Allocation Logic (Task 2)

The design for dynamic delivery slot allocation is documented separately as detailed pseudocode in **[`delivery-slot-allocation.md`](./delivery-slot-allocation.md)** at the project root. It covers:

- How incoming booking requests are processed.
- How slot availability is checked and capacity is enforced to prevent overbooking.
- How alternative slots are suggested when a preferred slot is full.
- How slots — as a shared, contended resource — are safely updated under concurrent requests (locking/atomic capacity updates).

---

## 📚 API Documentation

Interactive API documentation is auto-generated with **[Laravel Scramble](https://scramble.dedoc.co)** directly from the route definitions and Form Requests — no manual annotation needed.

Once the app is running locally, visit:

```
http://127.0.0.1:8000/docs/api
```

It includes:
- All available endpoints, grouped by resource.
- Required/optional request parameters and validation rules.
- Example requests and responses.
- Authentication requirements per endpoint.

---

## 📮 Postman Collection

A ready-to-use Postman collection and environment are included in the repository so reviewers can test every endpoint without manual setup.

```
postman/
├── Backend-Assessment.postman_collection.json
└── Backend-Assessment.postman_environment.json
```

**How to use:**

1. Open Postman → **Import** → select both files from the `postman/` folder (or drag-and-drop them).
2. In the top-right environment selector, choose **Backend Assessment** (or whatever name you gave the environment).
3. Update the environment variable `base_url` if you're not running on `http://127.0.0.1:8000`.
4. Run **Register** or **Login** first — the collection is set up to automatically save the returned `access_token` (and `refresh_token`) into the environment variables, so every protected request afterward is authenticated automatically.

**Environment variables included:**

| Variable | Description |
| :--- | :--- |
| `base_url` | Base API URL (default: `http://127.0.0.1:8000/api`) |
| `access_token` | Auto-filled after login/register |
| `refresh_token` | Auto-filled after login, used by `/api/refresh` |

> ⚠️ Note: the environment file does **not** contain real secrets — only placeholder/local values — so it's safe to commit to a public repository.

---

## ⚡ Performance Optimization

- MongoDB **indexes** on frequently queried and unique fields (e.g. `email`).
- Efficient, cursor-based **pagination** to avoid loading full collections.
- Query logic isolated in Repositories to allow targeted optimization without touching business logic.
- Lightweight API Resources to avoid over-fetching / leaking internal fields in responses.

---

## ⚙️ Installation & Setup

### Requirements

Make sure the following are installed on your machine:

- PHP **>= 8.2**
- [Composer](https://getcomposer.org)
- MongoDB (local instance or a connection string, e.g. MongoDB Atlas)
- The `mongodb` PHP extension enabled (`php -m | grep mongodb`)
- Node.js & npm (for asset building via Vite)

### 1. Clone the repository

```bash
git clone <repository-url>
cd project-name
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Then open `.env` and set your MongoDB connection and JWT secret:

```env
DB_CONNECTION=mongodb
DB_URI=mongodb://127.0.0.1:27017/laravel_assessment

JWT_SECRET=          # generate with: php artisan jwt:secret
```

Generate the JWT secret:

```bash
php artisan jwt:secret
```

### 4. Run migrations & seeders

```bash
php artisan migrate
php artisan db:seed
```

### 5. Start the development environment

The project ships with a single command that boots the server, queue listener, log tailing (Pail), and Vite dev server concurrently:

```bash
composer dev
```

Or, to run the API server only:

```bash
php artisan serve
```

The API will then be available at:

```
http://127.0.0.1:8000
```

And the interactive docs at:

```
http://127.0.0.1:8000/docs/api
```

---

## 🧪 Testing

Run the automated test suite (Feature + Unit) with:

```bash
composer test
```

or directly:

```bash
php artisan test
```

---

## 🤝 Contributing

Contributions are **greatly appreciated**.

1. Fork the project.
2. Create your feature branch: `git checkout -b feature/YourFeature`
3. Commit your changes: `git commit -m "Add YourFeature"`
4. Push to the branch: `git push origin feature/YourFeature`
5. Open a Pull Request.

Please make sure to:
- Check spelling/grammar in any docs you touch.
- Keep one suggestion per Pull Request.
- Include a clear, meaningful description of the change.

---

## 🧭 Troubleshooting Tips

- If requests fail unexpectedly, check your terminal output and confirm your `.env` values (especially `DB_URI` and `JWT_SECRET`) are correct.
- Ensure your MongoDB service is actually running before starting the app.
- Run `php artisan config:clear` if environment changes don't seem to take effect.
- Refer to the [Laravel documentation](https://laravel.com/docs) for anything framework-related.

---

## ✍️ Author

**Ziad Shalaby** — *Software Engineer*
[GitHub Profile](https://github.com/ZeadShalaby)

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  </a>
</p>
