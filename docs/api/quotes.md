# API de Cotizaciones (Quotes)

Este módulo permite la gestión completa de cotizaciones, incluyendo la generación de PDFs con imágenes de fondo personalizadas.

## Configuración

### Dependencias
El módulo utiliza **barryvdh/laravel-dompdf** para la generación de PDFs.

```bash
composer require barryvdh/laravel-dompdf
```

### Tablas de Base de Datos

#### `quote_settings`
Configuración global para cotizaciones (singleton).

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| company_name | string | Nombre de la empresa |
| company_address | text | Dirección de la empresa |
| company_phone | string | Teléfono de la empresa |
| company_email | string | Email de la empresa |
| company_logo_id | bigint | FK a media_assets (logo) |
| background_image_id | bigint | FK a media_assets (imagen de fondo por defecto) |
| last_page_image_id | bigint | FK a media_assets (imagen de última página) |
| default_terms_conditions | text | Términos y condiciones por defecto |
| default_notes | text | Notas por defecto |
| default_tax_rate | decimal(5,2) | Tasa de impuesto por defecto |

#### `quotes`
Tabla principal de cotizaciones.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| quote_number | string | Número único de cotización (ej: COT-202512-00001) |
| user_id | bigint | FK a users (cliente) |
| created_by | bigint | FK a users (creador) |
| title | string | Título de la cotización |
| description | text | Descripción |
| currency_id | bigint | FK a currencies |
| subtotal | decimal(15,2) | Subtotal |
| tax_rate | decimal(5,2) | Tasa de impuesto |
| tax_amount | decimal(15,2) | Monto de impuesto |
| discount_amount | decimal(15,2) | Descuento global |
| total | decimal(15,2) | Total |
| status | enum | draft, sent, accepted, rejected, expired |
| valid_until | date | Fecha de validez |
| notes | text | Notas |
| terms_conditions | text | Términos y condiciones |
| client_name | string | Nombre del cliente (sin cuenta) |
| client_email | string | Email del cliente (sin cuenta) |
| client_phone | string | Teléfono del cliente |
| client_address | text | Dirección del cliente |
| custom_background_image_id | bigint | FK a media_assets (fondo personalizado) |
| custom_last_page_image_id | bigint | FK a media_assets (última página personalizada) |

#### `quote_items`
Items/productos de las cotizaciones.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| quote_id | bigint | FK a quotes |
| name | string | Nombre del item |
| description | text | Descripción |
| quantity | decimal(10,2) | Cantidad |
| unit | string | Unidad de medida |
| unit_price | decimal(15,2) | Precio unitario |
| discount_percent | decimal(5,2) | Porcentaje de descuento |
| discount_amount | decimal(15,2) | Monto de descuento |
| subtotal | decimal(15,2) | Subtotal (cantidad * precio) |
| total | decimal(15,2) | Total (subtotal - descuento) |
| sort_order | int | Orden de visualización |

---

## Endpoints

### Cotizaciones

#### Listar cotizaciones
```
GET /api/quotes
```

**Query Parameters:**
- `status` - Filtrar por estado (draft, sent, accepted, rejected, expired)
- `user_id` - Filtrar por cliente
- `created_by` - Filtrar por creador
- `search` - Buscar en número, título, cliente
- `date_from` - Fecha desde
- `date_to` - Fecha hasta
- `sort_by` - Campo para ordenar (default: created_at)
- `sort_order` - Orden (asc/desc, default: desc)
- `per_page` - Items por página (default: 15)

**Response:**
```json
{
    "success": true,
    "data": {
        "data": [...],
        "current_page": 1,
        "per_page": 15,
        "total": 100
    }
}
```

#### Crear cotización
```
POST /api/quotes
```

**Body:**
```json
{
    "title": "Cotización de servicios web",
    "description": "Desarrollo de sitio web corporativo",
    "user_id": 1,
    "currency_id": 1,
    "tax_rate": 16,
    "discount_amount": 0,
    "valid_until": "2025-01-15",
    "notes": "Incluye hosting por 1 año",
    "terms_conditions": "50% anticipo, 50% al entregar",
    "client_name": "Juan Pérez",
    "client_email": "juan@example.com",
    "client_phone": "+52 555 123 4567",
    "client_address": "Calle Principal #123",
    "custom_background_image_id": null,
    "custom_last_page_image_id": null,
    "items": [
        {
            "name": "Diseño UI/UX",
            "description": "Diseño de interfaces de usuario",
            "quantity": 1,
            "unit": "pza",
            "unit_price": 5000,
            "discount_percent": 0
        },
        {
            "name": "Desarrollo Frontend",
            "quantity": 40,
            "unit": "hrs",
            "unit_price": 500,
            "discount_percent": 10
        }
    ]
}
```

**Response:**
```json
{
    "success": true,
    "message": "Cotización creada exitosamente",
    "data": {
        "id": 1,
        "quote_number": "COT-202512-00001",
        ...
    }
}
```

#### Ver cotización
```
GET /api/quotes/{id}
```

#### Actualizar cotización
```
PUT /api/quotes/{id}
```

#### Eliminar cotización
```
DELETE /api/quotes/{id}
```

