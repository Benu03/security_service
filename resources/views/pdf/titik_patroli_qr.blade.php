<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Checkpoint - {{ $data->location }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 40px;
        }
        h2 {
            margin-bottom: 10px;
        }
        .qr-wrapper {
            margin: 20px auto;
            display: inline-block;
            padding: 15px;
            border: 2px dashed #333;
            border-radius: 10px;
        }
        .checkpoint-code {
            margin-top: 15px;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .location {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <h2>Checkpoint QR Code</h2>
    <div class="location">{{ $data->location }}</div>

    <div class="qr-wrapper">
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
    </div>

    <div class="checkpoint-code">
        {{ $data->checkpoint_code }}
    </div>
</body>
</html>
