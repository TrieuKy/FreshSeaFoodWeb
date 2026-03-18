<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINBSB - <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin Panel'; ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Base CSS -->
    <link rel="stylesheet" href="/banhaisan/public/css/style.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="/banhaisan/public/css/admin_bsb.css">
</head>
<body class="theme-red">
    
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="/banhaisan/admin/dashboard">ADMINBSB - MATERIAL DESIGN</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <!-- Notifications -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">notifications</i>
                            <span class="label-count">7</span>
                        </a>
                    </li>
                    <!-- Tasks -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">flag</i>
                            <span class="label-count">9</span>
                        </a>
                    </li>
                    <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <section>
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <?php if(!empty($_SESSION['avatar'])): ?>
                        <img src="/banhaisan/public/images/avatars/<?php echo htmlspecialchars($_SESSION['avatar']); ?>" width="48" height="48" alt="User" />
                    <?php else: ?>
                        <div style="width:48px;height:48px;border-radius:50%;background:#FF9800;color:white;font-size:1.5rem;display:flex;align-items:center;justify-content:center;font-weight:700;">
                            <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username'] ?? 'Admin User'); ?></div>
                    <div class="email"><?php echo htmlspecialchars($_SESSION['username'] ?? 'admin'); ?>@example.com</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="/banhaisan/auth/logout"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Menu -->
            <div class="menu">
                <div class="main-navigation-title">MAIN NAVIGATION</div>
                <ul class="list">
                    
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : ''; ?>">
                        <a href="/banhaisan/admin/dashboard">
                            <i class="material-icons" style="color:#F44336;">home</i>
                            <span style="color:#F44336;font-weight:700;">Home</span>
                        </a>
                    </li>
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], '/orders') !== false ? 'active' : ''; ?>">
                        <a href="/banhaisan/admin/orders">
                            <i class="material-icons">receipt</i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], '/products') !== false ? 'active' : ''; ?>">
                        <a href="/banhaisan/admin/products">
                            <i class="material-icons">layers</i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], '/categories') !== false ? 'active' : ''; ?>">
                        <a href="/banhaisan/admin/categories">
                            <i class="material-icons">widgets</i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], '/customers') !== false ? 'active' : ''; ?>">
                        <a href="/banhaisan/admin/customers">
                            <i class="material-icons">people</i>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li class="<?php echo strpos($_SERVER['REQUEST_URI'], '/vouchers') !== false ? 'active' : ''; ?>">
                        <a href="/banhaisan/admin/vouchers">
                            <i class="material-icons">local_offer</i>
                            <span>Vouchers</span>
                        </a>
                    </li>
                     <li class="<?php echo strpos($_SERVER['REQUEST_URI'], '/statistics') !== false ? 'active' : ''; ?>">
                        <a href="/banhaisan/admin/statistics">
                            <i class="material-icons">pie_chart</i>
                            <span>Statistics</span>
                        </a>
                    </li>
                    
                    <!-- Dummies from mockup -->
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">text_fields</i>
                            <span>Typography</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">build</i>
                            <span>Helper Classes</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2016 - 2017 <a href="javascript:void(0);">AdminBSB - Material Design</a>.
                </div>
                <div class="version">
                    <b>Version: </b> 1.0.5
                </div>
            </div>
        </aside>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <?php echo $content; ?>
        </div>
    </section>
</body>
</html>
