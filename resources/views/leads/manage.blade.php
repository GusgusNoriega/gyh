@extends('layouts.app')

@section('title', 'Administrar Leads')

@section('content')
<div class="">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-[var(--c-text)]">Administrar Leads</h1>
      <p class="text-[var(--c-muted)] mt-1">Gestiona los leads que llegan desde la página de inicio</p>
    </div>
    <div class="flex gap-3">
      <button id="btn-create-lead" class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-xl hover:opacity-95 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Nuevo Lead
      </button>
    </div>
  </div>

  <!-- Search and Filters -->
  <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-6 mt-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
      <h2 class="text-lg font-semibold text-[var(--c-text)]">Leads</h2>
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
        <input type="text" id="search-leads" placeholder="Buscar (nombre, email, teléfono, empresa, RUC)..." class="px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg text-sm w-full sm:w-80">
        <select id="filter-status" class="px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg text-sm">
          <option value="">Todos los estados</option>
          <option value="new">Nuevo</option>
          <option value="contacted">Contactado</option>
          <option value="qualified">Calificado</option>
          <option value="lost">Perdido</option>
          <option value="won">Ganado</option>
        </select>
        <button id="btn-refresh-leads" class="p-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg hover:bg-[var(--c-surface)] transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Leads List -->
    <div id="leads-list" class="space-y-3"></div>

    <!-- Pagination -->
    <div id="leads-pagination" class="flex justify-between items-center mt-6"></div>
  </div>
</div>

