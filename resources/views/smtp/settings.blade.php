@extends('layouts.app')

@section('title', 'Configuración SMTP')

@section('content')
<div class="">
  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-[var(--c-text)]">Configuración SMTP</h1>
      <p class="text-[var(--c-muted)] mt-1">Configura el servidor de correo para el envío de emails</p>
    </div>
    <div class="flex gap-3">
      <button id="btn-test-smtp" class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--c-accent)] text-white rounded-xl hover:opacity-95 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Probar Conexión
      </button>
      <button id="btn-refresh-settings" class="p-2 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-lg hover:bg-[var(--c-surface)] transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Info Card -->
  <div class="bg-blue-500/10 border border-blue-500/30 rounded-2xl p-4 mb-6">
    <div class="flex items-start gap-3">
      <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <div>
        <h3 class="text-sm font-medium text-blue-400">Información</h3>
        <p class="text-sm text-[var(--c-muted)] mt-1">
          La configuración SMTP almacenada aquí tiene prioridad sobre las variables de entorno (.env). 
          Si algún campo queda vacío, se utilizará el valor del archivo .env como respaldo.
        </p>
      </div>
    </div>
  </div>

  <!-- SMTP Settings Form -->
  <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-6">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-lg font-semibold text-[var(--c-text)]">Servidor SMTP</h2>
      <span id="settings-status" class="text-xs text-[var(--c-muted)]">Cargando...</span>
    </div>

    <form id="smtp-form" class="space-y-6">
      <!-- Server Configuration -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Host -->
        <div>
          <label for="smtp_host" class="block text-sm font-medium text-[var(--c-text)] mb-2">
            <span class="flex items-center gap-2">
              <svg class="w-4 h-4 text-[var(--c-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
              </svg>
              Servidor SMTP (Host)
            </span>
          </label>
          <input type="text" id="smtp_host" name="smtp_host" placeholder="smtp.gmail.com" 
            class="w-full px-4 py-3 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-xl focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent transition">
          <p class="text-xs text-[var(--c-muted)] mt-1">Ej: smtp.gmail.com, smtp.office365.com</p>
        </div>

        <!-- Port -->
        <div>
          <label for="smtp_port" class="block text-sm font-medium text-[var(--c-text)] mb-2">
            <span class="flex items-center gap-2">
              <svg class="w-4 h-4 text-[var(--c-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              Puerto
            </span>
          </label>
          <input type="number" id="smtp_port" name="smtp_port" placeholder="587" min="1" max="65535"
            class="w-full px-4 py-3 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-xl focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent transition">
          <p class="text-xs text-[var(--c-muted)] mt-1">Común: 587 (TLS), 465 (SSL), 25 (sin cifrar)</p>
        </div>
      </div>

      <!-- Encryption -->
      <div>
        <label for="smtp_encryption" class="block text-sm font-medium text-[var(--c-text)] mb-2">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[var(--c-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Tipo de Encriptación
          </span>
        </label>
        <div class="flex gap-4">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="smtp_encryption" value="tls" class="w-4 h-4 text-[var(--c-primary)] border-[var(--c-border)] focus:ring-[var(--c-primary)]">
            <span class="text-sm text-[var(--c-text)]">TLS (Recomendado)</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="smtp_encryption" value="ssl" class="w-4 h-4 text-[var(--c-primary)] border-[var(--c-border)] focus:ring-[var(--c-primary)]">
            <span class="text-sm text-[var(--c-text)]">SSL</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="smtp_encryption" value="" class="w-4 h-4 text-[var(--c-primary)] border-[var(--c-border)] focus:ring-[var(--c-primary)]">
            <span class="text-sm text-[var(--c-text)]">Ninguna</span>
          </label>
        </div>
      </div>

      <!-- Divider -->
      <div class="border-t border-[var(--c-border)]"></div>

      <!-- Authentication -->
      <h3 class="text-base font-medium text-[var(--c-text)]">Autenticación</h3>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Username -->
        <div>
          <label for="smtp_username" class="block text-sm font-medium text-[var(--c-text)] mb-2">
            <span class="flex items-center gap-2">
              <svg class="w-4 h-4 text-[var(--c-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Usuario / Email
            </span>
          </label>
          <input type="text" id="smtp_username" name="smtp_username" placeholder="tu-email@gmail.com"
            class="w-full px-4 py-3 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-xl focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent transition">
        </div>

        <!-- Password -->
        <div>
          <label for="smtp_password" class="block text-sm font-medium text-[var(--c-text)] mb-2">
            <span class="flex items-center gap-2">
              <svg class="w-4 h-4 text-[var(--c-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
              </svg>
              Contraseña / App Password
            </span>
          </label>
          <div class="relative">
            <input type="password" id="smtp_password" name="smtp_password" placeholder="••••••••••••"
              class="w-full px-4 py-3 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-xl focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent transition pr-12">
            <button type="button" id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--c-muted)] hover:text-[var(--c-text)]">
              <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          <p class="text-xs text-[var(--c-muted)] mt-1">Para Gmail con 2FA, usa una "App Password"</p>
        </div>
      </div>

      <!-- Divider -->
      <div class="border-t border-[var(--c-border)]"></div>

      <!-- Sender Configuration -->
      <h3 class="text-base font-medium text-[var(--c-text)]">Remitente por Defecto</h3>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- From Address -->
        <div>
          <label for="smtp_from_address" class="block text-sm font-medium text-[var(--c-text)] mb-2">
            <span class="flex items-center gap-2">
              <svg class="w-4 h-4 text-[var(--c-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              Email del Remitente
            </span>
          </label>
          <input type="email" id="smtp_from_address" name="smtp_from_address" placeholder="noreply@tudominio.com"
            class="w-full px-4 py-3 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-xl focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent transition">
        </div>

        <!-- From Name -->
        <div>
          <label for="smtp_from_name" class="block text-sm font-medium text-[var(--c-text)] mb-2">
            <span class="flex items-center gap-2">
              <svg class="w-4 h-4 text-[var(--c-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Nombre del Remitente
            </span>
          </label>
          <input type="text" id="smtp_from_name" name="smtp_from_name" placeholder="Mi Aplicación"
            class="w-full px-4 py-3 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-xl focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent transition">
        </div>
      </div>

      <!-- Actions -->
      <div class="flex justify-end gap-3 pt-4 border-t border-[var(--c-border)]">
        <button type="button" id="btn-reset-form" class="px-4 py-2 text-[var(--c-muted)] hover:text-[var(--c-text)] transition">
          Restablecer
        </button>
        <button type="submit" id="btn-save-settings" class="inline-flex items-center gap-2 px-6 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-xl hover:opacity-95 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          Guardar Cambios
        </button>
      </div>
    </form>
  </div>

  <!-- Quick Tips -->
  <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Gmail -->
    <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-4">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center">
          <span class="text-lg">📧</span>
        </div>
        <h4 class="font-medium text-[var(--c-text)]">Gmail</h4>
      </div>
      <ul class="text-xs text-[var(--c-muted)] space-y-1">
        <li>• Host: smtp.gmail.com</li>
        <li>• Puerto: 587 (TLS) o 465 (SSL)</li>
        <li>• Usa App Password con 2FA</li>
      </ul>
    </div>

    <!-- Outlook -->
    <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-4">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
          <span class="text-lg">📬</span>
        </div>
        <h4 class="font-medium text-[var(--c-text)]">Outlook/Office 365</h4>
      </div>
      <ul class="text-xs text-[var(--c-muted)] space-y-1">
        <li>• Host: smtp.office365.com</li>
        <li>• Puerto: 587 (TLS)</li>
        <li>• Autenticación moderna</li>
      </ul>
    </div>

    <!-- Custom -->
    <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] p-4">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center">
          <span class="text-lg">⚙️</span>
        </div>
        <h4 class="font-medium text-[var(--c-text)]">Servidor Propio</h4>
      </div>
      <ul class="text-xs text-[var(--c-muted)] space-y-1">
        <li>• Consulta tu proveedor</li>
        <li>• Puerto común: 25, 587, 465</li>
        <li>• Verifica firewall</li>
      </ul>
    </div>
  </div>
