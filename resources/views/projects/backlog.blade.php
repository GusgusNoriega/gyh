@extends('layouts.app')

@section('title', 'Proyecto • Backlog')

@section('content')
@php
  $pid = (int)($projectId ?? 0);
  $projectName = $project->name ?? 'Sin nombre';
@endphp

<div class="max-w-7xl mx-auto space-y-6">
  <div class="flex items-center justify-between gap-3">
    <h2 class="text-xl font-semibold">{{ $projectName }} • #{{ $pid }} • Backlog</h2>
    <div class="flex items-center gap-2">
      <a href="{{ route('projects.show', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">← Overview</a>
      <a href="{{ route('projects.gantt', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Gantt</a>
      <a href="{{ route('projects.files', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Files</a>
      <a href="{{ route('projects') }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Lista de proyectos</a>
    </div>
  </div>

  <section class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
    <div class="flex items-center justify-between gap-3 mb-3">
      <h3 class="text-lg font-semibold">Backlog de Tareas</h3>
      <div class="flex items-center gap-2">
        <select id="bk-filter-status" class="text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-2 py-1 outline-none">
          <option value="">Estado: Todos</option>
        </select>
        <button id="bk-expand-all" class="text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Expandir todo</button>
        <button id="bk-collapse-all" class="text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Colapsar todo</button>
        <button id="bk-reload" class="text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Recargar</button>
        <button id="bk-new" class="text-sm px-3 py-2 rounded-xl bg-[var(--c-primary)] text-[var(--c-primary-ink)] hover:opacity-95">Nueva tarea</button>
      </div>
    </div>

    <div class="overflow-x-auto">
      <div id="bk-list" class="space-y-1"></div>
    </div>
  </section>
</div>

