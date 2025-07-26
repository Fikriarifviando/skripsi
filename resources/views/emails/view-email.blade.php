<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Surat Keluar</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 50vh; padding: 20px; margin: 30px;  ">
    <div style="background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 500px; text-align: center; padding: 20px; border: 1px solid #e0e0e0; margin: 0 auto;">
        <div style="background-color: #007bff; color: white; padding: 15px; border-radius: 8px 8px 0 0; margin: -20px -20px 20px;">
            <h2 style="margin: 0; font-size: 18px;">Kode Surat </h2>
        </div>
        
        <div style="background-color: #f8f9fa; border: 2px dashed #007bff; padding: 15px; margin: 20px 0; border-radius: 8px; display: inline-block;">
            <strong style="font-size: 24px; letter-spacing: 5px; color: #333;">{{ $kode }}</strong>
        </div>
        
        <p style="color: #666; line-height: 1.6;">
            Gunakan kode ini untuk melihat proses surat. 
            Untuk melihat proses surat dapat dilihat pada link di bawah ini.
        </p>
        
        <div style="margin-top: 20px; color: #888; font-size: 12px; border-top: 1px solid #e0e0e0; padding-top: 10px;">
            © {{ date('Y') }} Sistem Administrasi Surat
        </div>
    </div>
</body>
</html>