<!-- resources/views/emails/surat-perintah.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Perintah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: a.5;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 30px;
        }
        .footer {
            font-size: 12px;
            color: #666;
            margin-top: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>SMP NEGERI 33 SURABAYA</h2>
            <p>Jalan Putat Gede Selatan No. 8 Surabaya, Jawa Timur 60189</p>
        </div>
        
        <div class="content">
            <p>Kepada Yth.<br>Bapak/Ibu {{ $nama }}</p>
            
            <p>Dengan hormat,</p>
            
            <p>Bersama email ini kami lampirkan Surat Perintah No. {{ $no_surat }} untuk mengikuti kegiatan {{ $acara }} yang akan dilaksanakan pada tanggal {{ $tanggal }} di {{ $tempat }}.</p>
            
            <p>Mohon untuk dapat hadir tepat waktu dan melaksanakan tugas dengan penuh tanggung jawab.</p>
            
            <p>Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
            
            <p>Hormat kami,<br>Kepala SMP Negeri 33 Surabaya</p>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon untuk tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} SMP Negeri 33 Surabaya. All rights reserved.</p>
        </div>
    </div>
</body>
</html>