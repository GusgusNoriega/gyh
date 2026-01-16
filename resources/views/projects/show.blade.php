@extends('layouts.app')

@section('title', 'Proyecto • Overview')

@section('content')
@php
  $pid = (int)($projectId ?? 0);
  $projectName = $project->name ?? 'Sin nombre';
@endphp

<div class="max-w-7xl mx-auto space-y-6">
  <div class="flex items-center justify-between gap-3">
    <h2 class="text-xl font-semibold">{{ $projectName }} • #{{ $pid }} • Overview</h2>
    <div class="flex items-center gap-2">
      <a href="{{ route('projects.backlog', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Backlog</a>
      <a href="{{ route('projects.gantt', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Gantt</a>
      <a href="{{ route('projects.files', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Files</a>
      <a href="{{ route('projects') }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">← Lista de proyectos</a>
    </div>
  </div>

  <!-- Estadísticas rápidas -->
  <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
      <div class="flex items-center justify-between mb-2">
        <h4 class="text-sm font-medium text-[var(--c-muted)]">Total Tareas</h4>
        <svg class="w-5 h-5 text-[var(--c-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
      </div>
      <div id="stat-total-tasks" class="text-2xl font-bold">—</div>
    </div>

    <div class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
      <div class="flex items-center justify-between mb-2">
        <h4 class="text-sm font-medium text-[var(--c-muted)]">Completadas</h4>
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <div id="stat-completed-tasks" class="text-2xl font-bold text-green-500">—</div>
    </div>

    <div class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
      <div class="flex items-center justify-between mb-2">
        <h4 class="text-sm font-medium text-[var(--c-muted)]">En progreso</h4>
        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
      </div>
      <div id="stat-inprogress-tasks" class="text-2xl font-bold text-blue-500">—</div>
    </div>

    <div class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
      <div class="flex items-center justify-between mb-2">
        <h4 class="text-sm font-medium text-[var(--c-muted)]">Pendientes</h4>
        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <div id="stat-pending-tasks" class="text-2xl font-bold text-orange-500">—</div>
    </div>
  </section>

  <!-- Overview -->
  <section class="space-y-4">
    <div class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
      <div class="flex items-center justify-between gap-3 mb-3">
        <h3 class="text-lg font-semibold">Información del Proyecto</h3>
        <div class="flex items-center gap-2">
          <button id="btn-edit-project" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Editar</button>
        </div>
      </div>

      <div id="overview-grid" class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
        <div class="rounded-xl ring-1 ring-[var(--c-border)] p-3">
          <div class="text-[var(--c-muted)] text-xs">Código</div>
          <div id="ov-code" class="mt-1 font-medium">—</div>
        </div>
        <div class="rounded-xl ring-1 ring-[var(--c-border)] p-3 md:col-span-2">
          <div class="text-[var(--c-muted)] text-xs">Nombre</div>
          <div id="ov-name" class="mt-1 font-medium">—</div>
        </div>
        <div class="rounded-xl ring-1 ring-[var(--c-border)] p-3">
          <div class="text-[var(--c-muted)] text-xs">Owner ID</div>
          <div id="ov-owner" class="mt-1 font-medium">—</div>
        </div>
        <div class="rounded-xl ring-1 ring-[var(--c-border)] p-3">
          <div class="text-[var(--c-muted)] text-xs">Inicio plan.</div>
          <div id="ov-startp" class="mt-1 font-medium">—</div>
        </div>
        <div class="rounded-xl ring-1 ring-[var(--c-border)] p-3">
          <div class="text-[var(--c-muted)] text-xs">Fin plan.</div>
          <div id="ov-endp" class="mt-1 font-medium">—</div>
        </div>
        <div class="rounded-xl ring-1 ring-[var(--c-border)] p-3 md:col-span-3">
          <div class="text-[var(--c-muted)] text-xs mb-1">Progreso del Proyecto</div>
          <div class="flex items-center gap-3">
            <div class="flex-1 h-3 bg-[var(--c-elev)] rounded-full overflow-hidden">
              <div id="ov-progress-bar" class="h-full bg-[var(--c-primary)] rounded-full transition-all" style="width:0%"></div>
            </div>
            <span id="ov-progress" class="text-sm font-medium">0%</span>
          </div>
        </div>
        <div class="rounded-xl ring-1 ring-[var(--c-border)] p-3 md:col-span-3">
          <div class="text-[var(--c-muted)] text-xs">Descripción</div>
          <div id="ov-desc" class="mt-1">—</div>
        </div>
      </div>
    </div>

    <!-- Distribución por estado -->
    <div class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
      <h3 class="text-lg font-semibold mb-3">Distribución de Tareas por Estado</h3>
      <div id="status-distribution" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"></div>
    </div>

    <!-- Tareas recientes -->
    <div class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
      <div class="flex items-center justify-between gap-3 mb-3">
        <h3 class="text-lg font-semibold">Tareas Recientes</h3>
        <a href="{{ route('projects.backlog', ['id' => $pid]) }}" class="text-sm text-[var(--c-primary)] hover:underline">Ver todas →</a>
      </div>
      <div id="recent-tasks" class="space-y-2"></div>
    </div>
  </section>
