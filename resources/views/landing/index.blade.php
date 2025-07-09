<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ST Student - Hệ thống quản lý sinh viên trực tuyến</title>
    <meta name="description" content="ST Student - Phần mềm quản lý sinh viên hiện đại, giúp tối ưu hóa quy trình quản lý và nâng cao hiệu quả giáo dục.">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #ffffff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #4f46e5;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: #4f46e5;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .language-selector {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .flag {
            width: 20px;
            height: 15px;
            background: linear-gradient(to bottom, #da020e 33%, #ffd700 33%, #ffd700 66%, #da020e 66%);
            border-radius: 2px;
        }

        .auth-buttons {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-outline {
            background: transparent;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-outline:hover {
            background: #f9fafb;
        }

        .btn-primary {
            background: #1f2937;
            color: white;
        }

        .btn-primary:hover {
            background: #111827;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 80px 0;
            overflow: hidden;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .hero-text p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            background: #1f2937;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-hero-primary:hover {
            background: #111827;
            transform: translateY(-2px);
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 13px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.5);
        }

        .hero-mockup {
            position: relative;
        }

        .mockup-window {
            background: white;
            border-radius: 12px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            overflow: hidden;
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
        }

        .mockup-header {
            background: #f3f4f6;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mockup-dots {
            display: flex;
            gap: 6px;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .dot.red { background: #ef4444; }
        .dot.yellow { background: #f59e0b; }
        .dot.green { background: #10b981; }

        .mockup-content {
            padding: 2rem;
            background: white;
        }

        .mockup-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .tab {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .tab.active { background: #dbeafe; color: #1d4ed8; }
        .tab.purple { background: #ede9fe; color: #7c3aed; }
        .tab.green { background: #dcfce7; color: #16a34a; }

        .mockup-bars {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
        }

        .bar.long { width: 80%; }
        .bar.medium { width: 60%; }
        .bar.short { width: 40%; }

        /* Features Section */
        .features {
            padding: 100px 0;
            background: #f9fafb;
        }

        .features-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .features-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .features-header p {
            font-size: 1.2rem;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .feature-icon.blue { background: #dbeafe; }
        .feature-icon.green { background: #dcfce7; }
        .feature-icon.purple { background: #ede9fe; }
        .feature-icon.yellow { background: #fef3c7; }
        .feature-icon.red { background: #fee2e2; }
        .feature-icon.indigo { background: #e0e7ff; }

        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            color: #6b7280;
            line-height: 1.6;
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .cta h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-cta {
            background: white;
            color: #4f46e5;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255,255,255,0.2);
        }

        /* Footer */
        footer {
            background: #1f2937;
            color: white;
            padding: 60px 0 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-brand h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #4f46e5;
        }

        .footer-brand p {
            color: #9ca3af;
            line-height: 1.6;
        }

        .footer-section h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: white;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section ul li a {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section ul li a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid #374151;
            padding-top: 2rem;
            text-align: center;
            color: #9ca3af;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-right {
                gap: 0.5rem;
            }

            .auth-buttons {
                gap: 8px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 0.8rem;
            }

            .hero-content {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 2rem;
            }

            .hero-buttons {
                justify-content: center;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 480px) {
            .hero-text h1 {
                font-size: 1.75rem;
            }

            .features-header h2 {
                font-size: 2rem;
            }

            .cta h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-icon">📚</div>
                    <span>ST Student</span>
                </div>
                <div class="header-right">
                    <div class="auth-buttons">
                        <a href="{{ route('sso.redirect') }}" class="btn btn-outline">Đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Quản lý sinh viên trực tuyến nhanh chóng và chính xác</h1>
                    <p>ST Student giúp tối ưu hóa quy trình quản lý sinh viên với phân tích toàn diện về dữ liệu học tập cho nhiều lĩnh vực giáo dục</p>
                    <div class="hero-buttons">
                        <a href="{{ route('dashboard') }}" class="btn-hero-primary">
                            Dùng thử ngay
                            <span>→</span>
                        </a>
                    </div>
                </div>
                <div class="hero-mockup">
                    <div class="mockup-window">
                        <div class="mockup-header">
                            <div class="mockup-dots">
                                <div class="dot red"></div>
                                <div class="dot yellow"></div>
                                <div class="dot green"></div>
                            </div>
                        </div>
                        <div class="mockup-content">
                            <div class="mockup-tabs">
                                <div class="tab active">Sinh viên</div>
                                <div class="tab purple">Lớp học</div>
                                <div class="tab green">Báo cáo</div>
                            </div>
                            <div class="mockup-bars">
                                <div class="bar long"></div>
                                <div class="bar medium"></div>
                                <div class="bar short"></div>
                                <div class="bar long"></div>
                                <div class="bar medium"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="features-header">
                <h2>Tính năng quản lý toàn diện</h2>
                <p>ST Student tích hợp đầy đủ các công cụ cần thiết để quản lý sinh viên hiệu quả từ nhập học đến tốt nghiệp</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon blue">👥</div>
                    <h3>Quản lý hồ sơ sinh viên</h3>
                    <p>Lưu trữ và quản lý thông tin chi tiết của từng sinh viên bao gồm thông tin cá nhân, kết quả học tập và hoạt động ngoại khóa</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon green">📚</div>
                    <h3>Quản lý lớp học</h3>
                    <p>Tổ chức lớp học hiệu quả, phân công giáo viên chủ nhiệm và theo dõi danh sách sinh viên từng lớp một cách có hệ thống</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon purple">📝</div>
                    <h3>Quản lý tốt nghiệp</h3>
                    <p>Quản lý tốt nghiệp sinh viên, tạo danh sách sinh viên tốt nghiệp và theo dõi tiến trình tốt nghiệp</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon yellow">⚠️</div>
                    <h3>Cảnh báo học tập</h3>
                    <p>Theo dõi và cảnh báo sớm những sinh viên có nguy cơ học yếu để có biện pháp hỗ trợ kịp thời</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon red">📊</div>
                    <h3>Báo cáo và thống kê</h3>
                    <p>Tạo báo cáo chi tiết về kết quả học tập, tỷ lệ tốt nghiệp và các chỉ số quan trọng để hỗ trợ ra quyết định</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon indigo">🔔</div>
                    <h3>Hệ thống thông báo</h3>
                    <p>Gửi thông báo tự động về lịch học, kết quả thi, cảnh báo học tập và các thông tin quan trọng đến sinh viên</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Sẵn sàng tối ưu hóa quy trình quản lý sinh viên của bạn?</h2>
            <p>Tham gia cùng ST Student để nâng cao hiệu quả quản lý và giảng dạy tại trường học của bạn</p>
            <a href="{{ route('sso.redirect') }}" class="btn-cta">
                Bắt đầu ngay
                <span>→</span>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3>📚 ST Student</h3>
                    <p>ST Student cung cấp giải pháp quản lý sinh viên toàn diện và hiệu quả</p>
                </div>
                <div class="footer-section">
                    <h4>Liên kết nhanh</h4>
                    <ul>
                        <li><a href="#">Giới thiệu</a></li>
                        <li><a href="#">Tính năng</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    {{-- <h4>Liên hệ</h4>
                    <ul>
                        <li><a href="#">sale@ststudent.com</a></li>
                        <li><a href="#">+84-</a></li>
                        <li><a href="#">Tầng 7, Vạn Hạnh Mall, Quận Đình Nghĩa, Hà Nội</a></li>
                    </ul> --}}
                </div>
                <div class="footer-section">
                    {{-- <h4>Pháp lý</h4>
                    <ul>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">Điều khoản dịch vụ</a></li>
                        <li><a href="#">Chính sách cookie</a></li>
                    </ul> --}}
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 ST Student. All rights reserved</p>
            </div>
        </div>
    </footer>
</body>
</html>