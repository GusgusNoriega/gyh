@extends('layouts.app')

@section('title', 'Configuración de Cotizaciones')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-[var(--c-text)]">Configuración de Cotizaciones</h1>
      <p class="text-[var(--c-muted)] mt-1">Configura los valores y apariencia por defecto de las cotizaciones</p>
    </div>
    <a href="{{ route('quotes') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--c-elev)] text-[var(--c-text)] border border-[var(--c-border)] rounded-xl hover:bg-[var(--c-surface)] transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Volver a Cotizaciones
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Company Info -->
    <div class="lg:col-span-2">
      <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-6">
        <h2 class="text-lg font-semibold text-[var(--c-text)] mb-4">Información de la Empresa</h2>
        <form id="company-form" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="company-name" class="block text-sm font-medium text-[var(--c-text)] mb-1">Nombre de la Empresa</label>
              <input type="text" id="company-name" name="company_name" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
            </div>
            <div>
              <label for="company-ruc" class="block text-sm font-medium text-[var(--c-text)] mb-1">RUC</label>
              <input type="text" id="company-ruc" name="company_ruc" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
            </div>
            <div>
              <label for="company-email" class="block text-sm font-medium text-[var(--c-text)] mb-1">Email</label>
              <input type="email" id="company-email" name="company_email" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
            </div>
            <div>
              <label for="company-phone" class="block text-sm font-medium text-[var(--c-text)] mb-1">Teléfono</label>
              <input type="text" id="company-phone" name="company_phone" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
            </div>
            <div>
              <label for="default-tax-rate" class="block text-sm font-medium text-[var(--c-text)] mb-1">Tasa de Impuesto por Defecto (%)</label>
              <input type="number" id="default-tax-rate" name="default_tax_rate" step="0.01" min="0" max="100" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
            </div>
            <div>
              <label for="work-hours-per-day" class="block text-sm font-medium text-[var(--c-text)] mb-1">Horas de trabajo por día</label>
              <input type="number" id="work-hours-per-day" name="work_hours_per_day" step="0.25" min="0.25" max="24" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent">
              <p class="text-xs text-[var(--c-muted)] mt-1">Se usa para convertir horas ↔ días y calcular entrega estimada.</p>
            </div>
            <div class="md:col-span-2">
              <label for="company-address" class="block text-sm font-medium text-[var(--c-text)] mb-1">Dirección</label>
              <textarea id="company-address" name="company_address" rows="2" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent"></textarea>
            </div>
          </div>
          <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-lg hover:opacity-95 transition">
              Guardar Información
            </button>
          </div>
        </form>
      </div>

      <!-- Default Terms & Notes -->
      <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-6 mt-6">
        <h2 class="text-lg font-semibold text-[var(--c-text)] mb-4">Textos por Defecto</h2>
        <div class="space-y-4">
          <div>
            <label for="default-terms" class="block text-sm font-medium text-[var(--c-text)] mb-1">Términos y Condiciones por Defecto</label>
            <textarea id="default-terms" name="default_terms_conditions" rows="4" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent"></textarea>
            <button type="button" id="btn-save-terms" class="mt-2 px-4 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-lg hover:opacity-95 transition text-sm">
              Guardar Términos
            </button>
          </div>
          <div>
            <label for="default-notes" class="block text-sm font-medium text-[var(--c-text)] mb-1">Notas por Defecto</label>
            <textarea id="default-notes" name="default_notes" rows="3" class="w-full px-3 py-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent"></textarea>
            <button type="button" id="btn-save-notes" class="mt-2 px-4 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-lg hover:opacity-95 transition text-sm">
              Guardar Notas
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Images Section -->
    <div class="space-y-6">
      <!-- Company Logo -->
      <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-6">
        <h2 class="text-lg font-semibold text-[var(--c-text)] mb-4">Logo de la Empresa</h2>
        <div id="logo-preview" class="mb-4 flex items-center justify-center h-32 bg-[var(--c-elev)] rounded-xl border-2 border-dashed border-[var(--c-border)]">
          <span class="text-[var(--c-muted)] text-sm">Sin logo</span>
        </div>
        <div class="space-y-2">
          <x-media-input
            name="company_logo_id"
            mode="single"
            :max="1"
            placeholder="Seleccionar logo"
            button="Seleccionar Logo"
            preview="false"
          />
          <button type="button" id="btn-save-logo" class="w-full px-4 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-lg hover:opacity-95 transition">
            Guardar Logo
          </button>
          <button type="button" id="btn-remove-logo" class="w-full px-4 py-2 bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20 transition">
            Eliminar Logo
          </button>
        </div>
      </div>

      <!-- Background Image -->
      <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-6">
        <h2 class="text-lg font-semibold text-[var(--c-text)] mb-2">Imagen de Fondo del PDF</h2>
        <p class="text-xs text-[var(--c-muted)] mb-4">Esta imagen se usará como fondo en todas las páginas de las cotizaciones PDF.</p>
        <div id="background-preview" class="mb-4 flex items-center justify-center h-40 bg-[var(--c-elev)] rounded-xl border-2 border-dashed border-[var(--c-border)] overflow-hidden">
          <span class="text-[var(--c-muted)] text-sm">Sin imagen de fondo</span>
        </div>
        <div class="space-y-2">
          <x-media-input
            name="background_image_id"
            mode="single"
            :max="1"
            placeholder="Seleccionar imagen"
            button="Seleccionar Fondo"
            preview="false"
          />
          <button type="button" id="btn-save-background" class="w-full px-4 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-lg hover:opacity-95 transition">
            Guardar Fondo
          </button>
          <button type="button" id="btn-remove-background" class="w-full px-4 py-2 bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20 transition">
            Eliminar Fondo
          </button>
        </div>
      </div>

      <!-- Last Page Image -->
      <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-6">
        <h2 class="text-lg font-semibold text-[var(--c-text)] mb-2">Imagen de Última Página</h2>
        <p class="text-xs text-[var(--c-muted)] mb-4">Esta imagen se agregará como última página del PDF (ideal para firmas, sellos, etc.).</p>
        <div id="lastpage-preview" class="mb-4 flex items-center justify-center h-40 bg-[var(--c-elev)] rounded-xl border-2 border-dashed border-[var(--c-border)] overflow-hidden">
          <span class="text-[var(--c-muted)] text-sm">Sin imagen de última página</span>
        </div>
        <div class="space-y-2">
          <x-media-input
            name="last_page_image_id"
            mode="single"
            :max="1"
            placeholder="Seleccionar imagen"
            button="Seleccionar Imagen"
            preview="false"
          />
          <button type="button" id="btn-save-lastpage" class="w-full px-4 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-lg hover:opacity-95 transition">
            Guardar Imagen
          </button>
          <button type="button" id="btn-remove-lastpage" class="w-full px-4 py-2 bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20 transition">
            Eliminar Imagen
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const API_BASE = '/api';
  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const API_TOKEN = document.querySelector('meta[name="api-token"]')?.getAttribute('content') || null;

  if (!API_TOKEN) {
    showError('Autenticación requerida', 'No se encontró un token de acceso válido.');
    return;
  }

  // Load settings
  loadSettings();

  // Event listeners
  document.getElementById('company-form').addEventListener('submit', saveCompanyInfo);
  document.getElementById('btn-save-terms').addEventListener('click', saveTerms);
  document.getElementById('btn-save-notes').addEventListener('click', saveNotes);
  
  document.getElementById('btn-save-logo').addEventListener('click', () => saveImage('logo'));
  document.getElementById('btn-remove-logo').addEventListener('click', () => removeImage('logo'));
  document.getElementById('btn-save-background').addEventListener('click', () => saveImage('background-image'));
  document.getElementById('btn-remove-background').addEventListener('click', () => removeImage('background-image'));
  document.getElementById('btn-save-lastpage').addEventListener('click', () => saveImage('last-page-image'));
  document.getElementById('btn-remove-lastpage').addEventListener('click', () => removeImage('last-page-image'));

  async function loadSettings() {
    try {
      const response = await fetch(`${API_BASE}/quote-settings`, {
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (response.ok && data.success) {
        populateForm(data.data);
      } else {
        showApiError('Error al cargar configuración', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo cargar la configuración.');
    }
  }

  function populateForm(settings) {
    document.getElementById('company-name').value = settings.company_name || '';
    document.getElementById('company-ruc').value = settings.company_ruc || '';
    document.getElementById('company-email').value = settings.company_email || '';
    document.getElementById('company-phone').value = settings.company_phone || '';
    document.getElementById('company-address').value = settings.company_address || '';
    document.getElementById('default-tax-rate').value = settings.default_tax_rate || 0;
    document.getElementById('work-hours-per-day').value = settings.work_hours_per_day || 8;
    document.getElementById('default-terms').value = settings.default_terms_conditions || '';
    document.getElementById('default-notes').value = settings.default_notes || '';

    // Update media inputs
    if (settings.company_logo_id) {
      document.querySelector('input[name="company_logo_id"]').value = settings.company_logo_id;
    }
    if (settings.background_image_id) {
      document.querySelector('input[name="background_image_id"]').value = settings.background_image_id;
    }
    if (settings.last_page_image_id) {
      document.querySelector('input[name="last_page_image_id"]').value = settings.last_page_image_id;
    }

    // Update previews
    updatePreview('logo-preview', settings.company_logo);
    updatePreview('background-preview', settings.background_image);
    updatePreview('lastpage-preview', settings.last_page_image);
  }

  function updatePreview(containerId, image) {
    const container = document.getElementById(containerId);
    if (image && image.url) {
      container.innerHTML = `<img src="${image.url}" alt="${image.name || 'Preview'}" class="max-h-full max-w-full object-contain">`;
    } else {
      const defaultText = containerId === 'logo-preview' ? 'Sin logo' : 
                         containerId === 'background-preview' ? 'Sin imagen de fondo' : 
                         'Sin imagen de última página';
      container.innerHTML = `<span class="text-[var(--c-muted)] text-sm">${defaultText}</span>`;
    }
  }

  async function saveCompanyInfo(e) {
    e.preventDefault();

    const formData = {
      company_name: document.getElementById('company-name').value,
      company_ruc: document.getElementById('company-ruc').value,
      company_email: document.getElementById('company-email').value,
      company_phone: document.getElementById('company-phone').value,
      company_address: document.getElementById('company-address').value,
      default_tax_rate: parseFloat(document.getElementById('default-tax-rate').value) || 0,
      work_hours_per_day: parseFloat(document.getElementById('work-hours-per-day').value) || 8,
    };

    try {
      const response = await fetch(`${API_BASE}/quote-settings`, {
        method: 'PUT',
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
        window.dispatchEvent(new CustomEvent('api:response', { detail: data }));
      } else {
        showApiError('Error al guardar', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo guardar la información.');
    }
  }

  async function saveTerms() {
    try {
      const response = await fetch(`${API_BASE}/quote-settings/terms-conditions`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          default_terms_conditions: document.getElementById('default-terms').value
        })
      });

      const data = await response.json();

      if (response.ok && data.success) {
        window.dispatchEvent(new CustomEvent('api:response', { detail: data }));
      } else {
        showApiError('Error al guardar', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo guardar los términos.');
    }
  }

  async function saveNotes() {
    try {
      const response = await fetch(`${API_BASE}/quote-settings/notes`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          default_notes: document.getElementById('default-notes').value
        })
      });

      const data = await response.json();

      if (response.ok && data.success) {
        window.dispatchEvent(new CustomEvent('api:response', { detail: data }));
      } else {
        showApiError('Error al guardar', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo guardar las notas.');
    }
  }

  async function saveImage(type) {
    let inputName, endpoint, bodyKey;
    
    switch(type) {
      case 'logo':
        inputName = 'company_logo_id';
        endpoint = '/logo';
        bodyKey = 'company_logo_id';
        break;
      case 'background-image':
        inputName = 'background_image_id';
        endpoint = '/background-image';
        bodyKey = 'background_image_id';
        break;
      case 'last-page-image':
        inputName = 'last_page_image_id';
        endpoint = '/last-page-image';
        bodyKey = 'last_page_image_id';
        break;
    }

    const imageId = document.querySelector(`input[name="${inputName}"]`).value;
    
    if (!imageId) {
      showError('Error', 'Por favor selecciona una imagen primero.');
      return;
    }

    try {
      const response = await fetch(`${API_BASE}/quote-settings${endpoint}`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ [bodyKey]: parseInt(imageId) })
      });

      const data = await response.json();

      if (response.ok && data.success) {
        loadSettings(); // Reload to update previews
        window.dispatchEvent(new CustomEvent('api:response', { detail: data }));
      } else {
        showApiError('Error al guardar imagen', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo guardar la imagen.');
    }
  }

  async function removeImage(type) {
    if (!confirm('¿Estás seguro de eliminar esta imagen?')) return;

    let endpoint;
    
    switch(type) {
      case 'logo':
        endpoint = '/logo';
        break;
      case 'background-image':
        endpoint = '/background-image';
        break;
      case 'last-page-image':
        endpoint = '/last-page-image';
        break;
    }

    try {
      const response = await fetch(`${API_BASE}/quote-settings${endpoint}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (response.ok && data.success) {
        loadSettings(); // Reload to update previews
        window.dispatchEvent(new CustomEvent('api:response', { detail: data }));
      } else {
        showApiError('Error al eliminar imagen', data);
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo eliminar la imagen.');
    }
  }

  function showError(title, message) {
    window.dispatchEvent(new CustomEvent('api:response', {
      detail: { success: false, message: message }
    }));
  }

  function showApiError(title, apiResponse) {
    window.dispatchEvent(new CustomEvent('api:response', {
      detail: {
        success: false,
        message: apiResponse.message || 'Error desconocido',
        errors: apiResponse.errors || null
      }
    }));
  }
});
</script>
@endsection
