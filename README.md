# Portafolio — Roberto Carlos Vázquez Antelo

Sitio web de portafolio profesional construido sobre el perfil real de **Roberto Carlos Vázquez Antelo**, Desarrollador Full Stack con experiencia en PHP, MySQL y JavaScript.

## Stack tecnológico

| Capa | Tecnología | Rol |
|------|-----------|-----|
| Templating | PHP 8.x | Modularización y renderizado de secciones |
| Estilos | Tailwind CSS 3.x (CLI) | Design tokens y componentes artesanales |
| Interactividad | Alpine.js 3.x (CDN) | Nav reactiva + menú móvil |
| Tipografía | Syne + Inter (Google Fonts) | Display + cuerpo de texto |
| Fuente de datos | `data/CV_Roberto_Vazquez.html` | Única fuente de verdad del contenido |

## Arquitectura de carpetas

```
portafolio-roberto-vazquez/
├── data/
│   └── CV_Roberto_Vazquez.html      # Fuente de verdad del contenido
│
├── includes/
│   ├── header.php                   # Nav + meta SEO + carga de fuentes
│   ├── footer.php                   # Footer + scripts (Alpine.js + app.js)
│   └── sections/
│       ├── hero.php                 # Primera impresión, nombre y CTAs
│       ├── about.php                # Perfil, stack y disponibilidad
│       ├── experience.php           # Historial laboral (timeline)
│       ├── projects.php             # Proyectos en producción (cards)
│       ├── education.php            # Formación académica
│       ├── toolkit.php              # Kits y automatizaciones propias
│       └── contact.php              # Canales de contacto (fondo oscuro)
│
├── src/
│   ├── css/
│   │   ├── input.css                # Tailwind + design tokens + componentes
│   │   └── output.css               # CSS compilado — NO editar directamente
│   └── js/
│       └── app.js                   # Alpine store + IntersectionObserver
│
├── index.php                        # Punto de entrada — ensambla secciones
├── tailwind.config.js               # Design tokens completos
├── package.json                     # Scripts npm
└── README.md
```

## Design Tokens

Todos los valores de diseño están centralizados en `tailwind.config.js` y como CSS custom properties en `src/css/input.css`. Cambiar un token aquí actualiza todo el sitio.

### Paleta — Noir & Violet

| Token | Hex | Uso |
|-------|-----|-----|
| `noir` | `#0C0C0F` | Hero bg, nav, encabezados oscuros |
| `graphite` | `#1A1A24` | Superficies modo dark, footer cards |
| `cream` | `#F2F1ED` | Fondo principal de página |
| `ink` | `#111118` | Texto principal del cuerpo |
| `violet` | `#7C65F6` | Acento: CTAs, badges, bordes, links |

### Tipografía

| Rol | Fuente | Pesos |
|-----|--------|-------|
| Display / Títulos | Syne | 600, 700, 800 |
| Body / UI | Inter | 300, 400, 500, 600 |

## Levantar el entorno de desarrollo

### Requisitos previos

- PHP 8.0 o superior — verifica con `php -v`
- Node.js 18 o superior — verifica con `node -v`

### Pasos

**1. Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/portafolio-roberto-vazquez.git
cd portafolio-roberto-vazquez
```

**2. Instalar dependencias de Node**
```bash
npm install
```

**3. Compilar CSS (modo watch)**

Mantener esta terminal abierta mientras desarrollas. Tailwind detectará cambios en archivos `.php` y regenerará `src/css/output.css` automáticamente.
```bash
npm run dev
```

**4. Levantar el servidor PHP**

En una segunda terminal:
```bash
php -S localhost:8000
```

Abre [http://localhost:8000](http://localhost:8000) en tu navegador.

### Compilar para producción

```bash
npm run build
```

Genera `src/css/output.css` minificado y optimizado para producción.

---

## Cómo añadir contenido

### Nuevo proyecto

Edita [includes/sections/projects.php](includes/sections/projects.php) y añade un elemento al array `$proyectos`:

```php
[
    'nombre'      => 'Nombre del proyecto',
    'descripcion' => 'Descripción clara del proyecto.',
    'tags'        => ['PHP', 'MySQL'],
    'estado'      => 'En producción',  // 'En producción' | 'En desarrollo' | 'Archivado'
    'url'         => 'https://tu-proyecto.com',
    'github'      => null,  // o 'https://github.com/usuario/repo'
],
```

### Kit de Toolkit

Edita [includes/sections/toolkit.php](includes/sections/toolkit.php) y añade un elemento al array `$toolkit_items`:

```php
[
    'nombre'      => 'Nombre de la herramienta',
    'descripcion' => 'Qué hace y cómo ayuda.',
    'tipo'        => 'Automatización',  // 'Automatización' | 'Extensión' | 'CLI' | 'Starter Kit'
    'url'         => null,              // null = "Próximamente"
    'tags'        => ['Python', 'Node.js'],
],
```

### Nueva experiencia laboral

Edita [includes/sections/experience.php](includes/sections/experience.php) y añade un elemento al array `$experiencias`.

### Certificación

Edita [includes/sections/education.php](includes/sections/education.php) y añade un elemento al array `$certificaciones`.

---

## Licencia

MIT © Roberto Carlos Vázquez Antelo
