<!DOCTYPE html>
<html>
<head>
    <title>Imprimir QR</title>

    <style>
        /* PARA LA PANTALLA */
        body {
            margin: 0;
            padding: 15px;
        }

        .qr-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            width: 100%;
        }

        .qr-item {
            text-align: center;
            border: 1px solid #ccc;
            padding: 10px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .qr-item img {
            width: 100%;
            max-width: 120px;
            height: auto;
        }

        .name {
            font-size: 12px;
            font-weight: bold;
            margin-top: 6px;
        }

        .lugar {
            font-size: 11px;
            color: gray;
            margin-top: 2px;
        }

        /* TABLET */
        @media screen and (max-width: 1000px) {
            .qr-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* MÓVIL */
        @media screen and (max-width: 600px) {
            .qr-grid {
                grid-template-columns: 1fr;
            }
        }
        /* ---------------------------------------------------- */
        /* IMPRESIÓN */
        @media print {
            body {
                padding: 0;
            }
            .qr-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
            }

            .qr-item {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            img {
                max-width: 100px;
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
         
            <div class="lugar">
                @if($storage->storage === 'CAE')
                    CAE
                @elseif($storage->storage === 'odontology')
                    Odontología
                @endif
            </div>
        </div>
    @endforeach
</div>

</body>
</html>