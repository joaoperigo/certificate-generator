<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certificate;
use App\Models\CertificateStudent;

class CertificateStudentSeeder extends Seeder
{

    public function run()
    {
        // Lendo os dados do JSON
        $jsonData = file_get_contents(database_path('seeders/certificates.json'));
        $records = json_decode($jsonData, true);

        if (!$records) {
            die("Erro ao carregar os dados do JSON.");
        }

        $defaultData = '{"title": "asd", "pages": [{"backgroundImage": null, "objects": []}]}';

        foreach ($records as $record) {
            // Criar ou buscar Certificate pelo título (course_name)
            $certificate = Certificate::firstOrCreate(
                ['title' => $record['course_name']],  // Busca por course_name no title
                [
                    'quantity_hours' => $record['course_hours'] ?? null,
                    'data' => $defaultData // Atribui o valor padrão para 'data'
                ]
            );

            // Criar CertificateStudent vinculado ao Certificate
            CertificateStudent::create([
                'certificate_id' => $certificate->id,
                'name' => $record['student_name'],
                'code' => $record['certificate_code'],
                'cpf' => $record['cpf'] ?? null,
                'document' => $record['document'] ?? null,
                'unit' => $record['unit'] ?? null,
                'course' => $certificate->title, // Course do certificado
                'quantity_hours' => $certificate->quantity_hours, // Quantity_hours do certificado
                'start_date' => $record['dob'], // A data de nascimento como start_date
                'end_date' => $record['award_date'], // A data de premiação como end_date
            ]);
        }

        echo "Seeders concluídos com sucesso!";
    }

}
