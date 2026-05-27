<?php /* Vista: education/create.php */ ?>

<div class="max-w-2xl">
  <a href="<?= APP_URL ?>/education"
     class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors mb-6">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Volver a educación
  </a>

  <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
    <form method="POST" action="<?= APP_URL ?>/education/create" class="space-y-5">
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars($csrf) ?>">

      <div>
        <label for="institution" class="block text-xs font-medium text-gray-400 mb-1.5">
          Institución <span class="text-red-500">*</span>
        </label>
        <input type="text" id="institution" name="institution" required maxlength="150"
               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                      placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
               placeholder="Universidad Autónoma de México">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="degree" class="block text-xs font-medium text-gray-400 mb-1.5">
            Título / Certificación <span class="text-red-500">*</span>
          </label>
          <input type="text" id="degree" name="degree" required maxlength="150"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
                 placeholder="Ingeniería en Sistemas">
        </div>
        <div>
          <label for="field" class="block text-xs font-medium text-gray-400 mb-1.5">
            Área de estudio <span class="text-gray-600">(opcional)</span>
          </label>
          <input type="text" id="field" name="field" maxlength="150"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
                 placeholder="Desarrollo de Software">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="start_year" class="block text-xs font-medium text-gray-400 mb-1.5">
            Año de inicio <span class="text-red-500">*</span>
          </label>
          <input type="number" id="start_year" name="start_year" required
                 min="1990" max="<?= date('Y') ?>" value="<?= date('Y') ?>"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
        </div>
        <div>
          <label for="end_year" class="block text-xs font-medium text-gray-400 mb-1.5">Año de fin</label>
          <input type="number" id="end_year" name="end_year"
                 min="1990" max="<?= date('Y') + 10 ?>"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent disabled:opacity-50"
                 placeholder="<?= date('Y') ?>">
          <label class="flex items-center gap-2 mt-2 cursor-pointer">
            <input type="checkbox" id="is_current" name="is_current" value="1"
                   class="rounded border-gray-600 text-brand focus:ring-brand"
                   onchange="document.getElementById('end_year').disabled = this.checked">
            <span class="text-xs text-gray-400">En curso actualmente</span>
          </label>
        </div>
      </div>

      <div>
        <label for="sort_order" class="block text-xs font-medium text-gray-400 mb-1.5">Orden</label>
        <input type="number" id="sort_order" name="sort_order" value="0" min="0"
               class="w-32 bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                      focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
      </div>

      <div class="flex items-center gap-3 pt-2">
        <button type="submit"
                class="bg-brand hover:bg-violet-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
          Crear registro
        </button>
        <a href="<?= APP_URL ?>/education" class="text-sm text-gray-400 hover:text-white transition-colors px-3 py-2.5">
          Cancelar
        </a>
      </div>
    </form>
  </div>
</div>
