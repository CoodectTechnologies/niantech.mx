# Sistema de Variantes de Producto - Guía Completa

## 📋 Resumen Ejecutivo

Se ha implementado un sistema completo de variantes de producto que replica la funcionalidad de Shopify, permitiendo:

- ✅ Creación de productos simples (sin variantes)
- ✅ Creación de productos con variantes (hasta 3 opciones)
- ✅ Generación automática de todas las combinaciones
- ✅ Gestión individual de precio, SKU, inventario e imágenes por variante
- ✅ Activación/desactivación de variantes sin eliminarlas
- ✅ Sincronización de inventario por almacén para cada variante

---

## 🗄️ Modelo de Datos

### Tablas Creadas

#### 1. **products** (modificada)
- `has_variants`: boolean - Indica si el producto tiene variantes

#### 2. **product_options**
Almacena las opciones del producto (Color, Talla, Material, etc.)
- `id`: bigint
- `product_id`: FK a products
- `name`: string(50) - Nombre de la opción
- `position`: tinyint - Orden de visualización
- **Índices**: `[product_id, position]`, unique `[product_id, name]`

#### 3. **product_option_values**
Almacena los valores de cada opción (Rojo, S, Algodón, etc.)
- `id`: bigint
- `product_option_id`: FK a product_options
- `value`: string - Valor de la opción
- `position`: tinyint - Orden de visualización
- **Índices**: `[product_option_id, position]`, unique `[product_option_id, value]`

#### 4. **product_variants**
Almacena cada variante del producto
- `id`: bigint
- `product_id`: FK a products
- `sku`: string(100) unique
- `price`: decimal(12,2)
- `price_promotion`: decimal(12,2) nullable
- `cost`: decimal(12,2) nullable
- `weight_kl`: decimal(8,3) nullable
- `height`: decimal(8,2) nullable
- `width`: decimal(8,2) nullable
- `length`: decimal(8,2) nullable
- `position`: int - Orden de visualización
- `is_active`: boolean - Si está activa
- **Índices**: `[product_id, sku, is_active]`

#### 5. **product_variant_options** (pivot)
Relaciona variantes con sus valores de opción
- `id`: bigint
- `product_variant_id`: FK a product_variants
- `product_option_value_id`: FK a product_option_values
- **Índices**: ambos campos

#### 6. **product_variant_warehouses**
Inventario por variante y almacén
- `id`: bigint
- `product_variant_id`: FK a product_variants
- `product_warehouse_id`: FK a product_warehouses
- `quantity`: int - Cantidad en inventario
- **Índices**: `product_variant_id`

#### 7. **order_product** (modificada)
- `product_variant_id`: FK nullable a product_variants

### Relaciones del Modelo

```php
// Product
- hasMany: productOptions, productVariants

// ProductOption
- belongsTo: product
- hasMany: productOptionValues

// ProductOptionValue
- belongsTo: productOption
- belongsToMany: productVariants

// ProductVariant
- belongsTo: product
- hasMany: productVariantOptions, productVariantWarehouses
- belongsToMany: productOptionValues
- morphOne: image
- morphMany: images
```

---

## 🎯 UX / Experiencia de Usuario

### Flujo de Trabajo

#### **Paso 1: Producto Simple (Default)**
Por defecto, todos los productos son simples (sin variantes).

#### **Paso 2: Activar Variantes**
1. Usuario hace clic en el switch "Este producto tiene múltiples opciones"
2. Se muestra automáticamente un formulario para agregar la primera opción
3. El sistema se expande mostrando los controles de variantes

#### **Paso 3: Agregar Opciones**
1. Usuario ingresa el nombre de la opción (ej: "Color")
2. Agrega valores haciendo clic en "+ Agregar otro valor" (ej: "Rojo", "Azul", "Verde")
3. Puede agregar hasta 3 opciones diferentes
4. Puede eliminar opciones si son más de una

