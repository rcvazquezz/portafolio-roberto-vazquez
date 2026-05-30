<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title><?= htmlspecialchars($pageTitle ?? 'Panel') ?> — <?= APP_NAME ?></title>

  <!--
    Tipografía idéntica al portafolio público:
      · Syne  → headings, branding, números grandes
      · Inter → navegación, labels, body text
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    /**
     * Design tokens alineados con tailwind.config.js del portafolio.
     * Paleta: Noir & Violet — #0C0C0F base, #7C65F6 acento.
     * IMPORTANTE: mantener sincronizado con tailwind.config.js raíz.
     */
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['Syne', 'sans-serif'],
            body:    ['Inter', 'sans-serif'],
            sans:    ['Inter', 'sans-serif'],
          },
          colors: {
            noir:     '#0C0C0F',
            graphite: '#1A1A24',
            violet: {
              DEFAULT: '#7C65F6',
              dark:    '#5B45D4',
              muted:   '#A78BFA',
            },
          },
        },
      },
    };
  </script>

  <!-- Lucide: defer para no bloquear el parseo del HTML -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>

  <style>
    /*
     * Estilos del layout del panel CMS.
     * Solo lo que Tailwind CDN no puede expresar con clases atómicas.
     */

    /* ── Scrollbar personalizada ─────────────────────────────────────
       Idéntica a la del portafolio público pero adaptada a fondo oscuro.
    ──────────────────────────────────────────────────────────────── */
    ::-webkit-scrollbar       { width: 5px; }
    ::-webkit-scrollbar-track { background: #0C0C0F; }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.10); border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.20); }

    /* Selección de texto con acento de marca */
    ::selection { background-color: rgba(124,101,246,.25); color: #A78BFA; }

    /* ── Sidebar ────────────────────────────────────────────────────
       Superficie graphite con borde derecho como separador de planos.
    ──────────────────────────────────────────────────────────────── */
    .admin-sidebar {
      background: #1A1A24;
      border-right: 1px solid rgba(255,255,255,.06);
    }

    /* ── Topbar ──────────────────────────────────────────────────────
       Misma superficie que el sidebar para coherencia visual.
    ──────────────────────────────────────────────────────────────── */
    .admin-topbar {
      background: #1A1A24;
      border-bottom: 1px solid rgba(255,255,255,.06);
    }

    /* ── Nav item: estado activo ─────────────────────────────────────
       Borde izquierdo de 2px como indicador de sección activa.
       El fondo violeta translúcido evita que sea demasiado llamativo.
    ──────────────────────────────────────────────────────────────── */
    .nav-item {
      position: relative;
      transition:
        background-color 150ms cubic-bezier(.4,0,.2,1),
        color            150ms cubic-bezier(.4,0,.2,1);
    }
    .nav-item--active {
      background: rgba(124,101,246,.13);
      color: #A78BFA;
    }
    .nav-item--active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 20%;
      bottom: 20%;
      width: 2px;
      background: #7C65F6;
      border-radius: 0 2px 2px 0;
    }

    /* ── Monograma RV en el sidebar ── */
    .sidebar-logo {
      background: #7C65F6;
      border-radius: 8px;
      width: 2rem;
      height: 2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      box-shadow: 0 2px 8px rgba(124,101,246,.30);
    }
  </style>
</head>

<body class="h-full antialiased" style="background:#0C0C0F; color:#fff; font-family:'Inter',sans-serif;">

<?php
  /*
   * ── Datos globales del layout ──────────────────────────────────────
   * Se calculan una sola vez aquí en lugar de en cada controlador,
   * ya que son necesarios en todas las páginas (badge de mensajes,
   * usuario activo, path para el nav activo).
   */
  $unreadCount = (new \Models\Contact())->countUnread();
  $currentUser = \Core\Auth::user();
  $currentPath = trim($_GET['url'] ?? '', '/');

  /*
   * Definición centralizada del menú de navegación.
   * Para añadir una sección nueva: push de un array en $navItems.
   * La clave 'badge' es opcional; solo se renderiza si no es null.
   */
  $navItems = [
    ['path' => '',            'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
    ['path' => 'projects',    'icon' => 'folder-code',      'label' => 'Proyectos'],
    ['path' => 'experiences', 'icon' => 'briefcase',        'label' => 'Experiencia'],
    ['path' => 'education',   'icon' => 'graduation-cap',   'label' => 'Educación'],
    ['path' => 'contacts',    'icon' => 'mail',             'label' => 'Mensajes',
     'badge' => $unreadCount > 0 ? $unreadCount : null],
  ];
?>

<div class="flex h-screen overflow-hidden">

  <!-- ══════════════════════════════════════════════════════════════
       SIDEBAR — Navegación principal del panel
       Ancho fijo 240px, flex column para empujar el perfil al fondo.
  ══════════════════════════════════════════════════════════════════ -->
  <aside class="admin-sidebar w-[240px] flex-shrink-0 flex flex-col" aria-label="Menú lateral">

    <!-- Branding: mismo monograma RV y nombre que en la nav del portafolio -->
    <div class="flex items-center gap-3 h-[60px] px-5 flex-shrink-0"
         style="border-bottom:1px solid rgba(255,255,255,.06)">
      <div class="sidebar-logo">
        <span style="font-family:'Syne',sans-serif; font-weight:700;
                     font-size:.8125rem; color:#fff; letter-spacing:-.01em;">RV</span>
      </div>
      <div>
        <p style="font-family:'Syne',sans-serif; font-weight:600; font-size:.875rem;
                  color:#fff; line-height:1; letter-spacing:-.01em;">Portfolio CMS</p>
        <p style="font-size:.6875rem; color:rgba(255,255,255,.32); margin-top:.25rem;">
          Panel de administración
        </p>
      </div>
    </div>

    <!-- Navegación principal -->
    <nav class="flex-1 overflow-y-auto py-3 px-2" aria-label="Secciones del panel">
      <ul class="space-y-0.5" role="list">
        <?php foreach ($navItems as $item):
          /*
           * Un ítem está activo si su path coincide exactamente con el actual
           * (para dashboard) o el path actual comienza con el del ítem
           * (para secciones anidadas como projects/edit/1).
           */
          $isActive = ($currentPath === $item['path']) ||
                      ($item['path'] !== '' && str_starts_with($currentPath, $item['path']));
        ?>
          <li>
            <a href="<?= rtrim(APP_URL, '/') ?>/<?= $item['path'] ?>"
               class="nav-item <?= $isActive ? 'nav-item--active' : '' ?>
                      flex items-center gap-3 px-3 py-2.5 rounded-lg text-[.8125rem] font-medium
                      <?= $isActive
                            ? ''
                            : 'hover:bg-white/[.04] hover:text-white/70'
                      ?>"
               style="<?= $isActive ? '' : 'color:rgba(255,255,255,.42)' ?>">

              <i data-lucide="<?= $item['icon'] ?>"
                 class="w-4 h-4 flex-shrink-0"
                 style="<?= $isActive ? 'color:#A78BFA' : 'color:rgba(255,255,255,.28)' ?>"></i>

              <span class="flex-1"><?= $item['label'] ?></span>

              <?php if (!empty($item['badge'])): ?>
                <span style="font-size:.6875rem; font-weight:600; background:rgba(124,101,246,.20);
                             color:#A78BFA; padding:.1875rem .4375rem; border-radius:99px;
                             min-width:1.25rem; text-align:center; font-variant-numeric:tabular-nums;">
                  <?= $item['badge'] ?>
                </span>
              <?php endif; ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Separador visual entre navegación principal y acceso externo -->
      <div style="border-top:1px solid rgba(255,255,255,.06); margin:0.75rem 0.25rem;"></div>

      <!-- Enlace al portafolio público (abre en pestaña nueva) -->
      <a href="<?= APP_ENV === 'production' ? 'https://rcvazquezz.com' : '/portafolio-roberto-vazquez/' ?>"
         target="_blank"
         rel="noopener noreferrer"
         class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-[.8125rem] font-medium
                hover:bg-white/[.04] hover:text-white/60"
         style="color:rgba(255,255,255,.30)">
        <i data-lucide="external-link" class="w-4 h-4 flex-shrink-0"
           style="color:rgba(255,255,255,.22)"></i>
        <span class="flex-1">Ver portafolio</span>
        <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 flex-shrink-0"
           style="color:rgba(255,255,255,.16)"></i>
      </a>
    </nav>

    <!-- Perfil del admin: siempre visible en la parte inferior del sidebar -->
    <div class="p-3 flex-shrink-0" style="border-top:1px solid rgba(255,255,255,.06)">
      <div class="flex items-center gap-3 px-2 py-2">

        <!-- Avatar: inicial del nombre sobre fondo violeta translúcido -->
        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
             style="background:rgba(124,101,246,.15)">
          <span style="font-size:.75rem; font-weight:700; color:#A78BFA;">
            <?= strtoupper(mb_substr($currentUser['name'] ?? 'A', 0, 1)) ?>
          </span>
        </div>

        <div class="flex-1 min-w-0">
          <p style="font-size:.8125rem; font-weight:500; color:rgba(255,255,255,.75);
                    white-space:nowrap; overflow:hidden; text-overflow:ellipsis; line-height:1;">
            <?= htmlspecialchars($currentUser['name'] ?? '') ?>
          </p>
          <p style="font-size:.6875rem; color:rgba(255,255,255,.28); margin-top:.25rem;
                    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            <?= htmlspecialchars($currentUser['email'] ?? '') ?>
          </p>
        </div>

        <!-- Botón de cerrar sesión -->
        <a href="<?= APP_URL ?>/logout"
           title="Cerrar sesión"
           class="p-1.5 rounded-lg transition-colors duration-150"
           style="color:rgba(255,255,255,.22);"
           onmouseover="this.style.color='#FCA5A5';this.style.background='rgba(239,68,68,.10)'"
           onmouseout="this.style.color='rgba(255,255,255,.22)';this.style.background='transparent'">
          <i data-lucide="log-out" class="w-4 h-4"></i>
        </a>
      </div>
    </div>

  </aside><!-- /aside.sidebar -->

  <!-- ══════════════════════════════════════════════════════════════
       ÁREA PRINCIPAL — Topbar + contenido de la página activa
  ══════════════════════════════════════════════════════════════════ -->
  <div class="flex-1 flex flex-col overflow-hidden">

    <!-- Topbar: título de la página actual + contexto de fecha -->
    <header class="admin-topbar h-[60px] flex items-center justify-between px-6 flex-shrink-0">
      <h1 style="font-family:'Syne',sans-serif; font-weight:700; font-size:.9375rem;
                 color:#fff; letter-spacing:-.015em;">
        <?= htmlspecialchars($pageTitle ?? '') ?>
      </h1>
      <!-- Fecha como orientación contextual, oculta en pantallas pequeñas -->
      <time style="font-size:.75rem; color:rgba(255,255,255,.22);"
            class="hidden sm:block"
            datetime="<?= date('Y-m-d') ?>">
        <?= date('d M Y') ?>
      </time>
    </header>

    <!-- Área de contenido: scroll independiente del sidebar -->
    <main class="flex-1 overflow-y-auto p-6" id="main-content" style="background:#0C0C0F;">

      <!-- ── Mensaje flash ───────────────────────────────────────────
           Feedback visual del controlador (success / error).
           Se renderiza antes del contenido de la vista para que sea
           lo primero que el usuario vea tras una acción.
      ──────────────────────────────────────────────────────────────── -->
      <?php if (!empty($flash)): ?>
        <?php $isSuccess = $flash['type'] === 'success'; ?>
        <div role="alert"
             class="flex items-start gap-3 mb-5 px-4 py-3 rounded-xl text-[.8125rem]"
             style="<?= $isSuccess
                          ? 'background:rgba(16,185,129,.10); color:#6EE7B7; border:1px solid rgba(16,185,129,.22);'
                          : 'background:rgba(239,68,68,.10); color:#FCA5A5; border:1px solid rgba(239,68,68,.22);'
                    ?>">
          <i data-lucide="<?= $isSuccess ? 'check-circle' : 'alert-circle' ?>"
             class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
          <span><?= htmlspecialchars($flash['message']) ?></span>
        </div>
      <?php endif; ?>

      <!-- Vista inyectada por Controller::render() -->
      <?= $content ?>

    </main>
  </div>

</div><!-- /flex.container -->

<!--
  Inicializar Lucide tras DOMContentLoaded para asegurar que
  el script (cargado con defer) ya está disponible cuando se ejecuta.
-->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') lucide.createIcons();
  });
</script>

</body>
</html>
