<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Event - {{ $event->name }}</title>
    <style>
        /* CSS Khusus PDF agar Rapi di A4 */
        body { 
            font-family: sans-serif; 
            font-size: 12px; /* Font agak diperkecil biar muat banyak */
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #333; /* Garis bawah kop */
            padding-bottom: 10px;
        }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header h2 { margin: 5px 0; font-size: 16px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f0f0f0; font-weight: bold; }
        
        /* Agar baris tabel tidak terpotong aneh saat pindah halaman */
        tr { page-break-inside: avoid; } 
        
        .status-done { color: green; font-weight: bold; }
        .status-pending { color: red; font-weight: bold; }
        
        .meta-info {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .meta-info ul { margin: 0; padding-left: 20px; }
        .footer {
            position: fixed;
            bottom: 0;
            right: 0;
            font-size: 10px;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Pertanggungjawaban</h1>
        <h2>{{ $event->name }}</h2>
        <p style="margin:0; font-size:12px;">Tanggal Pelaksanaan: {{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</p>
    </div>

    <h3>A. Ringkasan Eksekutif</h3>
    <div class="meta-info">
        <p style="margin-top:0;"><strong>Deskripsi:</strong> {{ $event->description }}</p>
        <ul>
            {{-- Pastikan relasi 'creator' ada di Model Event, atau ganti jadi 'users->first()' --}}
            <li><strong>Ketua Pelaksana:</strong> {{ Auth::user()->name }}</li> 
            <li><strong>Total Anggota:</strong> {{ $totalMembers }} Orang</li>
            <li><strong>Progress Pekerjaan:</strong> {{ $progress }}% ({{ $completedTasks }} selesai dari {{ $totalTasks }} tugas)</li>
        </ul>
    </div>

    <h3>B. Tim Pelaksana</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Peran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    {{ ucfirst($user->pivot->role) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>C. Detail Tugas Lapangan</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Tugas</th>
                <th width="20%">Penanggung Jawab</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->tasks as $task)
            <tr>
                <td>
                    <strong>{{ $task->title }}</strong><br>
                    <span style="font-size: 10px; color: #555;">{{ $task->description }}</span>
                    @if($task->completion_note)
                        <br><i style="font-size: 10px; color: blue;">Note: {{ $task->completion_note }}</i>
                    @endif
                </td>
                <td>{{ $task->user->name ?? '-' }}</td>
                <td class="{{ $task->is_done ? 'status-done' : 'status-pending' }}">
                    {{ $task->is_done ? 'SELESAI' : 'PENDING' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Sukoharjo, {{ date('d F Y') }}</p>
        <br><br><br>
        <p><strong>{{ Auth::user()->name }}</strong><br>Ketua Panitia</p>
    </div>

    <div class="footer">
        Dicetak otomatis oleh sistem pada: {{ date('d-m-Y H:i:s') }}
    </div>

</body>
</html>