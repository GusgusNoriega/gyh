@extends('layouts.app')

@section('title', 'Catálogo • Categorías de Archivo')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
  <div class="flex items-center justify-between gap-3">
    <h2 class="text-xl font-semibold">Categorías de Archivo</h2>
    <div class="flex items-center gap-2">
      <a href="{{ route('projects') }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">
        Ir a Proyectos
      </a>
    </div>
  </div>

  <!-- Filtros -->
  <section class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
    <form id="filters-form" class="grid grid-cols-1 md:grid-cols-4 gap-3">
      <div class="md:col-span-2">
        <label class="block text-xs text-[var(--c-muted)] mb-1">Buscar</label>
        <input id="f-search" type="search" placeholder="Nombre, código o descripción..." class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"/>
      </div>
      <div class="md:col-span-2 flex items-end gap-2">
        <button type="button" id="btn-apply" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Aplicar</button>
        <button type="button" id="btn-clear" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Limpiar</button>
      </div>
    </form>
  </section>

  <!-- Tabla -->
  <section class="rounded-2xl border border-[var(--c-border)] overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-[var(--c-elev)] text-left">
          <tr>
            <th class="px-4 py-3">
              <button data-sort="id" class="sort-btn inline-flex items-center gap-1">ID <span class="opacity-60">↕</span></button>
            </th>
            <th class="px-4 py-3">
              <button data-sort="code" class="sort-btn inline-flex items-center gap-1">Código <span class="opacity-60">↕</span></button>
            </th>
            <th class="px-4 py-3">
              <button data-sort="name" class="sort-btn inline-flex items-center gap-1">Nombre <span class="opacity-60">↕</span></button>
            </th>
            <th class="px-4 py-3">Descripción</th>
            <th class="px-4 py-3">
              <button data-sort="sort_order" class="sort-btn inline-flex items-center gap-1">Orden <span class="opacity-60">↕</span></button>
            </th>
            <th class="px-4 py-3">Sistema</th>
            <th class="px-4 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody id="tbl-body" class="divide-y divide-[var(--c-border)]"></tbody>
      </table>
    </div>
  </section>
</div>

