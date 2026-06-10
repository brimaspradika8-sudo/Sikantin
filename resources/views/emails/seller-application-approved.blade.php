<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <div style="background-color: #f97316; color: white; padding: 20px; text-align: center;">
        <h1 style="margin: 0;">Sikantin</h1>
    </div>

    <div style="padding: 30px; background-color: #f9fafb;">
        <h2 style="color: #111827; margin-top: 0;">Selamat! 🎉</h2>
        <p style="color: #374151; font-size: 16px; line-height: 1.6;">
            Pengajuan Anda untuk menjadi penjual di Sikantin telah <strong>DISETUJUI</strong>!
        </p>

        <p style="color: #374151; font-size: 16px; line-height: 1.6;">
            Akun penjual Anda telah disetujui. Anda dapat login menggunakan email ini:
        </p>

        <div style="background-color: white; border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <p style="color: #6b7280; font-size: 14px; margin-top: 0;">
                <strong>Email Login:</strong>
            </p>
            <p style="color: #111827; font-size: 16px; font-weight: bold; font-family: 'Courier New', monospace; background-color: #f3f4f6; padding: 10px; border-radius: 4px; margin: 0;">
                {{ $sellerEmail }}
            </p>

            @if($sellerPassword)
                <p style="color: #6b7280; font-size: 14px; margin-top: 15px; margin-bottom: 5px;">
                    <strong>Password Sementara:</strong>
                </p>
                <p style="color: #111827; font-size: 16px; font-weight: bold; font-family: 'Courier New', monospace; background-color: #f3f4f6; padding: 10px; border-radius: 4px; margin: 0;">
                    {{ $sellerPassword }}
                </p>
            @else
                <p style="color: #374151; font-size: 16px; line-height: 1.6; margin-top: 15px;">
                    Gunakan password akun Anda yang sudah terdaftar sebelumnya.
                </p>
            @endif
        </div>

        <p style="color: #374151; font-size: 16px; line-height: 1.6;">
            <strong>Silakan amankan password Anda:</strong> Setelah login pertama kali, kami sangat merekomendasikan Anda untuk mengubah password ke yang lebih aman.
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}/login" style="background-color: #f97316; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold;">
                Login ke Akun Penjual
            </a>
        </div>

        <p style="color: #374151; font-size: 16px; line-height: 1.6;">
            Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi tim support kami.
        </p>

        <p style="color: #374151; font-size: 16px; line-height: 1.6;">
            Terima kasih telah bergabung dengan Sikantin!
        </p>

        <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
            Dengan hormat,<br>
            Tim Sikantin
        </p>
    </div>

    <div style="background-color: #111827; color: white; padding: 20px; text-align: center; font-size: 12px;">
        <p style="margin: 0;">© 2026 Sikantin. Semua hak dilindungi.</p>
    </div>
</div>
