<?php /* Vista: projects/edit.php — Variables: $project, $csrf */ ?>

<div class="max-w-2xl">
  <a href="<?= APP_URL ?>/projects"
     class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors mb-6">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Volver a proyectos
  </a>

  <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
    <form method="POST" action="<?= APP_URL ?>/projects/edit/<?= $project['id'] ?>" class="space-y-5">

      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars($csrf) ?>">

      <!-- Nombre -->
      <div>
        <label for="name" class="block text-xs font-medium text-gray-400 mb-1.5">
          Nombre del proyecto <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" name="name" required maxlength="150"
               value="<?= htmlspecialchars($project['name']) ?>"
               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                      focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
      </div>

      <!-- Descripción -->
      <div>
        <label for="description" class="block text-xs font-medium text-gray-400 mb-1.5">
          Descripción <span class="text-red-500">*</span>
        </label>
        <textarea id="description" name="description" required rows="4"
                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                         focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent resize-none"><?= htmlspecialchars($project['description']) ?></textarea>
      </div>

      <!-- Tags (mostrar como string separado por comas) -->
      <div>
        <label for="tags" class="block text-xs font-medium text-gray-400 mb-1.5">
          Tags <span class="text-gray-600">(separados por coma)</span>
        </label>
        <input type="text" id="tags" name="tags"
               value="<?= htmlspecialchars(implode(', ', $project['tags'])) ?>"
               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                      focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
      </div>

      <!-- URL y GitHub -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="url" class="block text-xs font-medium text-gray-400 mb-1.5">URL en producción</label>
          <input type="url" id="url" name="url"
                 value="<?= htmlspecialchars($project['url'] ?? '') ?>"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
                 placeholder="https://miproyecto.com">
        </div>
        <div>
          <label for="github_url" class="block text-xs font-medium text-gray-400 mb-1.5">URL de GitHub</label>
          <input type="url" id="github_url" name="github_url"
                 value="<?= htmlspecialchars($project['github_url'] ?? '') ?>"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
        </div>
      </div>

      <!-- Insignia (badge opcional de contexto) -->
      <div>
        <label for="insignia" class="block text-xs font-medium text-gray-400 mb-1.5">
          Insignia <span class="text-gray-600">(opcional — aparece como badge bajo el título)</span>
        </label>
        <input type="text" id="insignia" name="insignia" maxlength="100"
               value="<?= htmlspecialchars($project['insignia'] ?? '') ?>"
               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                      placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
               placeholder="Ej: Sistema institucional, Agencia de Desarrollo">
      </div>

      <!-- Estado y orden -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="status" class="block text-xs font-medium text-gray-400 mb-1.5">Estado</label>
          <select id="status" name="status"
                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                         focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
            <?php
            /* Los valores de estado se almacenan como etiquetas de visualización directas.
               'draft' es el único valor interno; los demás se muestran tal cual en el portafolio. */
            $statusOptions = [
                'En producción' => 'En producción',
                'En desarrollo' => 'En desarrollo',
                'Finalizado'    => 'Finalizado',
                'Archivado'     => 'Archivado',
                'draft'         => 'Borrador (oculto del portafolio)',
            ];
            foreach ($statusOptions as $value => $label):
            ?>
              <option value="<?= $value ?>" <?= $project['status'] === $value ? 'selected' : '' ?>>
                <?= $label ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="sort_order" class="block text-xs font-medium text-gray-400 mb-1.5">
            Orden <span class="text-gray-600">(0 = primero)</span>
          </label>
          <input type="number" id="sort_order" name="sort_order" min="0"
                 value="<?= (int) $project['sort_order'] ?>"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
        </div>
      </div>

      <!-- Botones -->
      <div class="flex items-center gap-3 pt-2">
        <button type="submit"
                class="bg-brand hover:bg-violet-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
          Guardar cambios
        </button>
        <a href="<?= APP_URL ?>/projects"
           class="text-sm text-gray-400 hover:text-white transition-colors px-3 py-2.5">
          Cancelar
        </a>
      </div>

    </form>
  </div>
</div>
