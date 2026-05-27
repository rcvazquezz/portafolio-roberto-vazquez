<?php
/**
 * Vista: dashboard/index.php
 *
 * Variables inyectadas por DashboardController::index():
 *   @var array  $user           Usuario autenticado { name, email }
 *   @var int    $totalProjects  Total de proyectos en la BD
 *   @var int    $totalExp       Total de entradas de experiencia
 *   @var int    $totalEdu       Total de entradas de educación
 *   @var int    $unreadContacts Mensajes de contacto sin leer
 *   @var int    $totalContacts  Total de mensajes recibidos
 *   @var array  $recentContacts Últimos 5 mensajes (filas de la BD)
 */

/* Nombre de pila del admin para el saludo (solo el primer token) */
$firstName = explode(' ', trim($user['name'] ?? 'Admin'))[0];
?>

<!-- ── Saludo de bienvenida ──────────────────────────────────────── -->
<div class="mb-8">
  <h2 style="font-family:'Syne',sans-serif; font-size:1.5rem; font-weight:700;
             color:#fff; letter-spacing:-.03em; line-height:1;">
    Hola, <?= htmlspecialchars($firstName) ?>
  </h2>
  <p style="font-size:.875rem; color:rgba(255,255,255,.35); margin-top:.375rem;">
    Resumen de tu portafolio al <?= date('d \d\e F \d\e Y') ?>.
  </p>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MÉTRICAS — Grid de 4 tarjetas
     Cada tarjeta es un enlace clickable a su sección del panel.
     La paleta de acento diferencia visualmente cada área del portafolio.
══════════════════════════════════════════════════════════════════════ -->
<?php
/**
 * Configuración de las tarjetas de métricas.
 *
 * Centralizar aquí permite añadir una nueva métrica con un push de
 * array, sin tocar el HTML de render.
 *
 * @var array{label:string, value:int, icon:string, accentBg:string,
 *            accentColor:string, link:string, badge?:string|null}[] $metrics
 */
$metrics = [
  [
    'label'       => 'Proyectos',
    'value'       => $totalProjects,
    'icon'        => 'folder-code',
    'accentBg'    => 'rgba(124,101,246,.14)',
    'accentColor' => '#A78BFA',
    'link'        => 'projects',
    'badge'       => null,
  ],
  [
    'label'       => 'Experiencia',
    'value'       => $totalExp,
    'icon'        => 'briefcase',
    'accentBg'    => 'rgba(59,130,246,.12)',
    'accentColor' => '#93C5FD',
    'link'        => 'experiences',
    'badge'       => null,
  ],
  [
    'label'       => 'Educación',
    'value'       => $totalEdu,
    'icon'        => 'graduation-cap',
    'accentBg'    => 'rgba(16,185,129,.12)',
    'accentColor' => '#6EE7B7',
    'link'        => 'education',
    'badge'       => null,
  ],
  [
    'label'       => 'Mensajes',
    'value'       => $totalContacts,
    'icon'        => 'mail',
    'accentBg'    => 'rgba(245,158,11,.12)',
    'accentColor' => '#FCD34D',
    'link'        => 'contacts',
    /* Badge solo visible cuando hay mensajes sin leer */
    'badge'       => $unreadContacts > 0 ? "{$unreadContacts} sin leer" : null,
  ],
];
?>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-8">
  <?php foreach ($metrics as $m): ?>
    <a href="<?= APP_URL ?>/<?= $m['link'] ?>"
       class="group block rounded-xl p-5"
       style="background:#1A1A24; border:1px solid rgba(255,255,255,.06);
              transition: border-color 200ms ease, transform 200ms ease;"
       onmouseover="this.style.borderColor='rgba(255,255,255,.12)'; this.style.transform='translateY(-2px)'"
       onmouseout="this.style.borderColor='rgba(255,255,255,.06)'; this.style.transform='translateY(0)'">

      <!-- Cabecera: ícono + badge de alerta (solo en Mensajes si hay sin leer) -->
      <div class="flex items-start justify-between mb-4">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
             style="background:<?= $m['accentBg'] ?>">
          <i data-lucide="<?= $m['icon'] ?>"
             class="w-[1.0625rem] h-[1.0625rem]"
             style="color:<?= $m['accentColor'] ?>"></i>
        </div>

        <?php if ($m['badge'] !== null): ?>
          <span style="font-size:.6875rem; font-weight:600; background:rgba(245,158,11,.12);
                       color:#FCD34D; border:1px solid rgba(245,158,11,.22);
                       padding:.1875rem .5rem; border-radius:99px; white-space:nowrap;">
            <?= htmlspecialchars($m['badge']) ?>
          </span>
        <?php endif; ?>
      </div>

      <!-- Número grande con font-display para jerarquía tipográfica clara -->
      <p style="font-family:'Syne',sans-serif; font-size:2rem; font-weight:700;
                color:#fff; line-height:1; letter-spacing:-.04em;
                font-variant-numeric:tabular-nums;">
        <?= $m['value'] ?>
      </p>
      <p style="font-size:.75rem; color:rgba(255,255,255,.32); margin-top:.375rem; font-weight:500;">
        <?= $m['label'] ?>
      </p>
    </a>
  <?php endforeach; ?>
</div>


