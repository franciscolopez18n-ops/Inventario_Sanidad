<!DOCTYPE html>
<html>
<head>
    <title>Imprimir QR</title>

    <style>
        body {
            font-family: Arial;
            margin: 0;
            padding: 20px;
        }

        .qr-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .qr-item {
            text-align: center;
            border: 1px solid #ccc;
            padding: 10px;
            page-break-inside: avoid;
        }

        img {
            width: 120px;
            height: 120px;
        }

        .name {
            font-size: 12px;
            font-weight: bold;
        }

        .location {
            font-size: 11px;
            color: gray;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

<div class="qr-grid">
    @foreach ($storages as $storage)
        <div class="qr-item">
            <img src="{{ route('qr.show', basename($storage->qr_path)) }}">
            <div class="name">{{ $storage->material->name }}</div>
            <div class="location">
                {{ $storage->storage === 'CAE' ? 'CAE' : 'Odontología' }}
            </div>
        </div>
    @endforeach
</div>

</body>
</html>