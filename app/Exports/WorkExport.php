<?php

namespace App\Exports;

use App\Models\Work;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorkExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Work::latest('id')->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Nombre', 'Apellidos', 'Correo electrónico', 'WhatsApp', 'Cargo o posición',
            'Institución', 'Ciudad', 'País', 'Título', 'Área', 'Otra área', 'Resumen', 'Estado', 'Fecha de registro',
        ];
    }

    public function map($work): array
    {
        return [
            $work->id,
            $work->first_name,
            $work->last_name,
            $work->email,
            $work->whatsapp_phone,
            $work->current_position,
            $work->institution,
            $work->city,
            $work->country,
            $work->title,
            $work->area,
            $work->area_other,
            $work->abstract,
            $work->status,
            $work->created_at,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4361EE']],
        ]);

        foreach (range('A', 'O') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(35);
        $sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(45);
        $sheet->getColumnDimension('M')->setAutoSize(false)->setWidth(70);

        return [];
    }
}