<!-- ══════════════════════════════════════════════════════════════════
     MENSAJES RECIENTES
     Lista de los últimos 5 mensajes de contacto recibidos.
     Los no leídos se identifican con un dot violeta animado.
══════════════════════════════════════════════════════════════════════ -->
<div class="rounded-xl overflow-hidden"
     style="background:#1A1A24; border:1px solid rgba(255,255,255,.06);">

  <!-- Header de la sección -->
  <div class="flex items-center justify-between px-5 py-4"
       style="border-bottom:1px solid rgba(255,255,255,.06)">
    <div class="flex items-center gap-2.5">
      <i data-lucide="inbox" class="w-4 h-4 flex-shrink-0"
         style="color:rgba(255,255,255,.28)"></i>
      <h2 style="font-family:'Syne',sans-serif; font-weight:700; font-size:.875rem;
                 color:#fff; letter-spacing:-.01em;">
        Mensajes recientes
      </h2>
      <?php if ($unreadContacts > 0): ?>
        <!-- Badge de cantidad sin leer, solo si hay alguno -->
        <span style="font-size:.6875rem; font-weight:600; background:rgba(124,101,246,.18);
                     color:#A78BFA; padding:.125rem .4375rem; border-radius:99px;
                     font-variant-numeric:tabular-nums;">
          <?= $unreadContacts ?>
        </span>
      <?php endif; ?>
    </div>
    <a href="<?= APP_URL ?>/contacts"
       style="font-size:.75rem; font-weight:500; color:rgba(255,255,255,.32);
              transition:color 150ms ease;"
       onmouseover="this.style.color='#A78BFA'"
       onmouseout="this.style.color='rgba(255,255,255,.32)'">
      Ver todos →
    </a>
  </div>

  <?php if (empty($recentContacts)): ?>

    <!-- Estado vacío: ilustración mínima + textos de ayuda -->
    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
      <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4"
           style="background:rgba(255,255,255,.04)">
        <i data-lucide="inbox" class="w-5 h-5" style="color:rgba(255,255,255,.18)"></i>
      </div>
      <p style="font-size:.875rem; font-weight:500; color:rgba(255,255,255,.25);">
        No hay mensajes todavía
      </p>
      <p style="font-size:.75rem; color:rgba(255,255,255,.15); margin-top:.375rem; max-width:20rem;">
        Los mensajes del formulario de contacto del portafolio aparecerán aquí.
      </p>
    </div>

  <?php else: ?>

    <ul style="list-style:none; padding:0; margin:0;">
      <?php foreach ($recentContacts as $contact): ?>
        <li class="flex items-start gap-4 px-5 py-4"
            style="border-bottom:1px solid rgba(255,255,255,.04);
                   transition:background 150ms ease;"
            onmouseover="this.style.background='rgba(255,255,255,.02)'"
            onmouseout="this.style.background='transparent'">

          <!-- Avatar: inicial del nombre en círculo neutro -->
          <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
               style="background:rgba(255,255,255,.07); flex-shrink:0;">
            <span style="font-size:.6875rem; font-weight:700; color:rgba(255,255,255,.45);">
              <?= strtoupper(mb_substr($contact['name'], 0, 1)) ?>
            </span>
          </div>

          <!-- Cuerpo: nombre, email y preview del mensaje -->
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-0.5">
              <p style="font-size:.8125rem; font-weight:600; color:rgba(255,255,255,.78);
                        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                <?= htmlspecialchars($contact['name']) ?>
              </p>
              <?php if (!$contact['is_read']): ?>
                <!--
                  Dot de "no leído": violeta, animación pulse.
                  La animación se define en el <style> al final de esta vista
                  para no polucionar el layout global.
                -->
                <span class="unread-dot flex-shrink-0"
                      title="No leído"
                      aria-label="Mensaje no leído"
                      role="img"></span>
              <?php endif; ?>
            </div>
            <p style="font-size:.75rem; color:rgba(255,255,255,.28); white-space:nowrap;
                      overflow:hidden; text-overflow:ellipsis;">
              <?= htmlspecialchars($contact['email']) ?>
            </p>
            <p style="font-size:.75rem; color:rgba(255,255,255,.40); margin-top:.25rem;
                      display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical;
                      overflow:hidden; line-height:1.5;">
              <?= htmlspecialchars($contact['message']) ?>
            </p>
          </div>

          <!-- Fecha de recepción -->
          <time style="font-size:.6875rem; color:rgba(255,255,255,.20); flex-shrink:0;
                       margin-top:.125rem; font-variant-numeric:tabular-nums;"
                datetime="<?= htmlspecialchars($contact['created_at']) ?>">
            <?= date('d/m', strtotime($contact['created_at'])) ?>
          </time>
        </li>
      <?php endforeach; ?>
    </ul>

  <?php endif; ?>
</div>

<!--
  Estilos aislados de esta vista.
  El keyframe del dot de "no leído" vive aquí y no en el layout global
  para no afectar a otras vistas que no lo necesiten.
-->
<style>
  .unread-dot {
    width: .4375rem;
    height: .4375rem;
    border-radius: 50%;
    background: #7C65F6;
    display: inline-block;
    animation: unread-pulse 2s cubic-bezier(.4,0,.6,1) infinite;
  }

  @keyframes unread-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .45; transform: scale(.7); }
  }
</style>