#### **Paso 4: Generar Variantes**
1. Usuario hace clic en "Generar variantes"
2. El sistema calcula todas las combinaciones posibles
3. Se muestra una tabla con todas las variantes generadas
4. Cada variante tiene valores por defecto del producto padre

#### **Paso 5: Editar Variantes**
En la tabla de variantes, el usuario puede:
- ✏️ Editar SKU individualmente
- 💰 Editar precio individualmente  
- 📦 Gestionar inventario por almacén (modal)
- 🖼️ (Futuro) Asignar imagen específica
- ✅ Activar/desactivar variantes
- 🗑️ Eliminar variantes específicas

#### **Paso 6: Guardar**
Al hacer clic en "Guardar cambios":
1. Se validan todos los datos
2. Se guardan las opciones y sus valores
3. Se crean/actualizan las variantes
4. Se sincroniza el inventario
5. Se eliminan variantes/opciones obsoletas

---

## 💻 Componente Livewire

### Propiedades Principales

```php
// Estado de variantes
public $hasVariants = false;              // Si tiene variantes activo
public $productOptions = [];              // Array de opciones
public $productVariants = [];             // Array de variantes
public $showVariantsTable = false;        // Mostrar tabla
```

### Estructura de Datos

#### **$productOptions**
```php
[
    [
        'id' => 1,
        'name' => 'Color',
        'position' => 1,
        'values' => [
            ['id' => 1, 'value' => 'Rojo', 'position' => 1],
            ['id' => 2, 'value' => 'Azul', 'position' => 2],
        ]
    ],
    [
        'id' => 2,
        'name' => 'Talla',
        'position' => 2,
        'values' => [
            ['id' => 3, 'value' => 'S', 'position' => 1],
            ['id' => 4, 'value' => 'M', 'position' => 2],
        ]
    ]
]
```

#### **$productVariants**
```php
[
    [
        'id' => 1,
        'sku' => 'PROD-001-A1B2C3',
        'price' => 100.00,
        'price_promotion' => null,
        'cost' => 50.00,
        'is_active' => true,
        'position' => 1,
        'option_values' => ['Rojo', 'S'],
        'option_values_ids' => [1, 3],
        'warehouses' => [
            1 => 10,  // warehouse_id => quantity
            2 => 5,
        ],
        'image_url' => null,
    ],
    // ... más variantes
]
```

### Métodos Públicos Principales

```php
// Gestión de opciones
toggleHasVariants()           // Activa/desactiva sistema de variantes
addOption()                   // Agrega nueva opción
removeOption($index)          // Elimina opción
addOptionValue($optionIndex)  // Agrega valor a una opción
removeOptionValue($optionIndex, $valueIndex) // Elimina valor

// Generación de variantes
generateVariants()            // Genera todas las combinaciones

// Gestión de variantes
updateVariant($index, $field, $value) // Actualiza campo de variante
toggleVariantStatus($index)   // Activa/desactiva variante
deleteVariant($index)         // Elimina variante

// Persistencia
saveVariants()                // Guarda todo en BD
```

---

## 🔧 Lógica de Negocio

### Algoritmo de Generación de Combinaciones

```php
// Ejemplo: Color (Rojo, Azul) x Talla (S, M, L)
// Resultado: 6 variantes
// - Rojo / S
// - Rojo / M
// - Rojo / L
// - Azul / S
// - Azul / M
// - Azul / L

// Implementación: Producto Cartesiano
private function generateCombinations() {
    // 1. Extraer valores válidos de cada opción
    // 2. Aplicar producto cartesiano
    // 3. Retornar combinaciones con IDs
}
```

### Generación Automática de SKU

```php
private function generateVariantSku($optionValues) {
    $baseSku = $this->product->sku ?? 'PROD';
    $suffix = strtoupper(substr(md5(implode('-', $optionValues)), 0, 6));
    return $baseSku . '-' . $suffix;
}
```

### Preservación de Variantes Existentes

