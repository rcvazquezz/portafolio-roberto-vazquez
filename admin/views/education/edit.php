<?php /* Vista: education/edit.php — Variables: $item, $csrf */ ?>

<div class="max-w-2xl">
  <a href="<?= APP_URL ?>/education"
     class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors mb-6">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Volver a educación
  </a>

  <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
    <form method="POST" action="<?= APP_URL ?>/education/edit/<?= $item['id'] ?>" class="space-y-5">
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars($csrf) ?>">

      <div>
        <label for="institution" class="block text-xs font-medium text-gray-400 mb-1.5">
          Institución <span class="text-red-500">*</span>
        </label>
        <input type="text" id="institution" name="institution" required maxlength="150"
               value="<?= htmlspecialchars($item['institution']) ?>"
               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                      focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="degree" class="block text-xs font-medium text-gray-400 mb-1.5">
            Título / Certificación <span class="text-red-500">*</span>
          </label>
          <input type="text" id="degree" name="degree" required maxlength="150"
                 value="<?= htmlspecialchars($item['degree']) ?>"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
        </div>
        <div>
          <label for="field" class="block text-xs font-medium text-gray-400 mb-1.5">Área de estudio</label>
          <input type="text" id="field" name="field" maxlength="150"
                 value="<?= htmlspecialchars($item['field'] ?? '') ?>"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
        </div>
      </div>

      <?php $isCurrent = (bool) $item['is_current']; ?>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="start_year" class="block text-xs font-medium text-gray-400 mb-1.5">
            Año de inicio <span class="text-red-500">*</span>
          </label>
          <input type="number" id="start_year" name="start_year" required
                 min="1990" max="<?= date('Y') ?>"
                 value="<?= $item['start_year'] ?>"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
        </div>
        <div>
          <label for="end_year" class="block text-xs font-medium text-gray-400 mb-1.5">Año de fin</label>
          <input type="number" id="end_year" name="end_year"
                 min="1990" max="<?= date('Y') + 10 ?>"
                 value="<?= $item['end_year'] ?? '' ?>"
                 <?= $isCurrent ? 'disabled' : '' ?>
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent disabled:opacity-50">
          <label class="flex items-center gap-2 mt-2 cursor-pointer">
            <input type="checkbox" id="is_current" name="is_current" value="1"
                   <?= $isCurrent ? 'checked' : '' ?>
                   class="rounded border-gray-600 text-brand focus:ring-brand"
                   onchange="document.getElementById('end_year').disabled = this.checked">
            <span class="text-xs text-gray-400">En curso actualmente</span>
          </label>
        </div>
      </div>

      <div>
        <label for="sort_order" class="block text-xs font-medium text-gray-400 mb-1.5">Orden</label>
        <input type="number" id="sort_order" name="sort_order" min="0"
               value="<?= (int) $item['sort_order'] ?>"
               class="w-32 bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                      focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
      </div>

      <div class="flex items-center gap-3 pt-2">
        <button type="submit"
                class="bg-brand hover:bg-violet-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
          Guardar cambios
        </button>
        <a href="<?= APP_URL ?>/education" class="text-sm text-gray-400 hover:text-white transition-colors px-3 py-2.5">
          Cancelar
        </a>
      </div>
    </form>
  </div>
</div>