<!-- Modal Editar Categoría -->
<div id="edit-modal" class="fixed inset-0 z-[12000] hidden" aria-modal="true" role="dialog">
  <div data-js="overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
  <div class="relative mx-auto mt-10 w-[95%] max-w-2xl">
    <div class="rounded-2xl overflow-hidden border border-[var(--c-border)] bg-[var(--c-surface)] shadow-soft">
      <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-[var(--c-border)]">
        <h3 class="text-lg font-semibold">Editar Categoría</h3>
        <button type="button" data-js="btn-close" class="p-2 rounded-xl hover:bg-[var(--c-elev)] transition" aria-label="Cerrar">
          <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 8.586l4.95-4.95a1 1 0 111.414 1.415L11.414 10l4.95 4.95a1 1 0 11-1.414 1.415L10 11.414l-4.95 4.95a1 1 0 11-1.415-1.415L8.586 10l-4.95-4.95A1 1 0 115.05 3.636L10 8.586z" clip-rule="evenodd"/></svg>
        </button>
      </div>
      <div class="px-5 py-4">
        <form id="edit-form" class="space-y-4">
          <input type="hidden" id="em-id" />
          
          <!-- Campos no editables (solo lectura) -->
          <div class="grid grid-cols-2 gap-3 p-3 rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)]">
            <div>
              <label class="block text-xs text-[var(--c-muted)] mb-1">ID (no editable)</label>
              <input id="em-id-display" type="text" readonly class="w-full text-sm rounded-xl bg-[var(--c-surface)] border border-[var(--c-border)] px-3 py-2 outline-none text-[var(--c-muted)] cursor-not-allowed"/>
            </div>
            <div>
              <label class="block text-xs text-[var(--c-muted)] mb-1">Código (no editable)</label>
              <input id="em-code" type="text" readonly class="w-full text-sm rounded-xl bg-[var(--c-surface)] border border-[var(--c-border)] px-3 py-2 outline-none text-[var(--c-muted)] cursor-not-allowed"/>
            </div>
          </div>

          <!-- Campos editables -->
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Nombre <span class="text-red-500">*</span></label>
            <input id="em-name" type="text" maxlength="255" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]" required/>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Descripción</label>
            <textarea id="em-description" rows="3" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"></textarea>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Orden</label>
            <input id="em-sort-order" type="number" min="0" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"/>
          </div>
        </form>
      </div>
      <div class="px-5 py-4 border-t border-[var(--c-border)] flex items-center justify-end gap-2">
        <button type="button" data-js="btn-cancel" class="rounded-lg border border-[var(--c-border)] px-4 py-2 hover:bg-[var(--c-elev)] transition text-sm">Cancelar</button>
        <button type="button" id="em-save" class="rounded-lg bg-[var(--c-primary)] text-[var(--c-primary-ink)] px-4 py-2 hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[var(--c-primary)] text-sm">Guardar</button>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const API_BASE   = '/api';
  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const API_TOKEN  = document.querySelector('meta[name="api-token"]')?.getAttribute('content') || null;

  if (!API_TOKEN) {
    window.dispatchEvent(new CustomEvent('api:response', {
      detail: {
        success: false,
        message: 'No se encontró un token de acceso válido. Por favor, inicia sesión nuevamente.',
        code: 'AUTH_REQUIRED',
        errors: { auth: ['Token ausente'] }
      }
    }));
    return;
  }

  const state = {
    search: '',
    order: 'sort_order',
    sort: 'asc',
    data: [],
  };

  const tbody = document.getElementById('tbl-body');
  const fSearch = document.getElementById('f-search');
  const btnApply = document.getElementById('btn-apply');
  const btnClear = document.getElementById('btn-clear');

  // Modal refs
  const modal = document.getElementById('edit-modal');
  const modalOverlay = modal.querySelector('[data-js="overlay"]');
  const modalClose = modal.querySelector('[data-js="btn-close"]');
  const modalCancel = modal.querySelector('[data-js="btn-cancel"]');
  const modalSave = document.getElementById('em-save');
  const emId = document.getElementById('em-id');
  const emIdDisplay = document.getElementById('em-id-display');
  const emCode = document.getElementById('em-code');
  const emName = document.getElementById('em-name');
  const emDescription = document.getElementById('em-description');
  const emSortOrder = document.getElementById('em-sort-order');

  function openModal(category) {
    emId.value = category.id;
    emIdDisplay.value = category.id;
    emCode.value = category.code ?? '';
    emName.value = category.name ?? '';
    emDescription.value = category.description ?? '';
    emSortOrder.value = category.sort_order ?? '';
    modal.classList.remove('hidden');
  }

  function closeModal() {
    modal.classList.add('hidden');
  }

  modalOverlay.addEventListener('click', closeModal);
  modalClose.addEventListener('click', closeModal);
  modalCancel.addEventListener('click', closeModal);

  modalSave.addEventListener('click', async () => {
    try {
      const id = emId.value;
      const body = {
        name: emName.value.trim(),
        description: emDescription.value.trim() || null,
        sort_order: emSortOrder.value ? Number(emSortOrder.value) : null,
      };
      await api('/api/file-categories/' + id, { method: 'PATCH', body: JSON.stringify(body) });
      closeModal();
      await load();
    } catch (err) {
      showError(err);
    }
  });

  async function api(url, opts = {}) {
    const res = await fetch(url, {
      ...opts,
      headers: {
        'Authorization': `Bearer ${API_TOKEN}`,
        'X-CSRF-TOKEN': CSRF_TOKEN,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        ...(opts.headers || {})
      }
    });
    const raw = await res.json().catch(()=>({}));
    const payload = { ...raw, status: res.status, raw };
    if (opts.method && opts.method !== 'GET') {
      window.dispatchEvent(new CustomEvent('api:response', { detail: payload }));
    }
    if (!res.ok) {
      try { window.dispatchEvent(new CustomEvent('api:response', { detail: payload })); } catch(_e){}
      throw payload;
    }
    return payload;
  }

  function esc(v){ return String(v ?? '').replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[s])); }

  function showError(err){
    try {
      window.dispatchEvent(new CustomEvent('api:response', { detail: { success:false, message: err?.message || 'Error', code: err?.code || 'ERROR', errors: err?.errors || null, status: err?.status ?? null, raw: err?.raw ?? err } }));
    } catch (_e) {}
  }

  async function load(){
    const params = new URLSearchParams();
    if (state.search) params.append('search', state.search);
    params.append('paginate', 'false');
    const { data } = await api('/api/file-categories?'+params.toString(), { method:'GET' });
    state.data = Array.isArray(data) ? data.slice() : [];
    render();
  }

  function render(){
    let rows = state.data.slice();
    const key = state.order;
    rows.sort((a,b)=>{
      const A = (a?.[key] ?? '').toString().toLowerCase();
      const B = (b?.[key] ?? '').toString().toLowerCase();
      if (A < B) return state.sort === 'asc' ? -1 : 1;
      if (A > B) return state.sort === 'asc' ? 1 : -1;
      return 0;
    });

    tbody.innerHTML = '';
    rows.forEach(s => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="px-4 py-3">${s.id}</td>
        <td class="px-4 py-3">
          <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-[var(--c-elev)] text-xs font-mono">
            ${esc(s.code ?? '')}
          </span>
        </td>
        <td class="px-4 py-3 font-medium">${esc(s.name ?? '')}</td>
        <td class="px-4 py-3 text-[var(--c-muted)]">${esc(s.description ?? '—')}</td>
        <td class="px-4 py-3">${s.sort_order ?? '—'}</td>
        <td class="px-4 py-3">
          ${s.is_system ? '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-blue-500/10 text-blue-400 text-xs">Sistema</span>' : '<span class="text-[var(--c-muted)]">No</span>'}
        </td>
        <td class="px-4 py-3">
          <button data-act="edit" data-id="${s.id}" class="text-xs px-2 py-1 rounded ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)] transition">Editar</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  }

  tbody.addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-act="edit"]');
    if (!btn) return;
    const id = Number(btn.getAttribute('data-id'));
    const category = state.data.find(c => c.id === id);
    if (category) openModal(category);
  });

  document.querySelectorAll('.sort-btn').forEach(btn => {
    btn.addEventListener('click', ()=>{
      const field = btn.getAttribute('data-sort');
      if (state.order === field) {
        state.sort = state.sort === 'asc' ? 'desc' : 'asc';
      } else {
        state.order = field;
        state.sort = 'asc';
      }
      render();
    });
  });

  btnApply.addEventListener('click', ()=>{ state.search = fSearch.value.trim(); load().catch(showError); });
  btnClear.addEventListener('click', ()=>{ fSearch.value=''; state.search=''; load().catch(showError); });

  load().catch(showError);
})();
</script>
@endsection