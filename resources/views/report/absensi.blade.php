<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <h1>{{ $acara }}</h1>
    <h2>{{ $agenda }}</h2>

    <p>Dicetak Pada : {{ $date }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Divisi</th>
            </tr>
        </thead>
        <tbody>
           @foreach($absensi as $idx => $abs)
           <tr>
                <td>{{ $idx + 1}}</td>
                <td>{{$abs->name}}</td>
           </tr>
           @endforeach
        </tbody>
    </table>
</body>
</html>