 
    <nav class="navbar">
        <div class="container nav-container">
            <a href="#" class="logo">
                <i class="fas fa-shopping-bag"></i>
                متجرنا
            </a>
            
            <div class="search-box">
                <input type="text" placeholder="ابحث عن أي منتج...">
                <button><i class="fas fa-search"></i> بحث</button>
            </div>
            
            <div class="nav-links">
                <div class="nav-link user-dropdown">
                    <span id="user-avatar-box">
                        <i class="fas fa-user" id="default-user-icon"></i>
                        <a href="login.html" id="avatar-login-link" style="display:none;text-decoration:none;">
                            <span class="avatar-login-span" style="display:block;font-size:1rem;color:#333;font-weight:500;margin-top:2px;">تسجيل الدخول</span>
                        </a>
                    </span>
                    <div class="dropdown-content" id="user-dropdown-content">
                        <a href="login.html" id="login-btn">تسجيل الدخول</a>
                        <a href="#" id="profile-link" style="display:none;">الملف الشخصي</a>
                        <a href="#" id="logout-btn" style="display:none;">تسجيل الخروج</a>
                    </div>
                </div>
                <a href="favorite.php" class="nav-link wishlist-link">
                    <div class="icon-container">
                        <i class="fas fa-heart"></i>
                        <span class="wishlist-count">0</span>
                    </div>
                    <span>المفضلة</span>
                </a>
                <a href="#" class="nav-link cart-link">
                    <div class="icon-container">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                    </div>
                    <span>السلة</span>
                </a>
            </div>
        </div>
    </nav>

    <style>
    .user-dropdown {
        position: relative;
        cursor: pointer;
    }
    .user-dropdown .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background: #fff;
        min-width: 160px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        z-index: 100;
        border-radius: 8px;
        overflow: hidden;
        top: 100%;
    }
    .user-dropdown:hover .dropdown-content,
    .user-dropdown.active .dropdown-content {
        display: block;
    }
    .user-dropdown .dropdown-content a {
        color: #333;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .user-dropdown .dropdown-content a:hover {
        background: #f1f1f1;
    }
    #user-avatar-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 48px;
        min-height: 48px;
        padding: 2px 0;
    }
    .avatar-login-span {
        color: #fff !important;
        font-size: 0.85rem;
        font-weight: 500;
        margin-top: 4px;
        text-align: center;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        white-space: normal;
    }
    @media (max-width: 600px) {
        .avatar-login-span {
            font-size: 0.75rem;
            margin-top: 1.5px;
            line-height: 1.1;
        }
        #user-avatar-box img, #user-avatar-box i {
            width: 22px !important;
            height: 22px !important;
        }
        #user-avatar-box {
            min-width: 32px;
            min-height: 32px;
            padding: 1px 0;
        }
        .user-dropdown .dropdown-content {
            min-width: 120px;
        }
    }
    #user-name img {
        border-radius: 50%;
        width: 24px;
        vertical-align: middle;
        margin-left: 6px;
    }
    .avatar-login-span, #avatar-login-link, #user-avatar-box span, #user-avatar-box a {
        color: #fff !important;
       
    }
    </style>

    <div id="cart" class="hidden">
        <div class="cart-header">
            <h3>سلة التسوق</h3>
            <button id="close_cart"><i class="fas fa-times"></i></button>
        </div>
        <div class="cart_content">
        </div>
        <div class="cart-footer">
            <div class="total_price">المجموع: $0</div>
            <button class="checkout-btn" onclick="window.location.href='checkout.php'">إتمام الشراء</button>
        </div>
    </div>

    <script src="js/common.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/wishlist.js"></script>
    <script>
        document.querySelector('.cart-link').onclick = function(e) {
            e.preventDefault();
            document.getElementById("cart").classList.remove("hidden");
        };

        document.getElementById("close_cart").onclick = function() {
            document.getElementById("cart").classList.add("hidden");
        };

        document.querySelector('.user-dropdown').onclick = function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
        };
        document.body.onclick = function() {
            document.querySelector('.user-dropdown').classList.remove('active');
        };

        window.addEventListener('DOMContentLoaded', function() {
            let user = localStorage.getItem('user');
            if (user) {
                user = JSON.parse(user);
                let name = 'حسابي';
                if(user.name && typeof user.name === 'string') {
                    name = user.name.split(' ')[0] || 'حسابي';
                }
                if(user.picture) {
                    document.getElementById("user-avatar-box").innerHTML = `<img src="${user.picture}" alt="avatar" style="width:32px;height:32px;border-radius:50%;object-fit:cover;vertical-align:middle;">` + `<span class='avatar-login-span' style='display:block;font-size:1rem;color:white;font-weight:500;'>${name}</span>`;
                } else {
                    document.getElementById("user-avatar-box").innerHTML = `<img src="image/default-avatar.png" alt="avatar" style="width:32px;height:32px;border-radius:50%;object-fit:cover;vertical-align:middle;">` + `<span class='avatar-login-span' style='display:block;font-size:1rem;color:white;font-weight:500;'>${name}</span>`;
                }
                document.getElementById("login-btn").style.display = "none";
                document.getElementById("profile-link").style.display = "block";
                document.getElementById("logout-btn").style.display = "block";
            } else {
                document.getElementById("user-avatar-box").innerHTML = `<i class='fas fa-user' id='default-user-icon'></i><a href='login.html' id='avatar-login-link' style='display:block;text-decoration:none;'><span class='avatar-login-span' style='display:block;font-size:1rem;color:white;font-weight:500;'>حسابي</span></a>`;
                document.getElementById("login-btn").style.display = "block";
                document.getElementById("profile-link").style.display = "none";
                document.getElementById("logout-btn").style.display = "none";
            }
            document.getElementById("logout-btn").onclick = function(e) {
                e.preventDefault();
                localStorage.removeItem('user');
                document.getElementById("user-avatar-box").innerHTML = `<i class='fas fa-user' id='default-user-icon'></i><a href='login.html' id='avatar-login-link' style='display:block;text-decoration:none;'><span class='avatar-login-span' style='display:block;font-size:1rem;color:white;font-weight:500;'>حسابي</span></a>`;
                document.getElementById("login-btn").style.display = "block";
                document.getElementById("profile-link").style.display = "none";
                document.getElementById("logout-btn").style.display = "none";
            };
        });
        document.getElementById("profile-link").onclick = function(e) {
            e.preventDefault();
            window.location.href = "profile.php";
        };
    </script>

    <div class="categories-bar">
        <div class="container">
            <ul class="categories">
                <li class="category"><a href="#"><i class="fas fa-tshirt"></i> ملابس</a></li>
                <li class="category"><a href="#"><i class="fas fa-spray-can-sparkles"></i> عطور</a></li>
                <li class="category"><a href="#"><i class="fas fa-gem"></i> اكسسوارات</a></li>
                <li class="category"><a href="#"><i class="fas fa-clock"></i> ساعات</a></li>
                <li class="category"><a href="#"><i class="fas fa-shopping-bag"></i> حقائب</a></li>
                <li class="category"><a href="#"><i class="fas fa-shoe-prints"></i> أحذية</a></li>
            </ul>
        </div>
    </div>