#### Restaurar cotización eliminada
```
POST /api/quotes/{id}/restore
```

#### Duplicar cotización
```
POST /api/quotes/{id}/duplicate
```

#### Cambiar estado
```
PATCH /api/quotes/{id}/status
```

**Body:**
```json
{
    "status": "sent"
}
```

---

### Items de Cotización

#### Agregar item
```
POST /api/quotes/{quoteId}/items
```

**Body:**
```json
{
    "name": "Consultoría",
    "description": "Sesión de consultoría técnica",
    "quantity": 2,
    "unit": "hrs",
    "unit_price": 1000,
    "discount_percent": 0
}
```

#### Actualizar item
```
PUT /api/quotes/{quoteId}/items/{itemId}
```

#### Eliminar item
```
DELETE /api/quotes/{quoteId}/items/{itemId}
```

#### Reordenar items
```
POST /api/quotes/{quoteId}/items/reorder
```

**Body:**
```json
{
    "items": [
        {"id": 3, "sort_order": 0},
        {"id": 1, "sort_order": 1},
        {"id": 2, "sort_order": 2}
    ]
}
```

---

### Generación de PDF

#### Descargar PDF
```
GET /api/quotes/{id}/pdf/download
```

Descarga directa del archivo PDF.

#### Preview PDF (Stream)
```
GET /api/quotes/{id}/pdf/preview
```

Muestra el PDF en el navegador sin descargarlo.

#### Obtener PDF en Base64
```
GET /api/quotes/{id}/pdf/base64
```

**Response:**
```json
{
    "success": true,
    "data": {
        "filename": "Cotizacion_COT-202512-00001_Servicios_web.pdf",
        "mime_type": "application/pdf",
        "base64": "JVBERi0xLjQKJ..."
    }
}
```

---

### Configuración de Cotizaciones

#### Obtener configuración
```
GET /api/quote-settings
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "company_name": "Mi Empresa",
        "company_address": "Calle Principal #123",
        "company_phone": "+52 555 123 4567",
        "company_email": "contacto@empresa.com",
        "company_logo_id": 5,
        "background_image_id": 10,
        "last_page_image_id": 11,
        "default_terms_conditions": "...",
        "default_notes": "...",
        "default_tax_rate": "16.00",
        "company_logo": {...},
        "background_image": {...},
        "last_page_image": {...}
    }
}
```

#### Actualizar configuración
```
PUT /api/quote-settings
```

**Body:**
```json
{
    "company_name": "Mi Nueva Empresa",
    "company_address": "Nueva dirección",
    "company_phone": "+52 555 987 6543",
    "company_email": "nuevo@empresa.com",
    "default_tax_rate": 16,
    "default_terms_conditions": "Términos actualizados...",
    "default_notes": "Notas por defecto..."
}
```

#### Actualizar logo de empresa
```
POST /api/quote-settings/logo
```

**Body:**
```json
{
    "company_logo_id": 5
}
```

#### Eliminar logo de empresa
```
DELETE /api/quote-settings/logo
```

#### Actualizar imagen de fondo
```
POST /api/quote-settings/background-image
```

**Body:**
```json
{
    "background_image_id": 10
}
```

#### Eliminar imagen de fondo
```
DELETE /api/quote-settings/background-image
```

#### Actualizar imagen de última página
```
POST /api/quote-settings/last-page-image
```

**Body:**
```json
{
    "last_page_image_id": 11
}
```

#### Eliminar imagen de última página
```
DELETE /api/quote-settings/last-page-image
```

#### Actualizar términos y condiciones
```
PUT /api/quote-settings/terms-conditions
```

**Body:**
```json
{
    "default_terms_conditions": "Nuevos términos y condiciones..."
}
```

#### Actualizar notas por defecto
```
PUT /api/quote-settings/notes
```

**Body:**
```json
{
    "default_notes": "Nuevas notas por defecto..."
}
```

---

## Flujo de Imágenes

### Imagen de Fondo (background_image)
- Se aplica a **todas las páginas** del PDF como fondo
- Se puede configurar globalmente en `quote_settings`
- Se puede personalizar por cotización usando `custom_background_image_id`
- La imagen personalizada de la cotización tiene prioridad sobre la global

### Imagen de Última Página (last_page_image)
- Se muestra como **página adicional al final** del PDF
- Ideal para firmas, sellos, condiciones legales adicionales
- Se puede configurar globalmente en `quote_settings`
- Se puede personalizar por cotización usando `custom_last_page_image_id`

### Uso con MediaAsset
Las imágenes se referencian mediante IDs de `media_assets`. El sistema:
1. Busca el archivo en storage
2. Lo convierte a Base64 para embebir en el PDF
3. Soporta URLs remotas (las descarga y convierte)

---

## Autenticación

Todos los endpoints requieren:
- Header `Authorization: Bearer {token}`
- Middleware `auth.api` y `admin.api`

---

## Códigos de Estado HTTP

| Código | Descripción |
|--------|-------------|
| 200 | Éxito |
| 201 | Creado exitosamente |
| 400 | Solicitud incorrecta |
| 404 | Recurso no encontrado |
| 422 | Error de validación |
| 500 | Error del servidor |