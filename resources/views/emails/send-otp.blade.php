<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã OTP - HANZO</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9fafb;
        }
        .email-wrapper {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .tagline {
            font-size: 14px;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 15px;
        }
        .description {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .otp-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px dashed #0284c7;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-label {
            font-size: 12px;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .otp-code {
            font-size: 48px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
        .otp-expire {
            font-size: 12px;
            color: #0284c7;
            margin-top: 15px;
            font-weight: 500;
        }
        .warning-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #92400e;
        }
        .info-list {
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #166534;
        }
        .info-list li {
            margin-bottom: 8px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }
        .footer-link {
            color: #0f172a;
            text-decoration: none;
            font-weight: 600;
        }
        .social-links {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .social-links a {
            color: #0f172a;
            text-decoration: none;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #0f172a;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #1e293b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <!-- Header -->
            <div class="header">
                <div class="logo">HANZO</div>
                <div class="tagline">ELEGANCE IN EVERY LINE</div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">Xin chào {{ $user->name }},</div>
                
                <div class="description">
                    Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản HANZO của bạn. Sử dụng mã OTP dưới đây để tiếp tục quá trình:
                </div>

                <!-- OTP Box -->
                <div class="otp-box">
                    <div class="otp-label">Mã OTP của bạn</div>
                    <div class="otp-code">{{ $otp }}</div>
                    <div class="otp-expire">⏰ Mã này sẽ hết hạn sau 10 phút</div>
                </div>

                <!-- Warning Box -->
                <div class="warning-box">
                    <strong>⚠️ Lưu ý bảo mật:</strong> Không bao giờ chia sẻ mã OTP này với bất kỳ ai. HANZO không bao giờ yêu cầu mã OTP của bạn qua tin nhắn hoặc cuộc gọi.
                </div>

                <!-- Info List -->
                <div class="info-list">
                    <strong>📋 Các bước tiếp theo:</strong>
                    <ul>
                        <li>✓ Sao chép mã OTP trên</li>
                        <li>✓ Quay lại trang đặt lại mật khẩu</li>
                        <li>✓ Dán mã vào ô nhập liệu</li>
                        <li>✓ Nhập mật khẩu mới của bạn</li>
                    </ul>
                </div>

                <div class="description" style="margin-top: 30px; color: #475569; font-size: 13px;">
                    Nếu bạn không yêu cầu đặt lại mật khẩu, bạn có thể bỏ qua email này. Tài khoản của bạn sẽ vẫn an toàn.
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0 0 10px 0;">
                    Nếu bạn có câu hỏi, vui lòng liên hệ:
                    <a href="mailto:support@hanzo.vn" class="footer-link">support@hanzo.vn</a>
                </p>
                
                <div class="social-links">
                    <a href="https://facebook.com/hanzo">Facebook</a>
                    <a href="https://instagram.com/hanzo">Instagram</a>
                    <a href="https://tiktok.com/@hanzo">TikTok</a>
                </div>

                <p style="margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                    © 2025 HANZO. Tất cả quyền được bảo lưu.<br>
                    <a href="https://hanzo.vn/privacy" style="color: #0f172a; text-decoration: none; font-size: 11px;">Chính sách bảo mật</a> | 
                    <a href="https://hanzo.vn/terms" style="color: #0f172a; text-decoration: none; font-size: 11px;">Điều khoản dịch vụ</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
