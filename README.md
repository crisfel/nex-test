# Mini CRM - Gestión de Clientes y Contactos

Prueba técnica: mini CRM monolito con Laravel 11 + Vue 3 (Composition API) + Pinia + Laravel Sanctum.
Con exportación de datos a Excel y PDF, y Dockerizado.

---

> ⚠️ **IMPORTANTE — PUERTOS**
>
> | Modo               | Puerto | URL                    |
> |--------------------|--------|------------------------|
> | 🐳 Opción A: Docker | **8001** | **http://localhost:8001** |
> | 💻 Opción B: Local  | **8000** | **http://localhost:8000** |
>
> **No los confundas.** Si usas Docker el puerto es **8001**. Si ejecutas local es **8000**.

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone <repo-url>
cd test-nex
```

### 2. Elegir modo de ejecución

Una vez clonado, puedes ejecutar la aplicación de dos formas:

---

## 🐳 Opción A: Docker (recomendado)

Usa la imagen pre-construida en Docker Hub. No necesitas PHP, Composer ni Node.js instalados.

### Prerrequisitos
- Docker y Docker Compose

### Pasos

```bash
# Desde la raíz del proyecto clonado:
docker compose up -d
```

> 🐳 **Acceder en: http://localhost:8001 (PUERTO 8001)**

La primera vez descargará la imagen `hunter2460/test-nex:latest` y creará el contenedor de MySQL.

### Detalles del entorno Docker

- Aplicación: **http://localhost:8001**
- MySQL host: puerto **3307** (evita conflictos con MySQL local)
- Las migraciones y seeders se ejecutan automáticamente al arrancar
- Los datos persisten en un volumen Docker (`db_data`)
- La imagen pre-construida está en Docker Hub: `hunter2460/test-nex:latest`

---

## 💻 Opción B: Local (desarrollo)

### Prerrequisitos
- PHP ^8.2
- Composer
- Node.js 18+
- MySQL 8 (o SQLite)

### Pasos

```bash
# Desde la raíz del proyecto clonado:

# 2a. Instalar dependencias PHP
composer install

# 2b. Configurar variables de entorno
cp .env.example .env
# Editar DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD en .env

# 2c. Generar key de la aplicación
php artisan key:generate

# 2d. Crear la base de datos y ejecutar migraciones + seeders
php artisan migrate --seed

# 2e. Instalar dependencias del frontend
npm install

# 2f. Compilar assets
npm run build
```

Luego inicia el servidor:

```bash
php artisan serve
```

> 💻 **Acceder en: http://localhost:8000 (PUERTO 8000)**

**Desarrollo con HMR (Hot Module Replacement):**

```bash
# Terminal 1 — Backend
php artisan serve

# Terminal 2 — Frontend (Vite Dev Server)
npm run dev
```

Los cambios en Vue/CSS se reflejan automáticamente sin recargar la página.

---

## Credenciales de prueba

| Campo  | Valor          |
|--------|----------------|
| Email  | admin@demo.com |
| Pass   | password       |

---

## Características

- CRUD completo de clientes y contactos
- Búsqueda y filtro por estado (Activo / Inactivo)
- Paginación
- Soft deletes en clientes y contactos
- Contacto primario único por cliente
- Exportación a Excel (.xlsx) — `maatwebsite/laravel-excel`
- Exportación a PDF — `barryvdh/laravel-dompdf`
- Autenticación con Laravel Sanctum (token Bearer)
- Notificaciones toast de éxito/error
- Arquitectura monolítica (Laravel renderiza vistas Blade, Vue se monta por página)
- Dockerizado y publicado en Docker Hub

---

## Arquitectura del proyecto

```
app/
├── Exports/
│   └── ClientsExport.php              # Export class para Excel (Maatwebsite)
├── Http/
│   ├── Controllers/
│   │   ├── Auth/                      # LoginController, RegisterController, etc.
│   │   ├── Client/                    # CRUD clientes (single-action __invoke)
│   │   ├── Contact/                   # CRUD contactos (single-action __invoke)
│   │   ├── Export/Client/             # ExportExcelController, ExportPdfController
│   │   └── Web/                       # ShowClientController, EditClientController
│   └── Requests/                      # Form Requests con validación y mensajes en español
├── Models/
│   ├── User.php                       # HasApiTokens + relaciones
│   ├── Client.php                     # SoftDeletes, relaciones
│   └── Contact.php                    # SoftDeletes, relaciones
└── Repositories/
    ├── ClientRepository.php           # Consultas paginadas, búsqueda, filtro por estado
    └── ContactRepository.php          # Creación/actualización con contacto primario único

resources/
├── js/
│   ├── app.js                         # Entry point Vue (mount por data-page en Blade)
│   ├── bootstrap.js                   # Axios instance + interceptor de token
│   ├── stores/                        # Pinia stores (auth, clients, contacts)
│   └── components/                    # AppLayout, Login, Register, ClientList, etc.
└── views/
    ├── layouts/app.blade.php          # Blade layout con @vite()
    ├── auth/                          # login.blade.php, register.blade.php
    └── clients/                       # index.blade.php, form.blade.php, detail.blade.php

routes/
├── api.php                            # API REST (protegidas con auth:sanctum)
└── web.php                            # Rutas Blade (web)
```

---

## API — Endpoints

| Método | Endpoint                        | Auth    | Descripción                          |
|--------|---------------------------------|---------|--------------------------------------|
| POST   | `/api/register`                 | No      | Registrar usuario                    |
| POST   | `/api/login`                    | No      | Iniciar sesión                       |
| POST   | `/api/logout`                   | Sanctum | Cerrar sesión                        |
| GET    | `/api/user`                     | Sanctum | Obtener usuario autenticado          |
| GET    | `/api/clients`                  | Sanctum | Listar clientes (paginado + filtros) |
| POST   | `/api/clients`                  | Sanctum | Crear cliente                        |
| GET    | `/api/clients/{id}`             | Sanctum | Detalle cliente + contactos          |
| PUT    | `/api/clients`                  | Sanctum | Actualizar cliente (id en body)      |
| DELETE | `/api/clients/{id}`             | Sanctum | Eliminar cliente (soft delete)       |
| GET    | `/api/clients/{id}/contacts`    | Sanctum | Listar contactos del cliente         |
| POST   | `/api/clients/{id}/contacts`    | Sanctum | Crear contacto                       |
| PUT    | `/api/contacts`                 | Sanctum | Actualizar contacto (id en body)     |
| DELETE | `/api/contacts/{id}`            | Sanctum | Eliminar contacto (soft delete)      |
| GET    | `/api/export/clients/excel`     | Sanctum | Exportar clientes a Excel            |
| GET    | `/api/export/clients/pdf`       | Sanctum | Exportar clientes a PDF              |

### Filtros en clientes

```
?search=nombre&status=activo&page=1
```

---

## Decisiones técnicas

- **Single-action controllers** (`__invoke`): cada controlador hace una sola cosa (SRP).
- **Patrón Repositorio** con interfaces vinculadas en `RepositoriesServiceProvider`: desacopla la lógica de acceso a datos.
- **Soft deletes**: no se pierden datos accidentalmente.
- **Contacto primario único**: se implementa con transacciones de base de datos en el `ContactRepository`.
- **Validación**: Form Requests de Laravel con mensajes de error en español. Errores 422 campo por campo.
- **Exportación**: `maatwebsite/excel` 3.x y `barryvdh/laravel-dompdf`.
- **Docker**: imagen basada en `php:8.2-apache` con Apache en puerto 8001, MySQL 8.0 con healthcheck, entrypoint que ejecuta migraciones y seeders automáticamente.
