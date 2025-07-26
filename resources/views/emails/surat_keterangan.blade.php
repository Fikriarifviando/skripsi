<!DOCTYPE html>
<html>
<head>
    <title>Surat Keterangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 30px;
        }
        .footer {
            margin-top: 50px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Pemberitahuan Surat Keterangan</h2>
    </div>

    <div class="content">
        <p>Yth. {{ $nama ?: 'Penerima' }},</p>
        
        <p>Dengan hormat,</p>
        
        <p>Kami informasikan bahwa Surat Keterangan dengan nomor <strong>{{ $no_surat ?: '-' }}</strong> 
        telah selesai diproses dan terlampir dalam email ini.</p>
        
        @if($nisn)
        <p>NISN: <strong>{{ $nisn }}</strong></p>
        @endif
        
        @if($keterangan)
        <p>Keterangan: <br>{{ $keterangan }}</p>
        @endif
        
        <p>Silakan periksa dokumen terlampir untuk melihat surat keterangan yang dimaksud.</p>
        
        <p>Terima kasih atas perhatiannya.</p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis, mohon untuk tidak membalas email ini.</p>
    </div>
</body>
</html>