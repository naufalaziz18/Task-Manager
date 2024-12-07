<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TasksExport implements FromCollection, WithHeadings
{
    /**
     * Mengambil data tugas dari database.
     */
    public function collection()
    {
        return Task::select('id', 'name', 'title', 'is_completed', 'created_at')->get();
    }

    /**
     * Menambahkan header untuk file Excel.
     */
    public function headings(): array
    {
        return ['ID', 'Name', 'Title', 'Completed', 'Created At'];
    }
}

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Barryvdh\DomPDF\Facade\Pdf;

class TaskController extends Controller
{
    /**
     * Metode untuk ekspor data ke PDF.
     */
    public function exportPdf()
    {
        $tasks = Task::all();

        // Generate PDF dari tampilan
        $pdf = Pdf::loadView('tasks.pdf', compact('tasks'));

        // Unduh file PDF
        return $pdf->download('tasks.pdf');
    }
}