Al regenerar variantes:
1. Se comparan las combinaciones nuevas con las existentes
2. Se preservan las variantes que ya existen (con sus datos editados)
3. Solo se crean las nuevas combinaciones
4. **NO** se eliminan automáticamente las obsoletas (debe ser manual)

### Validaciones Críticas

1. **Opciones**: 
   - Máximo 3 opciones
   - Nombre obligatorio
   - No duplicados por producto

2. **Valores de Opciones**:
   - Mínimo 1 valor por opción
   - No vacíos
   - No duplicados por opción

3. **Variantes**:
   - SKU único en toda la BD
   - Precio obligatorio
   - Al menos una variante activa (recomendado)

---

## 📦 Casos Borde (Edge Cases)

### 1. **Cambiar de Variante a Simple**
- Se desactiva `hasVariants`
- Se eliminan todas las opciones y variantes
- Se mantienen los datos base del producto

### 2. **Agregar Nueva Opción a Producto Existente**
- Regenerar variantes preserva las existentes
- Crea nuevas combinaciones
- Usuario debe revisar inventario de nuevas variantes

### 3. **Eliminar Valor de Opción**
- No elimina automáticamente variantes relacionadas
- Usuario debe regenerar variantes
- Se marcan como obsoletas implícitamente

### 4. **Producto con Miles de Variantes**
- Limitación natural: 3 opciones
- Máximo teórico: 100 x 100 x 100 = 1,000,000 variantes
- Recomendación: Máximo 10 valores por opción = 1,000 variantes
- Paginación futura para tablas grandes

### 5. **Variante Asociada a Pedido**
- No se puede eliminar directamente (constraint RESTRICT)
- Debe marcarse como inactiva
- Mantiene historial de ventas

---

## ⚡ Escalabilidad y Performance

### Optimizaciones Implementadas

1. **Índices de Base de Datos**
   - Búsqueda rápida de variantes por producto y SKU
   - Búsqueda de opciones y valores ordenados

2. **Eager Loading**
   ```php
   $product->load([
       'productOptions.productOptionValues',
       'productVariants.productOptionValues'
   ]);
   ```

3. **Livewire Wire:Model.defer**
   - Reducción de llamadas al servidor
   - Batch de actualizaciones

4. **Alpine.js para UI Reactivo**
   - Mostrar/ocultar secciones sin llamadas al servidor
   - Validaciones client-side

### Límites Recomendados

| Métrica | Límite Recomendado | Límite Técnico |
|---------|-------------------|----------------|
| Opciones por producto | 3 | 3 (hardcoded) |
| Valores por opción | 10 | ∞ |
| Variantes por producto | 100-500 | 1000+ |
| Almacenes | Ilimitado | - |

---

## 🚧 Riesgos y Recomendaciones

### Riesgos

1. **⚠️ Regeneración Masiva**
   - Regenerar variantes puede crear cientos de combinaciones
   - Mitigación: Validar antes de generar, mostrar preview

2. **⚠️ Inventario Desincronizado**
   - Variantes nuevas empiezan con inventario 0
   - Mitigación: Indicador visual, validación antes de publicar

3. **⚠️ Variantes Huérfanas**
   - Si se elimina una opción, las variantes quedan sin sentido
   - Mitigación: Forzar regeneración al modificar opciones

### Recomendaciones

1. **✅ Agregar Vista Previa**
   ```php
   // Mostrar "Se generarán X variantes" antes de ejecutar
   ```

2. **✅ Validación de Stock**
   ```php
   // Advertir si variantes activas tienen stock = 0
   ```

3. **✅ Bulk Edit**
   ```php
   // Editar múltiples variantes a la vez (precio, costo, etc.)
   ```

4. **✅ Import/Export**
   ```php
   // CSV para edición masiva de variantes
   ```

5. **✅ Historial de Cambios**
   ```php
   // Log de modificaciones a variantes (Spatie Activity Log)
   ```

---

## 📊 Comparación con Shopify

