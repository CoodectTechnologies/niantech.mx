<?php

namespace Database\Seeders;

use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $user = User::first();

        // Crear cuestionario de Lupus
        $questionnaire = Questionnaire::create([
            'user_id' => $user->id,
            'name' => 'Cuestionario de Pre-evaluación para Protocolo de Investigación sobre Lupus Eritematoso Sistémico',
            'description' => 'Este cuestionario tiene como objetivo identificar personas que puedan ser candidatas para participar en un protocolo de investigación sobre Lupus Eritematoso Sistémico (LES).',
            'status' => 'Publicado',
            'min_positive_percentage' => 60,
        ]);

        // Pregunta 1
        $question1 = $questionnaire->questions()->create([
            'question' => '¿Te han diagnosticado médicamente con Lupus Eritematoso Sistémico (LES)?',
            'type' => 'single',
            'order' => 0,
        ]);

        $question1->options()->create([
            'option_text' => 'Sí',
            'is_positive' => true,
            'order' => 0,
        ]);

        $question1->options()->create([
            'option_text' => 'No',
            'is_positive' => false,
            'order' => 1,
        ]);

        $question1->options()->create([
            'option_text' => 'No estoy seguro/a',
            'is_positive' => false,
            'order' => 2,
        ]);

        // Pregunta 2
        $question2 = $questionnaire->questions()->create([
            'question' => '¿Has sido evaluado/a por un reumatólogo por síntomas autoinmunes?',
            'type' => 'single',
            'order' => 1,
        ]);

        $question2->options()->create([
            'option_text' => 'Sí',
            'is_positive' => true,
            'order' => 0,
        ]);

        $question2->options()->create([
            'option_text' => 'No',
            'is_positive' => false,
            'order' => 1,
        ]);

        // Pregunta 3 (Múltiple selección)
        $question3 = $questionnaire->questions()->create([
            'question' => '¿Presentas o has presentado alguno de los siguientes síntomas de forma recurrente o crónica? (Marca todos los que apliquen)',
            'type' => 'multiple',
            'order' => 2,
        ]);

        $symptoms = [
            ['text' => 'Cansancio extremo sin causa aparente', 'positive' => true],
            ['text' => 'Dolor o inflamación en articulaciones', 'positive' => true],
            ['text' => 'Pérdida de cabello', 'positive' => true],
            ['text' => 'Erupciones en la piel (especialmente en mejillas y nariz)', 'positive' => true],
            ['text' => 'Fiebre sin causa conocida', 'positive' => true],
            ['text' => 'Sensibilidad al sol', 'positive' => true],
            ['text' => 'Llagas en la boca o nariz', 'positive' => true],
            ['text' => 'Hinchazón en piernas o párpados', 'positive' => true],
            ['text' => 'Dolor en el pecho al respirar profundo', 'positive' => true],
            ['text' => 'Ninguno de los anteriores', 'positive' => false],
        ];

        foreach ($symptoms as $index => $symptom) {
            $question3->options()->create([
                'option_text' => $symptom['text'],
                'is_positive' => $symptom['positive'],
                'order' => $index,
            ]);
        }

        // Pregunta 4
        $question4 = $questionnaire->questions()->create([
            'question' => '¿Te han realizado análisis de sangre con hallazgos como ANA positivos, anti-DNA, anti-Sm u otros autoanticuerpos?',
            'type' => 'single',
            'order' => 3,
        ]);

        $question4->options()->create([
            'option_text' => 'Sí',
            'is_positive' => true,
            'order' => 0,
        ]);

        $question4->options()->create([
            'option_text' => 'No',
            'is_positive' => false,
            'order' => 1,
        ]);

        $question4->options()->create([
            'option_text' => 'No sé / No recuerdo',
            'is_positive' => false,
            'order' => 2,
        ]);

        // Pregunta 5
        $question5 = $questionnaire->questions()->create([
            'question' => '¿Tienes familiares con enfermedades autoinmunes (como lupus, artritis reumatoide, esclerosis múltiple, etc.)?',
            'type' => 'single',
            'order' => 4,
        ]);

        $question5->options()->create([
            'option_text' => 'Sí',
            'is_positive' => true,
            'order' => 0,
        ]);

        $question5->options()->create([
            'option_text' => 'No',
            'is_positive' => false,
            'order' => 1,
        ]);

        $question5->options()->create([
            'option_text' => 'No sé',
            'is_positive' => false,
            'order' => 2,
        ]);

        // Pregunta 6
        $question6 = $questionnaire->questions()->create([
            'question' => '¿Estás interesado/a en participar en un protocolo de investigación sobre Lupus, si cumples con los criterios?',
            'type' => 'single',
            'order' => 5,
        ]);

        $question6->options()->create([
            'option_text' => 'Sí, me interesa',
            'is_positive' => true,
            'order' => 0,
        ]);

        $question6->options()->create([
            'option_text' => 'Tal vez, necesito más información',
            'is_positive' => false,
            'order' => 1,
        ]);

        $question6->options()->create([
            'option_text' => 'No',
            'is_positive' => false,
            'order' => 2,
        ]);

        // Regenerar cache
        Questionnaire::regenerateCache();

        $this->command->info('Cuestionario de Lupus creado exitosamente con 6 preguntas y múltiples opciones.');
    }
}
