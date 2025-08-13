# WMS Backend API

Este proyecto implementa una API REST para la gestión del sistema WMS, con autenticación, endpoints CRUD y pruebas automatizadas.

## Tecnologías utilizadas

- Laravel 11
- PHP 8.3 (dockerizado)
- Docker y Docker Compose
- NGINX como proxy reverso
- Laravel Sanctum para autenticación con tokens
- Pest (framework de testing oficial de Laravel 11)
- Tests automatizados de Autenticación y CRUD
- Colección Postman para pruebas manuales

---

## Requisitos Previos

- Docker
- Docker Compose
- Git

---

## Setup del entorno

### 1 - Clonar el repositorio

```bash
git clone <https://github.com/leonmsaia/wms_test_back.git>
cd wms_backend
```

### 2 - Estructura de carpetas

```
01-backend/
│
├── docker/
│   ├── php/ (Dockerfile de PHP 8.3)
│   └── nginx/ (Configuración de NGINX)
│
├── docker-compose.yml
├── .env
├── composer.json
└── Laravel Application (app, routes, tests, etc.)
```

### 3 - Configuración de entorno

Editar el archivo `.env` con los valores correspondientes:

```
APP_NAME=WMS_API
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=wms
DB_USERNAME=wms_user
DB_PASSWORD=wms_pass

SANCTUM_STATEFUL_DOMAINS=localhost:8000
```

> Nota: Ver datos de ejemplo en .env.example
> 

---

## Levantar el entorno Docker

### 4 - Build inicial

```bash
docker compose build
```

### 5 - Levantar los servicios

```bash
docker compose up -d
```

---

## Inicialización de Laravel

### 6 - Instalar dependencias

```bash
docker compose exec backend-php composer install
```

### 7 - Generar key de aplicación

```bash
docker compose exec backend-php php artisan key:generate
```

### 8 - Ejecutar migraciones

```bash
docker compose exec backend-php php artisan migrate
```

### 9 - Ejecutar seeders

```bash
docker compose exec backend-php php artisan db:seed
```

### 10 - Limpiar cachés

```bash
docker compose exec backend-php php artisan optimize
```

---

## URLs disponibles

- API Laravel → http://localhost:8000/

---

## Autenticación

**Endpoint de Login:**

POST `/api/auth/login`

Body de ejemplo:

```json
{
  "email": "admin@demo.com",
  "password": "secret1234"
}
```

Respuesta:

```json
{
  "access_token": "TOKEN_GENERADO",
  "token_type": "Bearer"
}
```

Todos los endpoints protegidos requieren el header:

```
Authorization: Bearer <token>
```

---

## Endpoints principales

- `POST /api/auth/register` → registrar usuario
- `POST /api/auth/login` → iniciar sesión
- `GET /api/auth/me` → usuario actual
- `POST /api/auth/logout` → cerrar sesión

---

## Tests Automatizados (Pest)

Ejecutar tests con:

```bash
docker compose exec backend-php ./vendor/bin/pest -v
```

Esto reinicializa la base de datos y ejecuta todos los tests de autenticación y CRUD.

---

## Colección Postman

Se incluye en `/docs/postman/WMS_TEST_ENDPOINTS.postman_collection.json` con:

- Autenticación (Login / Logout / Me / Register)
- CRUD de recursos
- Variables predefinidas
- Autenticación mediante variable `access_token`

Para usarla:

1. Importar la colección en Postman
2. Configurar la variable `base_url` con `http://localhost:8000`
3. Ejecutar el request de Login y guardar el token en la variable `access_token`

---
