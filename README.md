# Portafolio — Roberto Carlos Vázquez Antelo

Sitio web de portafolio profesional con panel CMS integrado. El contenido (proyectos, experiencia y formación) se administra desde un panel privado y se persiste en MySQL.

## Stack tecnológico

| Capa | Tecnología | Rol |
|------|-----------|-----|
| Backend | PHP 8.x | Renderizado de secciones + lógica del CMS |
| Estilos (público) | Tailwind CSS 3.x (CLI) | Design tokens y componentes artesanales |
| Estilos (admin) | Tailwind CSS (CDN) | Panel de administración |
| Interactividad | Alpine.js 3.x (CDN) | Nav reactiva + menú móvil |
| Base de datos | MySQL 8.x | Persistencia del contenido |
| Tipografía | Syne + Inter (Google Fonts) | Display + cuerpo de texto |

## Arquitectura de carpetas

```
portafolio-roberto-vazquez/
│
├── admin/                              # Panel CMS privado
│   ├── config/
│   │   ├── app.php                     # Constantes globales (APP_URL, SESSION_NAME…)
│   │   ├── database.php                # Credenciales MySQL — en .gitignore
│   │   └── database.example.php        # Plantilla para nuevos entornos
│   ├── src/
│   │   ├── Core/                       # Router, Controller base, Auth, PDO singleton
│   │   ├── Controllers/                # CRUD: proyectos, experiencias, educación, contactos
│   │   ├── Models/                     # Acceso a BD con PDO y prepared statements
│   │   └── Middleware/                 # AuthMiddleware — protege rutas privadas
│   ├── views/
│   │   ├── layouts/admin.php           # Layout base del panel (sidebar + topbar)
│   │   ├── auth/login.php              # Formulario de acceso
│   │   ├── dashboard/index.php         # Métricas del portafolio
│   │   ├── projects/                   # CRUD de proyectos (index, create, edit)
│   │   ├── experiences/                # CRUD de experiencia laboral
│   │   ├── education/                  # CRUD de formación académica
│   │   └── contacts/index.php          # Bandeja de mensajes del formulario de contacto
│   ├── index.php                       # Front controller del panel
│   └── .htaccess                       # Rewrite rules + bloqueo de src/ y config/
│
├── database/
│   └── portafolio-cms.sql              # Esquema completo exportado desde HeidiSQL
│
├── includes/
│   ├── data/
│   │   ├── db.php                      # Singleton PDO del portafolio público
│   │   └── portfolio-data.php          # getPublishedProjects(), getExperiences(), getEducation()
│   ├── components/
│   │   ├── contact-form.php            # Formulario de contacto (Alpine.js + Formspree)
│   │   └── contact-toast.php           # Toast de confirmación de envío
│   ├── sections/
│   │   ├── hero.php                    # Primera impresión, nombre y CTAs
│   │   ├── about.php                   # Perfil, stack y disponibilidad
│   │   ├── experience.php              # Historial laboral (timeline) — datos desde BD
│   │   ├── projects.php                # Proyectos destacados (cards) — datos desde BD
│   │   ├── education.php               # Formación académica — datos desde BD
│   │   ├── toolkit.php                 # Kits y automatizaciones propias
│   │   └── contact.php                 # Canales de contacto (fondo oscuro)
│   ├── header.php                      # Nav + meta SEO + JSON-LD + Google Fonts
│   └── footer.php                      # Footer + Google Analytics + Alpine.js
│
├── public/
│   └── cv-roberto-vazquez.pdf          # CV en PDF para descarga
│
├── src/
│   ├── css/
│   │   ├── input.css                   # Tailwind + design tokens + componentes
│   │   └── output.css                  # CSS compilado — no editar directamente
│   └── js/
│       └── app.js                      # Alpine.data() + IntersectionObserver
│
├── index.php                           # Punto de entrada — carga BD y ensambla secciones
├── robots.txt                          # Reglas de crawling (bloquea /admin/)
├── sitemap.xml                         # Sitemap para indexación de Google
├── tailwind.config.js                  # Design tokens (Noir & Violet)
└── package.json                        # Scripts npm (dev / build)
```

