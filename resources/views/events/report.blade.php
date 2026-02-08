<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Event - {{ $event->name }}</title>
    <style>
        @page { margin: 2.5cm; }
        body { 
            font-family: "Times New Roman", Times, serif; 
            font-size: 12pt; 
            line-height: 1.5;
            color: #000;
        }

        /* KOP SURAT */
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            position: relative;
        }
        .header h1 { 
            margin: 0; 
            font-size: 16pt; 
            font-weight: bold; 
            text-transform: uppercase; 
        }
        .header h2 { 
            margin: 0; 
            font-size: 14pt; 
            font-weight: bold; 
            margin-bottom: 5px;
        }
        .header p { 
            margin: 0; 
            font-size: 10pt; 
            font-style: italic; 
        }
        .line-thick { border-bottom: 3px solid #000; margin-top: 10px; }
        .line-thin { border-bottom: 1px solid #000; margin-top: 2px; margin-bottom: 25px; }

        /* TABEL DATA */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
            font-size: 11pt;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 8px; 
            text-align: left; 
            vertical-align: top; 
        }
        th { 
            background-color: #e0e0e0; 
            font-weight: bold; 
            text-align: center;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }

        /* STATUS BADGE */
        .badge-done { color: #155724; font-weight: bold; font-size: 10pt; }
        .badge-pending { color: #721c24; font-weight: bold; font-size: 10pt; }

        /* INFO TABLE */
        .info-table { border: none; margin-bottom: 20px; }
        .info-table td { border: none; padding: 4px 0; width: auto; }
        .label { width: 180px; font-weight: bold; }
        .colon { width: 20px; text-align: center; }

        /* FOOTER & SIGNATURE */
        .signature-section { margin-top: 50px; width: 100%; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .signature-space { height: 80px; }
        .footer {
            position: fixed; bottom: -30px; left: 0; right: 0;
            font-size: 9pt; color: #666; text-align: right;
            border-top: 1px solid #ccc; padding-top: 5px;
        }
    </style>
</head>
<body>

    {{-- ======================================================== --}}
    {{-- LOGIKA UTAMA PERBAIKAN BUG DISINI --}}
    {{-- ======================================================== --}}
    @php
        // 1. Ambil Nama Ketua (Pembuat Event)
        $ketuaName = $event->user->name ?? 'Administrator'; 

        // 2. FILTER USER AKTIF
        // Kita saring agar user yang 'trashed' (dihapus) TIDAK IKUT DIHITUNG
        // Jika kamu punya kolom status lain (misal: 'status' => 'accepted'), tambahkan di sini.
        $activeMembers = $event->users->filter(function($member) {
            // Pastikan member ada datanya DAN tidak di-soft delete
            return $member && !$member->trashed();
        });

        // 3. Hitung Total berdasarkan hasil filter di atas
        $totalActiveMembers = $activeMembers->count();
    @endphp

    <div class="header">
        <h1>LAPORAN PERTANGGUNGJAWABAN (LPJ)</h1>
        <h2>{{ strtoupper($event->name) }}</h2>
        <p>Lokasi: {{ $event->location }} | Tanggal: {{ \Carbon\Carbon::parse($event->date ?? $event->event_date)->isoFormat('D MMMM Y') }}</p>
        <div class="line-thick"></div>
        <div class="line-thin"></div>
    </div>

    <div class="content">
        <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px;">A. DATA KEGIATAN</h3>
        
        <table class="info-table">
            <tr>
                <td class="label">Nama Kegiatan</td>
                <td class="colon">:</td>
                <td>{{ $event->name }}</td>
            </tr>
            <tr>
                <td class="label">Ketua Pelaksana</td>
                <td class="colon">:</td>
                <td>{{ $ketuaName }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Pelaksanaan</td>
                <td class="colon">:</td>
                <td>{{ \Carbon\Carbon::parse($event->date ?? $event->event_date)->isoFormat('dddd, D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="label">Total Anggota Panitia</td>
                <td class="colon">:</td>
                {{-- GUNAKAN VARIABEL YANG SUDAH DI-FILTER --}}
                <td><strong>{{ $totalActiveMembers }} Orang</strong> (Aktif)</td>
            </tr>
            <tr>
                <td class="label">Status Penyelesaian</td>
                <td class="colon">:</td>
                <td>
                    @php
                        $totalTasks = $event->tasks->count();
                        $completedTasks = $event->tasks->where('is_done', true)->count();
                        $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    @endphp
                    {{ $percentage }}% ({{ $completedTasks }} dari {{ $totalTasks }} Tugas Selesai)
                </td>
            </tr>
        </table>
        
        <div style="margin-bottom: 20px;">
            <strong>Deskripsi Kegiatan:</strong><br>
            <p style="text-align: justify; margin-top: 5px;">
                {{ $event->description ?? 'Tidak ada deskripsi detail.' }}
            </p>
        </div>

        {{-- DAFTAR ANGGOTA (DITAMBAHKAN AGAR LEBIH JELAS) --}}
        <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-top: 30px;">B. DAFTAR ANGGOTA TIM</h3>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Anggota</th>
                    <th>Email</th>
                    <th>Peran</th>
                </tr>
            </thead>
            <tbody>
                {{-- LOOPING MENGGUNAKAN $activeMembers --}}
                @forelse($activeMembers as $index => $member)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ ucfirst($member->pivot->role ?? 'Anggota') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada anggota tim tambahan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>


        <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-top: 30px;">C. LAPORAN TUGAS & PROGRES</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 40%;">Nama Tugas / Aktivitas</th>
                    <th style="width: 25%;">Penanggung Jawab (PIC)</th>
                    <th style="width: 15%;">Tenggat</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($event->tasks as $index => $task)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $task->title }}</strong>
                        @if($task->completion_note)
                            <br><small style="color: #666; font-style: italic;">Catatan: {{ $task->completion_note }}</small>
                        @endif
                    </td>
                    {{-- Pastikan nama PIC aman dari user terhapus --}}
                    <td>{{ $task->user && !$task->user->trashed() ? $task->user->name : 'User Dihapus' }}</td>
                    <td>
                        {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') : '-' }}
                    </td>
                    <td style="text-align: center;">
                        @if($task->is_done)
                            <span class="badge-done">SELESAI</span>
                        @else
                            <span class="badge-pending">PENDING</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; font-style: italic;">Belum ada data tugas yang tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- AREA TANDA TANGAN --}}
        <div class="signature-section">
            <div class="signature-box">
                <p>Sukoharjo, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                <p>Ketua Pelaksana,</p>
                <div class="signature-space"></div>
                <p style="font-weight: bold; text-decoration: underline;">{{ strtoupper($ketuaName) }}</p>
            </div>
            <div style="clear: both;"></div>
        </div>

    </div>

    <div class="footer">
        Dokumen ini dicetak otomatis melalui Sistem Manajemen Event pada {{ date('d/m/Y H:i') }} WIB.
    </div>

</body>
</html>