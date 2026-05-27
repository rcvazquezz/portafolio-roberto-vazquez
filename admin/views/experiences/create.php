<?php /* Vista: experiences/create.php */ ?>

<div class="max-w-2xl">
  <a href="<?= APP_URL ?>/experiences"
     class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors mb-6">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Volver a experiencia
  </a>

  <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
    <form method="POST" action="<?= APP_URL ?>/experiences/create" class="space-y-5">
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars($csrf) ?>">

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="company" class="block text-xs font-medium text-gray-400 mb-1.5">
            Empresa <span class="text-red-500">*</span>
          </label>
          <input type="text" id="company" name="company" required maxlength="150"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
                 placeholder="Google">
        </div>
        <div>
          <label for="role" class="block text-xs font-medium text-gray-400 mb-1.5">
            Rol / Cargo <span class="text-red-500">*</span>
          </label>
          <input type="text" id="role" name="role" required maxlength="150"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent"
                 placeholder="Desarrollador Full Stack">
        </div>
      </div>

      <div>
        <label for="description" class="block text-xs font-medium text-gray-400 mb-1.5">
          Descripción
          <span class="text-gray-600 font-normal ml-1">— cada línea se muestra como un bullet en el portafolio</span>
        </label>
        <textarea id="description" name="description" rows="5"
                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                         placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent resize-none"
                  placeholder="Desarrollé aplicación Full Stack con PHP y MySQL.&#10;Lideré auditorías técnicas de infraestructura.&#10;Gestioné soporte técnico Nivel 1 y 2."></textarea>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="start_date" class="block text-xs font-medium text-gray-400 mb-1.5">
            Fecha de inicio <span class="text-red-500">*</span>
          </label>
          <input type="month" id="start_date" name="start_date" required
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
        </div>
        <div>
          <label for="end_date" class="block text-xs font-medium text-gray-400 mb-1.5">Fecha de fin</label>
          <input type="month" id="end_date" name="end_date"
                 class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                        focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
          <label class="flex items-center gap-2 mt-2 cursor-pointer">
            <input type="checkbox" id="is_current" name="is_current" value="1"
                   class="rounded border-gray-600 text-brand focus:ring-brand"
                   onchange="document.getElementById('end_date').disabled = this.checked">
            <span class="text-xs text-gray-400">Trabajo actual (Presente)</span>
          </label>
        </div>
      </div>

      <div>
        <label for="sort_order" class="block text-xs font-medium text-gray-400 mb-1.5">
          Orden <span class="text-gray-600">(0 = primero en el timeline)</span>
        </label>
        <input type="number" id="sort_order" name="sort_order" value="0" min="0"
               class="w-32 bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm text-white
                      focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent">
      </div>

      <div class="flex items-center gap-3 pt-2">
        <button type="submit"
                class="bg-brand hover:bg-violet-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
          Crear experiencia
        </button>
        <a href="<?= APP_URL ?>/experiences" class="text-sm text-gray-400 hover:text-white transition-colors px-3 py-2.5">
          Cancelar
        </a>
      </div>
    </form>
  </div>
</div>