</div>

<!-- Test Email Modal -->
<div id="test-email-modal" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
  <div class="relative mx-auto mt-20 w-full max-w-md">
    <div class="bg-[var(--c-surface)] rounded-2xl border border-[var(--c-border)] overflow-hidden">
      <div class="px-6 py-4 border-b border-[var(--c-border)]">
        <h3 class="text-lg font-semibold text-[var(--c-text)]">Probar Conexión SMTP</h3>
      </div>
      <div class="p-6">
        <p class="text-sm text-[var(--c-muted)] mb-4">
          Ingresa un correo electrónico para enviar un mensaje de prueba y verificar la configuración SMTP.
        </p>
        <div>
          <label for="test-email-address" class="block text-sm font-medium text-[var(--c-text)] mb-2">
            Correo de destino
          </label>
          <input type="email" id="test-email-address" placeholder="test@ejemplo.com"
            class="w-full px-4 py-3 bg-[var(--c-elev)] border border-[var(--c-border)] rounded-xl focus:ring-2 focus:ring-[var(--c-primary)] focus:border-transparent transition">
        </div>
        <div id="test-result" class="mt-4 hidden">
          <!-- Test result will appear here -->
        </div>
      </div>
      <div class="px-6 py-4 border-t border-[var(--c-border)] flex justify-end gap-3">
        <button type="button" id="btn-cancel-test" class="px-4 py-2 text-[var(--c-muted)] hover:text-[var(--c-text)] transition">
          Cancelar
        </button>
        <button type="button" id="btn-send-test" class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--c-primary)] text-[var(--c-primary-ink)] rounded-xl hover:opacity-95 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
          </svg>
          Enviar Prueba
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

  // Storage for settings IDs
  let settingsMap = {};

  // Verify token
  if (!API_TOKEN) {
    showError('Autenticación requerida', 'No se encontró un token de acceso válido. Por favor, inicia sesión nuevamente.');
    return;
  }

  // Load initial data
  loadSettings();

  // Event listeners
  document.getElementById('btn-refresh-settings').addEventListener('click', loadSettings);
  document.getElementById('btn-test-smtp').addEventListener('click', openTestModal);
  document.getElementById('btn-cancel-test').addEventListener('click', closeTestModal);
  document.getElementById('btn-send-test').addEventListener('click', sendTestEmail);
  document.getElementById('btn-reset-form').addEventListener('click', loadSettings);
  document.getElementById('smtp-form').addEventListener('submit', saveSettings);

  // Toggle password visibility
  document.getElementById('toggle-password').addEventListener('click', function() {
    const passwordInput = document.getElementById('smtp_password');
    const eyeIcon = document.getElementById('eye-icon');
    
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
    } else {
      passwordInput.type = 'password';
      eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
  });

  // Functions
  async function loadSettings() {
    document.getElementById('settings-status').textContent = 'Cargando...';

    try {
      const response = await fetch(`${API_BASE}/smtp/settings`, {
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (response.ok && data.success) {
        populateForm(data.data);
        document.getElementById('settings-status').textContent = 'Última actualización: ' + new Date().toLocaleTimeString();
      } else {
        showApiError('Error al cargar configuración', data);
        document.getElementById('settings-status').textContent = 'Error al cargar';
      }
    } catch (error) {
      showError('Error de conexión', 'No se pudo cargar la configuración. Verifica tu conexión a internet.');
      document.getElementById('settings-status').textContent = 'Error de conexión';
    }
  }

  function populateForm(settings) {
    settingsMap = {};
    
    settings.forEach(setting => {
      settingsMap[setting.key] = setting.id;
      
      const field = document.getElementById(setting.key);
      if (field) {
        if (field.type === 'radio') {
          // Handle radio buttons
          const radios = document.querySelectorAll(`input[name="${setting.key}"]`);
          radios.forEach(radio => {
            radio.checked = radio.value === setting.value;
          });
        } else {
          field.value = setting.value || '';
        }
      } else {
        // Try radio buttons
        const radios = document.querySelectorAll(`input[name="${setting.key}"]`);
        radios.forEach(radio => {
          radio.checked = radio.value === setting.value;
        });
      }
    });
  }

  async function saveSettings(e) {
    e.preventDefault();

    const form = document.getElementById('smtp-form');
    const formData = new FormData(form);
    const settings = {};

    // Collect form values
    ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_from_address', 'smtp_from_name'].forEach(key => {
      settings[key] = formData.get(key) || document.getElementById(key)?.value || '';
    });

    // Get encryption value from radio buttons
    const encryptionRadio = document.querySelector('input[name="smtp_encryption"]:checked');
    settings['smtp_encryption'] = encryptionRadio ? encryptionRadio.value : 'tls';

    const btn = document.getElementById('btn-save-settings');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Guardando...';

    try {
      // Update each setting
      for (const [key, value] of Object.entries(settings)) {
        if (settingsMap[key]) {
          const response = await fetch(`${API_BASE}/smtp/settings/${settingsMap[key]}`, {
            method: 'PUT',
            headers: {
              'Authorization': `Bearer ${API_TOKEN}`,
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': CSRF_TOKEN,
              'Accept': 'application/json'
            },
            body: JSON.stringify({ value: value })
          });

          const data = await response.json();

          if (!response.ok || !data.success) {
            showApiError(`Error al guardar ${key}`, data);
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Guardar Cambios';
            return;
          }
        }
      }

      window.dispatchEvent(new CustomEvent('api:response', {
        detail: {
          success: true,
          message: 'Configuración SMTP guardada exitosamente',
          code: 'SMTP_SETTINGS_SAVED'
        }
      }));

      // Reload settings
      loadSettings();
    } catch (error) {
      showError('Error de conexión', 'No se pudo guardar la configuración. Verifica tu conexión a internet.');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Guardar Cambios';
    }
  }

  function openTestModal() {
    document.getElementById('test-email-modal').classList.remove('hidden');
    document.getElementById('test-result').classList.add('hidden');
  }

  function closeTestModal() {
    document.getElementById('test-email-modal').classList.add('hidden');
  }

  async function sendTestEmail() {
    const email = document.getElementById('test-email-address').value;
    
    if (!email) {
      showError('Campo requerido', 'Por favor ingresa un correo electrónico de destino.');
      return;
    }

    const resultDiv = document.getElementById('test-result');
    const sendBtn = document.getElementById('btn-send-test');
    
    resultDiv.classList.remove('hidden');
    resultDiv.innerHTML = '<div class="flex items-center gap-2 text-[var(--c-muted)]"><svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Enviando correo de prueba...</div>';
    
    sendBtn.disabled = true;

    try {
      const response = await fetch(`${API_BASE}/smtp/test`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ email: email })
      });

      const data = await response.json();

      if (response.ok && data.success) {
        resultDiv.innerHTML = `
          <div class="p-3 rounded-lg bg-green-500/10 border border-green-500/30">
            <div class="flex items-start gap-2">
              <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
                <p class="text-sm font-medium text-green-400">¡Correo enviado exitosamente!</p>
                <p class="text-xs text-[var(--c-muted)] mt-1">${data.message}</p>
                <div class="text-xs text-[var(--c-muted)] mt-2 space-y-1">
                  <p>• Host: ${data.data?.host || 'N/A'}</p>
                  <p>• Puerto: ${data.data?.port || 'N/A'}</p>
                  <p>• Encriptación: ${data.data?.encryption || 'Ninguna'}</p>
                </div>
              </div>
            </div>
          </div>
        `;

        window.dispatchEvent(new CustomEvent('api:response', {
          detail: {
            success: true,
            message: data.message,
            code: data.code
          }
        }));
      } else {
        resultDiv.innerHTML = `
          <div class="p-3 rounded-lg bg-red-500/10 border border-red-500/30">
            <div class="flex items-start gap-2">
              <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
                <p class="text-sm font-medium text-red-400">Error al enviar correo</p>
                <p class="text-xs text-[var(--c-muted)] mt-1">${data.message || 'Error desconocido'}</p>
                ${data.data?.error ? `<p class="text-xs text-red-400/70 mt-2">${data.data.error}</p>` : ''}
              </div>
            </div>
          </div>
        `;

        showApiError('Error al enviar correo de prueba', data);
      }
    } catch (error) {
      resultDiv.innerHTML = `
        <div class="p-3 rounded-lg bg-red-500/10 border border-red-500/30">
          <div class="flex items-start gap-2">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
              <p class="text-sm font-medium text-red-400">Error de conexión</p>
              <p class="text-xs text-[var(--c-muted)] mt-1">No se pudo conectar con el servidor. Verifica tu conexión a internet.</p>
            </div>
          </div>
        </div>
      `;

      showError('Error de conexión', 'No se pudo enviar el correo de prueba. Verifica tu conexión a internet.');
    } finally {
      sendBtn.disabled = false;
    }
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
});
</script>
@endsection