<!-- Create/Edit Lead Modal -->
<div id="lead-modal" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
  <div class="relative mx-auto mt-8 w-full max-w-3xl max-h-[90vh] overflow-y-auto">
    <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] overflow-hidden">
      <div class="px-6 py-4 border-b border-[var(--c-border)]">
        <h3 id="lead-modal-title" class="text-lg font-semibold text-[var(--c-text)]">Crear Lead</h3>
      </div>
      <form id="lead-form" class="p-6 space-y-4">
        <input type="hidden" id="lead-id" name="id">

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label for="lead-name" class="block text-sm font-medium text-[var(--c-text)] mb-1">Nombre</label>
            <input type="text" id="lead-name" name="name" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
          </div>
          <div>
            <label for="lead-email" class="block text-sm font-medium text-[var(--c-text)] mb-1">Email</label>
            <input type="email" id="lead-email" name="email" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
          </div>
          <div>
            <label for="lead-phone" class="block text-sm font-medium text-[var(--c-text)] mb-1">Teléfono</label>
            <input type="text" id="lead-phone" name="phone" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
          </div>
          <div>
            <label for="lead-status" class="block text-sm font-medium text-[var(--c-text)] mb-1">Estado</label>
            <select id="lead-status" name="status" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
              <option value="new">Nuevo</option>
              <option value="contacted">Contactado</option>
              <option value="qualified">Calificado</option>
              <option value="lost">Perdido</option>
              <option value="won">Ganado</option>
            </select>
          </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
          <div class="sm:col-span-1">
            <label class="inline-flex items-center gap-2 text-sm">
              <input id="lead-is-company" type="checkbox" name="is_company" class="size-4 rounded border-white/20 bg-transparent text-[var(--c-primary)] focus:ring-[var(--c-primary)]">
              Es empresa
            </label>
          </div>
          <div class="sm:col-span-1">
            <label for="lead-company-name" class="block text-sm font-medium text-[var(--c-text)] mb-1">Empresa</label>
            <input type="text" id="lead-company-name" name="company_name" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
          </div>
          <div class="sm:col-span-1">
            <label for="lead-company-ruc" class="block text-sm font-medium text-[var(--c-text)] mb-1">RUC</label>
            <input type="text" id="lead-company-ruc" name="company_ruc" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
          </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label for="lead-project-type" class="block text-sm font-medium text-[var(--c-text)] mb-1">Tipo de proyecto</label>
            <select id="lead-project-type" name="project_type" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
              <option value="">(Sin especificar)</option>
              <option value="pagina_web">Página web</option>
              <option value="pagina_web_corporativa">Página web corporativa</option>
              <option value="landing_page">Landing page</option>
              <option value="crm">CRM</option>
              <option value="erp">ERP</option>
              <option value="software_a_medida">Software a medida</option>
              <option value="otros">Otros</option>
            </select>
          </div>
          <div>
            <label for="lead-budget" class="block text-sm font-medium text-[var(--c-text)] mb-1">Presupuesto (hasta) — S/</label>
            <input type="number" min="0" step="1" id="lead-budget" name="budget_up_to" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent" placeholder="Ej: 5000">
          </div>
        </div>

        <div>
          <label for="lead-message" class="block text-sm font-medium text-[var(--c-text)] mb-1">Mensaje</label>
          <textarea id="lead-message" name="message" rows="4" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent"></textarea>
        </div>

        <div>
          <label for="lead-notes" class="block text-sm font-medium text-[var(--c-text)] mb-1">Notas internas</label>
          <textarea id="lead-notes" name="notes" rows="3" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent"></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-[var(--c-border)]">
          <button type="button" id="btn-cancel-lead" class="px-4 py-2 text-[var(--c-muted)] hover:text-[var(--c-text)] transition">Cancelar</button>
          <button type="submit" class="px-4 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-lg hover:opacity-95 transition">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const API_BASE = '/api';
  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const API_TOKEN = document.querySelector('meta[name="api-token"]')?.getAttribute('content') || null;

  if (!API_TOKEN) {
    showError('Autenticación requerida', 'No se encontró un token de acceso válido. Por favor, inicia sesión nuevamente.');
    return;
  }

  loadLeads();

  document.getElementById('btn-create-lead').addEventListener('click', () => openLeadModal());
  document.getElementById('btn-refresh-leads').addEventListener('click', loadLeads);
  document.getElementById('search-leads').addEventListener('input', debounce(loadLeads, 300));
  document.getElementById('filter-status').addEventListener('change', loadLeads);
  document.getElementById('lead-form').addEventListener('submit', saveLead);
  document.getElementById('btn-cancel-lead').addEventListener('click', () => closeLeadModal());

  async function loadLeads(page = 1) {
    const search = document.getElementById('search-leads').value;
    const status = document.getElementById('filter-status').value;
    const url = `${API_BASE}/leads?page=${page}&per_page=15&search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}`;

    try {
      const response = await fetch(url, {
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        }
      });
      const data = await response.json();

      if (response.ok && data.success) {
        renderLeads(data.data);
        renderPagination(data.data);
      } else {
        showApiError('Error al cargar leads', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudieron cargar los leads.');
    }
  }

  function statusBadge(status) {
    const map = {
      new: { label: 'Nuevo', cls: 'bg-blue-500/20 text-blue-200 ring-blue-400/30' },
      contacted: { label: 'Contactado', cls: 'bg-yellow-500/20 text-yellow-200 ring-yellow-400/30' },
      qualified: { label: 'Calificado', cls: 'bg-purple-500/20 text-purple-200 ring-purple-400/30' },
      lost: { label: 'Perdido', cls: 'bg-red-500/20 text-red-200 ring-red-400/30' },
      won: { label: 'Ganado', cls: 'bg-green-500/20 text-green-200 ring-green-400/30' },
    };
    const s = map[status] || { label: status || '—', cls: 'bg-white/5 text-[var(--c-muted)] ring-white/10' };
    return `<span class="inline-flex items-center px-2 py-1 text-[11px] rounded-full ring-1 ${s.cls}">${s.label}</span>`;
  }

  function renderLeads(leadsData) {
    const container = document.getElementById('leads-list');
    container.innerHTML = '';

    if (leadsData.data.length === 0) {
      container.innerHTML = '<p class="text-[var(--c-muted)] text-center py-8">No se encontraron leads</p>';
      return;
    }

    leadsData.data.forEach(lead => {
      const el = document.createElement('div');
      el.className = 'p-4 bg-[var(--c-elev)] rounded-xl border border-[var(--c-border)]';

      const title = lead.name || lead.email || `Lead #${lead.id}`;
      const subtitleParts = [];
      if (lead.email) subtitleParts.push(lead.email);
      if (lead.phone) subtitleParts.push(lead.phone);
      if (lead.company_name) subtitleParts.push(lead.company_name);
      const subtitle = subtitleParts.join(' • ');

      el.innerHTML = `
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <div class="min-w-0">
            <div class="flex items-center gap-2">
              <h3 class="font-medium text-[var(--c-text)] truncate">${escapeHtml(title)}</h3>
              ${statusBadge(lead.status)}
            </div>
            <p class="text-sm text-[var(--c-muted)] truncate">${escapeHtml(subtitle || '—')}</p>
            <p class="text-xs text-[var(--c-muted)] mt-1">${new Date(lead.created_at).toLocaleString()}</p>
          </div>
          <div class="flex gap-2">
            <button class="edit-lead-btn px-3 py-1 text-sm bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-lg hover:opacity-95 transition" data-id="${lead.id}">Editar</button>
            <button class="delete-lead-btn px-3 py-1 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition" data-id="${lead.id}">Eliminar</button>
          </div>
        </div>
      `;

      container.appendChild(el);
    });

    container.querySelectorAll('.edit-lead-btn').forEach(btn => {
      btn.addEventListener('click', async (e) => {
        const id = e.target.dataset.id;
        await openLeadModalFromApi(id);
      });
    });

    container.querySelectorAll('.delete-lead-btn').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const id = e.target.dataset.id;
        deleteLead(id);
      });
    });
  }

  function renderPagination(leadsData) {
    const container = document.getElementById('leads-pagination');
    container.innerHTML = '';
    if (leadsData.last_page <= 1) return;

    const prevBtn = document.createElement('button');
    prevBtn.textContent = 'Anterior';
    prevBtn.className = 'px-3 py-2 rounded-lg bg-[var(--c-elev)] text-[var(--c-text)] hover:bg-[var(--c-elev)]/80 disabled:opacity-50';
    prevBtn.disabled = !leadsData.prev_page_url;
    prevBtn.addEventListener('click', () => loadLeads(leadsData.current_page - 1));

    const nextBtn = document.createElement('button');
    nextBtn.textContent = 'Siguiente';
    nextBtn.className = 'px-3 py-2 rounded-lg bg-[var(--c-elev)] text-[var(--c-text)] hover:bg-[var(--c-elev)]/80 disabled:opacity-50';
    nextBtn.disabled = !leadsData.next_page_url;
    nextBtn.addEventListener('click', () => loadLeads(leadsData.current_page + 1));

    const pageInfo = document.createElement('div');
    pageInfo.textContent = `Página ${leadsData.current_page} de ${leadsData.last_page}`;
    pageInfo.className = 'text-sm text-[var(--c-muted)]';

    container.appendChild(prevBtn);
    container.appendChild(pageInfo);
    container.appendChild(nextBtn);
  }

  function openLeadModal() {
    document.getElementById('lead-modal-title').textContent = 'Crear Lead';
    document.getElementById('lead-id').value = '';
    document.getElementById('lead-name').value = '';
    document.getElementById('lead-email').value = '';
    document.getElementById('lead-phone').value = '';
    document.getElementById('lead-is-company').checked = false;
    document.getElementById('lead-company-name').value = '';
    document.getElementById('lead-company-ruc').value = '';
    document.getElementById('lead-project-type').value = '';
    document.getElementById('lead-budget').value = '';
    document.getElementById('lead-message').value = '';
    document.getElementById('lead-notes').value = '';
    document.getElementById('lead-status').value = 'new';

    document.getElementById('lead-modal').classList.remove('hidden');
  }

  async function openLeadModalFromApi(id) {
    try {
      const response = await fetch(`${API_BASE}/leads/${id}`, {
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        }
      });
      const data = await response.json();

      if (!(response.ok && data.success)) {
        showApiError('Error al cargar lead', data);
        return;
      }

      const lead = data.data;
      document.getElementById('lead-modal-title').textContent = 'Editar Lead';
      document.getElementById('lead-id').value = lead.id;
      document.getElementById('lead-name').value = lead.name || '';
      document.getElementById('lead-email').value = lead.email || '';
      document.getElementById('lead-phone').value = lead.phone || '';
      document.getElementById('lead-is-company').checked = !!lead.is_company;
      document.getElementById('lead-company-name').value = lead.company_name || '';
      document.getElementById('lead-company-ruc').value = lead.company_ruc || '';
      document.getElementById('lead-project-type').value = lead.project_type || '';
      document.getElementById('lead-budget').value = (lead.budget_up_to === null || lead.budget_up_to === undefined) ? '' : lead.budget_up_to;
      document.getElementById('lead-message').value = lead.message || '';
      document.getElementById('lead-notes').value = lead.notes || '';
      document.getElementById('lead-status').value = lead.status || 'new';

      document.getElementById('lead-modal').classList.remove('hidden');
    } catch (error) {
      showError('Error de conexión', 'No se pudo cargar el lead.');
    }
  }

  function closeLeadModal() {
    document.getElementById('lead-modal').classList.add('hidden');
  }

  async function saveLead(e) {
    e.preventDefault();

    const id = document.getElementById('lead-id').value;
    const payload = {
      name: document.getElementById('lead-name').value || null,
      email: document.getElementById('lead-email').value || null,
      phone: document.getElementById('lead-phone').value || null,
      is_company: document.getElementById('lead-is-company').checked,
      company_name: document.getElementById('lead-company-name').value || null,
      company_ruc: document.getElementById('lead-company-ruc').value || null,
      project_type: document.getElementById('lead-project-type').value || null,
      budget_up_to: document.getElementById('lead-budget').value ? parseInt(document.getElementById('lead-budget').value, 10) : null,
      message: document.getElementById('lead-message').value || null,
      notes: document.getElementById('lead-notes').value || null,
      status: document.getElementById('lead-status').value || 'new',
      source: 'admin_manual'
    };

    try {
      let response;
      if (!id) {
        // Crear (público)
        response = await fetch(`${API_BASE}/leads`, {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${API_TOKEN}`,
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });
      } else {
        response = await fetch(`${API_BASE}/leads/${id}`, {
          method: 'PATCH',
          headers: {
            'Authorization': `Bearer ${API_TOKEN}`,
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });
      }

      const data = await response.json();

      if (response.ok && data.success) {
        closeLeadModal();
        loadLeads();
        window.dispatchEvent(new CustomEvent('api:response', { detail: data }));
      } else {
        showApiError('Error al guardar lead', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo guardar el lead.');
    }
  }

  async function deleteLead(id) {
    if (!confirm('¿Estás seguro de que quieres eliminar este lead?')) return;

    try {
      const response = await fetch(`${API_BASE}/leads/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        }
      });
      const data = await response.json();

      if (response.ok && data.success) {
        loadLeads();
        window.dispatchEvent(new CustomEvent('api:response', { detail: data }));
      } else {
        showApiError('Error al eliminar lead', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo eliminar el lead.');
    }
  }

  function escapeHtml(str) {
    return (str || '').replace(/[&<>"']/g, function(m) {
      return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]);
    });
  }

  function showError(title, message) {
    window.dispatchEvent(new CustomEvent('api:response', {
      detail: {
        success: false,
        message: message,
        code: 'CLIENT_ERROR',
        errors: { general: [message] }
      }
    }));
  }

  function showApiError(title, apiResponse) {
    console.error('API Error:', apiResponse);
    window.dispatchEvent(new CustomEvent('api:response', {
      detail: {
        success: false,
        message: apiResponse.message || 'Error desconocido',
        code: apiResponse.code || 'UNKNOWN_ERROR',
        errors: apiResponse.errors || null,
        status: apiResponse.status || null,
        raw: apiResponse
      }
    }));
  }

  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
});
</script>
@endsection

