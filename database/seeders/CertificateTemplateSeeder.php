<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CertificateTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('certificate_templates')->insert([
            [
                'name' => 'Type 1 - Two Pages',
                'description' => 'Complete certificate with student data page',
                'objects' => json_encode([
                    'page1' => [
                        // Add the same objects as defined in the Vue component
                    ],
                    'page2' => [
                        // Add the same objects as defined in the Vue component
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Type 1 - Two Pages',
                'description' => 'Complete certificate with student data page',
                'objects' => json_encode([
                    'page1' => [
                        // Add the same objects as defined in the Vue component
                    ],
                    'page2' => [
                        // Add the same objects as defined in the Vue component
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Type 1 - Two Pages',
                'description' => 'Complete certificate with student data page',
                'objects' => json_encode([
                    'page1' => [
                        // Add the same objects as defined in the Vue component
                    ],
                    'page2' => [
                        // Add the same objects as defined in the Vue component
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
