<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Tamu {{ $month }}</title>
    <style>
        body { font-family: Calibri, Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #d9e2f3; }
    </style>
</head>
<body>
    <h3>Buku Tamu - {{ $month }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>NISN/NIP</th>
                <th>Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($visits as $i => $visit)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $visit->visited_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $visit->nama }}</td>
                    <td>{{ ucfirst($visit->visitor_type) }}</td>
                    <td>{{ $visit->identifier }}</td>
                    <td>{{ $visit->kelas ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
