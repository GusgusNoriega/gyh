@extends('layouts.app')

@section('title', 'Plantillas de Email')

@section('content')
<div class="">
  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-[var(--c-text)]">Plantillas de Email</h1>
      <p class="text-[var(--c-muted)] mt-1">Gestiona las plantillas de correo electrónico del sistema</p>
    </div>
    <div class="flex gap-3">
      <button id="btn-create-template" class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-xl hover:opacity-95 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Nueva Plantilla
      </button>
    </div>
  </div>

  <!-- Search and Filters -->
  <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-6 mb-6">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-4">
      <h2 class="text-lg font-semibold text-[var(--c-text)]">Todas las Plantillas</h2>
      <div class="flex flex-wrap items-center gap-2">
        <input type="text" id="search-templates" placeholder="Buscar plantillas..." 
          class="px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg text-sm w-48">
        <select id="filter-status" class="px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg text-sm">
          <option value="">Todos los estados</option>
          <option value="active">Activas</option>
          <option value="inactive">Inactivas</option>
          <option value="trashed">Eliminadas</option>
        </select>
        <button id="btn-refresh-templates" class="p-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg hover:bg-[var(--c-surface)] transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Templates List -->
    <div id="templates-list" class="space-y-3">
      <!-- Templates will be loaded here -->
      <div class="flex items-center justify-center py-12 text-[var(--c-muted)]">
        <svg class="w-6 h-6 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        Cargando plantillas...
      </div>
    </div>

    <!-- Pagination -->
    <div id="templates-pagination" class="flex justify-between items-center mt-6">
      <!-- Pagination will be loaded here -->
    </div>
  </div>

  <!-- SMTP Test Card -->
  <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-6">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold text-[var(--c-text)]">Prueba de Envío SMTP</h3>
        <p class="text-sm text-[var(--c-muted)] mt-1">Envía un correo de prueba para verificar la configuración SMTP</p>
      </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-[var(--c-text)] mb-2">Plantilla</label>
        <select id="test-template-key" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg text-sm">
          <option value="">Seleccionar plantilla...</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-[var(--c-text)] mb-2">Email de destino</label>
        <input type="email" id="test-email-input" placeholder="test@ejemplo.com" 
          class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg text-sm">
      </div>
      <div class="flex items-end">
        <button id="btn-send-test-email" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-[var(--c-accent)] text-white rounded-xl hover:opacity-95 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
          </svg>
          Enviar Prueba
        </button>
      </div>
    </div>
    <div id="test-variables-container" class="mt-4 hidden">
      <label class="block text-sm font-medium text-[var(--c-text)] mb-2">Variables de prueba (JSON)</label>
      <textarea id="test-variables-json" rows="3" placeholder='{"nombre": "Usuario de Prueba", "app_name": "Mi App"}'
        class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg text-sm font-mono"></textarea>
      <p class="text-xs text-[var(--c-muted)] mt-1">Las variables requeridas se muestran al seleccionar una plantilla</p>
    </div>
  </div>
</div>