| Característica | Shopify | Nuestra Implementación | Estado |
|----------------|---------|------------------------|--------|
| Máx. opciones | 3 | 3 | ✅ |
| Máx. valores/opción | 100 | ∞ | ✅ |
| Máx. variantes | 100 (Basic) | 1000+ | ✅ |
| Generación automática | ✅ | ✅ | ✅ |
| SKU por variante | ✅ | ✅ | ✅ |
| Precio por variante | ✅ | ✅ | ✅ |
| Inventario por variante | ✅ | ✅ (multi-almacén) | ✅ |
| Imagen por variante | ✅ | 🚧 Preparado | 🔜 |
| Peso/dimensiones | ✅ | ✅ | ✅ |
| Bulk edit | ✅ | ❌ | 🔜 |
| Import/Export | ✅ | ❌ | 🔜 |

---

## 🎨 Frontend (Interfaz)

### Stack Tecnológico
- **Livewire 3**: Reactividad del servidor
- **Alpine.js 3**: Interactividad cliente
- **Bootstrap 5**: Estilos (Metronic)

### Componentes UI

#### 1. **Switch de Variantes**
```blade
<input wire:model.live="hasVariants" 
       wire:click="toggleHasVariants">
```

#### 2. **Lista de Opciones**
- Cards expandibles
- Badges para valores
- Botones de agregar/eliminar

#### 3. **Tabla de Variantes**
- Columnas: Active, Variant, SKU, Price, Stock, Actions
- Modal para gestión de inventario
- Edición inline

### Feedback Visual

| Acción | Feedback |
|--------|----------|
| Generando variantes | Spinner + "Generando..." |
| Variante guardada | Toast notification |
| Error de validación | Alert rojo |
| Variante inactiva | Opacity 50% |

---

## 🧪 Testing Recomendado

```php
// Unit Tests
test('genera combinaciones correctamente')
test('calcula SKU único')
test('preserva variantes existentes')

// Feature Tests
test('puede crear producto con variantes')
test('puede agregar opción a producto existente')
test('puede editar variante individual')
test('puede eliminar variante')
test('sincroniza inventario correctamente')

// Browser Tests (Dusk)
test('flujo completo de creación de variantes')
test('regeneración no pierde datos editados')
```

---

## 📝 Notas Finales

### Archivos Modificados/Creados

**Migraciones:**
- `2025_12_24_132717_create_product_options_table.php`
- `2025_12_24_132901_create_product_option_values_table.php`
- `2025_12_24_133005_create_product_variants_table.php`
- `2025_12_24_133807_create_product_variant_options_table.php`
- `2025_12_24_134110_create_product_variant_warehouses_table.php`
- `2025_12_24_134529_add_has_variants_to_products_table.php`
- `2022_03_21_050146_create_order_product_table.php` (modificada)

**Modelos:**
- `Product.php` (actualizado)
- `ProductOption.php`
- `ProductOptionValue.php`
- `ProductVariant.php`
- `ProductVariantOption.php`
- `ProductVariantWarehouse.php`

**Componentes Livewire:**
- `Form.php` (actualizado)

**Vistas:**
- `form.blade.php` (actualizado)
- `_variants.blade.php` (nuevo)

---

## 🚀 Próximos Pasos

1. **Gestión de Imágenes por Variante**
   - Upload individual
   - Galería por variante

2. **Bulk Edit**
   - Edición masiva de precios
   - Aplicar descuentos a múltiples variantes

3. **Import/Export**
   - CSV con todas las variantes
   - Edición offline

4. **Vista de Cliente (Ecommerce)**
   - Selector de variantes dinámico
   - Validación de stock en tiempo real
   - Precio cambia según selección

5. **Reportes**
   - Variantes más vendidas
   - Stock crítico por variante
   - Análisis de rentabilidad

---

## 📞 Soporte

Para dudas o mejoras, consultar:
- Documentación de Livewire 3
- Documentación de Alpine.js 3
- Código fuente en `app/Livewire/Admin/Catalog/Product/Product/Form.php`
