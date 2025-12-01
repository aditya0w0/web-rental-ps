<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportsExport implements WithMultipleSheets
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new SalesSheet($this->data['sales'] ?? collect()),
            new RentalsSheet($this->data['rentals'] ?? collect()),
        ];
    }
}

class SalesSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    public function __construct(private Collection $sales) {}

    public function collection(): Collection
    {
        return $this->sales->map(function($s){
            return [
                'Order ID' => $s->order_number,
                'Date' => optional($s->created_at)->format('d M Y'),
                'Customer' => optional($s->user)->name,
                'Total' => $s->total_price,
            ];
        });
    }

    public function headings(): array
    {
        return ['Order ID','Date','Customer','Total'];
    }

    public function title(): string
    {
        return 'Sales';
    }
}

class RentalsSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    public function __construct(private Collection $rentals) {}

    public function collection(): Collection
    {
        return $this->rentals->map(function($r){
            return [
                'Rental ID' => $r->id,
                'Start Date' => optional($r->start_time)->format('d M Y'),
                'End Date' => optional($r->end_time)->format('d M Y'),
                'Customer' => optional($r->user)->name,
                'Total' => $r->total_price,
            ];
        });
    }

    public function headings(): array
    {
        return ['Rental ID','Start Date','End Date','Customer','Total'];
    }

    public function title(): string
    {
        return 'Rentals';
    }
}