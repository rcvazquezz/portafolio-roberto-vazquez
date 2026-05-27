<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Acceso — <?= APP_NAME ?></title>

  <!--
    Tipografía idéntica al portafolio público:
      · Syne     → headings (display)
      · Inter    → body, inputs, labels
    Precarga de dominios para evitar FOUT.
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    /**
     * Design tokens sincronizados con tailwind.config.js del portafolio.
     * Paleta: Noir & Violet — #0C0C0F base, #7C65F6 acento.
     * IMPORTANTE: si cambias estos valores, actualiza también el config raíz.
     */
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['Syne', 'sans-serif'],
            body:    ['Inter', 'sans-serif'],
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

  <style>
    /*
     * Estilos que el CDN de Tailwind no puede generar:
     * gradientes compuestos, transiciones con easing personalizados,
     * overrides de autofill del navegador.
     *
     * Regla: nada aquí que Tailwind pueda expresar con clases atómicas.
     */

    /* ── Fondo de página ────────────────────────────────────────────
       Dos glows violeta asimétricos sobre negro profundo.
       Imita el lenguaje visual del hero del portafolio en modo oscuro.
    ──────────────────────────────────────────────────────────────── */
    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background:
        radial-gradient(ellipse 65% 45% at 78% -5%,  rgba(124,101,246,.16) 0%, transparent 55%),
        radial-gradient(ellipse 45% 40% at 20% 105%, rgba(124,101,246,.08) 0%, transparent 50%),
        #0C0C0F;
    }

    /* ── Card del formulario ────────────────────────────────────────
       Superficie graphite elevada con sombra multicapa:
         · Ring exterior muy sutil para definir el borde en negro profundo
         · Sombra difusa de elevación real
    ──────────────────────────────────────────────────────────────── */
    .login-card {
      background: #1A1A24;
      border: 1px solid rgba(255, 255, 255, .07);
      border-radius: 16px;
      box-shadow:
        0 0 0 1px rgba(124, 101, 246, .06),
        0 8px 24px rgba(0, 0, 0, .35),
        0 32px 64px rgba(0, 0, 0, .25);
    }

    /* ── Input oscuro ────────────────────────────────────────────────
       Mismo sistema de estados que .form-input en input.css del portafolio:
         1. Default  → borde rgba blanco muy tenue
         2. Hover    → borde más visible
         3. Focus    → borde violeta + ring translúcido (no outline nativo)
    ──────────────────────────────────────────────────────────────── */
    .cms-input {
      display: block;
      width: 100%;
      background: rgba(255, 255, 255, .04);
      border: 1px solid rgba(255, 255, 255, .09);
      border-radius: 8px;
      padding: .6875rem .875rem;
      font-size: .875rem;
      font-family: 'Inter', sans-serif;
      color: #fff;
      outline: none;
      transition:
        border-color 200ms cubic-bezier(.4, 0, .2, 1),
        box-shadow   200ms cubic-bezier(.4, 0, .2, 1);
    }
    .cms-input::placeholder { color: rgba(255, 255, 255, .22); }
    .cms-input:hover         { border-color: rgba(255, 255, 255, .16); }
    .cms-input:focus {
      border-color: #7C65F6;
      box-shadow: 0 0 0 3px rgba(124, 101, 246, .18);
    }

    /*
     * Evita que Chrome/Safari sobreescriban el fondo oscuro del input
     * con el fondo amarillo/blanco del autofill nativo del navegador.
     * Truco estándar: inyectar box-shadow inset de 1000px.
     */
    .cms-input:-webkit-autofill,
    .cms-input:-webkit-autofill:hover,
    .cms-input:-webkit-autofill:focus {
      -webkit-box-shadow: 0 0 0 1000px #1A1A24 inset;
      -webkit-text-fill-color: #fff;
      caret-color: #fff;
    }

    /* ── Botón primario ──────────────────────────────────────────────
       Mismo spring easing que .btn-primary del portafolio:
       cubic-bezier(.34, 1.56, .64, 1) → ligero rebote al hover.
    ──────────────────────────────────────────────────────────────── */
    .cms-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      width: 100%;
      background: #7C65F6;
      color: #fff;
      font-family: 'Inter', sans-serif;
      font-weight: 600;
      font-size: .875rem;
      padding: .6875rem 1.25rem;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 16px rgba(124, 101, 246, .28);
      transition:
        background-color 150ms cubic-bezier(.4, 0, .2, 1),
        transform        350ms cubic-bezier(.34, 1.56, .64, 1),
        box-shadow       150ms cubic-bezier(.4, 0, .2, 1);
    }
    .cms-btn:hover {
      background: #5B45D4;
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(124, 101, 246, .40);
    }
    .cms-btn:active  { transform: translateY(0); }
    .cms-btn:focus-visible {
      outline: 2px solid #7C65F6;
      outline-offset: 3px;
    }

    /* ── Monograma RV: escala spring al hover del enlace ── */
    .rv-logo {
      width: 2.75rem;
      height: 2.75rem;
      border-radius: 10px;
      background: #7C65F6;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 16px rgba(124, 101, 246, .30);
      transition: transform 350ms cubic-bezier(.34, 1.56, .64, 1),
                  box-shadow 200ms cubic-bezier(.4, 0, .2, 1);
    }
    .rv-logo:hover {
      transform: scale(1.07);
      box-shadow: 0 8px 28px rgba(124, 101, 246, .45);
    }
  </style>
