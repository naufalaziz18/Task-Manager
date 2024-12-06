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

        <!-- Form Tambah Task -->
        <div class="card mb-4">
            <div class="card-body">
            <form action="/tasks" method="POST" class="d-flex flex-column gap-2">
                @csrf
                <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                <input type="text" name="title" class="form-control" placeholder="Enter task title" required>
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
                            <form action="/tasks/{{ $task->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </li>
                    @endforeach
                </ul>

                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
