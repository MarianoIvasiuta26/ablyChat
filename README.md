# AblyChat

AblyChat es una aplicación de chat en tiempo real construida con Laravel, Filament y Ably. Cuenta con un sistema de chat privado integrado en el panel de administración de Filament.
Este proyecto utiliza [Laravel Sail](https://laravel.com/docs/sail) para un entorno de desarrollo basado en Docker.

## Tecnologías

- **Framework:** [Laravel 12](https://laravel.com)
- **Panel de Administración:** [Filament 4](https://filamentphp.com)
- **Tiempo Real:** [Ably](https://ably.com) y [Laravel Echo](https://laravel.com/docs/broadcasting)
- **Frontend:** [Livewire](https://livewire.laravel.com), [Flux](https://fluxui.dev), [Volt](https://livewire.laravel.com/docs/volt), [TailwindCSS 4](https://tailwindcss.com)
- **Empaquetador:** [Vite](https://vitejs.dev)
- **Entorno de Desarrollo:** [Laravel Sail](https://laravel.com/docs/sail) (Docker)

## Requisitos Previos

Asegúrate de tener instalado lo siguiente en tu sistema:

- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- [Git](https://git-scm.com/)

## Instalación

1. **Clonar el repositorio:**

   ```bash
   git clone <url-del-repositorio>
   cd ablyChat
   ```

2. **Instalar dependencias de PHP:**

   Si tienes PHP y Composer instalados localmente:
   ```bash
   composer install
   ```
   
   Si no, puedes usar un contenedor temporal de Docker:
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php82-composer:latest \
       composer install --ignore-platform-reqs
   ```

3. **Configuración del Entorno:**

   Copia el archivo de entorno de ejemplo y configúralo:

   ```bash
   cp .env.example .env
   ```

   Actualiza el archivo `.env` con tus credenciales de Ably. La base de datos ya está configurada para usar MySQL en Docker por defecto con Sail.

   ```env
   BROADCAST_CONNECTION=ably
   ABLY_KEY=tu-clave-api-de-ably
   ```

4. **Iniciar Sail:**

   ```bash
   ./vendor/bin/sail up -d
   ```
   
   Recomendamos configurar un alias para `sail` (opcional):
   ```bash
   alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
   ```

5. **Generar Clave de Aplicación:**

   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

6. **Instalar dependencias de Node.js:**

   ```bash
   ./vendor/bin/sail npm install
   ```

7. **Ejecutar Migraciones:**

   ```bash
   ./vendor/bin/sail artisan migrate
   ```

## Ejecutando la Aplicación

Con Sail ejecutándose (`./vendor/bin/sail up -d`), puedes iniciar el servidor de desarrollo de Vite:

```bash
./vendor/bin/sail npm run dev
```

Para procesar las colas (necesario para el chat):

```bash
./vendor/bin/sail artisan queue:listen
```

### Comandos Útiles de Sail

- **Detener contenedores:** `./vendor/bin/sail down`
- **Ejecutar tests:** `./vendor/bin/sail test`
- **Acceder al contenedor:** `./vendor/bin/sail shell`

## Características

- **Chat Privado:** Mensajería privada en tiempo real entre usuarios.
- **Integración con Filament:** Perfectamente integrado en el panel de control de Filament.
- **Actualizaciones en Tiempo Real:** Entrega instantánea de mensajes usando Ably.

## Licencia

Este proyecto es software de código abierto licenciado bajo la [licencia MIT](https://opensource.org/licenses/MIT).
