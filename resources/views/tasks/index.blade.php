<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Task Manager</h1>

        <!-- Tombol Export -->
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('tasks.export') }}" class="btn btn-success me-2">
                <i class="bi bi-file-earmark-excel"></i> Export to Excel
            </a>
            <a href="{{ route('tasks.export-pdf') }}" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> Export to PDF
            </a>
        </div>

        <!-- Form Pencarian -->
        <div class="mb-4">
            <form action="{{ route('tasks.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari tugas..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        <!-- Form Tambah Task -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="/tasks" method="POST" class="d-flex flex-column gap-2">
                    @csrf
                    <input type="text" name="name" class="form-control" placeholder="Masukan Nama" required>
                    <input type="text" name="title" class="form-control" placeholder="Masukan Tugas" required>
                    <button type="submit" class="btn btn-primary mt-2">Tambah Task</button>
                </form>
            </div>
        </div>

        <!-- Daftar Tasks -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Task Anda</h5>
            </div>
            <div class="card-body">
                @if ($tasks->isEmpty())
                    <p class="text-muted">Tidak ada task yang tersedia. Mulai tambahkan beberapa!</p>
                @else
                    <ul class="list-group">
                        @foreach ($tasks as $task)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <form action="/tasks/{{ $task->id }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="checkbox" class="form-check-input me-2"
                                            onchange="this.form.submit()" 
                                            {{ $task->is_completed ? 'checked' : '' }} 
                                            name="is_completed" 
                                            value="{{ !$task->is_completed }}">
                                    </form>
                                    <div>
                                        <strong>{{ $task->name }}:</strong>
                                        <span class="{{ $task->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                                            {{ $task->title }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm me-2">
                                        Edit
                                    </a>
                                    <!-- Tombol Delete -->
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-task-id="{{ $task->id }}">
                                        Delete
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus tugas ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Menghubungkan tombol Delete ke modal
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');

        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang memicu modal
            const taskId = button.getAttribute('data-task-id'); // Ambil ID tugas
            deleteForm.setAttribute('action', `/tasks/${taskId}`); // Set URL form
        });
    </script>
</body>
</html>
