<?php /* Vista: projects/index.php — Variables: $projects */ ?>

<div class="flex items-center justify-between mb-6">
  <p class="text-sm text-gray-400"><?= count($projects) ?> proyecto(s) en total</p>
  <a href="<?= APP_URL ?>/projects/create"
     class="inline-flex items-center gap-2 bg-brand hover:bg-violet-700 text-white text-sm
            font-medium px-4 py-2 rounded-lg transition-colors">
    <i data-lucide="plus" class="w-4 h-4"></i>
    Nuevo proyecto
  </a>
</div>

<?php if (empty($projects)): ?>
  <div class="bg-gray-900 border border-gray-800 rounded-xl px-5 py-16 text-center">
    <i data-lucide="folder-open" class="w-10 h-10 text-gray-700 mx-auto mb-3"></i>
    <p class="text-sm text-gray-500">No hay proyectos todavía.</p>
    <a href="<?= APP_URL ?>/projects/create" class="text-sm text-violet-400 hover:underline mt-2 inline-block">
      Crear el primero →
    </a>
  </div>
<?php else: ?>
  <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
      <thead class="border-b border-gray-800">
        <tr class="text-left text-xs text-gray-500 uppercase tracking-wider">
          <th class="px-5 py-3 font-medium">Nombre</th>
          <th class="px-5 py-3 font-medium">Tags</th>
          <th class="px-5 py-3 font-medium">Estado</th>
          <th class="px-5 py-3 font-medium">Orden</th>
          <th class="px-5 py-3 font-medium text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-800">
        <?php foreach ($projects as $project): ?>
          <tr class="hover:bg-gray-800/50 transition-colors">
            <td class="px-5 py-4">
              <p class="font-medium text-white"><?= htmlspecialchars($project['name']) ?></p>
              <p class="text-xs text-gray-500 mt-0.5 line-clamp-1 max-w-xs">
                <?= htmlspecialchars($project['description']) ?>
              </p>
            </td>
            <td class="px-5 py-4">
              <div class="flex flex-wrap gap-1">
                <?php foreach (array_slice($project['tags'], 0, 3) as $tag): ?>
                  <span class="text-xs bg-gray-800 text-gray-300 border border-gray-700 px-2 py-0.5 rounded-full">
                    <?= htmlspecialchars($tag) ?>
                  </span>
                <?php endforeach; ?>
                <?php if (count($project['tags']) > 3): ?>
                  <span class="text-xs text-gray-600">+<?= count($project['tags']) - 3 ?></span>
                <?php endif; ?>
              </div>
            </td>
            <td class="px-5 py-4">
              <?php if ($project['status'] === 'published'): ?>
                <span class="inline-flex items-center gap-1 text-xs text-emerald-400 bg-emerald-900/30 border border-emerald-800 px-2 py-0.5 rounded-full">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                  Publicado
                </span>
              <?php else: ?>
                <span class="inline-flex items-center gap-1 text-xs text-gray-400 bg-gray-800 border border-gray-700 px-2 py-0.5 rounded-full">
                  <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                  Borrador
                </span>
              <?php endif; ?>
            </td>
            <td class="px-5 py-4 text-gray-400"><?= $project['sort_order'] ?></td>
            <td class="px-5 py-4">
              <div class="flex items-center justify-end gap-2">
                <!-- Editar -->
                <a href="<?= APP_URL ?>/projects/edit/<?= $project['id'] ?>"
                   class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                   title="Editar">
                  <i data-lucide="pencil" class="w-4 h-4"></i>
                </a>
                <!-- Eliminar -->
                <form method="POST" action="<?= APP_URL ?>/projects/delete/<?= $project['id'] ?>"
                      onsubmit="return confirm('¿Eliminar «<?= htmlspecialchars(addslashes($project['name'])) ?>»? Esta acción no se puede deshacer.')">
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
