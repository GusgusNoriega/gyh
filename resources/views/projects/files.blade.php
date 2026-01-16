@extends('layouts.app')

@section('title', 'Proyecto • Files')

@section('content')
@php
  $pid = (int)($projectId ?? 0);
  $projectName = $project->name ?? 'Sin nombre';
@endphp

<div class="max-w-7xl mx-auto space-y-6">
  <div class="flex items-center justify-between gap-3">
    <h2 class="text-xl font-semibold">{{ $projectName }} • #{{ $pid }} • Files</h2>
    <div class="flex items-center gap-2">
      <a href="{{ route('projects.show', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">← Overview</a>
      <a href="{{ route('projects.backlog', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Backlog</a>
      <a href="{{ route('projects.gantt', ['id' => $pid]) }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Gantt</a>
      <a href="{{ route('projects') }}" class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Lista de proyectos</a>
    </div>
  </div>

  <section class="rounded-2xl border border-[var(--c-border)] bg-[var(--c-surface)] p-4">
    <div class="flex items-center justify-between gap-3 mb-3">
      <h3 class="text-lg font-semibold">Adjuntos • Proyecto</h3>
      <div class="flex items-center gap-2">
        <select id="fl-category" class="text-sm rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-2 py-1 outline-none">
          <option value="">Todas las categorías</option>
        </select>
        <button id="fl-reload" class="text-sm px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Recargar</button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
      <div class="md:col-span-1 rounded-xl ring-1 ring-[var(--c-border)] p-3">
        <h4 class="text-sm font-semibold mb-2">Agregar adjunto</h4>
        <div class="space-y-2 text-sm">
          <label class="block text-xs text-[var(--c-muted)]">Media Asset ID</label>
          <input id="fl-asset-id" type="number" min="1" class="w-full rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          <label class="block text-xs text-[var(--c-muted)]">Categoría</label>
          <select id="fl-asset-category" class="w-full rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none">
            <option value="">—</option>
          </select>
          <label class="inline-flex items-center gap-2 mt-1"><input id="fl-asset-primary" type="checkbox"/> <span>Principal</span></label>
          <label class="block text-xs text-[var(--c-muted)]">Orden</label>
          <input id="fl-asset-order" type="number" value="0" class="w-full rounded-xl bg-[var(--c-elev)] border border-[var(--c-border)] px-3 py-2 outline-none"/>
          <button id="fl-add" class="w-full text-sm px-3 py-2 rounded-xl bg-[var(--c-primary)] text-[var(--c-primary-ink)] hover:opacity-95">Adjuntar</button>
        </div>
      </div>

      <div class="md:col-span-3">
        <div id="fl-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3"></div>
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

  const fl = {
    list: document.getElementById('fl-list'),
    filter: document.getElementById('fl-category'),
    reload: document.getElementById('fl-reload'),
    addBtn: document.getElementById('fl-add'),
    assetId: document.getElementById('fl-asset-id'),
    catSel: document.getElementById('fl-asset-category'),
    primary: document.getElementById('fl-asset-primary'),
    order: document.getElementById('fl-asset-order'),
  };

  const state = {
    fileCategories: [],
    attachments: [],
  };

  async function loadFileCategories(){
    try{
      const { data } = await api(API_BASE + '/file-categories?paginate=false', { method: 'GET' });
      state.fileCategories = data || [];
      fl.filter.innerHTML = '<option value="">Todas las categorías</option>' + state.fileCategories.map(c => `<option value="${c.id}">${esc(c.name)}</option>`).join('');
      fl.catSel.innerHTML = '<option value="">—</option>' + state.fileCategories.map(c => `<option value="${c.id}">${esc(c.name)}</option>`).join('');
    }catch(e){ showError(e); }
  }

  async function loadAttachments(){
    try{
      const params = new URLSearchParams();
      const cat = fl.filter.value; if (cat) params.append('file_category_id', cat);
      const { data } = await api(API_BASE + '/projects/' + PROJECT_ID + '/attachments?' + params.toString(), { method:'GET' });
      const items = data?.data ?? data ?? [];
      state.attachments = items;
      renderAttachments();
    }catch(e){ showError(e); }
  }

  function renderAttachments(){
    fl.list.innerHTML = '';
   (state.attachments || []).forEach(a => {
      const card = document.createElement('div');
      card.className = 'rounded-xl ring-1 ring-[var(--c-border)] p-3 text-sm';
      card.innerHTML = `
        <div class="text-xs text-[var(--c-muted)] mb-1">Asset #${a.id}</div>
        <div class="font-medium mb-1">${esc(a.name ?? '(sin nombre)')}</div>
        <div class="text-xs mb-2">${esc(a.type ?? '')} • ${esc(a.mime_type ?? '')}</div>
        <div class="flex items-center gap-2">
          <a href="${a.url}" target="_blank" rel="noopener" class="text-xs px-2 py-1 rounded ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">Abrir</a>
          <button data-act="del-att" data-id="${a.pivot?.id ?? ''}" class="text-xs px-2 py-1 rounded ring-1 ring-red-900/40 text-red-400 hover:bg-red-900/20">Quitar</button>
        </div>
      `;
      fl.list.appendChild(card);
    });
  }

  fl.reload.addEventListener('click', ()=> loadAttachments().catch(showError));
  fl.addBtn.addEventListener('click', async ()=>{
    try{
      const body = {
        media_asset_id: Number(fl.assetId.value),
        file_category_id: fl.catSel.value ? Number(fl.catSel.value) : null,
        is_primary: !!fl.primary.checked,
        sort_order: Number(fl.order.value || 0),
      };
      await api(API_BASE + '/projects/' + PROJECT_ID + '/attachments', { method:'POST', body: JSON.stringify(body) });
      fl.assetId.value=''; fl.catSel.value=''; fl.primary.checked=false; fl.order.value='0';
      await loadAttachments();
    }catch(e){ showError(e); }
  });
  fl.list.addEventListener('click', async (e)=>{
    const b = e.target.closest('button[data-act="del-att"]');
    if(!b) return;
    const id = b.getAttribute('data-id');
    if(!id) return;
    if(!confirm('¿Quitar adjunto?')) return;
    try{
      await api(API_BASE + '/projects/' + PROJECT_ID + '/attachments/' + id, { method:'DELETE' });
      await loadAttachments();
    }catch(e){ showError(e); }
  });

  // Init
  Promise.resolve()
    .then(loadFileCategories)
    .then(loadAttachments)
    .catch(showError);
})();
</script>
@endsection