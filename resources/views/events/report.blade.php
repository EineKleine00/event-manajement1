<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Event - {{ $event->name }}</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .header { text-align: center; margin-bottom: 30px; }
        .status-done { color: green; font-weight: bold; }
        .status-pending { color: red; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>LAPORAN PERTANGGUNGJAWABAN</h1>
        <h2>{{ $event->name }}</h2>
        <p>Tanggal Pelaksanaan: {{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</p>
    </div>

    <hr>

    <h3>A. Ringkasan</h3>
    <p>{{ $event->description }}</p>
    <ul>
        <li><strong>Ketua Pelaksana:</strong> {{ $event->creator->name }}</li>
        <li><strong>Total Anggota:</strong> {{ $event->users->count() }} Orang</li>
        <li><strong>Progress Pekerjaan:</strong> {{ $progress }}% ({{ $completedTasks }} dari {{ $totalTasks }} tugas selesai)</li>
    </ul>

    <h3>B. Tim Pelaksana</h3>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Peran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->pivot->role) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>C. Detail Tugas</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Tugas</th>
                <th>Penanggung Jawab</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->user->name }}</td>
                <td class="{{ $task->is_done ? 'status-done' : 'status-pending' }}">
                    {{ $task->is_done ? 'SELESAI' : 'BELUM SELESAI' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
    </div>

</body>
</html>