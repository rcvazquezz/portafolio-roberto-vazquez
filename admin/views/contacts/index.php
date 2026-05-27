<?php /* Vista: contacts/index.php — Variables: $contacts, $unread */ ?>

<div class="flex items-center justify-between mb-6">
  <p class="text-sm text-gray-400">
    <?= count($contacts) ?> mensaje(s)
    <?php if ($unread > 0): ?>
      · <span class="text-blue-400 font-medium"><?= $unread ?> sin leer</span>
    <?php endif; ?>
  </p>
</div>

<?php if (empty($contacts)): ?>
  <div class="bg-gray-900 border border-gray-800 rounded-xl px-5 py-16 text-center">
    <i data-lucide="inbox" class="w-10 h-10 text-gray-700 mx-auto mb-3"></i>
    <p class="text-sm text-gray-500">No hay mensajes todavía.</p>
  </div>
<?php else: ?>
  <div class="space-y-3">
    <?php foreach ($contacts as $contact): ?>
      <div class="bg-gray-900 border <?= !$contact['is_read'] ? 'border-blue-800' : 'border-gray-800' ?> rounded-xl p-5">
        <div class="flex items-start justify-between gap-4">

          <!-- Info del remitente -->
          <div class="flex items-start gap-4 flex-1 min-w-0">
            <div class="w-9 h-9 rounded-full <?= !$contact['is_read'] ? 'bg-blue-900/40' : 'bg-gray-800' ?> flex items-center justify-center flex-shrink-0">
              <span class="text-sm font-semibold <?= !$contact['is_read'] ? 'text-blue-400' : 'text-gray-400' ?>">
                <?= strtoupper(substr($contact['name'], 0, 1)) ?>
              </span>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-0.5">
                <p class="text-sm font-semibold text-white"><?= htmlspecialchars($contact['name']) ?></p>
                <?php if (!$contact['is_read']): ?>
                  <span class="text-xs bg-blue-900/40 text-blue-400 border border-blue-800 px-2 py-0.5 rounded-full">
                    Nuevo
                  </span>
                <?php endif; ?>
              </div>
              <p class="text-xs text-gray-500"><?= htmlspecialchars($contact['email']) ?></p>
              <p class="text-sm text-gray-300 mt-2 leading-relaxed">
                <?= nl2br(htmlspecialchars($contact['message'])) ?>
              </p>
              <p class="text-xs text-gray-600 mt-2">
                <?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?>
                <?php if ($contact['ip_address']): ?>
                  · IP: <?= htmlspecialchars($contact['ip_address']) ?>
                <?php endif; ?>
              </p>
            </div>
          </div>

          <!-- Acciones -->
          <div class="flex items-center gap-2 flex-shrink-0">
            <?php if (!$contact['is_read']): ?>
              <form method="POST" action="<?= APP_URL ?>/contacts/read/<?= $contact['id'] ?>">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars($_SESSION[CSRF_TOKEN_NAME] ?? '') ?>">
                <button type="submit"
                        title="Marcar como leído"
                        class="p-1.5 text-gray-400 hover:text-blue-400 hover:bg-blue-900/20 rounded-lg transition-colors">
                  <i data-lucide="check" class="w-4 h-4"></i>
                </button>
              </form>
            <?php endif; ?>

            <!-- Responder por email -->
            <a href="mailto:<?= htmlspecialchars($contact['email']) ?>?subject=Re: Contacto desde rcvazquez.com"
               title="Responder"
               class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
              <i data-lucide="reply" class="w-4 h-4"></i>
            </a>

            <!-- Eliminar -->
            <form method="POST" action="<?= APP_URL ?>/contacts/delete/<?= $contact['id'] ?>"
                  onsubmit="return confirm('¿Eliminar el mensaje de <?= htmlspecialchars(addslashes($contact['name'])) ?>?')">
              <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars($_SESSION[CSRF_TOKEN_NAME] ?? '') ?>">
              <button type="submit"
                      title="Eliminar"
                      class="p-1.5 text-gray-400 hover:text-red-400 hover:bg-red-900/20 rounded-lg transition-colors">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
              </button>
            </form>
          </div>

        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