## Design Tokens

Centralizados en `tailwind.config.js` y como CSS custom properties en `src/css/input.css`. Cambiar un token actualiza todo el sitio.

### Paleta — Noir & Violet

| Token | Hex | Uso |
|-------|-----|-----|
| `noir` | `#0C0C0F` | Hero bg, fondo admin, encabezados oscuros |
| `graphite` | `#1A1A24` | Sidebar y topbar del panel CMS |
| `cream` | `#F2F1ED` | Fondo principal del portafolio público |
| `ink` | `#111118` | Texto principal del cuerpo |
| `violet` | `#7C65F6` | Acento: CTAs, badges, bordes, links |

### Tipografía

| Rol | Fuente | Pesos |
|-----|--------|-------|
| Display / Títulos | Syne | 600, 700, 800 |
| Body / UI | Inter | 400, 500, 600 |

---

## Configurar el entorno de desarrollo

### Requisitos previos

- PHP 8.0 o superior — `php -v`
- Node.js 18 o superior — `node -v`
- MySQL 8.x (incluido en Laragon)
- Laragon (recomendado para Windows)

### Pasos

**1. Clonar el repositorio**
```bash
git clone https://github.com/rcvazquezz/portafolio-roberto-vazquez.git
cd portafolio-roberto-vazquez
```

**2. Importar la base de datos**

En HeidiSQL (o cualquier cliente MySQL), importar el esquema completo:
```
database/portafolio-cms.sql
```
Esto crea la base de datos `portfolio_cms` con todas las tablas y estructura.

**3. Configurar credenciales MySQL**
```bash
cp admin/config/database.example.php admin/config/database.php
# Editar database.php con los valores de tu entorno local
```

Laragon por defecto: `host = 127.0.0.1`, `user = root`, `password = (vacío)`.

**4. Instalar dependencias y compilar CSS**
```bash
npm install
npm run dev     # modo watch — mantener abierto mientras desarrollas
```

**5. Acceder desde Laragon**

Colocar el proyecto en `C:/laragon/www/` y acceder en:
- Portafolio público: `http://localhost/portafolio-roberto-vazquez/`
- Panel admin: `http://localhost/portafolio-roberto-vazquez/admin/`

### Compilar para producción

```bash
npm run build
```

---

## Panel de administración

Accede en `/admin/` con el usuario administrador. Desde aquí se gestiona todo el contenido que aparece en el portafolio.

### Secciones gestionables

| Sección admin | Efecto en el portafolio |
|--------------|------------------------|
| `/admin/projects` | Sección "Proyectos Destacados" |
| `/admin/experiences` | Sección "Experiencia" (timeline) |
| `/admin/education` | Sección "Formación" |
| `/admin/contacts` | Mensajes del formulario de contacto |

### Estados de proyectos

| Valor | Visible en portafolio | Badge |
|-------|----------------------|-------|
| `En producción` | ✅ | Verde |
| `En desarrollo` | ✅ | Amarillo |
| `Finalizado` | ✅ | Índigo |
| `Archivado` | ✅ | Gris |
| `draft` | ❌ | — |

### Formato: Áreas de conocimiento (Educación)

El campo acepta una categoría por línea con el formato `Categoría: hab1, hab2`:

```
Arquitectura & Software: Ciclo de vida del software, Análisis de Sistemas
Data & Lógica: Bases de Datos Relacionales, Algoritmia
Despliegue: Sistemas de Gestión Institucional
```

Cada línea se renderiza como un grupo de badges en la card de formación.

### Formato: Descripción de Experiencia

Cada línea del campo Descripción se muestra como un bullet `→` en el timeline. Una línea = un logro/responsabilidad.

---

## Licencia

MIT © Roberto Carlos Vázquez Antelo
