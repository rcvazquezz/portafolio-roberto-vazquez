<?php /* Vista: education/index.php — Variables: $education */ ?>

<div class="flex items-center justify-between mb-6">
  <p class="text-sm text-gray-400"><?= count($education) ?> entrada(s)</p>
  <a href="<?= APP_URL ?>/education/create"
     class="inline-flex items-center gap-2 bg-brand hover:bg-violet-700 text-white text-sm
            font-medium px-4 py-2 rounded-lg transition-colors">
    <i data-lucide="plus" class="w-4 h-4"></i>
    Nueva educación
  </a>
</div>

<?php if (empty($education)): ?>
  <div class="bg-gray-900 border border-gray-800 rounded-xl px-5 py-16 text-center">
    <i data-lucide="graduation-cap" class="w-10 h-10 text-gray-700 mx-auto mb-3"></i>
    <p class="text-sm text-gray-500">No hay registros de educación todavía.</p>
  </div>
<?php else: ?>
  <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
      <thead class="border-b border-gray-800">
        <tr class="text-left text-xs text-gray-500 uppercase tracking-wider">
          <th class="px-5 py-3 font-medium">Institución / Título</th>
          <th class="px-5 py-3 font-medium">Período</th>
          <th class="px-5 py-3 font-medium">Orden</th>
          <th class="px-5 py-3 font-medium text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-800">
        <?php foreach ($education as $item): ?>
          <tr class="hover:bg-gray-800/50 transition-colors">
            <td class="px-5 py-4">
              <p class="font-medium text-white"><?= htmlspecialchars($item['institution']) ?></p>
              <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($item['degree']) ?></p>
              <?php if ($item['field']): ?>
                <p class="text-xs text-gray-600 mt-0.5"><?= htmlspecialchars($item['field']) ?></p>
              <?php endif; ?>
            </td>
            <td class="px-5 py-4 text-gray-400 text-xs">
              <?= $item['start_year'] ?>
              —
              <?= $item['is_current'] ? '<span class="text-emerald-400">En curso</span>' : ($item['end_year'] ?? '—') ?>
            </td>
            <td class="px-5 py-4 text-gray-400"><?= $item['sort_order'] ?></td>
            <td class="px-5 py-4">
              <div class="flex items-center justify-end gap-2">
                <a href="<?= APP_URL ?>/education/edit/<?= $item['id'] ?>"
                   class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                   title="Editar">
                  <i data-lucide="pencil" class="w-4 h-4"></i>
                </a>
                <form method="POST" action="<?= APP_URL ?>/education/delete/<?= $item['id'] ?>"
                      onsubmit="return confirm('¿Eliminar «<?= htmlspecialchars(addslashes($item['degree'])) ?>»?')">
                  <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars($_SESSION[CSRF_TOKEN_NAME] ?? '') ?>">
                  <button type="submit"
                          class="p-1.5 text-gray-400 hover:text-red-400 hover:bg-red-900/20 rounded-lg transition-colors"
                          title="Eliminar">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
