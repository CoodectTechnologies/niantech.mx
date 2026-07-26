NIANTECH
🟢 Actualizar a laravel 10
🟢 Corregir sincronizadores
🟢 Actualizar a livewire v3 
🟢 Mejorar pusher
🟢 Integrar chatbot con AI
🟢 Agregar otra libreria de extracción de texto de pdf
🟢 Mejorar extracción con status y message
🟢 Mejorar configuración de laravel pint
🟢 Mejorar configuración de laravel cs fixer
🟢 Corregir setting dontenv ya que la eliminamos
🟢 Corregir asantibanez/livewire-charts ya que la eliminamos
🟢 Corregir webhook de stripe payment gateway
🟢 Actualizar libreria de mercadopago y refactorizar sus clases en su payment gateway
🟢 Corregir iconos en modo oscuro
🟢 Acomodar links de footer
🟢 Reparar imagenes de "Siguenos" en el sidebar
🟢 Integrar variantes
    - 🟢 Agregar variantes en la vista checkout pago y checkout complete
    - 🟢 Checar en los resumenes si vienen los desgloses de tax
    - 🟢 Agregar leyenda de sin stock en carrito cuando ya no tenga stock, 
    - 🟢 Contemplar el middleware si esta desactivada una variante.
    - 🟢 Corregir las tabs del boton de edit de la variante porque cuando cargo imagenes se me desconfigura
    - 🟢 Mini carrito arreglar
    - 🟢 Cambiar a que no puede tener una variante por default, o tiene variantes o no tiene.
    - 🟢 Mejorar imagenes en formulario para ligar imagenes.
🟢 Checar popup
🟢 Afinar carrito olvidado
🟢 Agregar en el correo de creación de pedido la variante que se seleccionó
🟢 Integrar subscripciones
🟢 Integrar módulos faltantes de web
🟢 Preferencias de notificaciones 
🟢 Configurar bien el honey
🟢 Checar como interactuará un cliente con el panel
🟢 Acomodar arquitectura para servicios
🟢 Acomodar arquitectura para integraciones 
    - 🟢 Odoo
    - 🟢 VadetoBrands
🟢 integración con Vadeto Brands
    - 🟢 Sincronizar productos
    - 🟢 Sincronizar imagenes
🟡 Integración con Odoo
    - 🟢 Traducciones, obtener de la api el idioma de la web
    - 🟢 Idear forma de sincronizar
    - 🟢 Canales de logs especiales
    - 🟢 Sincronizar productos
    - 🟢 Sincronizar clientes
    - 🟢 Sincronizar paises
    - 🟢 Sincronizar estados
    - 🟢 Cambiar estructura de carrito
    - 🟢 Cambiar estructura de checkout
    - 🟢 Cambiar estructura de direcciones
    - 🟢 Sincronizar direcciones de envío
    - 🟡 Sincronizar ordenes
        - Metodos de envío
        - Ordenes
🟢 Restructurar shipping price de las ordenes (shipping_price_subtotal, shipping_price_tax, shipping_price_total)
🟢 Modificar los conceptos del  form de erp, será odoo
🟢 El menu el movil no se ve en la ecommerce
🟢 El filtro de catalogo no sirve
🟢 Agregar slug in tags y categories de blog
🟢 Error a la hora de generar variantes de producto
🟢 Que se puedan poner videos en el banner
🟢 Mejorar throw para excepciones de Odoo en registros/ediciones/eliminaciones, generando un throw especifico
🟢 Los iconos de los contadores del ecommerce, remover la bolita si no tiene nada, y ponerla en el color primario
🟡 Rediseño
    - 🟢 Limpiar css custom dark
    - 🟢 Mejorar dark css
    - 🟢 El botón de categorías dirá: "Productos" y eliminar el botón de productos ho ver como es la mejor manera.
    - 🟢 Página de productos
    - 🟢 página de carrito
    - 🟢 Placeholder de imagenes a productos sin imagen con marca del logo
    - Grafica de analiticas la de pastel, se ve muy grande
    - Los calendarios de rangos no se ven bien en modo oscuro
    - Graficas anteriores
🟢 Usar vite
🟢 Actualizar livewire v4 
🟢 Instalar echo 
🟢 Instalar reverb 
🟢 Arreglar graficas ya que eliminamos una libreria de gráficas, tocará hacerlas manuales
🟢 Cambiar sincronizador de tipo de cambio en el dashbboard de proveedores ya que actualmente esta con PCH
🟢 Migrar a laravel 12 en limpio
🟢 Testear la impersonación de otras personas con productos en carrito
🟢 Testear la hora en la que se vendio o registro algo ya que ahora tenemos UTC
🟢 Analizar si usar laravel localization
🟢 Traerse la logica de subscripciones de mi horli para planes gratis
🟡 Instalar ngrok
🟡 Agregar logs para pasarelas de pago
🟡 Generar un buen diseño para el modal del popup
🟡 Método de envío por default, el más barato pero solo si no han escogido ya alguno
🟡 Cancelación de ordenes a la semana de no haberlas pagado
🟡 Sinonimos
🟡 ¿El Rastreo como funcionará ya con odoo integrado?
🟡 Mejorar dashboard principal como tiendanube
🟢 Mejorar filtro de precios ya que si hay productos en USD, ya no es consistente el filtro ya que filtra por el valor guardado de la base de datos ejemplo 10usd, y si el filtro de de 0 a 100, 100 dolares en pesos son como 1700, entonces esos productos de 1700 pesos se seguiran mostrando ya que su valor es 10 usd, me doy a entender?
🟠 Generar servicio de productos, para reutilizar con sincronizador de proveedores
🟠 Generar tipos de opciones para productos (Color, Texto, Selector)
🟠 Integrar algun proveedor como estafeta (Wishlist)
🟠 Centros de distribución
    - 🟠 Nombre de la ubicación
    - 🟠 Dirección

Es un buen ejemplo este demo: https://themeforest.net/item/razox-gaming-gear-woocommerce-theme/51982116
