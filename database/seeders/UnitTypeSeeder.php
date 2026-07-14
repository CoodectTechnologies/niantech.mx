<?php

namespace Database\Seeders;

use App\Models\UnitType;
use Illuminate\Database\Seeder;

class UnitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $unitTypes = [
            [
                'name' => 'Pieza',
                'code' => 'H87',
                'description' => 'Unidad utilizada para contar objetos individuales.'],
            [
                'name' => 'Servicio',
                'code' => 'E48',
                'description' => 'Unidades específicas de la industria (varias)'],
            [
                'name' => 'Docena',
                'code' => 'DZN',
                'description' => 'Unidad de cantidad igual a doce.'],
            [
                'name' => 'Gramo',
                'code' => 'GRM',
                'description' => 'Unidad de masa en el Sistema Internacional de Unidades.'],
            [
                'name' => 'Kilogramo',
                'code' => 'KGM',
                'description' => 'Unidad de masa en el Sistema Internacional de Unidades.'],
            [
                'name' => 'Miligramo',
                'code' => 'MGM',
                'description' => 'Unidad de masa en el Sistema Internacional de Unidades, igual a la milésima parte de un gramo.'],
            [
                'name' => 'Litro',
                'code' => 'LTR',
                'description' => 'Unidad de volumen igual a 1 decímetro cúbico.'],
            [
                'name' => 'Mililitro',
                'code' => 'MLT',
                'description' => 'Unidad de volumen en el Sistema Internacional de Unidades, igual a la milésima parte de un litro.'],
            [
                'name' => 'Centímetro',
                'code' => 'CM',
                'description' => 'Unidad de longitud en el Sistema Internacional de Unidades.'],
            [
                'name' => 'Metro',
                'code' => 'MTR',
                'description' => 'Unidad de longitud en el Sistema Internacional de Unidades.'],
            [
                'name' => 'Milímetro',
                'code' => 'MMT',
                'description' => 'Unidad de longitud en el Sistema Internacional de Unidades, igual a la milésima parte de un metro.'],
            [
                'name' => 'Pulgada',
                'code' => 'INH',
                'description' => 'Unidad de longitud utilizada en el sistema de medidas inglés.'],
            [
                'name' => 'Metro cuadrado',
                'code' => 'MTK',
                'description' => 'Unidad de superficie en el Sistema Internacional de Unidades.'],
            [
                'name' => 'Metro cúbico',
                'code' => 'M3',
                'description' => 'Unidad de volumen en el Sistema Internacional de Unidades.'],
            [
                'name' => 'Hora',
                'code' => 'HUR',
                'description' => 'Unidad de tiempo igual a 60 minutos.'],
            [
                'name' => 'Minuto',
                'code' => 'MIN',
                'description' => 'Unidad de tiempo igual a 60 segundos.'],
            [
                'name' => 'Segundo',
                'code' => 'SEC',
                'description' => 'Unidad de tiempo en el Sistema Internacional de Unidades.'],
        ];
        foreach ($unitTypes as $unitType) {
            UnitType::create($unitType);
        }
    }
}
