<div>
    <div x-data="app">
        <div class="separator separator-gray-300 separator-dashed mb-10"></div>
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <h2 class="mb-4">Preferencias de notificaciones</h2>
                <span class="text-gray-600">Ajusta qué tipo de notificaciones necesitas</span>
            </div>
            <div class="col-lg-8">
                <div>
                    <h5 class="mb-3">Notificaciones</h5>
                    <p class="text-muted">Activa o desactiva los métodos de notificación disponibles.</p>
    
                    <div class="mb-4">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="create_push" wire:model="notificationPreference.push_notifications">
                            <label class="form-check-label fw-bold" for="create_push">
                                Notificaciones Push
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="create_email" wire:model="notificationPreference.email_notifications">
                            <label class="form-check-label fw-bold" for="create_email">
                                Notificaciones por Email
                            </label>
                        </div>
                    </div>
    
                    <!-- Save Button -->
                    <div class="d-grid">
                        <button class="btn btn-dark" wire:click="save">
                            Guardar preferencias
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </button>
                    </div>
                </div>
    
                <div class="card card-xl-stretch mb-xl-8 mt-10">
                    <div class="card-header border-0">
                        <h3 class="card-title fw-bolder text-dark">Notificaciones push</h3>
                    </div>
                    <div class="card-body pt-0" wire:ignore>
                        <div id="notification-not-active" class="d-show" x-cloak>
                            <button class="btn btn-primary" x-on:click="solicitarNotificaciones()">Activar Notificaciones</button>
                        </div>
                        <div id="notification-is-active" class="d-none" x-cloak>
                            <span class="badge badge-success">Las notificaciones push estan activadas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        Alpine.data('app', () => ({
            init() {
                this.btnNotifications();
            },
            btnNotifications(){
                // Función para mostrar/ocultar botones según permisos
                console.log(Notification.permission);
                if ('Notification' in window && Notification.permission === 'denied') {
                    $('#notification-not-active').removeClass('d-none').addClass('d-block');
                    $('#notification-is-active').removeClass('d-block').addClass('d-none');
                } else if ('Notification' in window && Notification.permission === 'granted') {
                    $('#notification-not-active').removeClass('d-block').addClass('d-none');
                    $('#notification-is-active').removeClass('d-none').addClass('d-block');
                }
            },
            solicitarNotificaciones(){
                if ('Notification' in window) {
                    Notification.requestPermission().then(function (permission) {
                        if (permission === 'granted') {
                            alert('¡Ahora recibirás notificaciones push, recuerda mantener MI HORLI abierto en alguna pestaña!');
                            window.location.reload()
                        } else if (permission === 'denied') {
                            alert('Tienes las notificaciones bloqueadas. Por favor habilítalas desde la configuración del navegador.');
                        }
                    });
                } else {
                    alert('Tu navegador no soporta notificaciones push.');
                }
            }
        }));
    </script>
@endscript