<!-- Create/Edit Template Modal -->
<div id="template-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
  <div class="relative mx-auto my-8 w-full max-w-4xl px-4">
    <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] overflow-hidden">
      <div class="px-6 py-4 border-b border-[var(--c-border)] flex items-center justify-between">
        <h3 id="template-modal-title" class="text-lg font-semibold text-[var(--c-text)]">Nueva Plantilla</h3>
        <button id="btn-close-modal" class="p-2 hover:bg-[var(--c-elev)] rounded-lg transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      
      <form id="template-form" class="p-6 max-h-[70vh] overflow-y-auto">
        <input type="hidden" id="template-id" name="id">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Left Column -->
          <div class="space-y-4">
            <!-- Name -->
            <div>
              <label for="template-name" class="block text-sm font-medium text-[var(--c-text)] mb-1">
                Nombre <span class="text-red-500">*</span>
              </label>
              <input type="text" id="template-name" name="name" placeholder="Ej: Bienvenida de Usuario"
                class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent" required>
            </div>

            <!-- Key -->
            <div>
              <label for="template-key" class="block text-sm font-medium text-[var(--c-text)] mb-1">
                Clave única <span class="text-red-500">*</span>
              </label>
              <input type="text" id="template-key" name="key" placeholder="welcome_email"
                class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent font-mono text-sm" required>
              <p class="text-xs text-[var(--c-muted)] mt-1">Solo letras minúsculas, números y guiones bajos</p>
            </div>

            <!-- Subject -->
            <div>
              <label for="template-subject" class="block text-sm font-medium text-[var(--c-text)] mb-1">
                Asunto <span class="text-red-500">*</span>
              </label>
              <input type="text" id="template-subject" name="subject" placeholder="¡Bienvenido [nombre]!"
                class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent" required>
              <p class="text-xs text-[var(--c-muted)] mt-1">Usa [variable] para incluir shortcodes</p>
            </div>

            <!-- Description -->
            <div>
              <label for="template-description" class="block text-sm font-medium text-[var(--c-text)] mb-1">
                Descripción
              </label>
              <input type="text" id="template-description" name="description" placeholder="Descripción de la plantilla"
                class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
            </div>

            <!-- Is Active -->
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="template-is-active" name="is_active" checked
                  class="w-4 h-4 rounded border-[var(--c-border)] text-[var(--c-primary)] focus:ring-[var(--c-primary)]">
                <span class="text-sm font-medium text-[var(--c-text)]">Plantilla activa</span>
              </label>
            </div>
          </div>

          <!-- Right Column - Variables Schema -->
          <div>
            <label class="block text-sm font-medium text-[var(--c-text)] mb-1">
              Variables (Shortcodes)
            </label>
            <div id="variables-list" class="space-y-2 mb-3 max-h-[200px] overflow-y-auto">
              <!-- Variables will be added here -->
            </div>
            <button type="button" id="btn-add-variable" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg hover:bg-[var(--c-surface)] transition">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
              </svg>
              Agregar Variable
            </button>
            <p class="text-xs text-[var(--c-muted)] mt-2">Las variables se usan como [nombre_variable] en el contenido</p>
          </div>
        </div>

        <!-- Content HTML -->
        <div class="mt-6">
          <div class="flex items-center justify-between mb-1">
            <label for="template-content" class="block text-sm font-medium text-[var(--c-text)]">
              Contenido HTML <span class="text-red-500">*</span>
            </label>
            <button type="button" id="btn-preview-template" class="inline-flex items-center gap-1 text-xs text-[var(--c-primary)] hover:underline">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              Vista previa
            </button>
          </div>
          <textarea id="template-content" name="content_html" rows="12" placeholder="<!DOCTYPE html><html>..."
            class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent font-mono text-sm" required></textarea>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3 pt-4 border-t border-[var(--c-border)] mt-6">
          <button type="button" id="btn-cancel-template" class="px-4 py-2 text-[var(--c-muted)] hover:text-[var(--c-text)] transition">
            Cancelar
          </button>
          <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-xl hover:opacity-95 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Preview Modal -->
<div id="preview-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
  <div class="relative mx-auto my-8 w-full max-w-3xl px-4">
    <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] overflow-hidden">
      <div class="px-6 py-4 border-b border-[var(--c-border)] flex items-center justify-between">
        <h3 class="text-lg font-semibold text-[var(--c-text)]">Vista Previa del Email</h3>
        <button id="btn-close-preview" class="p-2 hover:bg-[var(--c-elev)] rounded-lg transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="p-4">
        <div class="bg-gray-100 rounded-lg p-2 mb-3">
          <p class="text-sm text-gray-600"><strong>Asunto:</strong> <span id="preview-subject"></span></p>
        </div>
        <div id="preview-content" class="bg-white rounded-lg border min-h-[400px] max-h-[60vh] overflow-auto">
          <!-- Preview iframe -->
          <iframe id="preview-iframe" class="w-full h-[500px] border-0"></iframe>
        </div>
      </div>
      <div class="px-6 py-4 border-t border-[var(--c-border)] flex justify-end">
        <button id="btn-close-preview-bottom" class="px-4 py-2 bg-[var(--c-elev)] text-[var(--c-text)] rounded-lg hover:bg-[var(--c-surface)] transition">
          Cerrar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- View Template Modal -->
