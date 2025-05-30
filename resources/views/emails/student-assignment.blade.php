<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thông báo phân công cán sự lớp</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .content {
            margin-bottom: 20px;
        }
        .footer {
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
        }
        .highlight {
            font-weight: bold;
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Thông báo phân công cán sự lớp</h2>
    </div>

    <div class="content">
        <p>Xin chào <span class="highlight">{{ $student->full_name }}</span>,</p>

        <p>Chúng tôi xin thông báo rằng bạn đã được phân công làm <span class="highlight">{{ $role }}</span> cho lớp <span class="highlight">{{ $class->name }}</span> (Mã lớp: {{ $class->code }}).</p>

        <p>Thông tin chi tiết:</p>
        <ul>
            <li>Họ và tên: {{ $student->full_name }}</li>
            <li>Mã sinh viên: {{ $student->code }}</li>
            <li>Lớp: {{ $class->name }}</li>
            <li>Vai trò: {{ $role }}</li>
            <li>Thời gian phân công: {{ now()->format('d/m/Y H:i') }}</li>
        </ul>

        <p>Chúc mừng bạn đã được tin tưởng giao trọng trách này. Chúng tôi tin rằng bạn sẽ hoàn thành tốt nhiệm vụ được giao.</p>

        <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với giáo viên chủ nhiệm hoặc phòng đào tạo.</p>

        <p>Trân trọng,<br>
        Ban quản lý đào tạo</p>
    </div>

    <div class="footer">
        <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        <p>&copy; {{ date('Y') }} Hệ thống quản lý sinh viên trực tuyến</p>
    </div>
</body>
</html>
