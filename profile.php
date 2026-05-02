<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الملف الشخصي</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="صفحة الملف الشخصي للمستخدم">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .profile-wrapper {
      width: 100%;
      max-width: 410px;
      margin: 40px auto 0 auto;
    }
    .profile-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 18px;
      justify-content: center;
    }
    .profile-header .icon {
      font-size: 1.7rem;
      color: var(--secondary-color);
      background: #fff;
      border-radius: 50%;
      box-shadow: 0 2px 8px rgba(44, 62, 80, 0.07);
      padding: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .profile-header .title {
      font-size: 1.35rem;
      font-weight: 800;
      letter-spacing: 0.5px;
      color: var(--primary-color);
    }
    .profile-card {
      background: var(--light-color);
      padding: 38px 24px 28px 24px;
      border-radius: 12px;
      box-shadow: var(--shadow);
      text-align: center;
      position: relative;
      overflow: hidden;
      transition: var(--transition);
      margin-bottom: 20px;
    }
    .avatar-gradient {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      padding: 3px;
      border-radius: 50%;
      display: inline-block;
      margin-bottom: 16px;
      box-shadow: 0 4px 18px 0 rgba(0,0,0,0.10), 0 1.5px 4px rgba(44,62,80,0.10);
    }
    .profile-avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      background: #f3f4f8;
      border: 4px solid #fff;
      transition: var(--transition);
      position: relative;
      z-index: 2;
      display: block;
    }
    .profile-avatar:hover {
      box-shadow: 0 8px 32px 0 rgba(35,47,62,0.13), 0 2px 8px rgba(44,62,80,0.13);
      transform: scale(1.04);
    }
    .profile-info {
      background: var(--gray-color);
      border-radius: 10px;
      padding: 18px 10px 10px 10px;
      margin-bottom: 18px;
      box-shadow: 0 1.5px 4px rgba(44,62,80,0.07);
      position: relative;
      z-index: 1;
    }
    .profile-name {
      font-size: 1.3rem;
      font-weight: 900;
      color: var(--primary-color);
      margin-bottom: 4px;
      letter-spacing: 0.5px;
      text-shadow: 0 1px 2px #eee;
      font-family: 'Tajawal', sans-serif;
    }
    .profile-email {
      color: var(--text-color);
      font-size: 1.05rem;
      margin-bottom: 8px;
      direction: ltr;
      text-align: center;
      background: var(--light-color);
      border-radius: 20px;
      display: inline-block;
      padding: 7px 16px;
      font-weight: 600;
      letter-spacing: 0.2px;
      box-shadow: 0 1px 4px rgba(44,62,80,0.07);
    }
    .profile-status {
      display: inline-block;
      background: var(--secondary-color);
      color: #fff;
      font-size: 0.97rem;
      font-weight: 700;
      border-radius: 20px;
      padding: 5px 18px;
      margin-top: 8px;
      margin-bottom: 2px;
      letter-spacing: 0.2px;
      box-shadow: 0 1px 4px rgba(251,176,52,0.08);
    }
    .profile-actions {
      margin-top: 18px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      align-items: center;
    }
    .main-btn {
      background: var(--primary-color);
      color: #fff;
      border: none;
      border-radius: 9px;
      padding: 13px 0;
      font-size: 1.13rem;
      font-weight: 800;
      cursor: pointer;
      width: 100%;
      max-width: 240px;
      transition: background 0.2s, transform 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      box-shadow: 0 2px 8px rgba(44, 62, 80, 0.09);
      letter-spacing: 0.5px;
      font-family: 'Tajawal', sans-serif;
    }
    .main-btn:hover {
      background: var(--secondary-color);
      color: var(--primary-color);
      transform: translateY(-2px) scale(1.04);
      box-shadow: 0 4px 16px rgba(35,47,62,0.13);
    }
    .back-link {
      display: block;
      margin-top: 22px;
      color: var(--primary-color);
      text-decoration: none;
      font-size: 1.08rem;
      transition: color 0.2s, text-decoration 0.2s;
      font-weight: 700;
      letter-spacing: 0.2px;
    }
    .back-link:hover {
      color: var(--secondary-color);
      text-decoration: underline;
      transform: scale(1.04);
    }
    @media (max-width: 600px) {
      .profile-wrapper {
        max-width: 99vw;
        padding: 0 2vw;
      }
      .profile-card {
        padding: 14px 2px 10px 2px;
      }
      .avatar-gradient {
        margin-bottom: 10px;
      }
      .profile-avatar {
        width: 70px;
        height: 70px;
      }
      .profile-header .icon {
        font-size: 1.2rem;
        padding: 7px;
      }
      .profile-header .title {
        font-size: 1.1rem;
      }
      .main-btn {
        font-size: 1rem;
        padding: 10px 0;
      }
      .profile-name {
        font-size: 1.1rem;
      }
      .profile-email {
        font-size: 0.97rem;
        padding: 6px 10px;
      }
    }
  </style>
</head>
<body>
  <?php include 'includes/header.php'; ?>
  <div class="profile-wrapper">
    <div class="profile-header">
      <span class="icon"><i class="fas fa-user-circle"></i></span>
      <span class="title">الملف الشخصي</span>
    </div>
    <div class="profile-card">
      <span class="avatar-gradient"><img src="image/default-avatar.png" alt="صورة المستخدم" class="profile-avatar" id="profile-avatar"></span>
      <div class="profile-info">
        <div class="profile-name" id="profile-name">اسم المستخدم</div>
        <div class="profile-email" id="profile-email">user@example.com</div>
        <div class="profile-status"><i class="fas fa-check-circle"></i> عضو في Flow Point</div>
      </div>
      <div class="profile-actions">
        <button class="main-btn" id="profile-logout-btn">
          <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
        </button>
        <a href="index.php" class="back-link">
          <i class="fas fa-arrow-right"></i> العودة للصفحة الرئيسية
        </a>
      </div>
    </div>
  </div>
      <!-- التذييل -->
      <footer class="footer">
        <?php include 'includes/footer.php';?>
       
    </footer>
    <script>
  document.addEventListener('DOMContentLoaded', function() {
    const user = JSON.parse(localStorage.getItem('user')) || {};
    document.getElementById('profile-name').textContent = user.name || 'اسم المستخدم';
    document.getElementById('profile-email').textContent = user.email || 'user@example.com';
    if(user.picture) {
      document.getElementById('profile-avatar').src = user.picture;
    }
    // استخدم الزر الجديد فقط
    const logoutBtn = document.getElementById('profile-logout-btn');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', function() {
        localStorage.removeItem('user');
        window.location.href = 'index.php';
      });
    }
  });
</script>
</body>
</html>
