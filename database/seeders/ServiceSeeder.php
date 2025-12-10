<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'servicio' => 'Corte de Cabello Hombre',
                'descripcion' => 'Corte moderno, clásico y con estilo personalizado.',
                'precio' => 120,
                'img' => 'assets/images/services/corte-hombre.webp',
                'created_at' => '2025-12-09 18:30:39',
                'updated_at' => '2025-12-09 18:30:39',
            ],
            [
                'servicio' => 'Corte de Cabello Mujer',
                'descripcion' => 'Corte a la moda adaptado a tu estilo único.',
                'precio' => 120,
                'img' => 'assets/images/services/corte-mujer.webp',
                'created_at' => '2025-12-09 18:30:39',
                'updated_at' => '2025-12-09 18:30:39',
            ],
            [
                'servicio' => 'Corte de Cabello Niño',
                'descripcion' => 'Corte cómodo y divertido para los más pequeños.',
                'precio' => 120,
                'img' => 'assets/images/services/corte-nino.webp',
                'created_at' => '2025-12-09 18:30:39',
                'updated_at' => '2025-12-09 18:30:39',
            ],
            [
                'servicio' => 'Afeitado de Barba',
                'descripcion' => 'Afeitado a navaja con toalla caliente.',
                'precio' => 100,
                'img' => 'assets/images/services/afeitado.webp',
                'created_at' => '2025-12-09 18:30:39',
                'updated_at' => '2025-12-09 18:30:39',
            ],
            [
                'servicio' => 'Tinte de Cabello',
                'descripcion' => 'Coloración profesional de alta calidad.',
                'precio' => 250,
                'img' => 'assets/images/services/tinte.webp',
                'created_at' => '2025-12-09 18:30:39',
                'updated_at' => '2025-12-09 18:30:39',
            ],
        ]);
    }
}