</div>

<!-- Modal Editar Proyecto -->
<div id="project-edit-modal" class="fixed inset-0 z-[12000] hidden" aria-modal="true" role="dialog">
  <div data-js="overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
  <div class="relative mx-auto mt-10 w-[95%] max-w-2xl">
    <div class="rounded-2xl overflow-hidden border border-[var(--c-border)] bg-[var(--c-surface)] shadow-soft">
      <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-[var(--c-border)]">
        <h3 class="text-lg font-semibold">Editar proyecto</h3>
        <button type="button" data-js="btn-close" class="p-2 rounded-xl hover:bg-[var(--c-elev)] transition" aria-label="Cerrar">
          <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 8.586l4.95-4.95a1 1 0 111.414 1.415L11.414 10l4.95 4.95a1 1 0 11-1.414 1.415L10 11.414l-4.95 4.95a1 1 0 11-1.415-1.415L8.586 10l-4.95-4.95A1 1 0 115.05 3.636L10 8.586z" clip-rule="evenodd"/></svg>
        </button>
      </div>
      <div class="px-5 py-4">
        <form id="edit-form" class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Código</label>
            <input id="ef-code" type="text" maxlength="50" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Nombre</label>
            <input id="ef-name" type="text" maxlength="255" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          </div>
          <div class="md:col-span-2">
            <label class="block text-xs text-[var(--c-muted)] mb-1">Descripción</label>
            <textarea id="ef-desc" rows="3" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"></textarea>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Owner (ID)</label>
            <input id="ef-owner" type="number" min="1" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Progreso</label>
            <input id="ef-progress" type="number" min="0" max="100" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Inicio plan.</label>
            <input id="ef-startp" type="date" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Fin plan.</label>
            <input id="ef-endp" type="date" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          </div>
        </form>
      </div>
      <div class="px-5 py-4 border-t border-[var(--c-border)] flex items-center justify-end gap-2">
        <button type="button" data-js="btn-cancel" class="rounded-lg border border-[var(--c-border)] px-4 py-2 hover:bg-[var(--c-elev)] transition text-sm">Cancelar</button>
        <button type="button" id="ef-save" class="rounded-lg bg-[var(--c-primary)] text-[var(--c-primary-ink)] px-4 py-2 hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[var(--c-primary)] text-sm">Guardar</button>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const PROJECT_ID = {{ $pid }};
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

  function showError(err){
    try {
      window.dispatchEvent(new CustomEvent('api:response', { detail: { success:false, message: err?.message || 'Error', code: err?.code || 'ERROR', errors: err?.errors || null, status: err?.status ?? null, raw: err?.raw ?? err } }));
    } catch (_e) {}
  }
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
      try { window.dispatchEvent(new CustomEvent('api:response', { detail: payload })); } catch (_e) {}
      throw payload;
    }
    return payload;
  }
  function esc(v){
    return String(v ?? '').replace(/[&<>"']/g, m => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    })[m]);
  }

  // Overview refs
  const ov = {
    code: document.getElementById('ov-code'),
    name: document.getElementById('ov-name'),
    owner: document.getElementById('ov-owner'),
    startp: document.getElementById('ov-startp'),
    endp: document.getElementById('ov-endp'),
    progress: document.getElementById('ov-progress'),
    pbar: document.getElementById('ov-progress-bar'),
    desc: document.getElementById('ov-desc'),
    editBtn: document.getElementById('btn-edit-project'),
  };

  // Stats refs
  const stats = {
    total: document.getElementById('stat-total-tasks'),
    completed: document.getElementById('stat-completed-tasks'),
    inProgress: document.getElementById('stat-inprogress-tasks'),
    pending: document.getElementById('stat-pending-tasks'),
    distribution: document.getElementById('status-distribution'),
    recentTasks: document.getElementById('recent-tasks'),
  };

  // Edit modal
  const em = {
    root: document.getElementById('project-edit-modal'),
    overlay: null, close: null, cancel: null, save: null,
    code: null, name: null, desc: null, owner: null, progress: null, startp: null, endp: null
  };
  em.overlay = document.querySelector('#project-edit-modal [data-js="overlay"]');
  em.close = document.querySelector('#project-edit-modal [data-js="btn-close"]');
  em.cancel = document.querySelector('#project-edit-modal [data-js="btn-cancel"]');
  em.save = document.getElementById('ef-save');
  em.code = document.getElementById('ef-code');
  em.name = document.getElementById('ef-name');
  em.desc = document.getElementById('ef-desc');
  em.owner = document.getElementById('ef-owner');
  em.progress = document.getElementById('ef-progress');
  em.startp = document.getElementById('ef-startp');
  em.endp = document.getElementById('ef-endp');

  function openEdit(p){
    em.code.value = p.code ?? '';
    em.name.value = p.name ?? '';
    em.desc.value = p.description ?? '';
    em.owner.value = p.owner_id ?? '';
    em.progress.value = p.progress ?? '';
    em.startp.value = p.start_planned ?? '';
    em.endp.value = p.end_planned ?? '';
    em.root.classList.remove('hidden');
  }
  function closeEdit(){ em.root.classList.add('hidden'); }
  em.overlay.addEventListener('click', closeEdit);
  em.close.addEventListener('click', closeEdit);
  em.cancel.addEventListener('click', closeEdit);
  em.save.addEventListener('click', async ()=>{
    try {
      const body = {
        code: em.code.value || null,
        name: em.name.value || null,
        description: em.desc.value || null,
        owner_id: em.owner.value ? Number(em.owner.value) : null,
        progress: em.progress.value ? Number(em.progress.value) : null,
        start_planned: em.startp.value || null,
        end_planned: em.endp.value || null,
      };
      await api(API_BASE + '/projects/' + PROJECT_ID, { method: 'PATCH', body: JSON.stringify(body) });
      closeEdit();
      await loadAll();
    } catch (e){ showError(e); }
  });

  ov.editBtn.addEventListener('click', async ()=>{
    if (!state.project) { await loadOverview(); }
    openEdit(state.project || {});
  });

  // State
  const state = { 
    project: null,
    tasks: [],
    taskStatuses: [],
  };

  // Loaders
  async function loadOverview(){
    try{
      const { data } = await api(API_BASE + '/projects/' + PROJECT_ID, { method: 'GET' });
      state.project = data;
      ov.code.textContent = data.code ?? '—';
      ov.name.textContent = data.name ?? '—';
      ov.owner.textContent = data.owner_id ?? '—';
      ov.startp.textContent = data.start_planned ?? '—';
      ov.endp.textContent = data.end_planned ?? '—';
      const prog = Math.min(100, Math.max(0, Number(data.progress ?? 0)));
      ov.pbar.style.width = prog + '%';
      ov.progress.textContent = prog + '%';
      ov.desc.textContent = data.description ?? '—';
    } catch(e){ showError(e); }
  }

  async function loadTasks(){
    try{
      const { data } = await api(API_BASE + '/tasks?project_id=' + PROJECT_ID + '&per_page=1000', { method: 'GET' });
      state.tasks = data?.data ?? data ?? [];
    } catch(e){ showError(e); }
  }

  async function loadTaskStatuses(){
    try{
      const { data } = await api(API_BASE + '/task-status?paginate=false', { method: 'GET' });
      state.taskStatuses = data || [];
    } catch(e){ showError(e); }
  }

  function renderStats(){
    const total = state.tasks.length;
    const completed = state.tasks.filter(t => t.progress === 100).length;
    const inProgress = state.tasks.filter(t => t.progress > 0 && t.progress < 100).length;
    const pending = state.tasks.filter(t => !t.progress || t.progress === 0).length;

    stats.total.textContent = total;
    stats.completed.textContent = completed;
    stats.inProgress.textContent = inProgress;
    stats.pending.textContent = pending;

    // Distribución por estado
    const statusCounts = {};
    state.tasks.forEach(t => {
      const sid = t.status_id ?? 'sin-estado';
      statusCounts[sid] = (statusCounts[sid] || 0) + 1;
    });

    stats.distribution.innerHTML = '';
    Object.entries(statusCounts).forEach(([sid, count]) => {
      const status = state.taskStatuses.find(s => s.id === Number(sid));
      const statusName = status ? status.name : (sid === 'sin-estado' ? 'Sin estado' : `Estado #${sid}`);
      
      const card = document.createElement('div');
      card.className = 'rounded-xl ring-1 ring-[var(--c-border)] p-3';
      card.innerHTML = `
        <div class="text-sm font-medium mb-1">${esc(statusName)}</div>
        <div class="text-2xl font-bold text-[var(--c-primary)]">${count}</div>
        <div class="text-xs text-[var(--c-muted)] mt-1">${((count / total) * 100).toFixed(1)}% del total</div>
      `;
      stats.distribution.appendChild(card);
    });

    // Tareas recientes (últimas 5)
    const recent = state.tasks.slice(0, 5);
    stats.recentTasks.innerHTML = '';
    if (recent.length === 0) {
      stats.recentTasks.innerHTML = '<div class="text-sm text-[var(--c-muted)] text-center py-4">No hay tareas.</div>';
    } else {
      recent.forEach(t => {
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between gap-3 p-3 rounded-lg hover:bg-[var(--c-elev)] transition';
        const prog = Math.min(100, Math.max(0, Number(t.progress ?? 0)));
        div.innerHTML = `
          <div class="flex-1 min-w-0">
            <div class="text-sm font-medium">${esc(t.title ?? '(sin título)')}</div>
            <div class="text-xs text-[var(--c-muted)] mt-0.5">${t.code ? esc(t.code) + ' • ' : ''}#${t.id}</div>
          </div>
          <div class="flex items-center gap-3">
            <div class="w-24 h-2 bg-[var(--c-elev)] rounded-full overflow-hidden">
              <div class="h-full bg-[var(--c-primary)] rounded-full" style="width:${prog}%"></div>
            </div>
            <div class="text-xs text-[var(--c-muted)] w-12 text-right">${prog}%</div>
          </div>
        `;
        stats.recentTasks.appendChild(div);
      });
    }
  }

  async function loadAll(){
    await loadOverview();
    await loadTaskStatuses();
    await loadTasks();
    renderStats();
  }

  // Init
  Promise.resolve()
    .then(loadAll)
    .catch(showError);
})();
</script>
@endsection