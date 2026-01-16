@extends('layouts.app')

@section('title', 'Proyectos • Gestión')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
  <div class="flex items-center justify-between gap-3">
    <h2 class="text-xl font-semibold">Proyectos</h2>
    <div class="flex items-center gap-2">
      <button id="btn-new-project" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl bg-[var(--c-primary)] text-[var(--c-primary-ink)] hover:opacity-95 shadow-soft">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        Nuevo
      </button>
    </div>
  </div>

  <!-- Filtros -->
  <section class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
    <form id="filters-form" class="grid grid-cols-1 md:grid-cols-5 gap-3">
      <div class="col-span-2">
        <label class="block text-xs text-[var(--c-muted)] mb-1">Buscar</label>
        <input id="f-search" type="search" placeholder="Nombre, código, descripción..." class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"/>
      </div>

      <div>
        <label class="block text-xs text-[var(--c-muted)] mb-1">Propietario (ID)</label>
        <input id="f-owner" type="number" min="1" placeholder="user_id" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"/>
      </div>

      <div>
        <label class="block text-xs text-[var(--c-muted)] mb-1">Desde (planificado)</label>
        <input id="f-from" type="date" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"/>
      </div>

      <div>
        <label class="block text-xs text-[var(--c-muted)] mb-1">Hasta (planificado)</label>
        <input id="f-to" type="date" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"/>
      </div>

      <div class="md:col-span-5 flex items-center gap-2">
        <button type="button" id="btn-apply" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Aplicar</button>
        <button type="button" id="btn-clear" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Limpiar</button>
        <div class="ml-auto flex items-center gap-2">
          <label class="text-xs text-[var(--c-muted)]">Por página</label>
          <select id="f-per-page" class="text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-2 py-1 outline-none focus:border-[var(--c-primary)]">
            <option>10</option>
            <option selected>15</option>
            <option>25</option>
            <option>50</option>
            <option>100</option>
          </select>
        </div>
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
              <button data-sort="code" class="sort-btn inline-flex items-center gap-1">Código <span class="opacity-60">↕</span></button>
            </th>
            <th class="px-4 py-3">
              <button data-sort="name" class="sort-btn inline-flex items-center gap-1">Nombre <span class="opacity-60">↕</span></button>
            </th>
            <th class="px-4 py-3">Owner</th>
            <th class="px-4 py-3">
              <button data-sort="start_planned" class="sort-btn inline-flex items-center gap-1">Inicio plan. <span class="opacity-60">↕</span></button>
            </th>
            <th class="px-4 py-3">
              <button data-sort="end_planned" class="sort-btn inline-flex items-center gap-1">Fin plan. <span class="opacity-60">↕</span></button>
            </th>
            <th class="px-4 py-3">Progreso</th>
            <th class="px-4 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody id="tbl-body" class="divide-y divide-[var(--c-border)]"></tbody>
      </table>
    </div>

    <!-- Paginación -->
    <div id="pagination" class="flex items-center justify-between gap-3 px-4 py-3 bg-[var(--c-surface)]">
      <div class="text-xs text-[var(--c-muted)]" id="pg-info"></div>
      <div class="flex items-center gap-2">
        <button id="pg-prev" class="px-3 py-1 rounded-lg ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)] text-xs">Anterior</button>
        <span id="pg-page" class="text-xs"></span>
        <button id="pg-next" class="px-3 py-1 rounded-lg ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)] text-xs">Siguiente</button>
      </div>
    </div>
  </section>
</div>

