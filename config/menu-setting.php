<?php

return [
    [
        'section' => [
            'name' => 'SYSTEM',
            'modules' => [
                [
                    'name' => 'Welcome',
                    'icon' => 'fa-light fa-house-heart',
                    'urlName' => 'admin.setting.welcome',
                    'active' => 'admin.setting.welcome',
                    'canany' => [null],
                ],
                [
                    'name' => 'General',
                    'icon' => 'fa-light fa-gear',
                    'urlName' => 'admin.setting.general',
                    'active' => 'admin.setting.general',
                    'canany' => ['generales'],
                ],
                [
                    'name' => 'Permissions',
                    'icon' => 'fa-light fa-shield-check',
                    'urlName' => 'admin.setting.permission',
                    'active' => 'admin.setting.permission',
                    'canany' => ['permisos'],
                ],
                [
                    'name' => 'Roles',
                    'icon' => 'fa-light fa-user-shield',
                    'urlName' => 'admin.setting.role.index',
                    'active' => 'admin.setting.role*',
                    'canany' => ['roles'],
                ],
                [
                    'name' => 'System logs',
                    'icon' => 'fa-light fa-file-lines',
                    'urlName' => 'admin.setting.log',
                    'active' => 'admin.setting.log',
                    'canany' => ['logs'],
                ],
                [
                    'name' => 'Backups',
                    'icon' => 'fa-light fa-cloud-arrow-up',
                    'urlName' => 'admin.setting.backup',
                    'active' => 'admin.setting.backup',
                    'canany' => ['backups'],
                ],
                [
                    'name' => 'Web modules',
                    'icon' => 'fa-light fa-puzzle-piece',
                    'urlName' => 'admin.setting.module-web',
                    'active' => 'admin.setting.module-web',
                    'canany' => ['módulos web'],
                ],
                [
                    'name' => 'Notification preferences',
                    'icon' => 'fa-light fa-bell',
                    'urlName' => 'admin.setting.notification',
                    'active' => 'admin.setting.notification',
                    'canany' => ['notificaciones'],
                ],
            ],
        ],
    ],
    [
        'section' => [
            'name' => 'E-COMMERCE',
            'modules' => [
                [
                    'name' => 'Shipping zones',
                    'icon' => 'fa-light fa-truck-fast',
                    'urlName' => 'admin.setting.shipping-zone.index',
                    'active' => 'admin.setting.shipping-zone*',
                    'canany' => ['zonas de envío'],
                ],
                [
                    'name' => 'Shipping classes',
                    'icon' => 'fa-light fa-tags',
                    'urlName' => 'admin.setting.shipping-class',
                    'active' => 'admin.setting.shipping-class',
                    'canany' => ['clases de envío'],
                ],
                [
                    'name' => 'Countries',
                    'icon' => 'fa-light fa-globe',
                    'urlName' => 'admin.setting.country',
                    'active' => 'admin.setting.country',
                    'canany' => ['países'],
                ],
                [
                    'name' => 'States',
                    'icon' => 'fa-light fa-map-location-dot',
                    'urlName' => 'admin.setting.state',
                    'active' => 'admin.setting.state',
                    'canany' => ['estados'],
                ],
                [
                    'name' => 'Warehouses',
                    'icon' => 'fa-light fa-warehouse',
                    'urlName' => 'admin.setting.warehouse',
                    'active' => 'admin.setting.warehouse',
                    'canany' => ['producto almacenes'],
                ],
                [
                    'name' => 'Popup',
                    'icon' => 'fa-light fa-window-restore',
                    'urlName' => 'admin.setting.popup',
                    'active' => 'admin.setting.popup',
                    'canany' => ['popup'],
                ],
                [
                    'name' => 'Configurator PC',
                    'icon' => 'fa-light fa-computer',
                    'urlName' => 'admin.setting.configurator',
                    'active' => 'admin.setting.configurator',
                    'canany' => ['configurador'],
                ],
                [
                    'name' => 'Invoice',
                    'icon' => 'fa-light fa-file-invoice-dollar',
                    'urlName' => 'admin.setting.invoice.credential',
                    'active' => 'admin.setting.invoice.credential',
                    'canany' => ['facturas credenciales fiel'],
                ],
                [
                    'name' => 'Integrations',
                    'icon' => 'fa-light fa-truck-ramp-box',
                    'urlName' => 'admin.setting.integration.index',
                    'active' => 'admin.setting.integration.index',
                    'canany' => ['proveedor erp', 'proveedor pch', 'proveedor vadeto brands'],
                ],
            ],
        ],
    ],
    [
        'section' => [
            'name' => 'PAYMENTS',
            'modules' => [
                [
                    'name' => 'Currencies',
                    'icon' => 'fa-light fa-coins',
                    'urlName' => 'admin.setting.currency',
                    'active' => 'admin.setting.currency',
                    'canany' => ['monedas'],
                ],
                [
                    'name' => 'Access to payment gateways',
                    'icon' => 'fa-light fa-credit-card',
                    'urlName' => 'admin.setting.access-payment',
                    'active' => 'admin.setting.access-payment',
                    'canany' => ['pasarelas de pago'],
                ],
            ],
        ],
    ],
    [
        'section' => [
            'name' => 'WEB',
            'modules' => [
                [
                    'name' => 'Web',
                    'icon' => 'fa-light fa-browser',
                    'urlName' => 'admin.setting.contact',
                    'active' => 'admin.setting.contact',
                    'canany' => ['contacto'],
                ],
                [
                    'name' => 'Analytics tags',
                    'icon' => 'fa-light fa-chart-line',
                    'urlName' => 'admin.setting.tag-analytic',
                    'active' => 'admin.setting.tag-analytic',
                    'canany' => ['etiquetas analíticas'],
                ],
                [
                    'name' => 'Access to Mailchimp',
                    'icon' => 'fa-light fa-envelope-circle-check',
                    'urlName' => 'admin.setting.access-mailchimp',
                    'active' => 'admin.setting.access-mailchimp',
                    'canany' => ['accesos mailchimp'],
                ],
                [
                    'name' => 'Access to captcha',
                    'icon' => 'fa-light fa-shield-halved',
                    'urlName' => 'admin.setting.access-captcha',
                    'active' => 'admin.setting.access-captcha',
                    'canany' => ['accesos captcha'],
                ],
                [
                    'name' => 'Access to google',
                    'icon' => 'fa-light fa-magnifying-glass',
                    'urlName' => 'admin.setting.access-google',
                    'active' => 'admin.setting.access-google',
                    'canany' => ['accesos google'],
                ],
                [
                    'name' => 'Privacy notices',
                    'icon' => 'fa-light fa-user-lock',
                    'urlName' => 'admin.setting.privacy-notice.index',
                    'active' => 'admin.setting.privacy-notice',
                    'canany' => ['avisos de privacidad'],
                ],
            ],
        ],
    ],
];
