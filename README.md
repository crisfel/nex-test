# Mini CRM - Gestión de Clientes y Contactos

Prueba técnica: mini CRM monolito con Laravel 11 + Vue 3 (Composition API) + Pinia + Vue Router + Laravel Sanctum.

---

## Requisitos

- PHP ^8.2
- Composer
- Node.js 18+
- MySQL 8 (o SQLite para desarrollo)

## Instalación

```bash
# 1. Clonar el repositorio
git clone <repo-url> mini-crm
cd mini-crm

# 2. Instalar dependencias PHP
composer install

# 3. Configurar variables de entorno
cp .env.example .env
# Editar .env con credenciales de base de datos (ver sección "Base de datos" abajo)

# 4. Generar key de aplicación
php artisan key:generate

# 5. Ejecutar migraciones y seeders
php artisan migrate --seed

# 6. Instalar dependencias frontend
npm install

# 7. Compilar assets
npm run build

# 8. Iniciar servidor de desarrollo
php artisan serve
# En otra terminal:
npm run dev
```

Acceder a `http://localhost:8000`.

### Base de datos

Por defecto el `.env.example` está configurado para MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mini_crm
DB_USERNAME=root
DB_PASSWORD=
```

Crear la base de datos `mini_crm` antes de migrar.

**Alternativa SQLite**: cambiar a `DB_CONNECTION=sqlite` y comentar las líneas de MySQL. Asegurarse de que exista `database/database.sqlite`.

## Credenciales de prueba

| Campo  | Valor            |
|--------|------------------|
| Email  | admin@demo.com   |
| Pass   | password         |

## Arquitectura

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php      # Registro, login, logout, user
│   │   ├── ClientController.php    # CRUD clientes
│   │   └── ContactController.php   # CRUD contactos (anidado a cliente)
│   └── Requests/                   # Form Requests con validación
│       ├── Auth/LoginRequest.php
│       ├── Auth/RegisterRequest.php
│       ├── Client/StoreClientRequest.php
│       ├── Client/UpdateClientRequest.php
│       ├── Contact/StoreContactRequest.php
│       └── Contact/UpdateContactRequest.php
├── Models/
│   ├── User.php                    # HasApiTokens + relaciones
│   ├── Client.php                  # SoftDeletes, relaciones
│   └── Contact.php                 # SoftDeletes, relaciones
└── Repositories/
    ├── ClientRepository.php        # Consultas + paginación + filtros
    └── ContactRepository.php       # Lógica de contacto primario

resources/
├── js/
│   ├── app.js                      # Entry point Vue
│   ├── bootstrap.js                # Axios instance + interceptors
│   ├── App.vue                     # Layout principal + nav
│   ├── router/index.js             # Vue Router con guards
│   ├── stores/
│   │   ├── auth.js                 # Auth Pinia store
│   │   ├── clients.js              # Clients Pinia store
│   │   └── contacts.js             # Contacts Pinia store
│   └── components/
│       ├── Login.vue
│       ├── Register.vue
│       ├── ClientList.vue           # Listado + búsqueda/filtro + paginación
│       ├── ClientForm.vue           # Crear/editar cliente
│       ├── ClientDetail.vue         # Detalle + contactos anidados
│       └── ContactForm.vue          # Modal crear/editar contacto
└── views/
    └── app.blade.php                # Blade layout que monta Vue
```

### Patrón Repositorio

Se implementó el patrón repositorio para separar la lógica de acceso a datos de los controladores:
- `ClientRepository`: consultas paginadas, búsqueda por nombre, filtro por estado.
- `ContactRepository`: creación/actualización con manejo transaccional del contacto primario único.

### API

| Método | Endpoint                    | Auth     | Descripción                        |
|--------|-----------------------------|----------|------------------------------------|
| POST   | `/api/register`             | No       | Registrar usuario                  |
| POST   | `/api/login`                | No       | Iniciar sesión                     |
| POST   | `/api/logout`               | Sanctum  | Cerrar sesión                      |
| GET    | `/api/user`                 | Sanctum  | Obtener usuario autenticado        |
| GET    | `/api/clients`              | Sanctum  | Listar clientes (paginado + filtros)|
| POST   | `/api/clients`              | Sanctum  | Crear cliente                      |
| GET    | `/api/clients/{id}`         | Sanctum  | Detalle cliente + contactos        |
| PUT    | `/api/clients/{id}`         | Sanctum  | Actualizar cliente                 |
| DELETE | `/api/clients/{id}`         | Sanctum  | Eliminar cliente (soft delete)     |
| GET    | `/api/clients/{id}/contacts`| Sanctum  | Listar contactos del cliente       |
| POST   | `/api/clients/{id}/contacts`| Sanctum  | Crear contacto                     |
| PUT    | `/api/contacts/{id}`        | Sanctum  | Actualizar contacto                |
| DELETE | `/api/contacts/{id}`        | Sanctum  | Eliminar contacto (soft delete)    |

Filtros en clientes: `?search=nombre&status=activo&page=1`

### Decisiones técnicas

**Eliminación**: Se optó por **soft deletes** tanto en clientes como en contactos. En un CRM es crítico no perder datos accidentalmente. Los registros eliminados quedan visibles solo a nivel de base de datos.

**Contacto primario único**: La lógica se implementa en el `ContactRepository` usando transacciones de base de datos. Cuando se crea o actualiza un contacto con `is_primary=true`, automáticamente se establece `is_primary=false` en los demás contactos del mismo cliente dentro de la misma transacción. En el frontend se muestra un aviso al usuario.

**Validación**: Se usan Form Requests de Laravel con mensajes de error en español. Los errores 422 se muestran en el frontend campo por campo.

**Paginación**: El endpoint `GET /api/clients` usa paginación de Laravel (10 por página). El frontend muestra controles "Anterior/Siguiente".

**Autenticación**: Laravel Sanctum con tokens Bearer. El frontend almacena el token en localStorage y lo envía mediante un interceptor de Axios.

**Frontend SPA**: Vue 3 se monta dentro de una vista Blade (`app.blade.php`). El enrutamiento es interno con Vue Router (hash mode). Las rutas están protegidas por un navigation guard que verifica la existencia del token.

**Unicidad de email por cliente**: La migración de contacts define un unique compuesto `(email, client_id)`, permitiendo el mismo email en diferentes clientes pero no duplicados dentro del mismo.
