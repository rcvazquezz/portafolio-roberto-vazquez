# Portafolio — Roberto Carlos Vázquez Antelo

Sitio web de portafolio profesional con panel CMS integrado. El contenido (proyectos, experiencia y formación) se administra desde un panel de administración privado y se almacena en MySQL.

## Stack tecnológico

| Capa | Tecnología | Rol |
|------|-----------|-----|
| Backend | PHP 8.x | Renderizado de secciones + lógica del CMS |
| Estilos (público) | Tailwind CSS 3.x (CLI) | Design tokens y componentes artesanales |
| Estilos (admin) | Tailwind CSS (CDN) | Panel de administración |
| Interactividad | Alpine.js 3.x (CDN) | Nav reactiva + menú móvil |
| Base de datos | MySQL 8.x | Persistencia del contenido del portafolio |
| Tipografía | Syne + Inter (Google Fonts) | Display + cuerpo de texto |

## Arquitectura de carpetas

```
portafolio-roberto-vazquez/
│
├── admin/                          # Panel CMS privado (acceso restringido)
│   ├── config/
│   │   ├── app.php                 # Constantes globales del panel
│   │   ├── database.php            # Credenciales MySQL — en .gitignore
│   │   └── database.example.php   # Plantilla para configurar database.php
│   ├── src/
│   │   ├── Core/                   # Router, Controller base, Auth, Database
│   │   ├── Controllers/            # CRUD: proyectos, experiencias, educación, contactos
│   │   ├── Models/                 # Acceso a BD con PDO (prepared statements)
│   │   └── Middleware/             # AuthMiddleware — protege rutas privadas
│   ├── views/
│   │   ├── layouts/admin.php       # Layout base del panel (sidebar + topbar)
│   │   ├── auth/login.php          # Formulario de acceso
│   │   ├── dashboard/              # Vista de métricas
│   │   ├── projects/               # CRUD de proyectos
│   │   ├── experiences/            # CRUD de experiencia laboral
│   │   ├── education/              # CRUD de formación académica
│   │   └── contacts/               # Bandeja de mensajes recibidos
│   ├── index.php                   # Front controller del panel
│   └── .htaccess                   # Rewrite rules + protección de src/ y config/
│
├── database/
│   ├── migration.sql               # ALTER TABLE para las columnas del CMS
│   └── seed.sql                    # Datos iniciales del portafolio
│
├── includes/
│   ├── data/
│   │   ├── db.php                  # Singleton PDO del portafolio público
│   │   └── portfolio-data.php      # Funciones: getPublishedProjects(), getExperiences(), getEducation()
│   ├── header.php                  # Nav + meta SEO + carga de fuentes
│   ├── footer.php                  # Footer + scripts (Alpine.js + app.js)
│   └── sections/
│       ├── hero.php                # Primera impresión, nombre y CTAs
│       ├── about.php               # Perfil, stack y disponibilidad
│       ├── experience.php          # Historial laboral (timeline) — datos desde BD
│       ├── projects.php            # Proyectos destacados (cards) — datos desde BD
│       ├── education.php           # Formación académica — datos desde BD
│       ├── toolkit.php             # Kits y automatizaciones propias
│       └── contact.php             # Canales de contacto (fondo oscuro)
│
├── src/
│   ├── css/
│   │   ├── input.css               # Tailwind + design tokens + componentes artesanales
│   │   └── output.css              # CSS compilado — NO editar directamente
│   └── js/
│       └── app.js                  # Alpine store + IntersectionObserver
│
├── index.php                       # Punto de entrada — carga BD y ensambla secciones
├── tailwind.config.js              # Design tokens completos (Noir & Violet)
├── package.json                    # Scripts npm (dev/build)
└── README.md
```

## Design Tokens

Todos los valores de diseño están centralizados en `tailwind.config.js` y como CSS custom properties en `src/css/input.css`.

### Paleta — Noir & Violet

| Token | Hex | Uso |
|-------|-----|-----|
| `noir` | `#0C0C0F` | Hero bg, nav, encabezados oscuros, fondo admin |
| `graphite` | `#1A1A24` | Sidebar y topbar del panel CMS |
| `cream` | `#F2F1ED` | Fondo principal del portafolio público |
| `ink` | `#111118` | Texto principal del cuerpo |
| `violet` | `#7C65F6` | Acento: CTAs, badges, bordes, links, branding |

### Tipografía

| Rol | Fuente | Pesos |
|-----|--------|-------|
| Display / Títulos | Syne | 600, 700, 800 |
| Body / UI | Inter | 400, 500, 600 |

## Configurar el entorno de desarrollo

### Requisitos previos

- PHP 8.0 o superior — `php -v`
- Node.js 18 o superior — `node -v`
- MySQL 8.x (incluido en Laragon)
- Laragon (recomendado para Windows) o MAMP/XAMPP

### Pasos

**1. Clonar el repositorio**
```bash
git clone https://github.com/rcvazquezz/portafolio-roberto-vazquez.git
cd portafolio-roberto-vazquez
```

**2. Configurar la base de datos**

Crear la BD en MySQL:
```sql
CREATE DATABASE `portfolio_cms` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Ejecutar las migraciones (en orden):
```bash
# En HeidiSQL, MySQL Workbench o cualquier cliente MySQL:
# 1. database/migration.sql   → crea/altera las tablas
# 2. database/seed.sql        → carga el contenido inicial
```

**3. Configurar credenciales MySQL**
```bash
cp admin/config/database.example.php admin/config/database.php
# Editar database.php con los valores de tu entorno
```

**4. Instalar dependencias y compilar CSS**
```bash
npm install
npm run dev   # modo watch — mantener abierto mientras desarrollas
```

**5. Servir con Laragon**

Colocar el proyecto en `C:/laragon/www/` y acceder en:
- Portafolio: `http://localhost/portafolio-roberto-vazquez/`
- Panel admin: `http://localhost/portafolio-roberto-vazquez/admin/`

### Compilar para producción

```bash
npm run build
```

---

## Panel de administración

Accede en `/admin/` con las credenciales del usuario administrador.

### Gestionar contenido

| Sección | Ruta admin | Efecto en el portafolio |
|---------|-----------|------------------------|
| Proyectos | `/admin/projects` | Sección "Proyectos Destacados" |
| Experiencia | `/admin/experiences` | Sección "Experiencia" (timeline) |
| Educación | `/admin/education` | Sección "Formación" |
| Mensajes | `/admin/contacts` | Bandeja de mensajes del formulario de contacto |

### Estado de proyectos

| Valor | Visible en portafolio | Badge |
|-------|----------------------|-------|
| `En producción` | Sí | Verde |
| `En desarrollo` | Sí | Amarillo |
| `Finalizado` | Sí | Índigo |
| `Archivado` | Sí | Gris |
| `draft` | No | — |

### Formato de áreas de conocimiento (Educación)

El campo **Áreas de conocimiento** acepta una categoría por línea:

```
Arquitectura & Software: Ciclo de vida del software, Análisis de Sistemas
Data & Lógica: Bases de Datos Relacionales, Algoritmia
Despliegue: Sistemas de Gestión Institucional
```

Cada línea se renderiza como un grupo de badges en la card de formación.

### Bullets de Experiencia

El campo **Descripción** de cada experiencia laboral acepta un logro por línea. Cada línea se muestra como un bullet `→` en el timeline del portafolio.

---

## Licencia

MIT © Roberto Carlos Vázquez Antelo