</head>

<body>
  <div class="w-full max-w-[22rem] px-4">

    <!-- ── Branding ──────────────────────────────────────────────────
         Monograma RV + título: misma identidad que la nav del portafolio.
         El enlace abre el portafolio público en una pestaña nueva.
    ─────────────────────────────────────────────────────────────── -->
    <div class="flex flex-col items-center text-center mb-8">
      <a href="/portafolio-roberto-vazquez/"
         target="_blank"
         title="Abrir portafolio público"
         class="block mb-5 mx-auto">
        <div class="rv-logo mx-auto">
          <span style="font-family:'Syne',sans-serif; font-weight:700; font-size:.875rem;
                       color:#fff; letter-spacing:-.01em;">RV</span>
        </div>
      </a>

      <h1 style="font-family:'Syne',sans-serif; font-size:1.375rem; font-weight:700;
                 color:#fff; letter-spacing:-.025em; line-height:1.2;">
        Portfolio CMS
      </h1>
      <p style="font-size:.8125rem; color:rgba(255,255,255,.38); margin-top:.375rem;">
        Panel de administración privado
      </p>
    </div>

    <!-- ── Alerta de credenciales incorrectas ────────────────────────
         El controlador de login establece el flash en la sesión.
         Solo se renderiza si existe y es de tipo 'error'.
    ─────────────────────────────────────────────────────────────── -->
    <?php if (!empty($flash) && $flash['type'] === 'error'): ?>
      <div role="alert"
           class="flex items-start gap-3 mb-5 px-4 py-3 rounded-xl text-red-300"
           style="background:rgba(239,68,68,.10); border:1px solid rgba(239,68,68,.22); font-size:.8125rem;">
        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
             aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span><?= htmlspecialchars($flash['message']) ?></span>
      </div>
    <?php endif; ?>

    <!-- ── Formulario de acceso ──────────────────────────────────────
         novalidate → la validación la gestiona el servidor (AuthController).
         CSRF token → campo hidden obligatorio para prevenir CSRF attacks.
    ─────────────────────────────────────────────────────────────── -->
    <div class="login-card px-8 py-8">
      <form method="POST" action="<?= APP_URL ?>/login" novalidate>

        <input type="hidden"
               name="<?= CSRF_TOKEN_NAME ?>"
               value="<?= htmlspecialchars($csrf) ?>">

        <!-- Email -->
        <div style="margin-bottom:1.25rem;">
          <label for="email"
                 style="display:block; font-size:.6875rem; font-weight:600;
                        color:rgba(255,255,255,.38); text-transform:uppercase;
                        letter-spacing:.07em; margin-bottom:.5rem;">
            Email
          </label>
          <input
            type="email"
            id="email"
            name="email"
            required
            autocomplete="email"
            class="cms-input"
            placeholder="admin@rcvazquez.com"
          >
        </div>

        <!-- Contraseña -->
        <div style="margin-bottom:1.75rem;">
          <label for="password"
                 style="display:block; font-size:.6875rem; font-weight:600;
                        color:rgba(255,255,255,.38); text-transform:uppercase;
                        letter-spacing:.07em; margin-bottom:.5rem;">
            Contraseña
          </label>
          <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="current-password"
            class="cms-input"
            placeholder="••••••••"
          >
        </div>

        <!-- Submit -->
        <button type="submit" class="cms-btn">
          Entrar al panel
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </button>

      </form>
    </div>

    <!-- ── Aviso de acceso restringido ── -->
    <p style="text-align:center; font-size:.6875rem; color:rgba(255,255,255,.18); margin-top:1.25rem;">
      Panel privado · Solo administradores autorizados
    </p>

  </div>
</body>
</html>
