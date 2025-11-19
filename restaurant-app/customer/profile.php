<?php
session_start();
// Cek Login
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../index.php");
    exit;
}

// Ambil inisial nama untuk avatar default jika perlu
$initials = strtoupper(substr($_SESSION['user']['fullname'], 0, 2));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    
    <link href="../assets/css/customer.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* CSS Khusus Halaman Profil agar Kartu terlihat Mewah */
        .profile-card-large {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border: 1px solid #e2e8f0;
            position: relative;
        }
        
        .profile-header-bg {
            height: 120px;
            width: 100%;
            background: linear-gradient(135deg, #ff0099, #ff4757);
            position: absolute;
            top: 0;
            left: 0;
        }
        
        .profile-avatar-wrapper {
            margin-top: 60px; /* Agar avatar naik setengah ke header */
            position: relative;
            z-index: 2;
        }
        
        .profile-avatar-big {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            object-fit: cover;
            background: #fff;
        }
        
        .profile-body {
            padding: 20px 40px 40px;
            width: 100%;
            z-index: 2;
        }
        
        .profile-name-large {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            margin: 10px 0 5px;
        }
        
        .profile-badge {
            display: inline-block;
            padding: 5px 15px;
            background: #ecfdf5;
            color: #059669;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 30px;
        }
        
        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            text-align: left;
            width: 100%;
            margin-top: 20px;
            border-top: 1px solid #f1f5f9;
            padding-top: 30px;
        }
        
        .info-item {
            background: #f8fafc;
            padding: 20px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.2s;
        }
        .info-item:hover { transform: translateY(-3px); }
        
        .info-icon-box {
            width: 45px; height: 45px;
            background: white;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            color: #ff0099;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        
        .info-label { font-size: 12px; color: #64748b; display: block; margin-bottom: 2px; }
        .info-value { font-size: 15px; font-weight: 600; color: #334155; word-break: break-all; }
    </style>
</head>
<body>

    <nav class="topbar-container">
        <div class="brand">
            <div class="brand-icon"><i class="fas fa-utensils"></i></div>
            <div class="brand-text"><h2>RestoApp</h2><span>Pesan Online</span></div>
        </div>
        
        <div class="top-right">
             <a href="cart.php" class="cart-btn-top"><i class="fas fa-shopping-cart cart-icon"></i></a>
             <div class="user-profile">
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars($_SESSION['user']['fullname']) ?></span>
                    <span class="user-role">Customer</span>
                </div>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['fullname']) ?>&background=ff0099&color=fff" class="user-avatar">
            </div>
        </div>
    </nav>

    <div class="main-layout">
        
        <aside class="sidebar-nav">
            <ul>
                <li><a href="menu.php"><i class="fas fa-home"></i> Menu</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-bag"></i> Keranjang</a></li>
                <li><a href="orders.php"><i class="fas fa-receipt"></i> Pesanan Saya</a></li>
                <li><a href="profile.php" class="active"><i class="fas fa-user"></i> Profil</a></li>
                
                <li class="logout-item">
                    <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
                </li>
            </ul>
        </aside>

        <main class="content-area">
            <div class="page-header">
                <h1>Profil Saya</h1>
                <p>Informasi lengkap akun Anda</p>
            </div>

            <div class="profile-card-large">
                <div class="profile-header-bg"></div>
                
                <div class="profile-avatar-wrapper">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['fullname']) ?>&background=ffffff&color=ff0099&size=256" class="profile-avatar-big" alt="Profile">
                </div>
                
                <div class="profile-body">
                    <h2 class="profile-name-large"><?= htmlspecialchars($_SESSION['user']['fullname']) ?></h2>
                    <span class="profile-badge">Customer Member</span>
                    
                    <div class="profile-info-grid">
                        <div class="info-item">
                            <div class="info-icon-box"><i class="far fa-envelope"></i></div>
                            <div>
                                <span class="info-label">Email Address</span>
                                <span class="info-value"><?= htmlspecialchars($_SESSION['user']['email']) ?></span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon-box"><i class="fas fa-shield-alt"></i></div>
                            <div>
                                <span class="info-label">Status Akun</span>
                                <span class="info-value">Verified Customer</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon-box"><i class="far fa-calendar-alt"></i></div>
                            <div>
                                <span class="info-label">Bergabung Sejak</span>
                                <span class="info-value"><?= date('F Y') ?></span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon-box"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <span class="info-label">Lokasi</span>
                                <span class="info-value">Indonesia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>
</html>