<div id="view-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
  <div class="relative mx-auto my-8 w-full max-w-3xl px-4">
    <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] overflow-hidden">
      <div class="px-6 py-4 border-b border-[var(--c-border)] flex items-center justify-between">
        <h3 id="view-modal-title" class="text-lg font-semibold text-[var(--c-text)]">Detalles de Plantilla</h3>
        <button id="btn-close-view" class="p-2 hover:bg-[var(--c-elev)] rounded-lg transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="p-6" id="view-modal-content">
        <!-- Content will be loaded here -->
      </div>
      <div class="px-6 py-4 border-t border-[var(--c-border)] flex justify-end gap-3">
        <button id="btn-edit-from-view" class="px-4 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-lg hover:opacity-95 transition">
          Editar
        </button>
        <button id="btn-close-view-bottom" class="px-4 py-2 bg-[var(--c-elev)] text-[var(--c-text)] rounded-lg hover:bg-[var(--c-surface)] transition">
          Cerrar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const API_BASE = '/api';
  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const API_TOKEN = document.querySelector('meta[name="api-token"]')?.getAttribute('content') || null;

  // State
  let currentTemplateId = null;
  let variablesCount = 0;
  let allTemplates = [];

  // Verify token
  if (!API_TOKEN) {
    showError('Autenticación requerida', 'No se encontró un token de acceso válido. Por favor, inicia sesión nuevamente.');
    return;
  }

  // Load initial data
  loadTemplates();

  // Event listeners
  document.getElementById('btn-create-template').addEventListener('click', () => openTemplateModal());
  document.getElementById('btn-refresh-templates').addEventListener('click', () => loadTemplates());
  document.getElementById('search-templates').addEventListener('input', debounce(loadTemplates, 300));
  document.getElementById('filter-status').addEventListener('change', loadTemplates);
  document.getElementById('template-form').addEventListener('submit', saveTemplate);
  document.getElementById('btn-cancel-template').addEventListener('click', closeTemplateModal);
  document.getElementById('btn-close-modal').addEventListener('click', closeTemplateModal);
  document.getElementById('btn-add-variable').addEventListener('click', addVariableRow);
  document.getElementById('btn-preview-template').addEventListener('click', showPreview);
  document.getElementById('btn-close-preview').addEventListener('click', closePreview);
  document.getElementById('btn-close-preview-bottom').addEventListener('click', closePreview);
  document.getElementById('btn-close-view').addEventListener('click', closeViewModal);
  document.getElementById('btn-close-view-bottom').addEventListener('click', closeViewModal);
  document.getElementById('btn-edit-from-view').addEventListener('click', editFromView);
  document.getElementById('btn-send-test-email').addEventListener('click', sendTestEmail);
  document.getElementById('test-template-key').addEventListener('change', updateTestVariables);

  // Auto-generate key from name
  document.getElementById('template-name').addEventListener('input', function(e) {
    const keyField = document.getElementById('template-key');
    if (!currentTemplateId && keyField.value === '') {
      keyField.value = e.target.value
        .toLowerCase()
        .replace(/[^a-z0-9\s]/g, '')
        .replace(/\s+/g, '_')
        .substring(0, 50);
    }
  });

  // Functions
  async function loadTemplates(page = 1) {
    const search = document.getElementById('search-templates').value;
    const status = document.getElementById('filter-status').value;
    
    let url = `${API_BASE}/email-templates?page=${page}&per_page=10`;
    
    if (search) {
      url += `&search=${encodeURIComponent(search)}`;
    }
    
    if (status === 'active') {
      url += '&is_active=true';
    } else if (status === 'inactive') {
      url += '&is_active=false';
    } else if (status === 'trashed') {
      url += '&only_trashed=true';
    }

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
        allTemplates = data.data.data || [];
        renderTemplates(data.data);
        renderPagination(data.data);
        updateTestTemplateSelect(allTemplates);
      } else {
        showApiError('Error al cargar plantillas', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudieron cargar las plantillas.');
    }
  }

  function renderTemplates(templatesData) {
    const container = document.getElementById('templates-list');
    const templates = templatesData.data || [];

    if (templates.length === 0) {
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12 text-center">
          <svg class="w-16 h-16 text-[var(--c-muted)] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
          <p class="text-[var(--c-muted)] mb-2">No se encontraron plantillas</p>
          <button onclick="document.getElementById('btn-create-template').click()" class="text-[var(--c-primary)] hover:underline text-sm">
            Crear primera plantilla
          </button>
        </div>
      `;
      return;
    }

    container.innerHTML = templates.map(template => `
      <div class="flex items-center justify-between p-4 bg-[var(--c-elev)] rounded-xl border border-[var(--c-border)] hover:border-[var(--c-primary)]/50 transition group">
        <div class="flex items-center gap-4 flex-1 min-w-0">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center ${template.is_active ? 'bg-green-500/20' : 'bg-gray-500/20'} flex-shrink-0">
            <svg class="w-6 h-6 ${template.is_active ? 'text-green-400' : 'text-gray-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2 mb-1">
              <h3 class="font-medium text-[var(--c-text)] truncate">${escapeHtml(template.name)}</h3>
              ${template.deleted_at ? '<span class="px-2 py-0.5 text-xs rounded-full bg-red-500/20 text-red-400">Eliminada</span>' : ''}
              ${!template.is_active && !template.deleted_at ? '<span class="px-2 py-0.5 text-xs rounded-full bg-yellow-500/20 text-yellow-400">Inactiva</span>' : ''}
            </div>
            <p class="text-sm text-[var(--c-muted)] truncate">${escapeHtml(template.subject)}</p>
            <p class="text-xs text-[var(--c-muted)] mt-1">
              <code class="bg-[var(--c-surface)] px-1 rounded">${escapeHtml(template.key)}</code>
              ${template.description ? ' • ' + escapeHtml(template.description) : ''}
            </p>
          </div>
        </div>
        <div class="flex items-center gap-2 ml-4">
          <button onclick="viewTemplate(${template.id})" class="p-2 text-[var(--c-muted)] hover:text-[var(--c-text)] hover:bg-[var(--c-surface)] rounded-lg transition" title="Ver detalles">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </button>
          ${template.deleted_at ? `
            <button onclick="restoreTemplate(${template.id})" class="p-2 text-green-400 hover:bg-green-500/20 rounded-lg transition" title="Restaurar">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
            </button>
          ` : `
            <button onclick="editTemplate(${template.id})" class="p-2 text-[var(--c-muted)] hover:text-[var(--c-primary)] hover:bg-[var(--c-surface)] rounded-lg transition" title="Editar">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </button>
            <button onclick="deleteTemplate(${template.id})" class="p-2 text-[var(--c-muted)] hover:text-red-400 hover:bg-red-500/20 rounded-lg transition" title="Eliminar">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
            </button>
          `}
        </div>
      </div>
    `).join('');
  }

  function renderPagination(templatesData) {
    const container = document.getElementById('templates-pagination');
    
    if (templatesData.last_page <= 1) {
      container.innerHTML = '';
      return;
    }

    container.innerHTML = `
      <button onclick="loadTemplates(${templatesData.current_page - 1})" 
        class="px-3 py-2 rounded-lg bg-[var(--c-elev)] text-[var(--c-text)] hover:bg-[var(--c-surface)] disabled:opacity-50 transition"
        ${!templatesData.prev_page_url ? 'disabled' : ''}>
        Anterior
      </button>
      <span class="text-sm text-[var(--c-muted)]">
        Página ${templatesData.current_page} de ${templatesData.last_page}
      </span>
      <button onclick="loadTemplates(${templatesData.current_page + 1})" 
        class="px-3 py-2 rounded-lg bg-[var(--c-elev)] text-[var(--c-text)] hover:bg-[var(--c-surface)] disabled:opacity-50 transition"
        ${!templatesData.next_page_url ? 'disabled' : ''}>
        Siguiente
      </button>
    `;
  }

  function updateTestTemplateSelect(templates) {
    const select = document.getElementById('test-template-key');
    const activeTemplates = templates.filter(t => t.is_active && !t.deleted_at);
    
    select.innerHTML = '<option value="">Seleccionar plantilla...</option>' + 
      activeTemplates.map(t => `<option value="${t.key}" data-schema='${JSON.stringify(t.variables_schema || {})}'>${escapeHtml(t.name)} (${t.key})</option>`).join('');
  }

  function updateTestVariables() {
    const select = document.getElementById('test-template-key');
    const container = document.getElementById('test-variables-container');
    const textarea = document.getElementById('test-variables-json');
    const selectedOption = select.options[select.selectedIndex];
    
    if (!select.value) {
      container.classList.add('hidden');
      return;
    }

    container.classList.remove('hidden');
    
    try {
      const schema = JSON.parse(selectedOption.dataset.schema || '{}');
      const testData = {};
      
      for (const [key, meta] of Object.entries(schema)) {
        testData[key] = meta.description || `Valor de ${key}`;
      }
      
      textarea.value = JSON.stringify(testData, null, 2);
    } catch (e) {
      textarea.value = '{}';
    }
  }

  async function sendTestEmail() {
    const templateKey = document.getElementById('test-template-key').value;
    const email = document.getElementById('test-email-input').value;
    const variablesJson = document.getElementById('test-variables-json').value;

    if (!templateKey) {
      showError('Campo requerido', 'Selecciona una plantilla para la prueba.');
      return;
    }

    if (!email) {
      showError('Campo requerido', 'Ingresa un email de destino.');
      return;
    }

    let variables = {};
    try {
      variables = JSON.parse(variablesJson || '{}');
    } catch (e) {
      showError('JSON inválido', 'El formato de las variables no es válido.');
      return;
    }

    const btn = document.getElementById('btn-send-test-email');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Enviando...';

    try {
      const response = await fetch(`${API_BASE}/email-templates/test/send`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          template_key: templateKey,
          to: email,
          data: variables
        })
      });

      const data = await response.json();

      if (response.ok && data.success) {
        window.dispatchEvent(new CustomEvent('api:response', { detail: data }));
      } else {
        showApiError('Error al enviar prueba', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo enviar el correo de prueba.');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg> Enviar Prueba';
    }
  }

  function openTemplateModal(template = null) {
    const modal = document.getElementById('template-modal');
    const title = document.getElementById('template-modal-title');
    const form = document.getElementById('template-form');
    
    form.reset();
    document.getElementById('variables-list').innerHTML = '';
    variablesCount = 0;

    if (template) {
      currentTemplateId = template.id;
      title.textContent = 'Editar Plantilla';
      document.getElementById('template-id').value = template.id;
      document.getElementById('template-name').value = template.name;
      document.getElementById('template-key').value = template.key;
      document.getElementById('template-subject').value = template.subject;
      document.getElementById('template-description').value = template.description || '';
      document.getElementById('template-content').value = template.content_html;
      document.getElementById('template-is-active').checked = template.is_active;

      // Load variables
      if (template.variables_schema) {
        for (const [key, meta] of Object.entries(template.variables_schema)) {
          addVariableRow(key, meta.required || false, meta.description || '');
        }
      }
    } else {
      currentTemplateId = null;
      title.textContent = 'Nueva Plantilla';
      document.getElementById('template-is-active').checked = true;
      // Add one empty variable row
      addVariableRow();
    }

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeTemplateModal() {
    document.getElementById('template-modal').classList.add('hidden');
    document.body.style.overflow = '';
    currentTemplateId = null;
  }

  function addVariableRow(name = '', required = false, description = '') {
    const container = document.getElementById('variables-list');
    const index = variablesCount++;
    
    const row = document.createElement('div');
    row.className = 'flex items-center gap-2 p-2 bg-[var(--c-surface)] rounded-lg variable-row';
    row.innerHTML = `
      <input type="text" name="var_name_${index}" value="${escapeHtml(name)}" placeholder="nombre_variable"
        class="flex-1 px-2 py-1 bg-[var(--c-elev)] border border-[var(--c-border)] rounded text-sm font-mono">
      <input type="text" name="var_desc_${index}" value="${escapeHtml(description)}" placeholder="Descripción"
        class="flex-1 px-2 py-1 bg-[var(--c-elev)] border border-[var(--c-border)] rounded text-sm">
      <label class="flex items-center gap-1 text-xs whitespace-nowrap">
        <input type="checkbox" name="var_req_${index}" ${required ? 'checked' : ''} class="rounded">
        Requerida
      </label>
      <button type="button" onclick="this.closest('.variable-row').remove()" class="p-1 text-red-400 hover:bg-red-500/20 rounded">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    `;
    container.appendChild(row);
  }

  function collectVariablesSchema() {
    const schema = {};
    const rows = document.querySelectorAll('.variable-row');
    
    rows.forEach((row, index) => {
      const nameInput = row.querySelector(`input[name^="var_name_"]`);
      const descInput = row.querySelector(`input[name^="var_desc_"]`);
      const reqInput = row.querySelector(`input[name^="var_req_"]`);
      
      if (nameInput && nameInput.value.trim()) {
        const key = nameInput.value.trim().toLowerCase().replace(/[^a-z0-9_]/g, '_');
        schema[key] = {
          required: reqInput?.checked || false,
          description: descInput?.value.trim() || ''
        };
      }
    });
    
    return schema;
  }

  async function saveTemplate(e) {
    e.preventDefault();

    const id = document.getElementById('template-id').value;
    const formData = {
      name: document.getElementById('template-name').value,
      key: document.getElementById('template-key').value.toLowerCase().replace(/[^a-z0-9_]/g, '_'),
      subject: document.getElementById('template-subject').value,
      content_html: document.getElementById('template-content').value,
      description: document.getElementById('template-description').value || null,
      is_active: document.getElementById('template-is-active').checked,
      variables_schema: collectVariablesSchema()
    };

    const method = id ? 'PUT' : 'POST';
    const url = id ? `${API_BASE}/email-templates/${id}` : `${API_BASE}/email-templates`;

    try {
      const response = await fetch(url, {
        method: method,
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
      });

      const data = await response.json();

      if (response.ok && data.success) {
        closeTemplateModal();
        loadTemplates();
        window.dispatchEvent(new CustomEvent('api:response', { detail: data }));
      } else {
        showApiError('Error al guardar plantilla', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo guardar la plantilla.');
    }
  }

  window.editTemplate = async function(id) {
    try {
      const response = await fetch(`${API_BASE}/email-templates/${id}`, {
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (response.ok && data.success) {
        openTemplateModal(data.data);
      } else {
        showApiError('Error al cargar plantilla', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo cargar la plantilla.');
    }
  };

  window.viewTemplate = async function(id) {
    try {
      const response = await fetch(`${API_BASE}/email-templates/${id}?with_trashed=true`, {
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (response.ok && data.success) {
        showViewModal(data.data);
      } else {
        showApiError('Error al cargar plantilla', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo cargar la plantilla.');
    }
  };

  function showViewModal(template) {
    document.getElementById('view-modal-title').textContent = template.name;
    document.getElementById('btn-edit-from-view').dataset.id = template.id;
    
    const variablesHtml = template.variables_schema ? 
      Object.entries(template.variables_schema).map(([key, meta]) => `
        <span class="inline-flex items-center gap-1 px-2 py-1 bg-[var(--c-surface)] rounded text-xs">
          <code>[${key}]</code>
          ${meta.required ? '<span class="text-red-400">*</span>' : ''}
        </span>
      `).join('') : '<span class="text-[var(--c-muted)]">Sin variables</span>';

    document.getElementById('view-modal-content').innerHTML = `
      <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-xs text-[var(--c-muted)]">Clave</label>
            <p class="font-mono text-sm">${escapeHtml(template.key)}</p>
          </div>
          <div>
            <label class="text-xs text-[var(--c-muted)]">Estado</label>
            <p class="text-sm">
              ${template.is_active ? '<span class="text-green-400">Activa</span>' : '<span class="text-yellow-400">Inactiva</span>'}
              ${template.deleted_at ? '<span class="text-red-400 ml-2">(Eliminada)</span>' : ''}
            </p>
          </div>
        </div>
        <div>
          <label class="text-xs text-[var(--c-muted)]">Asunto</label>
          <p class="text-sm">${escapeHtml(template.subject)}</p>
        </div>
        <div>
          <label class="text-xs text-[var(--c-muted)]">Descripción</label>
          <p class="text-sm">${template.description ? escapeHtml(template.description) : '<span class="text-[var(--c-muted)]">Sin descripción</span>'}</p>
        </div>
        <div>
          <label class="text-xs text-[var(--c-muted)]">Variables</label>
          <div class="flex flex-wrap gap-2 mt-1">${variablesHtml}</div>
        </div>
        <div>
          <label class="text-xs text-[var(--c-muted)]">Vista previa del contenido</label>
          <div class="mt-2 bg-white rounded-lg border overflow-hidden">
            <iframe id="view-iframe" class="w-full h-[300px] border-0"></iframe>
          </div>
        </div>
      </div>
    `;

    // Load content into iframe
    const iframe = document.getElementById('view-iframe');
    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
    iframeDoc.open();
    iframeDoc.write(template.content_html);
    iframeDoc.close();

    document.getElementById('view-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeViewModal() {
    document.getElementById('view-modal').classList.add('hidden');
    document.body.style.overflow = '';
  }

  function editFromView() {
    const id = document.getElementById('btn-edit-from-view').dataset.id;
    closeViewModal();
    editTemplate(id);
  }

  window.deleteTemplate = async function(id) {
    if (!confirm('¿Estás seguro de que quieres eliminar esta plantilla? Podrás restaurarla después.')) return;

    try {
      const response = await fetch(`${API_BASE}/email-templates/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (response.ok && data.success) {
        loadTemplates();
        window.dispatchEvent(new CustomEvent('api:response', { detail: data }));
      } else {
        showApiError('Error al eliminar plantilla', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo eliminar la plantilla.');
    }
  };

  window.restoreTemplate = async function(id) {
    try {
      const response = await fetch(`${API_BASE}/email-templates/${id}/restore`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (response.ok && data.success) {
        loadTemplates();
        window.dispatchEvent(new CustomEvent('api:response', { detail: data }));
      } else {
        showApiError('Error al restaurar plantilla', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo restaurar la plantilla.');
    }
  };

  function showPreview() {
    const subject = document.getElementById('template-subject').value;
    const content = document.getElementById('template-content').value;

    if (!content) {
      showError('Contenido vacío', 'Ingresa el contenido HTML para ver la vista previa.');
      return;
    }

    document.getElementById('preview-subject').textContent = subject || '(Sin asunto)';
    
    const iframe = document.getElementById('preview-iframe');
    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
    iframeDoc.open();
    iframeDoc.write(content);
    iframeDoc.close();

    document.getElementById('preview-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closePreview() {
    document.getElementById('preview-modal').classList.add('hidden');
    document.body.style.overflow = '';
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
    window.dispatchEvent(new CustomEvent('api:response', {
      detail: {
        success: false,
        message: apiResponse.message || 'Error desconocido',
        code: apiResponse.code || 'UNKNOWN_ERROR',
        errors: apiResponse.errors || null
      }
    }));
  }

  function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function debounce(func, wait) {
    let timeout;
    return function(...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }

  // Make loadTemplates global for pagination
  window.loadTemplates = loadTemplates;
});
</script>
@endsection