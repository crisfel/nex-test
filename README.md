# Mini CRM - Gestión de Clientes y Contactos

Prueba técnica: mini CRM monolito con Laravel 11 + Vue 3 (Composition API) + Pinia + Laravel Sanctum.
Con exportación de datos a Excel y PDF, y Dockerizado.

---

> ⚠️ **IMPORTANTE — PUERTOS**
>
> | Modo               | Puerto | URL                    |
> |--------------------|--------|------------------------|
> | 🐳 Docker          | **8001** | **http://localhost:8001** |
> | 💻 Local (clonado) | **8000** | **http://localhost:8000** |
>
> **No los confundas.** Si usas Docker el puerto es **8001**. Si clonas y ejecutas local es **8000**.

---

## ⚡ Inicio rápido con Docker Hub

### Prerrequisitos
- Docker y Docker Compose instalados

### Pasos

```bash
# 1. Crear una carpeta y descargar solo el docker-compose.yml
mkdir mini-crm && cd mini-crm
curl -O https://raw.githubusercontent.com/hunter2460/test-nex/main/docker-compose.yml

# 2. Iniciar los servicios (descarga la imagen automáticamente)
docker compose up -d

# 3. Acceder a la aplicación
#    🐳 PUERTO 8001
start http://localhost:8001
```

> 🐳 **La aplicación Dockerizada se accede en: http://localhost:8001**

### O clonando el repositorio completo

```bash
git clone <repo-url>
cd test-nex
docker compose up -d
```

> 🐳 **Acceder en: http://localhost:8001 (PUERTO 8001)**

### Credenciales de prueba

| Campo  | Valor          |
|--------|----------------|
| Email  | admin@demo.com |
| Pass   | password       |

### Detalles del entorno Docker

- La aplicación se sirve en **http://localhost:8001**
- MySQL está expuesto en el puerto **3307** del host (evita conflictos con MySQL local)
- Las migraciones y seeders se ejecutan automáticamente al arrancar el contenedor
- Los datos persisten en un volumen Docker (`db_data`)
- La imagen预construida está en Docker Hub: `hunter2460/test-nex:latest`

---

## Instalación local (desarrollo clonando el repo)

### Prerrequisitos
- PHP ^8.2
- Composer
- Node.js 18+
- MySQL 8 (o SQLite para desarrollo)

### Pasos

```bash
# 1. Clonar el repositorio
git clone <repo-url>
cd test-nex

# 2. Instalar dependencias PHP
composer install

# 3. Configurar variables de entorno
cp .env.example .env
# Editar DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD en .env

# 4. Generar key de la aplicación
php artisan key:generate

# 5. Crear la base de datos y ejecutar migraciones + seeders
php artisan migrate --seed

# 6. Instalar dependencias del frontend
npm install

# 7a. Compilar assets para producción (sin HMR)
npm run build
```

Luego inicia el servidor:

```bash
# Terminal 1 — Servidor Laravel
php artisan serve
#    💻 PUERTO 8000
```

> 💻 **La aplicación local se accede en: http://localhost:8000 (PUERTO 8000)**

**Opcional — Desarrollo con HMR (Hot Module Replacement):**

```bash
# Terminal 1 — Backend
php artisan serve

# Terminal 2 — Frontend (Vite Dev Server con HMR)
npm run dev
```

Los cambios en Vue se reflejan automáticamente sin recargar la página.

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
- **Soft deletes**: no se pierden datos accidentalmente. Ideal para un CRM.
- **Contacto primario único**: se implementa con transacciones de base de datos en el `ContactRepository`.
- **Textos en español neutro (Colombia)**: con tildes, sin voseo.
- **Validación**: Form Requests de Laravel con mensajes de error en español. Errores 422 campo por campo.
- **Exportación**: `maatwebsite/excel` 3.x y `barryvdh/laravel-dompdf`.
- **Docker**: imagen basada en `php:8.2-apache` con Apache en puerto 8001, MySQL 8.0 con healthcheck, entrypoint que ejecuta migraciones y seeders automáticamente.