<!-- Modal Crear/Editar Proyecto -->
<div id="project-modal" class="fixed inset-0 z-[12000] hidden" aria-modal="true" role="dialog">
  <div data-js="overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
  <div class="relative mx-auto mt-10 w-[95%] max-w-2xl">
    <div class="rounded-2xl overflow-hidden border border-[var(--c-border)] bg-[var(--c-surface)] shadow-soft">
      <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-[var(--c-border)]">
        <h3 id="pm-title" class="text-lg font-semibold">Nuevo proyecto</h3>
        <button type="button" data-js="btn-close" class="p-2 rounded-xl hover:bg-[var(--c-elev)] transition" aria-label="Cerrar">
          <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 8.586l4.95-4.95a1 1 0 111.414 1.415L11.414 10l4.95 4.95a1 1 0 11-1.414 1.415L10 11.414l-4.95 4.95a1 1 0 11-1.415-1.415L8.586 10l-4.95-4.95A1 1 0 115.05 3.636L10 8.586z" clip-rule="evenodd"/></svg>
        </button>
      </div>
      <div class="px-5 py-4">
        <form id="project-form" class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <input type="hidden" id="pm-id" />
          <div class="md:col-span-1">
            <label class="block text-xs text-[var(--c-muted)] mb-1">Código</label>
            <input id="pm-code" type="text" maxlength="50" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]" required />
          </div>
          <div class="md:col-span-1">
            <label class="block text-xs text-[var(--c-muted)] mb-1">Nombre</label>
            <input id="pm-name" type="text" maxlength="255" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]" required />
          </div>
          <div class="md:col-span-2">
            <label class="block text-xs text-[var(--c-muted)] mb-1">Descripción</label>
            <textarea id="pm-description" rows="3" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"></textarea>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Owner (ID)</label>
            <input id="pm-owner" type="number" min="1" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"/>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Progreso</label>
            <input id="pm-progress" type="number" min="0" max="100" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"/>
          </div>
          <div class="md:col-span-2 grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs text-[var(--c-muted)] mb-1">Inicio plan.</label>
              <input id="pm-start-planned" type="date" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"/>
            </div>
            <div>
              <label class="block text-xs text-[var(--c-muted)] mb-1">Fin plan.</label>
              <input id="pm-end-planned" type="date" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none focus:border-[var(--c-primary)]"/>
            </div>
          </div>
        </form>
      </div>
      <div class="px-5 py-4 border-t border-[var(--c-border)] flex items-center justify-end gap-2">
        <button type="button" data-js="btn-cancel" class="rounded-lg border border-[var(--c-border)] px-4 py-2 hover:bg-[var(--c-elev)] transition text-sm">Cancelar</button>
        <button type="button" id="pm-save" class="rounded-lg bg-[var(--c-primary)] text-[var(--c-primary-ink)] px-4 py-2 hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[var(--c-primary)] text-sm">Guardar</button>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  // Estándar tomado de la vista de usuarios: API_BASE, CSRF y API_TOKEN desde meta
  const API_BASE   = '/api';
  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const API_TOKEN  = document.querySelector('meta[name="api-token"]')?.getAttribute('content') || null;

  // Verificar token antes de continuar (mismo patrón que users.manage)
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

  // Estado
  const state = {
    page: 1,
    per_page: 15,
    order: 'created_at',
    sort: 'desc',
    search: '',
    owner_id: '',
    from: '',
    to: '',
    data: null,
  };

  // Elementos
  const tbody = document.getElementById('tbl-body');
  const pgInfo = document.getElementById('pg-info');
  const pgPrev = document.getElementById('pg-prev');
  const pgNext = document.getElementById('pg-next');
  const pgPage = document.getElementById('pg-page');
  const perPageSel = document.getElementById('f-per-page');

  // Filtros
  const fSearch = document.getElementById('f-search');
  const fOwner = document.getElementById('f-owner');
  const fFrom = document.getElementById('f-from');
  const fTo = document.getElementById('f-to');
  const btnApply = document.getElementById('btn-apply');
  const btnClear = document.getElementById('btn-clear');

  // Modal
  const modal = document.getElementById('project-modal');
  const overlay = modal.querySelector('[data-js="overlay"]');
  const btnClose = modal.querySelector('[data-js="btn-close"]');
  const btnCancel = modal.querySelector('[data-js="btn-cancel"]');
  const btnNew = document.getElementById('btn-new-project');
  const btnSave = document.getElementById('pm-save');
  const pmTitle = document.getElementById('pm-title');

  const pmId = document.getElementById('pm-id');
  const pmCode = document.getElementById('pm-code');
  const pmName = document.getElementById('pm-name');
  const pmDescription = document.getElementById('pm-description');
  const pmOwner = document.getElementById('pm-owner');
  const pmProgress = document.getElementById('pm-progress');
  const pmStartPlanned = document.getElementById('pm-start-planned');
  const pmEndPlanned = document.getElementById('pm-end-planned');

  function showModal(editing = null) {
    if (editing) {
      pmTitle.textContent = 'Editar proyecto';
      pmId.value = editing.id;
      pmCode.value = editing.code != null ? editing.code : '';
      pmName.value = editing.name != null ? editing.name : '';
      pmDescription.value = editing.description != null ? editing.description : '';
      pmOwner.value = editing.owner_id != null ? editing.owner_id : '';
      pmProgress.value = editing.progress != null ? editing.progress : '';
      pmStartPlanned.value = editing.start_planned != null ? editing.start_planned : '';
      pmEndPlanned.value = editing.end_planned != null ? editing.end_planned : '';
    } else {
      pmTitle.textContent = 'Nuevo proyecto';
      pmId.value = '';
      pmCode.value = '';
      pmName.value = '';
      pmDescription.value = '';
      pmOwner.value = '';
      pmProgress.value = '';
      pmStartPlanned.value = '';
      pmEndPlanned.value = '';
    }
    modal.classList.remove('hidden');
  }
  function hideModal() {
    modal.classList.add('hidden');
  }

  overlay.addEventListener('click', hideModal);
  btnClose.addEventListener('click', hideModal);
  btnCancel.addEventListener('click', hideModal);
  btnNew.addEventListener('click', () => showModal());

  // API helper con encabezados estándar (Authorization, CSRF, Accept)
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
    const raw = await res.json().catch(() => ({}));
    const payload = { ...raw, status: res.status, raw };
    // Modal JSON estandarizado (opcional, sólo en operaciones mutables)
    if (opts.method && opts.method !== 'GET') {
      window.dispatchEvent(new CustomEvent('api:response', { detail: payload }));
    }
    if (!res.ok) {
      try { window.dispatchEvent(new CustomEvent('api:response', { detail: payload })); } catch (_e) {}
      throw payload;
    }
    return payload;
  }

  function buildQuery(params){
    const q = new URLSearchParams();
    Object.entries(params).forEach(([k,v])=>{
      if (v !== undefined && v !== null && String(v).trim() !== '') q.append(k, v);
    });
    return q.toString();
  }

  async function load() {
    const params = {
      page: state.page,
      per_page: state.per_page,
      search: state.search,
      order: state.order,
      sort: state.sort,
    };
    if (state.owner_id) params.owner_id = state.owner_id;
    if (state.from) params.from = state.from;
    if (state.to) params.to = state.to;

    const qs = buildQuery(params);
    const { data } = await api('/api/projects?' + qs, { method: 'GET' });
    state.data = data;
    render();
  }

  function render() {
    const d = state.data;
    if (!d) return;
    tbody.innerHTML = '';
    (d.data || []).forEach(row => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="px-4 py-3 whitespace-nowrap">${esc(row.code ?? '')}</td>
        <td class="px-4 py-3">${esc(row.name ?? '')}</td>
        <td class="px-4 py-3">${row.owner_id ?? '—'}</td>
        <td class="px-4 py-3">${row.start_planned ?? '—'}</td>
        <td class="px-4 py-3">${row.end_planned ?? '—'}</td>
        <td class="px-4 py-3">
          <div class="w-28 h-2 bg-[var(--c-elev)] rounded">
            <div class="h-2 bg-[var(--c-primary)] rounded" style="width:${Math.min(100, Math.max(0, Number(row.progress ?? 0)))}%"></div>
          </div>
          <span class="text-xs text-[var(--c-muted)]">${row.progress ?? 0}%</span>
        </td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2">
            <a href="/admin/projects/${row.id}" class="text-xs px-2 py-1 rounded ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Ver</a>
            <button data-act="edit" data-id="${row.id}" class="text-xs px-2 py-1 rounded ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Editar</button>
            <button data-act="del" data-id="${row.id}" class="text-xs px-2 py-1 rounded ring-1 ring-red-900/40 text-red-400 hover:bg-red-900/20">Eliminar</button>
          </div>
        </td>
      `;
      tbody.appendChild(tr);
    });

    // Paginación
    pgInfo.textContent = `Mostrando ${d.from != null ? d.from : 0} - ${d.to != null ? d.to : 0} de ${d.total != null ? d.total : 0}`;
    pgPage.textContent = `Página ${d.current_page != null ? d.current_page : 1} de ${d.last_page != null ? d.last_page : 1}`;
    pgPrev.disabled = ((d.current_page != null ? d.current_page : 1) <= 1);
    pgNext.disabled = ((d.current_page != null ? d.current_page : 1) >= (d.last_page != null ? d.last_page : 1));
  }

  // Orden
  document.querySelectorAll('.sort-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const field = btn.getAttribute('data-sort');
      if (state.order === field) {
        state.sort = state.sort === 'asc' ? 'desc' : 'asc';
      } else {
        state.order = field;
        state.sort = 'asc';
      }
      state.page = 1;
      load().catch(showError);
    });
  });

  // Paginación
  pgPrev.addEventListener('click', () => { const cp = (state.data && state.data.current_page != null) ? state.data.current_page : 1; state.page = Math.max(1, cp - 1); load().catch(showError); });
  pgNext.addEventListener('click', () => { const lp = (state.data && state.data.last_page != null) ? state.data.last_page : 1; const cp = (state.data && state.data.current_page != null) ? state.data.current_page : 1; state.page = Math.min(lp, cp + 1); load().catch(showError); });
  perPageSel.addEventListener('change', () => { state.per_page = Number(perPageSel.value || 15); state.page = 1; load().catch(showError); });

  // Filtros
  btnApply.addEventListener('click', () => {
    state.search = fSearch.value.trim();
    state.owner_id = fOwner.value.trim();
    state.from = fFrom.value;
    state.to = fTo.value;
    state.page = 1;
    load().catch(showError);
  });
  btnClear.addEventListener('click', () => {
    fSearch.value = ''; fOwner.value=''; fFrom.value=''; fTo.value='';
    state.search = ''; state.owner_id=''; state.from=''; state.to='';
    state.page = 1;
    load().catch(showError);
  });

  // Acciones fila
  tbody.addEventListener('click', async (e) => {
    const t = e.target.closest('button[data-act]');
    if (!t) return;
    const id = t.getAttribute('data-id');
    const act = t.getAttribute('data-act');
    const row = (state.data?.data || []).find(r => String(r.id) === String(id));
    if (!row) return;

    if (act === 'edit') {
      showModal(row);
    } else if (act === 'del') {
      if (!confirm('¿Enviar proyecto a la papelera?')) return;
      try {
        await api('/api/projects/' + id, { method: 'DELETE' });
        await load();
      } catch (err) {
        showError(err);
      }
    }
  });

  // Guardar modal
  btnSave.addEventListener('click', async () => {
    const body = {
      code: pmCode.value.trim(),
      name: pmName.value.trim(),
      description: pmDescription.value.trim() || null,
      owner_id: pmOwner.value ? Number(pmOwner.value) : null,
      progress: pmProgress.value ? Number(pmProgress.value) : null,
      start_planned: pmStartPlanned.value || null,
      end_planned: pmEndPlanned.value || null,
    };

    const editId = pmId.value.trim();
    try {
      if (editId) {
        await api('/api/projects/' + editId, { method: 'PATCH', body: JSON.stringify(body) });
      } else {
        await api('/api/projects', { method: 'POST', body: JSON.stringify(body) });
      }
      hideModal();
      await load();
    } catch (err) {
      showError(err);
    }
  });

  function esc(v) {
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
    };
    return String(v ?? '').replace(/[&<>"']/g, ch => map[ch]);
  }
  function showError(err){
    try {
      window.dispatchEvent(new CustomEvent('api:response', { detail: { success:false, message: (err && err.message) || 'Error', code: (err && err.code) || 'ERROR', errors: (err && err.errors) || null, status: (err && err.status) != null ? err.status : null, raw: (err && err.raw) != null ? err.raw : err } }));
    } catch (_e) {}
  }

  // Inicial
  state.per_page = Number(perPageSel.value || 15);
  load().catch(showError);
})();
</script>
@endsection