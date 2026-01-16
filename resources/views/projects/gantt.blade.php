@extends('layouts.app')

@section('title', 'Proyecto • Gantt')

@section('content')
@php
  $pid = (int)($projectId ?? 0);
  $projectName = $project->name ?? 'Sin nombre';
@endphp

<div class="max-w-7xl mx-auto space-y-6">
  <div class="flex items-center justify-between gap-3">
    <h2 class="text-xl font-semibold">{{ $projectName }} • #{{ $pid }} • Gantt</h2>
    <div class="flex items-center gap-2">
      <a href="{{ route('projects.show', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">← Overview</a>
      <a href="{{ route('projects.backlog', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Backlog</a>
      <a href="{{ route('projects.files', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Files</a>
      <a href="{{ route('projects') }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Lista de proyectos</a>
    </div>
  </div>

  <section class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
    <div class="flex items-center justify-between gap-3 mb-3">
      <h3 class="text-lg font-semibold">Diagrama de Gantt</h3>
      <div class="flex items-center gap-2">
        <button id="gt-expand-all" class="text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Expandir todo</button>
        <button id="gt-collapse-all" class="text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Colapsar todo</button>
        <button id="gt-reload" class="text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Recargar</button>
      </div>
    </div>

    <!-- Timeline jerárquico -->
    <div id="gt-timeline" class="space-y-1"></div>

    <div class="mt-6">
      <h4 class="text-sm font-semibold mb-2">Dependencias</h4>
      <form id="dep-form" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
        <div>
          <label class="block text-xs text-[var(--c-muted)] mb-1">Predecesora (ID)</label>
          <input id="dep-pred" type="number" min="1" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
        </div>
        <div>
          <label class="block text-xs text-[var(--c-muted)] mb-1">Sucesora (ID)</label>
          <input id="dep-succ" type="number" min="1" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
        </div>
        <div>
          <label class="block text-xs text-[var(--c-muted)] mb-1">Tipo</label>
          <select id="dep-type" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none">
            <option>FS</option>
            <option>SS</option>
            <option>FF</option>
            <option>SF</option>
          </select>
        </div>
        <div>
          <label class="block text-xs text-[var(--c-muted)] mb-1">Lag (min)</label>
          <input id="dep-lag" type="number" value="0" class="w-full text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
        </div>
        <div>
          <button type="button" id="dep-add" class="w-full text-sm px-3 py-2 rounded-xl bg-[var(--c-primary)] text-[var(--c-primary-ink)] hover:opacity-95">Agregar</button>
        </div>
      </form>

      <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-[var(--c-elev)] text-left">
            <tr>
              <th class="px-4 py-3">ID</th>
              <th class="px-4 py-3">Predecesora</th>
              <th class="px-4 py-3">Sucesora</th>
              <th class="px-4 py-3">Tipo</th>
              <th class="px-4 py-3">Lag</th>
              <th class="px-4 py-3">Acciones</th>
            </tr>
          </thead>
          <tbody id="dep-body" class="divide-y divide-[var(--c-border)]"></tbody>
        </table>
      </div>
    </div>
  </section>
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

  // Refs
  const gt = {
    timeline: document.getElementById('gt-timeline'),
    reload: document.getElementById('gt-reload'),
    expandAll: document.getElementById('gt-expand-all'),
    collapseAll: document.getElementById('gt-collapse-all'),
    dep: {
      pred: document.getElementById('dep-pred'),
      succ: document.getElementById('dep-succ'),
      type: document.getElementById('dep-type'),
      lag: document.getElementById('dep-lag'),
      add: document.getElementById('dep-add'),
      body: document.getElementById('dep-body'),
    }
  };

  // State
  const state = {
    tasks: [],
    dependencies: [],
    expandedTasks: new Set(),
  };

  // Load
  async function loadGanttData(){
    // Tasks (todas del proyecto, sin filtrar por parent)
    const { data: tasksPayload } = await api(API_BASE + '/tasks?project_id=' + PROJECT_ID + '&per_page=1000', { method: 'GET' });
    state.tasks = tasksPayload?.data ?? tasksPayload ?? [];
    // Dependencies
    const { data: depsPayload } = await api(API_BASE + '/task-dependencies?project_id=' + PROJECT_ID, { method: 'GET' });
    state.dependencies = depsPayload?.data ?? depsPayload ?? [];
  }

  // Construir árbol jerárquico
  function buildTaskTree(tasks) {
    const taskMap = {};
    const roots = [];
    
    // Crear mapa de tareas
    tasks.forEach(t => {
      taskMap[t.id] = { ...t, children: [] };
    });
    
    // Construir árbol
    tasks.forEach(t => {
      if (t.parent_id && taskMap[t.parent_id]) {
        taskMap[t.parent_id].children.push(taskMap[t.id]);
      } else {
        roots.push(taskMap[t.id]);
      }
    });
    
    return roots;
  }

  function dateToMs(d){ return d ? new Date(d + 'T00:00:00Z').getTime() : NaN; }
  
  function renderGantt(){
    gt.timeline.innerHTML = '';
    
    const tasksWithDates = state.tasks.filter(t => t.start_planned && t.end_planned);
    if (tasksWithDates.length === 0) {
      gt.timeline.innerHTML = '<div class="text-sm text-[var(--c-muted)]">No hay tareas con fechas planificadas.</div>';
      return;
    }
    
    const minStart = Math.min(...tasksWithDates.map(t => dateToMs(t.start_planned)));
    const maxEnd = Math.max(...tasksWithDates.map(t => dateToMs(t.end_planned)));
    const range = Math.max(1, (maxEnd - minStart));
    
    const tree = buildTaskTree(state.tasks);
    
    function renderTaskNode(task, level = 0) {
      const hasChildren = task.children && task.children.length > 0;
      const isExpanded = state.expandedTasks.has(task.id);
      const hasDates = task.start_planned && task.end_planned;
      
      const row = document.createElement('div');
      row.className = 'task-row';
      row.dataset.taskId = task.id;
      
      // Calcular posición en el timeline
      let timelineBar = '';
      if (hasDates) {
        const s = dateToMs(task.start_planned);
        const e = dateToMs(task.end_planned);
        const left = ((s - minStart) / range) * 100;
        const width = Math.max(((e - s) / range) * 100, 1);
        const prog = Math.min(100, Math.max(0, Number(task.progress ?? 0)));
        
        timelineBar = `
          <div class="relative h-6 bg-[var(--c-elev)] rounded-lg overflow-hidden">
            <div class="absolute inset-0 flex items-center px-1 text-[10px] text-[var(--c-muted)]" style="left:${left}%;width:${width}%">
              <div class="absolute h-3 bg-[var(--c-primary)] rounded top-1/2 -translate-y-1/2" style="width:${width}%"></div>
              <div class="absolute h-2 bg-green-500/60 rounded top-1/2 -translate-y-1/2" style="width:${prog}%"></div>
            </div>
          </div>
        `;
      } else {
        timelineBar = '<div class="h-6 bg-[var(--c-elev)] rounded-lg flex items-center justify-center text-[10px] text-[var(--c-muted)]">Sin fechas</div>';
      }
      
      const indent = level * 24;
      const expandIcon = hasChildren 
        ? `<button class="expand-btn p-0.5 hover:bg-[var(--c-elev)] rounded transition" data-task-id="${task.id}" style="margin-left:${indent}px">
             <svg class="w-4 h-4 transition-transform ${isExpanded ? 'rotate-90' : ''}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
             </svg>
           </button>`
        : `<span class="inline-block w-4" style="margin-left:${indent}px"></span>`;
      
      row.innerHTML = `
        <div class="flex items-center gap-2 p-2 hover:bg-[var(--c-elev)] rounded-lg transition">
          <div class="w-72 text-sm flex items-center gap-1">
            ${expandIcon}
            <span class="font-medium">${esc(task.title ?? '(sin título)')}</span>
            <span class="text-[var(--c-muted)] text-xs">#${task.id}</span>
            ${hasChildren ? `<span class="text-[var(--c-muted)] text-xs">(${task.children.length})</span>` : ''}
          </div>
          <div class="flex-1 min-w-0">${timelineBar}</div>
          <div class="text-xs text-[var(--c-muted)] w-16 text-right">${Math.min(100, Math.max(0, Number(task.progress ?? 0)))}%</div>
        </div>
      `;
      
      gt.timeline.appendChild(row);
      
      // Renderizar hijos si está expandido
      if (hasChildren && isExpanded) {
        task.children.forEach(child => renderTaskNode(child, level + 1));
      }
    }
    
    tree.forEach(task => renderTaskNode(task, 0));
  }

  function renderDeps(){
    gt.dep.body.innerHTML = '';
    (state.dependencies || []).forEach(d => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="px-4 py-3">${d.id}</td>
        <td class="px-4 py-3">${d.predecessor_task_id}</td>
        <td class="px-4 py-3">${d.successor_task_id}</td>
        <td class="px-4 py-3">${d.type}</td>
        <td class="px-4 py-3">${d.lag_minutes}</td>
        <td class="px-4 py-3">
          <button data-act="del" data-id="${d.id}" class="text-xs px-2 py-1 rounded ring-1 ring-red-900/40 text-red-400 hover:bg-red-900/20">Eliminar</button>
        </td>
      `;
      gt.dep.body.appendChild(tr);
    });
  }

  // Manejadores de eventos
  gt.timeline.addEventListener('click', (e) => {
    const btn = e.target.closest('.expand-btn');
    if (!btn) return;
    
    const taskId = Number(btn.getAttribute('data-task-id'));
    if (state.expandedTasks.has(taskId)) {
      state.expandedTasks.delete(taskId);
    } else {
      state.expandedTasks.add(taskId);
    }
    renderGantt();
  });

  gt.expandAll.addEventListener('click', () => {
    state.tasks.forEach(t => {
      const children = state.tasks.filter(child => child.parent_id === t.id);
      if (children.length > 0) {
        state.expandedTasks.add(t.id);
      }
    });
    renderGantt();
  });

  gt.collapseAll.addEventListener('click', () => {
    state.expandedTasks.clear();
    renderGantt();
  });

  gt.reload.addEventListener('click', async ()=>{
    try{
      await loadGanttData();
      renderGantt();
      renderDeps();
    } catch(e){ showError(e); }
  });

  gt.dep.add.addEventListener('click', async ()=>{
    try{
      const body = {
        project_id: PROJECT_ID,
        predecessor_task_id: Number(gt.dep.pred.value),
        successor_task_id: Number(gt.dep.succ.value),
        type: gt.dep.type.value,
        lag_minutes: Number(gt.dep.lag.value || 0),
      };
      await api(API_BASE + '/task-dependencies', { method: 'POST', body: JSON.stringify(body) });
      gt.dep.pred.value=''; gt.dep.succ.value=''; gt.dep.type.value='FS'; gt.dep.lag.value='0';
      await loadGanttData();
      renderGantt();
      renderDeps();
    } catch(e){ showError(e); }
  });

  gt.dep.body.addEventListener('click', async (e)=>{
    const b = e.target.closest('button[data-act="del"]');
    if(!b) return;
    const id = b.getAttribute('data-id');
    if (!confirm('¿Eliminar dependencia?')) return;
    try{
      await api(API_BASE + '/task-dependencies/' + id, { method: 'DELETE' });
      await loadGanttData();
      renderGantt();
      renderDeps();
    }catch(e){ showError(e); }
  });

  // Init
  Promise.resolve()
    .then(loadGanttData)
    .then(()=>{ renderGantt(); renderDeps(); })
    .catch(showError);
})();
</script>
@endsection