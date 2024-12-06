<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Menampilkan halaman dashboard dengan task milik user yang login
    public function index()
    {
        // Ambil data tugas milik user yang sedang login
        $tasks = Task::where('user_id', auth()->id())->get();

        // Kirim data tugas ke view
        return view('tasks.index', compact('tasks'));
    }

    // Menyimpan task baru
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

    // Memperbarui status task
    public function update(Request $request, Task $task)
    {
        // Memastikan hanya user yang membuat task yang bisa memperbaruinya
        if ($task->user_id !== auth()->id()) {
            return redirect('/dashboard')->withErrors('Unauthorized action.');
        }

        $task->update([
            'is_completed' => $request->boolean('is_completed', false), // Default ke false
        ]);

        return redirect('/dashboard');
    }

    // Menghapus task
    public function destroy(Task $task)
    {
        // Memastikan hanya user yang membuat task yang bisa menghapusnya
        if ($task->user_id !== auth()->id()) {
            return redirect('/dashboard')->withErrors('Unauthorized action.');
        }

        $task->delete();
        return redirect('/dashboard');
    }

    // Proses login
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

    // Proses logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