<!-- Modal Nueva/Editar Tarea -->
<div id="task-modal" class="fixed inset-0 z-[12000] hidden" aria-modal="true" role="dialog">
  <div data-js="overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
  <div class="relative mx-auto mt-10 w-[95%] max-w-2xl">
    <div class="rounded-2xl overflow-hidden border border-[var(--c-border)] bg-[var(--c-surface)] shadow-soft">
      <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-[var(--c-border)]">
        <h3 id="tm-title" class="text-lg font-semibold">Nueva tarea</h3>
        <button type="button" data-js="btn-close" class="p-2 rounded-xl hover:bg-[var(--c-elev)] transition" aria-label="Cerrar">
          <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 8.586l4.95-4.95a1 1 0 111.414 1.415L11.414 10l4.95 4.95a1 1 0 11-1.414 1.415L10 11.414l-4.95 4.95a1 1 0 11-1.415-1.415L8.586 10l-4.95-4.95A1 1 0 115.05 3.636L10 8.586z" clip-rule="evenodd"/></svg>
        </button>
      </div>
      <div class="px-5 py-4">
        <form id="task-form" class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <input type="hidden" id="tf-id" />
          <div class="md:col-span-2">
            <label class="block text-xs text-[var(--c-muted)] mb-1">Título</label>
            <input id="tf-title" type="text" maxlength="255" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none" required/>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Código</label>
            <input id="tf-code" type="text" maxlength="50" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Padre (ID opcional)</label>
            <input id="tf-parent" type="number" min="1" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Estado</label>
            <select id="tf-status" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"></select>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Assignee (ID opcional)</label>
            <input id="tf-assignee" type="number" min="1" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Progreso (%)</label>
            <input id="tf-progress" type="number" min="0" max="100" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          </div>
          <div>
            <label class="block text-xs text-[var(--c-muted)] mb-1">Prioridad</label>
            <input id="tf-priority" type="number" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          </div>
          <div class="md:col-span-2 grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs text-[var(--c-muted)] mb-1">Inicio plan.</label>
              <input id="tf-startp" type="date" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
            </div>
            <div>
              <label class="block text-xs text-[var(--c-muted)] mb-1">Fin plan.</label>
              <input id="tf-endp" type="date" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
            </div>
          </div>
          <div class="md:col-span-2">
            <label class="block text-xs text-[var(--c-muted)] mb-1">Descripción</label>
            <textarea id="tf-desc" rows="3" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"></textarea>
          </div>
        </form>
      </div>
      <div class="px-5 py-4 border-t border-[var(--c-border)] flex items-center justify-end gap-2">
        <button type="button" data-js="btn-cancel" class="rounded-lg border border-[var(--c-border)] px-4 py-2 hover:bg-[var(--c-elev)] transition text-sm">Cancelar</button>
        <button type="button" id="tf-save" class="rounded-lg bg-[var(--c-primary)] text-[var(--c-primary-ink)] px-4 py-2 hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[var(--c-primary)] text-sm">Guardar</button>
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

  const bk = {
    list: document.getElementById('bk-list'),
    status: document.getElementById('bk-filter-status'),
    reload: document.getElementById('bk-reload'),
    add: document.getElementById('bk-new'),
    expandAll: document.getElementById('bk-expand-all'),
    collapseAll: document.getElementById('bk-collapse-all'),
  };
  
  const tm = {
    root: document.getElementById('task-modal'),
    overlay: null, close: null, cancel: null, save: null,
    title: null, id: null, code: null, parent: null, status: null, assignee: null, 
    startp: null, endp: null, progress: null, priority: null, desc: null, modalTitle: null
  };
  tm.overlay = document.querySelector('#task-modal [data-js="overlay"]');
  tm.close = document.querySelector('#task-modal [data-js="btn-close"]');
  tm.cancel = document.querySelector('#task-modal [data-js="btn-cancel"]');
  tm.save = document.getElementById('tf-save');
  tm.modalTitle = document.getElementById('tm-title');
  tm.id = document.getElementById('tf-id');
  tm.title = document.getElementById('tf-title');
  tm.code = document.getElementById('tf-code');
  tm.parent = document.getElementById('tf-parent');
  tm.status = document.getElementById('tf-status');
  tm.assignee = document.getElementById('tf-assignee');
  tm.startp = document.getElementById('tf-startp');
  tm.endp = document.getElementById('tf-endp');
  tm.progress = document.getElementById('tf-progress');
  tm.priority = document.getElementById('tf-priority');
  tm.desc = document.getElementById('tf-desc');

  const state = {
    tasks: [],
    taskStatuses: [],
    expandedTasks: new Set(),
  };

  function openTaskModal(task = null){ 
    if (task) {
      tm.modalTitle.textContent = 'Editar tarea';
      tm.id.value = task.id;
      tm.title.value = task.title ?? '';
      tm.code.value = task.code ?? '';
      tm.parent.value = task.parent_id ?? '';
      tm.status.value = task.status_id ?? '';
      tm.assignee.value = task.assignee_id ?? '';
      tm.startp.value = task.start_planned ?? '';
      tm.endp.value = task.end_planned ?? '';
      tm.progress.value = task.progress ?? '';
      tm.priority.value = task.priority ?? '';
      tm.desc.value = task.description ?? '';
    } else {
      tm.modalTitle.textContent = 'Nueva tarea';
      tm.id.value = '';
      tm.title.value = '';
      tm.code.value = '';
      tm.parent.value = '';
      tm.status.value = '';
      tm.assignee.value = '';
      tm.startp.value = '';
      tm.endp.value = '';
      tm.progress.value = '';
      tm.priority.value = '';
      tm.desc.value = '';
    }
    tm.root.classList.remove('hidden'); 
  }
  function closeTaskModal(){ tm.root.classList.add('hidden'); }
  tm.overlay.addEventListener('click', closeTaskModal);
  tm.close.addEventListener('click', closeTaskModal);
  tm.cancel.addEventListener('click', closeTaskModal);
  
  tm.save.addEventListener('click', async ()=>{
    try {
      const body = {
        project_id: PROJECT_ID,
        title: tm.title.value,
        code: tm.code.value || null,
        parent_id: tm.parent.value ? Number(tm.parent.value) : null,
        status_id: tm.status.value ? Number(tm.status.value) : null,
        assignee_id: tm.assignee.value ? Number(tm.assignee.value) : null,
        start_planned: tm.startp.value || null,
        end_planned: tm.endp.value || null,
        progress: tm.progress.value ? Number(tm.progress.value) : null,
        priority: tm.priority.value ? Number(tm.priority.value) : null,
        description: tm.desc.value || null,
      };
      
      const editId = tm.id.value;
      if (editId) {
        await api(API_BASE + '/tasks/' + editId, { method: 'PATCH', body: JSON.stringify(body) });
      } else {
        await api(API_BASE + '/tasks', { method: 'POST', body: JSON.stringify(body) });
      }
      closeTaskModal();
      await loadBacklog();
    } catch (e){ showError(e); }
  });

  async function loadTaskStatus(){
    try{
      const { data } = await api(API_BASE + '/task-status?paginate=false', { method: 'GET' });
      state.taskStatuses = data || [];
      bk.status.innerHTML = '<option value="">Estado: Todos</option>' + (state.taskStatuses.map(s => `<option value="${s.id}">${esc(s.name)} (${esc(s.code)})</option>`).join(''));
      tm.status.innerHTML = '<option value="">—</option>' + (state.taskStatuses.map(s => `<option value="${s.id}">${esc(s.name)}</option>`).join(''));
    } catch(e){ showError(e); }
  }

  async function loadBacklog(){
    try{
      const params = new URLSearchParams();
      params.append('project_id', PROJECT_ID);
      params.append('per_page', '1000');
      const statusSel = bk.status.value;
      if (statusSel) params.append('status_id', statusSel);
      const { data } = await api(API_BASE + '/tasks?' + params.toString(), { method: 'GET' });
      state.tasks = data?.data ?? data ?? [];
      renderBacklog();
    } catch(e){ showError(e); }
  }

  function buildTaskTree(tasks) {
    const taskMap = {};
    const roots = [];
    
    tasks.forEach(t => {
      taskMap[t.id] = { ...t, children: [] };
    });
    
    tasks.forEach(t => {
      if (t.parent_id && taskMap[t.parent_id]) {
        taskMap[t.parent_id].children.push(taskMap[t.id]);
      } else {
        roots.push(taskMap[t.id]);
      }
    });
    
    return roots;
  }

  function getStatusName(statusId) {
    if (!statusId) return '—';
    const status = state.taskStatuses.find(s => s.id === statusId);
    return status ? status.name : statusId;
  }

  function renderBacklog(){
    bk.list.innerHTML = '';
    const tree = buildTaskTree(state.tasks);
    
    function renderTaskNode(task, level = 0) {
      const hasChildren = task.children && task.children.length > 0;
      const isExpanded = state.expandedTasks.has(task.id);
      const prog = Math.min(100, Math.max(0, Number(task.progress ?? 0)));
      const indent = level * 24;
      
      const card = document.createElement('div');
      card.className = 'task-card p-3 rounded-lg hover:bg-[var(--c-elev)] transition border border-transparent hover:border-[var(--c-border)]';
      card.dataset.taskId = task.id;
      
      const expandIcon = hasChildren 
        ? `<button class="expand-btn p-1 hover:bg-[var(--c-surface)] rounded transition" data-task-id="${task.id}" style="margin-left:${indent}px">
             <svg class="w-4 h-4 transition-transform ${isExpanded ? 'rotate-90' : ''}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
             </svg>
           </button>`
        : `<span class="inline-block w-6" style="margin-left:${indent}px"></span>`;
      
      card.innerHTML = `
        <div class="flex items-start gap-3">
          <div class="flex items-center gap-1 flex-shrink-0">
            ${expandIcon}
          </div>
          <div class="flex-1 min-w-0 grid grid-cols-12 gap-3 items-center">
            <div class="col-span-4">
              <div class="font-medium text-sm">${esc(task.title ?? '(sin título)')}</div>
              <div class="text-xs text-[var(--c-muted)] mt-0.5">
                <span>${task.code ? esc(task.code) : ''}</span>
                ${task.code ? ' • ' : ''}
                <span>#${task.id}</span>
                ${hasChildren ? ` • <span class="font-medium">${task.children.length} subtarea${task.children.length !== 1 ? 's' : ''}</span>` : ''}
              </div>
            </div>
            <div class="col-span-2 text-xs">
              <span class="inline-flex items-center px-2 py-1 rounded-lg bg-[var(--c-elev)] text-[var(--c-text)]">
                ${esc(getStatusName(task.status_id))}
              </span>
            </div>
            <div class="col-span-2 text-xs text-[var(--c-muted)]">
              ${task.start_planned ? task.start_planned : '—'} / ${task.end_planned ? task.end_planned : '—'}
            </div>
            <div class="col-span-2">
              <div class="w-full h-2 bg-[var(--c-elev)] rounded-full overflow-hidden">
                <div class="h-full bg-[var(--c-primary)] rounded-full transition-all" style="width:${prog}%"></div>
              </div>
              <div class="text-xs text-[var(--c-muted)] mt-0.5">${prog}%</div>
            </div>
            <div class="col-span-2 flex items-center gap-2 justify-end">
              <button data-act="edit" data-id="${task.id}" class="text-xs px-2 py-1 rounded ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)] transition">Editar</button>
              <button data-act="del" data-id="${task.id}" class="text-xs px-2 py-1 rounded ring-1 ring-red-900/40 text-red-400 hover:bg-red-900/20 transition">Eliminar</button>
            </div>
          </div>
        </div>
      `;
      
      bk.list.appendChild(card);
      
      if (hasChildren && isExpanded) {
        task.children.forEach(child => renderTaskNode(child, level + 1));
      }
    }
    
    if (tree.length === 0) {
      bk.list.innerHTML = '<div class="text-sm text-[var(--c-muted)] p-4 text-center">No hay tareas en el backlog.</div>';
    } else {
      tree.forEach(task => renderTaskNode(task, 0));
    }
  }

  bk.list.addEventListener('click', (e) => {
    const expandBtn = e.target.closest('.expand-btn');
    if (expandBtn) {
      const taskId = Number(expandBtn.getAttribute('data-task-id'));
      if (state.expandedTasks.has(taskId)) {
        state.expandedTasks.delete(taskId);
      } else {
        state.expandedTasks.add(taskId);
      }
      renderBacklog();
      return;
    }
    
    const editBtn = e.target.closest('button[data-act="edit"]');
    if (editBtn) {
      const id = Number(editBtn.getAttribute('data-id'));
      const task = state.tasks.find(t => t.id === id);
      if (task) openTaskModal(task);
      return;
    }
    
    const delBtn = e.target.closest('button[data-act="del"]');
    if (delBtn) {
      const id = editBtn.getAttribute('data-id');
      if (!confirm('¿Enviar tarea a la papelera?')) return;
      (async () => {
        try{
          await api(API_BASE + '/tasks/' + id, { method: 'DELETE' });
          await loadBacklog();
        }catch(err){ showError(err); }
      })();
      return;
    }
  });

  bk.expandAll.addEventListener('click', () => {
    state.tasks.forEach(t => {
      const children = state.tasks.filter(child => child.parent_id === t.id);
      if (children.length > 0) {
        state.expandedTasks.add(t.id);
      }
    });
    renderBacklog();
  });

  bk.collapseAll.addEventListener('click', () => {
    state.expandedTasks.clear();
    renderBacklog();
  });

  bk.reload.addEventListener('click', ()=> loadBacklog().catch(showError));
  bk.add.addEventListener('click', ()=> openTaskModal());
  bk.status.addEventListener('change', ()=> loadBacklog().catch(showError));

  // Init
  Promise.resolve()
    .then(loadTaskStatus)
    .then(loadBacklog)
    .catch(showError);
})();
</script>
@endsection