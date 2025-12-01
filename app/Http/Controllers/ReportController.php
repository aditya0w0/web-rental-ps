<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Rental;
use App\Exports\ReportsExport;
use Illuminate\Support\Facades\View;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $sales = Order::where('status', 'completed')
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->get();

        $rentals = Rental::where('status', 'completed')
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('start_time', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('start_time', '<=', $endDate);
            })
            ->get();

        return view('reports.index', compact('sales', 'rentals', 'startDate', 'endDate'));
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', $data);
            return $pdf->download('report.pdf');
        }
        $html = $this->buildPdfHtml($data);
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="report.html"',
        ]);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);
        if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            return \Maatwebsite\Excel\Facades\Excel::download(new ReportsExport($data), 'report.xlsx');
        }
        $html = $this->buildExcelHtml($data);
        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="report.xls"',
        ]);
    }

    private function getReportData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $sales = Order::where('status', 'completed')
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->get();

        $rentals = Rental::where('status', 'completed')
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('start_time', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('start_time', '<=', $endDate);
            })
            ->get();

        return [
            'sales' => $sales,
            'rentals' => $rentals,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    private function buildCsv(array $data): string
    {
        $out = fopen('php://temp', 'r+');
        fputcsv($out, ['Sales Report']);
        fputcsv($out, ['Order ID','Date','Customer','Total']);
        foreach ($data['sales'] as $sale) {
            fputcsv($out, [
                $sale->order_number,
                optional($sale->created_at)->format('d M Y'),
                optional($sale->user)->name,
                $sale->total_price,
            ]);
        }
        fputcsv($out, []);
        fputcsv($out, ['Rental Report']);
        fputcsv($out, ['Rental ID','Start Date','End Date','Customer','Total']);
        foreach ($data['rentals'] as $r) {
            fputcsv($out, [
                $r->id,
                optional($r->start_time)->format('d M Y'),
                optional($r->end_time)->format('d M Y'),
                optional($r->user)->name,
                $r->total_price,
            ]);
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);
        return $csv;
    }

    private function buildPdfHtml(array $data): string
    {
        $html = '<html><head><meta charset="UTF-8"><title>Sales and Rental Report</title><style>table{border-collapse:collapse;width:100%}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#f2f2f2}h1,h2{text-align:center}.date-range{text-align:center;margin-bottom:20px}</style></head><body>';
        $html .= '<h1>Sales and Rental Report</h1>';
        if (!empty($data['startDate']) && !empty($data['endDate'])) {
            $html .= '<div class="date-range"><strong>From:</strong> '.(new \Carbon\Carbon($data['startDate']))->format('d M Y').' <strong>To:</strong> '.(new \Carbon\Carbon($data['endDate']))->format('d M Y').'</div>';
        }
        $html .= '<h2>Sales Report</h2><table><thead><tr><th>Order ID</th><th>Date</th><th>Customer</th><th>Total</th></tr></thead><tbody>';
        foreach (($data['sales'] ?? []) as $s) {
            $html .= '<tr><td>'.($s->order_number).'</td><td>'.(optional($s->created_at)->format('d M Y')).'</td><td>'.(optional($s->user)->name).'</td><td>Rp '.number_format($s->total_price,0,',','.').'</td></tr>';
        }
        if (empty($data['sales']) || count($data['sales'])===0) {
            $html .= '<tr><td colspan="4" style="text-align:center">No sales data available.</td></tr>';
        }
        $html .= '</tbody></table>';
        $html .= '<h2>Rental Report</h2><table><thead><tr><th>Rental ID</th><th>Start Date</th><th>End Date</th><th>Customer</th><th>Total</th></tr></thead><tbody>';
        foreach (($data['rentals'] ?? []) as $r) {
            $html .= '<tr><td>'.$r->id.'</td><td>'.(optional($r->start_time)->format('d M Y')).'</td><td>'.(optional($r->end_time)->format('d M Y')).'</td><td>'.(optional($r->user)->name).'</td><td>Rp '.number_format($r->total_price,0,',','.').'</td></tr>';
        }
        if (empty($data['rentals']) || count($data['rentals'])===0) {
            $html .= '<tr><td colspan="5" style="text-align:center">No rental data available.</td></tr>';
        }
        $html .= '</tbody></table></body></html>';
        return $html;
    }

    private function buildExcelHtml(array $data): string
    {
        $html = '<html><head><meta charset="UTF-8"><style>table{border-collapse:collapse;width:100%}th,td{border:1px solid #ddd;padding:6px;text-align:left}th{background:#f2f2f2}h2{margin:10px 0}</style></head><body>';
        $html .= '<h2>Sales Report</h2><table><thead><tr><th>Order ID</th><th>Date</th><th>Customer</th><th>Total</th></tr></thead><tbody>';
        foreach (($data['sales'] ?? []) as $s) {
            $html .= '<tr><td>'.($s->order_number).'</td><td>'.(optional($s->created_at)->format('d M Y')).'</td><td>'.(optional($s->user)->name).'</td><td>'.($s->total_price).'</td></tr>';
        }
        if (empty($data['sales']) || count($data['sales'])===0) {
            $html .= '<tr><td colspan="4" style="text-align:center">No sales data available.</td></tr>';
        }
        $html .= '</tbody></table>';
        $html .= '<h2>Rental Report</h2><table><thead><tr><th>Rental ID</th><th>Start Date</th><th>End Date</th><th>Customer</th><th>Total</th></tr></thead><tbody>';
        foreach (($data['rentals'] ?? []) as $r) {
            $html .= '<tr><td>'.$r->id.'</td><td>'.(optional($r->start_time)->format('d M Y')).'</td><td>'.(optional($r->end_time)->format('d M Y')).'</td><td>'.(optional($r->user)->name).'</td><td>'.($r->total_price).'</td></tr>';
        }
        if (empty($data['rentals']) || count($data['rentals'])===0) {
            $html .= '<tr><td colspan="5" style="text-align:center">No rental data available.</td></tr>';
        }
        $html .= '</tbody></table></body></html>';
        return $html;
    }
}