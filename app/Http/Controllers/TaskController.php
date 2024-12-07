<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan task milik user yang login.
     */
    public function index(Request $request)
{
    // Ambil query pencarian dari input
    $search = $request->input('search');

    // Ambil tugas milik user yang sedang login dan berdasarkan pencarian
    $tasks = Task::where('user_id', auth()->id())
        ->when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
        })
        ->get();

    // Kirim data tugas ke view
    return view('tasks.index', compact('tasks'));
}

    /**
     * Menyimpan task baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
        ]);

        // Simpan tugas ke dalam database
        Task::create([
            'name' => $request->name,       // Menyimpan nama pengguna
            'title' => $request->title,     // Menyimpan judul tugas
            'user_id' => auth()->id(),      // Menghubungkan tugas dengan pengguna yang login
            'is_completed' => false,        // Status awal tugas belum selesai
        ]);

        return redirect('/dashboard');
    }

    /**
     * Memperbarui status task.
     */
    public function update(Request $request, Task $task)
    {
        // Pastikan hanya user yang membuat task bisa memperbarui
        if ($task->user_id !== auth()->id()) {
            return redirect('/dashboard')->withErrors('Unauthorized action.');
        }

        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        // Perbarui data
        $task->update([
            'title' => $request->input('title'),
            'name' => $request->input('name'),
        ]);

        return redirect('/dashboard')->with('success', 'Task updated successfully.');
    }

    /**
     * Menghapus task.
     */
    public function destroy(Task $task)
    {
        // Memastikan hanya user yang membuat task yang bisa menghapusnya
        if ($task->user_id !== auth()->id()) {
            return redirect('/dashboard')->withErrors('Unauthorized action.');
        }

        $task->delete();
        return redirect('/dashboard');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        // Validasi input data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Mencoba login
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/dashboard');
        }

        return back()->withErrors(['message' => 'Login gagal, coba lagi.']);
    }

    /**
     * Proses logout.
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    /**
     * Metode untuk ekspor data ke PDF.
     */
    public function exportPdf()
    {
        $tasks = Task::where('user_id', auth()->id())->get(); // Ambil data milik user login

        // Generate PDF dari tampilan
        $pdf = Pdf::loadView('tasks.pdf', compact('tasks'));

        // Unduh file PDF
        return $pdf->download('tasks.pdf');
    }

    public function edit(Task $task)
    {
        // Pastikan hanya user yang membuat task bisa mengedit
        if ($task->user_id !== auth()->id()) {
            return redirect('/dashboard')->withErrors('Unauthorized action.');
        }

        return view('tasks.edit', compact('task'));
    }
}
