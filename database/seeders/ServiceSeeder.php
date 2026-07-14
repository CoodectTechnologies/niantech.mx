<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $locale = config('translatable.fallback');

        $categories = [
            [
                'name' => 'Bienes inmuebles y propiedad',
                'fragment' => "Trámites relacionados con la compra, venta, uso y regularización de bienes inmuebles.\n\nCompra-venta: Compra o venta de casas, terrenos o departamentos.",
                'body' => "Donación: Entrega gratuita de un inmueble a otra persona.\n\nPermuta: Intercambio de un inmueble por otro bien.\n\nDación en pago: Pago de una deuda mediante la entrega de un inmueble.\n\nTransmisiones de propiedad: Cambio legal de dueño de un bien.\n\nAdjudicaciones judiciales: Recepción legal de un inmueble por orden de un juez.\n\nFusión y subdivisión de predios: Unir o dividir terrenos legalmente.\n\nConstitución de régimen de condominio: Regularización de edificios o desarrollos en condominio.\n\nDisolución de copropiedad: Separación de un bien que pertenece a varias personas.\n\nDiligencias de apeo y deslinde: Definición de límites y colindancias de un terreno.\n\nReversión: Recuperación de un bien bajo condiciones previamente acordadas.",
                'image' => ['assets/web/images/services/1.png', 'service/1.png'],
            ],
            [
                'name' => 'Créditos, garantías y obligaciones',
                'fragment' => "Servicios relacionados con préstamos, deudas y garantías.\n\nApertura de crédito: Formalización de créditos o financiamientos.",
                'body' => "Contrato de mutuo: Préstamo de dinero entre personas.\n\nReconocimiento de adeudo: Documento donde se acepta una deuda.\n\nConstitución de hipoteca, fianza y/o gravamen: Garantías para asegurar el pago de una deuda.\n\nCancelación de hipoteca, fianza y/o gravamen: Liberación de un inmueble ya pagado.",
                'image' => ['assets/web/images/services/2.png', 'service/2.png'],
            ],
            [
                'name' => 'Familia, personas y voluntad',
                'fragment' => "Trámites relacionados con la vida personal, familiar y decisiones importantes.\n\nCapitulaciones matrimoniales: Acuerdos sobre bienes antes o durante el matrimonio.",
                'body' => "Divorcios: Trámite notarial de divorcio.\n\nCarta permiso para menor: Autorización para que un menor viaje o realice trámites.\n\nTestamento: Documento donde se decide el destino de los bienes.\n\nJuicios sucesorios testamentarios e intestamentarios: Trámite de herencias con o sin testamento.\n\nDirectriz anticipada: Decisiones anticipadas sobre tratamientos médicos.\n\nDeclaración unilateral de voluntad: Manifestación legal de una decisión personal.",
                'image' => ['assets/web/images/services/3.png', 'service/3.png'],
            ],
            [
                'name' => 'Empresas y sociedades',
                'fragment' => "Servicios notariales para la creación y organización de empresas.\n\nConstitución de sociedades: Creación legal de empresas.",
                'body' => "Fusión o escisión de sociedades: Unión o separación de empresas.\n\nProtocolización de actas de asamblea: Formalización de acuerdos de socios.",
                'image' => ['assets/web/images/services/4.png', 'service/4.png'],
            ],
            [
                'name' => 'Contratos, convenios y actos legales',
                'fragment' => "Formalización de acuerdos entre personas.\n\nContrato de transacción: Acuerdo para resolver un conflicto.",
                'body' => "Convenio modificatorio: Cambios a un contrato ya firmado.\n\nRatificación de gestión oficiosa: Validación de actos realizados sin autorización previa.",
                'image' => ['assets/web/images/services/5.png', 'service/5.png'],
            ],
            [
                'name' => 'Poderes y representación',
                'fragment' => "Autorizaciones legales para actuar en nombre de otra persona.\n\nPoderes: Autorización para que alguien represente legalmente a otra persona.",
                'body' => 'Revocación de poder: Cancelación de un poder otorgado.',
                'image' => ['assets/web/images/services/6.webp', 'service/6.webp'],
            ],
            [
                'name' => 'Certificaciones y fe notarial',
                'fragment' => "Servicios donde el notario da certeza legal a documentos y hechos.\n\nFe de hechos: Certificación de hechos presenciados por el notario.",
                'body' => "Ratificación de firmas: Validación de firmas en documentos.\n\nCopias certificadas: Copias con validez legal de documentos notariales.\n\nEscritura aclaratoria: Aclaración de datos en escrituras existentes.\n\nEscritura rectificatoria: Corrección de errores en escrituras.",
                'image' => ['assets/web/images/services/7.webp', 'service/7.webp'],
            ],
            [
                'name' => 'Fideicomisos y beneficiarios',
                'fragment' => "Administración y protección de bienes y derechos.\n\nConstitución de fideicomisos: Administración de bienes para un fin específico.",
                'body' => "Constitución de cláusula de beneficiario: Designación de beneficiarios.\n\nConsolidación de cláusula de beneficiario: Confirmación de derechos del beneficiario.",
                'image' => ['assets/web/images/services/8.jpg', 'service/8.jpg'],
            ],
        ];

        foreach ($categories as $index => $cat) {
            $service = Service::create([
                'name' => [$locale => $cat['name']],
                'slug' => Str::slug($cat['name']),
                'fragment' => [$locale => $cat['fragment']],
                'body' => [$locale => nl2br($cat['body'])], // nl2br para mantener los saltos de línea en HTML
                'order' => ($index + 1),
                'meta_title' => [$locale => $cat['name'].' - Notaría Número 7'],
                'meta_description' => [$locale => Str::limit($cat['fragment'], 155)],
                'meta_keywords' => [$locale => 'notaría, servicios, '.$cat['name']],
            ]);
            $service->image()->create([
                'url' => mediaManagerSeeder($cat['image'][0], $cat['image'][1]),
                'main' => 1,
            ]);
        }

        Service::regenerateCache();
    }
}
