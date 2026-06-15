<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title>Disarpus Lombok Barat - Portal Layanan Digital & Kuisioner</title>
    <meta name="description" content="Portal Layanan Digital Dinas Kearsipan dan Perpustakaan Kabupaten Lombok Barat. Akses survei IPLM, TKM, dan Layanan Pengaduan masyarakat secara online.">
    <meta name="keywords" content="disarpus lobar, perpustakaan lombok barat, kuisioner iplm, survei tkm lombok barat, layanan pengaduan disarpus">
    <meta name="author" content="Disarpus Lombok Barat">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://kuisioner-disarpus.page.gd/">
    <meta property="og:title" content="Disarpus Lombok Barat - Portal Layanan Digital">
    <meta property="og:description" content="Akses survei literasi (IPLM & TKM) dan layanan pengaduan masyarakat Kabupaten Lombok Barat dalam satu portal.">
    <meta property="og:image" content="https://kuisioner-disarpus.page.gd/assets/logo_disarpus.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="Disarpus Lombok Barat - Portal Layanan Digital">
    <meta property="twitter:description" content="Akses survei literasi (IPLM & TKM) dan layanan pengaduan masyarakat Kabupaten Lombok Barat.">
    <meta property="twitter:image" content="https://kuisioner-disarpus.page.gd/assets/logo_disarpus.png">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/loader.css">
    
    <style>
        :root {
            --primary: #0F52BA;
            --primary-dark: #0a3d8f;
            --primary-light: #eff6ff;
            --accent: #F4C430;
            --accent-glow: rgba(244, 196, 48, 0.3);
            
            --bg-body: #f8fafc;
            --bg-surface: #ffffff;
            --bg-soft: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
            --shadow-float: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }

        /* ===== Base Reset ===== */
        *, *::before, *::after { box-sizing: border-box; outline: none; }
        
        html, body { 
            margin: 0; padding: 0; 
            width: 100%; 
            overflow-x: hidden;
            scroll-behavior: smooth;
        }
        body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Subtle Background Pattern */
        .bg-pattern {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background-image: 
                radial-gradient(ellipse 600px 600px at 5% 15%, rgba(15, 82, 186, 0.06) 0%, transparent 70%),
                radial-gradient(ellipse 500px 500px at 95% 80%, rgba(244, 196, 48, 0.05) 0%, transparent 70%),
                radial-gradient(ellipse 400px 300px at 50% 0%, rgba(15, 82, 186, 0.03) 0%, transparent 70%),
                radial-gradient(ellipse 350px 350px at 80% 20%, rgba(99, 102, 241, 0.04) 0%, transparent 70%),
                radial-gradient(ellipse 300px 400px at 20% 70%, rgba(16, 185, 129, 0.03) 0%, transparent 70%);
        }

        /* ===== Background Decorations ===== */
        .bg-decorations {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: -1;
        }
        
        .decor-shape {
            position: absolute;
            pointer-events: none;
            user-select: none;
        }

        /* Large Blurred Color Blobs */
        .shape-blue-1 {
            top: 12%;
            left: -100px;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(15, 82, 186, 0.12) 0%, rgba(15, 82, 186, 0) 70%);
            filter: blur(50px);
            animation: drift-slow 20s infinite alternate ease-in-out;
        }

        .shape-gold-1 {
            top: 45%;
            right: -150px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(244, 196, 48, 0.08) 0%, rgba(244, 196, 48, 0) 70%);
            filter: blur(60px);
            animation: drift-slow 25s infinite alternate-reverse ease-in-out;
        }

        .shape-green-1 {
            top: 75%;
            left: 5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.07) 0%, rgba(16, 185, 129, 0) 70%);
            filter: blur(50px);
            animation: drift-slow 18s infinite alternate ease-in-out;
        }

        /* Geometric Abstract Shapes - Outline Rings */
        .shape-ring-1 {
            top: 18%;
            right: 10%;
            width: 160px;
            height: 160px;
            border: 2px dashed rgba(15, 82, 186, 0.12);
            border-radius: 50%;
            animation: spin-slow 40s infinite linear;
        }

        .shape-ring-1::after {
            content: '';
            position: absolute;
            top: 15px; left: 15px; right: 15px; bottom: 15px;
            border: 1px solid rgba(15, 82, 186, 0.06);
            border-radius: 50%;
        }

        .shape-ring-2 {
            top: 55%;
            left: 6%;
            width: 220px;
            height: 220px;
            border: 1.5px solid rgba(16, 185, 129, 0.08);
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; /* Organic wavy circle */
            animation: morph-spin 25s infinite linear;
        }

        /* Abstract Dots Group */
        .shape-dots-1 {
            top: 32%;
            left: 8%;
            width: 112px;
            height: 112px;
            background-image: radial-gradient(rgba(15, 82, 186, 0.12) 1.5px, transparent 1.5px);
            background-size: 16px 16px;
        }

        .shape-dots-2 {
            top: 78%;
            right: 12%;
            width: 128px;
            height: 128px;
            background-image: radial-gradient(rgba(244, 196, 48, 0.12) 1.5px, transparent 1.5px);
            background-size: 16px 16px;
        }

        /* Animations */
        @keyframes drift-slow {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, 30px) scale(1.1); }
        }

        @keyframes spin-slow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes morph-spin {
            0% {
                border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
                transform: rotate(0deg);
            }
            50% {
                border-radius: 60% 40% 50% 50% / 50% 60% 40% 60%;
            }
            100% {
                border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
                transform: rotate(360deg);
            }
        }


        a { text-decoration: none; color: inherit; transition: all 0.2s ease; }
        h1, h2, h3, h4, h5 { font-weight: 700; color: var(--text-main); margin-top: 0; letter-spacing: -0.02em; }
        
        /* ===== Utils ===== */
        .wrapper { max-width: 1200px; margin: 0 auto; padding: 0 24px; position: relative; z-index: 2; }
        .text-gradient {
            background: linear-gradient(135deg, #0F52BA 0%, #1a6bde 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* ===== Buttons ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
            z-index: 1;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(15, 82, 186, 0.25);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 82, 186, 0.35);
            color: #fff;
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(255,255,255,0.15), transparent);
            opacity: 0; transition: 0.3s;
        }
        .btn-primary:hover::after { opacity: 1; }

        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--border);
            color: var(--text-main);
            box-shadow: var(--shadow-sm);
        }
        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: #fff;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5em 1.1em;
            font-size: 0.75em;
            font-weight: 700;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-primary {
            background: rgba(15, 82, 186, 0.08);
            color: var(--primary);
            border: 1px solid rgba(15, 82, 186, 0.12);
        }
        .badge-light {
            background: #fff;
            color: var(--text-muted);
            border: 1px solid var(--border);
            text-transform: none;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
        }
        
        /* ===== Navigation ===== */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 0.9rem 0;
            transition: all 0.3s ease;
        }
        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.97);
            box-shadow: var(--shadow-md);
            padding: 0.7rem 0;
        }
        
        .nav-content { display: flex; justify-content: space-between; align-items: center; }
        .nav-actions { display: flex; align-items: center; gap: 10px; }

        .brand { display: flex; align-items: center; gap: 14px; }
        .brand-logos { display: flex; align-items: center; gap: 8px; }
        .brand-logos img { height: 38px; width: auto; transition: 0.3s; }
        .brand-text { display: flex; flex-direction: column; line-height: 1.15; }
        .brand-title { font-weight: 800; font-size: 1.15rem; color: var(--primary); letter-spacing: -0.5px; }
        .brand-subtitle { font-size: 0.78rem; color: var(--text-muted); font-weight: 500; letter-spacing: 0.2px; }

        /* ===== Hero Section — Two Column ===== */
        .hero {
            padding: 5rem 0 4rem;
            position: relative;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .hero-content { text-align: left; }
        
        .hero-badges { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 2rem; }

        /* Floating abstract shapes */
        .shape-blob {
            position: absolute;
            z-index: -1;
            filter: blur(80px);
            opacity: 0.5;
            border-radius: 50%;
            animation: float 12s infinite ease-in-out;
        }
        .shape-1 { top: -5%; left: -5%; width: 350px; height: 350px; background: rgba(15, 82, 186, 0.12); animation-delay: 0s; }
        .shape-2 { bottom: 5%; right: -5%; width: 280px; height: 280px; background: rgba(244, 196, 48, 0.08); animation-delay: 3s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .hero h1 {
            font-size: 3.25rem;
            line-height: 1.12;
            margin-bottom: 1.25rem;
            letter-spacing: -1.5px;
            font-weight: 800;
            color: var(--text-main);
        }
        
        .hero p {
            font-size: 1.05rem;
            color: var(--text-muted);
            max-width: 480px;
            margin-bottom: 2rem;
            line-height: 1.75;
        }

        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; }

        /* ===== Stats Card (right side of hero) ===== */
        .stats-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            box-shadow: var(--shadow-lg);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            max-width: 380px;
            margin-left: auto;
        }

        .stat-block {
            text-align: center;
            padding: 1.25rem 1rem;
            position: relative;
        }
        .stat-block:first-child {
            border-right: 1px solid var(--border);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin: 0 auto 1rem;
        }

        .stat-val {
            display: block;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 6px;
        }
        .stat-lbl {
            font-size: 0.78rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* ===== Services Section ===== */
        .services-section { padding: 4rem 0 6rem; flex-grow: 1; }
        
        .section-header { text-align: center; margin-bottom: 3.5rem; }
        .section-header h2 { font-size: 2rem; margin-bottom: 0.5rem; font-weight: 800; }
        .section-divider {
            width: 40px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
            margin: 0 auto 1rem;
        }
        .section-header p { color: var(--text-muted); font-size: 1rem; }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        /* ===== Feature Cards ===== */
        .feature-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.25rem 2rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            height: 100%;
            text-decoration: none;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 5px;
            background: var(--border);
            opacity: 0.5;
            transition: 0.3s;
        }

        .card-iplm::before { background: rgba(15, 82, 186, 0.3); }
        .card-tkm::before { background: rgba(16, 185, 129, 0.3); }
        .card-aduan::before { background: rgba(244, 196, 48, 0.3); }

        .card-iplm:hover::before { background: var(--primary); opacity: 1; }
        .card-tkm:hover::before { background: #10b981; opacity: 1; }
        .card-aduan:hover::before { background: var(--accent); opacity: 1; }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-float);
            border-color: transparent;
        }

        .icon-box {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        .card-iplm .icon-box { background: rgba(15, 82, 186, 0.08); color: var(--primary); }
        .card-tkm .icon-box { background: rgba(16, 185, 129, 0.08); color: #10b981; }
        .card-aduan .icon-box { background: rgba(244, 196, 48, 0.1); color: #d4a017; }

        .feature-card:hover .icon-box { transform: scale(1.1) rotate(-5deg); color: #fff; box-shadow: 0 8px 16px rgba(0,0,0,0.1); }
        .card-iplm:hover .icon-box { background: var(--primary); }
        .card-tkm:hover .icon-box { background: #10b981; }
        .card-aduan:hover .icon-box { background: #d4a017; }

        .feature-card h3 { font-size: 1.2rem; margin-bottom: 0.75rem; color: var(--text-main); font-weight: 700; }
        .feature-card p { color: var(--text-muted); font-size: 0.9rem; line-height: 1.65; margin-bottom: 2rem; flex-grow: 1; }

        .card-footer {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--text-main);
            padding-top: 1.25rem;
            border-top: 1px solid var(--bg-soft);
        }
        .card-footer i { 
            width: 30px; height: 30px; background: var(--bg-soft); border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; transition: 0.3s;
            font-size: 0.85rem;
        }
        .feature-card:hover .card-footer i { background: var(--text-main); color: #fff; transform: translateX(4px); }

        /* ===== Footer ===== */
        .site-footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 0 0 2rem;
            margin-top: auto;
            position: relative;
            overflow: hidden;
            width: 100%;
        }
        
        .footer-wave {
            display: block;
            width: 100%;
            height: auto;
        }
        
        .footer-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0;
            background: radial-gradient(circle at 80% 20%, rgba(15, 82, 186, 0.15) 0%, transparent 50%);
            opacity: 0.6;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1.5fr 0.8fr 1fr 1.5fr;
            gap: 2.5rem;
            margin-bottom: 3rem;
            position: relative;
            z-index: 1;
            padding-top: 2rem;
        }

        .footer-brand h2 { color: #fff; font-size: 1.4rem; margin-bottom: 1rem; font-weight: 800; }
        .footer-brand p { font-size: 0.88rem; line-height: 1.7; opacity: 0.8; max-width: 280px; margin-bottom: 1.75rem; }

        .social-links { display: flex; gap: 10px; }
        .social-btn {
            width: 38px; height: 38px; border-radius: 50%;
            background: rgba(255,255,255,0.06); color: #cbd5e1;
            display: flex; align-items: center; justify-content: center;
            transition: 0.3s; border: 1px solid rgba(255,255,255,0.06);
            text-decoration: none; font-size: 1rem;
        }
        .social-btn:hover { background: var(--primary); border-color: var(--primary); transform: translateY(-3px); color: #fff; }

        .footer-links h4 { color: #fff; font-size: 0.9rem; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
        .footer-links ul { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 0.75rem; font-size: 0.9rem; }
        .footer-links a { 
            color: #94a3b8; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; 
            transition: 0.2s;
        }
        .footer-links a:hover { color: #fff; padding-left: 4px; }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            color: #94a3b8;
        }
        .footer-contact-item i {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 3px;
            flex-shrink: 0;
        }

        /* Map Container */
        .footer-map-container {
            border-radius: 12px;
            overflow: hidden;
            height: 140px;
            width: 100%;
            position: relative;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .footer-map-container iframe {
            width: 100%; height: 100%; border: 0;
            filter: grayscale(100%) invert(92%) contrast(83%);
            transition: 0.3s;
        }
        .footer-map-container:hover iframe { filter: none; }
        
        .map-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #60a5fa;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 8px;
            transition: 0.2s;
        }
        .map-link:hover { color: #93c5fd; }

        .footer-address {
            margin-top: 10px;
            font-size: 0.82rem;
            color: #94a3b8;
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }
        .footer-address i { margin-top: 2px; color: #64748b; }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.08);
            padding-top: 2rem;
            text-align: center;
            font-size: 0.83rem;
            color: #64748b;
            position: relative;
            z-index: 1;
        }

        /* ===== Mobile Responsive ===== */
        @media (max-width: 1024px) {
            .hero-grid { grid-template-columns: 1fr; gap: 2.5rem; }
            .stats-card { max-width: 100%; margin-left: 0; }
            .hero h1 { font-size: 2.75rem; }
        }

        @media (max-width: 768px) {
            .wrapper { padding: 0 16px; width: 100%; }
            .cards-grid { grid-template-columns: 1fr; gap: 1.25rem; }
            .hero { padding: 2.5rem 0 3rem; }
            .hero-content { text-align: left; }
            .hero h1 { font-size: 2.1rem; line-height: 1.2; }
            .hero p { font-size: 0.95rem; margin-bottom: 1.75rem; }
            .hero-actions { flex-direction: row; }
            .stats-card { grid-template-columns: 1fr 1fr; }
            
            .footer-content { grid-template-columns: 1fr; gap: 2rem; }
            .nav-content { gap: 0.75rem; } 
            .nav-actions { gap: 8px; }
            .services-section { padding: 2.5rem 0 4rem; }
        }

        @media (max-width: 480px) {
            .hero h1 { font-size: 1.85rem; letter-spacing: -1px; }
            .feature-card { padding: 1.75rem 1.5rem; }
            .nav-content { flex-direction: column; gap: 1rem; text-align: center; }
            .brand { justify-content: center; }
            .nav-actions { width: 100%; justify-content: center; }
            .nav-actions .btn { flex: 1; font-size: 0.8rem; padding: 0.55rem 0.75rem; }
            .hero-badges { justify-content: center; }
            .hero-content { text-align: center; }
            .hero p { max-width: 100%; }
            .hero-actions { justify-content: center; }
            .stats-card { margin: 0 auto; }
            .stat-val { font-size: 2rem; }
        }
    </style>
</head>
<body>

    <?php include BASE_PATH . 'config/loader.php'; ?>
    <div class="bg-pattern"></div>
    <div class="bg-decorations">
        <div class="decor-shape shape-blue-1"></div>
        <div class="decor-shape shape-gold-1"></div>
        <div class="decor-shape shape-green-1"></div>
        <div class="decor-shape shape-ring-1"></div>
        <div class="decor-shape shape-ring-2"></div>
        <div class="decor-shape shape-dots-1"></div>
        <div class="decor-shape shape-dots-2"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="wrapper nav-content">
            <div class="brand">
                <div class="brand-logos">
                    <img src="<?= BASE_URL ?>/assets/logo_lobar.png" alt="Logo Lobar">
                    <img src="<?= BASE_URL ?>/assets/logo_disarpus.png" alt="Logo Disarpus">
                </div>
                <div class="brand-text">
                    <span class="brand-title">DISARPUS</span>
                    <span class="brand-subtitle">Kab. Lombok Barat</span>
                </div>
            </div>
            <div class="nav-actions">
                 <a href="#footer-map" class="btn btn-outline" style="padding: 0.55rem 1.15rem; font-size: 0.82rem;">
                    <i class="bi bi-geo-alt-fill"></i> Kunjungi Kami
                 </a>
                 <a href="pustakawan/form_pengaduan" class="btn btn-primary" style="padding: 0.55rem 1.15rem; font-size: 0.82rem;">
                    <i class="bi bi-chat-quote-fill"></i> Layanan Pengaduan
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero wrapper">
        <!-- Abstract Shapes -->
        <div class="shape-blob shape-1"></div>
        <div class="shape-blob shape-2"></div>
        
        <div class="hero-grid">
            <!-- Left: Text Content -->
            <div class="hero-content">
                <div class="hero-badges">
                    <div class="badge-pill badge-primary"><i class="bi bi-patch-check-fill"></i> Portal Resmi Layanan Publik</div>
                    <div class="badge-pill badge-light">
                        <i class="bi bi-calendar3" style="color: var(--primary);"></i> <?= $tgl_sekarang ?>
                    </div>
                </div>
                
                <h1>Portal Survei Literasi Masyarakat<br><span class="text-gradient">Lombok Barat</span></h1>
                
                <p>Akses terintegrasi untuk pendataan indeks literasi dan tingkat kegemaran membaca masyarakat demi Lombok Barat yang lebih cerdas.</p>
                
                <div class="hero-actions">
                    <a href="#layanan-utama" class="btn btn-primary">
                        Akses Layanan <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="https://disarpus.lombokbaratkab.go.id/" target="_blank" class="btn btn-outline">
                        Website Utama <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </div>
            </div>

            <!-- Right: Stats Card -->
            <div class="stats-card">
                <div class="stat-block">
                    <div class="stat-icon">
                        <i class="bi bi-calendar2-week"></i>
                    </div>
                    <span class="stat-val count-up" data-target="<?= date('Y') ?>">0</span>
                    <span class="stat-lbl">Tahun Periode</span>
                </div>
                <div class="stat-block">
                    <div class="stat-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <span class="stat-val count-up" data-target="<?= $total_libraries ?>">0</span>
                    <span class="stat-lbl">Perpustakaan Terdaftar</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="wrapper services-section" id="layanan-utama">
        <div class="section-header">
            <h2>Pusat Layanan Data</h2>
            <div class="section-divider"></div>
            <p>Pilih instrumen survei atau layanan yang Anda butuhkan.</p>
        </div>

        <div class="cards-grid">
            <!-- IPLM -->
            <a href="pustakawan/pilih_perpustakaan?target=iplm" class="feature-card card-iplm">
                <div class="icon-box">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <h3>Survei IPLM</h3>
                <p>Indeks Pembangunan Literasi Masyarakat. Instrumen strategis untuk mengukur kemajuan infrastruktur dan budaya literasi.</p>
                <div class="card-footer">
                    <span>Mulai Survei</span>
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>

            <!-- TKM -->
            <a href="pustakawan/kuisioner_tkm.php?target=tkm" class="feature-card card-tkm">
                <div class="icon-box">
                    <i class="bi bi-book-half"></i>
                </div>
                <h3>Survei TKM</h3>
                <p>Tingkat Kegemaran Membaca. Bantu kami memetakan preferensi dan kebiasaan membaca masyarakat Lombok Barat.</p>
                <div class="card-footer">
                    <span>Mulai Survei</span>
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>

            <!-- Pengaduan -->
            <a href="pustakawan/form_pengaduan" class="feature-card card-aduan">
                <div class="icon-box">
                    <i class="bi bi-envelope-open-heart"></i>
                </div>
                <h3>Kotak Aspirasi</h3>
                <p>Punya saran, kritik, atau keluhan? Suara Anda adalah fondasi utama kami dalam meningkatkan kualitas pelayanan publik.</p>
                <div class="card-footer">
                    <span>Kirim Pesan</span>
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer" id="footer-map">
        <!-- SVG Wave Divider -->
        <svg class="footer-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none" style="height: 80px; width: 100%; margin-bottom: -1px;">
            <path fill="#0f172a" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
        
        <div class="footer-bg"></div>

        <div class="wrapper">
            <div class="footer-content">
                <!-- Brand & Social -->
                <div class="footer-brand">
                    <h2>Disarpus Lobar</h2>
                    <p>Berkomitmen menghadirkan layanan kearsipan dan perpustakaan yang modern, inklusif, dan akuntabel.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/disarpuslobar#" target="_blank" rel="noopener noreferrer" class="social-btn"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/disarpuslobar" target="_blank" rel="noopener noreferrer" class="social-btn"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.youtube.com/@disarpuslobar" target="_blank" rel="noopener noreferrer" class="social-btn"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-links">
                    <h4>Akses</h4>
                    <ul>
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="pustakawan/pilih_perpustakaan?target=iplm">Survei IPLM</a></li>
                        <li><a href="pustakawan/kuisioner_tkm.php?target=tkm">Survei TKM</a></li>
                        <li><a href="pustakawan/form_pengaduan">Pengaduan</a></li>
                    </ul>
                </div>

                <!-- Contacts -->
                <div class="footer-links">
                    <h4>Kontak</h4>
                    <div class="footer-contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>Telp. &nbsp;(0370) 681239</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-printer-fill"></i>
                        <span>Fax. &nbsp;&nbsp;(0370) 681250</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span><a href="mailto:disarpus@lombokbaratkab.go.id" style="color: #94a3b8;">disarpus@<br>lombokbaratkab.go.id</a></span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-mailbox2"></i>
                        <span>Kode Pos: 83363</span>
                    </div>
                </div>

                <!-- Location & Map -->
                <div class="footer-links">
                    <h4>Lokasi Kami</h4>
                    <a href="https://maps.google.com/maps?q=Perpustakaan+Daerah+Lombok+Barat" target="_blank" rel="noopener noreferrer" class="map-link">
                        Lihat di Maps <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                    <div class="footer-map-container">
                        <iframe src="https://maps.google.com/maps?q=Perpustakaan+Daerah+Lombok+Barat&t=&z=15&ie=UTF8&iwloc=&output=embed" loading="lazy"></iframe>
                    </div>
                    <div class="footer-address">
                        <i class="bi bi-geo-alt-fill"></i> Jl. Raya BIL KM 21 Gerung, Lombok Barat
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                &copy; <?= date('Y') ?> Dinas Kearsipan dan Perpustakaan Kabupaten Lombok Barat. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>/assets/loader.js"></script>
    
    <!-- Interactions Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Navbar Scroll Effect
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Card Animations (Intersection Observer)
            const cards = document.querySelectorAll('.feature-card');
            const cardObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry, i) => {
                    if (entry.isIntersecting) {
                        const card = entry.target;
                        const index = Array.from(cards).indexOf(card);
                        card.style.transition = 'opacity 0.6s cubic-bezier(0.25, 0.8, 0.25, 1), transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1)';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, index * 100);
                        cardObserver.unobserve(card);
                    }
                });
            }, { threshold: 0.15 });

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                cardObserver.observe(card);
            });

            // Hero Content Fade-in
            const heroContent = document.querySelector('.hero-content');
            const statsCard = document.querySelector('.stats-card');
            if (heroContent) {
                heroContent.style.opacity = '0';
                heroContent.style.transform = 'translateY(20px)';
                heroContent.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
                setTimeout(() => {
                    heroContent.style.opacity = '1';
                    heroContent.style.transform = 'translateY(0)';
                }, 100);
            }
            if (statsCard) {
                statsCard.style.opacity = '0';
                statsCard.style.transform = 'translateY(20px)';
                statsCard.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
                setTimeout(() => {
                    statsCard.style.opacity = '1';
                    statsCard.style.transform = 'translateY(0)';
                }, 300);
            }

            // Counters Animation
            const counters = document.querySelectorAll('.count-up');
            const speed = 50;

            const animateCount = (counter) => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const inc = Math.max(1, target / speed);

                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(() => animateCount(counter), 20);
                } else {
                    counter.innerText = target;
                }
            };

            // Trigger counters when in view
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        animateCount(counter);
                        observer.unobserve(counter);
                    }
                });
            }, { threshold: 0.5 });

            counters.forEach(counter => observer.observe(counter));
        });
    </script>
</body>
</html>
