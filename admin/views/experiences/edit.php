<?php /* Vista: experiences/edit.php — Variables: $experience, $csrf */ ?>

<div class="max-w-2xl">
  <a href="<?= APP_URL ?>/experiences"
     class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors mb-6">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Volver a experiencia
  </a>

  <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
    <form method="POST" action="<?= APP_URL ?>/experiences/edit/<?= $experience['id'] ?>" class="space-y-5">
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars($csrf) ?>">

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="company" class="block text-xs font-medium text-gray-400 mb-1.5">
            Empresa <span class="text-red-500">*</span>
          </label>
          <input type="text" id="company" name="company" required maxlength="150"
                 value="<?= htmlspecialchars($experience['company']) ?>"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
        </div>
        <div>
          <label for="role" class="block text-xs font-medium text-gray-400 mb-1.5">
            Rol / Cargo <span class="text-red-500">*</span>
          </label>
          <input type="text" id="role" name="role" required maxlength="150"
                 value="<?= htmlspecialchars($experience['role']) ?>"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
        </div>
      </div>

      <div>
        <label for="description" class="block text-xs font-medium text-gray-400 mb-1.5">Descripción</label>
        <textarea id="description" name="description" rows="3"
                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                         focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent resize-none"><?= htmlspecialchars($experience['description']) ?></textarea>
      </div>

      <?php
        /* Convertir DATE (YYYY-MM-DD) a formato YYYY-MM para <input type="month"> */
        $startMonth = substr($experience['start_date'], 0, 7);
        $endMonth   = $experience['end_date'] ? substr($experience['end_date'], 0, 7) : '';
        $isCurrent  = (bool) $experience['is_current'];
      ?>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="start_date" class="block text-xs font-medium text-gray-400 mb-1.5">
            Fecha de inicio <span class="text-red-500">*</span>
          </label>
          <input type="month" id="start_date" name="start_date" required
                 value="<?= $startMonth ?>"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
        </div>
        <div>
          <label for="end_date" class="block text-xs font-medium text-gray-400 mb-1.5">Fecha de fin</label>
          <input type="month" id="end_date" name="end_date"
                 value="<?= $endMonth ?>"
                 <?= $isCurrent ? 'disabled' : '' ?>
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent disabled:opacity-50">
          <label class="flex items-center gap-2 mt-2 cursor-pointer">
            <input type="checkbox" id="is_current" name="is_current" value="1"
                   <?= $isCurrent ? 'checked' : '' ?>
                   class="rounded border-gray-600 text-brand focus:ring-brand"
                   onchange="document.getElementById('end_date').disabled = this.checked">
            <span class="text-xs text-gray-400">Trabajo actual (Presente)</span>
          </label>
        </div>
      </div>

      <div>
        <label for="sort_order" class="block text-xs font-medium text-gray-400 mb-1.5">Orden</label>
        <input type="number" id="sort_order" name="sort_order" min="0"
               value="<?= (int) $experience['sort_order'] ?>"
               class="w-32 bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                      focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
      </div>

      <div class="flex items-center gap-3 pt-2">
        <button type="submit"
                class="bg-brand hover:bg-violet-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
          Guardar cambios
        </button>
        <a href="<?= APP_URL ?>/experiences" class="text-sm text-gray-400 hover:text-white transition-colors px-3 py-2.5">
          Cancelar
        </a>
      </div>
    </form>
  </div>
</div>
