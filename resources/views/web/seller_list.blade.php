<!DOCTYPE html>
<html lang="en">

<head>
    <title>Arab-Car-Part</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" type="image/png" href="./imges/favicon.png" />
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome 6.5.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ url('/public') }}/web_assets_new/style.css" />
    <link rel="stylesheet" href="{{ url('/public') }}/web_assets_new/seller.css" />

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg arab-part-top-navber-add">
        <div class="container">
            <a class="navbar-brand navbar-logo-add" href="#">
                <img src="{{ url('/public') }}/web_assets_new/images/logo.png" alt="Logo" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
                aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <header class="header w-100 d-flex justify-content-between align-items-center flex-wrap">
                    <!-- Search Box -->
                    <div class="search-box">
                        <input type="text" placeholder="Search for parts, categories..." class="form-control" />
                        <button><i class="bi bi-search"></i></button>
                    </div>

                    <!-- Contact Info -->
                    <div class="contact-info d-flex">
                        <div class="phone me-3 d-flex align-items-center">
                            <i class="bi bi-telephone me-2"></i>
                            <div>
                                <small>Need help?</small>
                                <div class="email-text">(012) 345-0111</div>
                            </div>
                        </div>
                        <div class="email d-flex align-items-center">
                            <i class="bi bi-envelope me-2"></i>
                            <div class="need-text">
                                <small>Email</small>
                                <div class="email-text">arabcarpart@info.com</div>
                            </div>
                        </div>
                    </div>

                    <!-- Language & Login -->
                    <div class="language-login d-flex align-items-center">
                        <div class="dropdown english-drop me-3">
                            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                EN
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">EN</a></li>
                                <li><a class="dropdown-item" href="#">AR</a></li>
                                <li><a class="dropdown-item" href="#">FR</a></li>
                            </ul>
                        </div>
                        <!-- Button -->
                        <button class="login-btn" onclick="openModal()">
                            <img src="{{ url('/public') }}/web_assets_new/images/login-icon.png" alt="User" />
                            Login/Register
                        </button>

                        <!-- Modal -->
                        <!-- Login Modal -->
                        <div id="loginModal" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal()">&times;</span>
                                <div class="login-modal">
                                    <div class="logo">
                                        <img src="{{ url('/public') }}/web_assets_new/images/logo.png" alt="Logo" />
                                    </div>
                                    <h2>Login</h2>

                                    <div class="input-group" style="position: relative;">
                                        <label>Email</label>

                                        <i class="bi bi-envelope"></i>
                                        <input type="email" placeholder="Enter your email address"
                                            style="padding-left: 35px;" />
                                    </div>
                                    <div class="input-group">
                                        <label>Password</label>
                                        <div class="password-wrapper">
                                            <i class="bi bi-lock-fill"></i>
                                            <input type="password" id="loginPassword"
                                                placeholder="Enter your Password" />
                                            <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                                        </div>
                                    </div>

                                    <div class="options">
                                        <label><input type="checkbox" /> Remember me</label>
                                        <a href="#">Forgot Password?</a>
                                    </div>

                                    <button class="login-btn">Login</button>

                                    <div class="signup-link">Don't have an account? <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#signupModal" id="openSignupFromLogin">Sign up</a></div>

                                    <div class="or-divider">or continue with</div>

                                    <div class="social-buttons">
                                        <a href="#"><img src="{{ url('/public') }}/web_assets_new/images/facebook.png" alt="facebook" /></a>
                                        <a href="#"><img src="{{ url('/public') }}/web_assets_new/images/apple.png" alt="apple" /></a>
                                        <a href="#"><img src="{{ url('/public') }}/web_assets_new/images/google.png" alt="google" /></a>
                                        <a href="#"><img src="{{ url('/public') }}/web_assets_new/images/linkedin.png" alt="linkedin" /></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Signup Modal (Bootstrap) -->
                        <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel"
                            aria-hidden="true" data-bs-backdrop="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="login-modal">
                                            <div class="logo">
                                                <img src="{{ url('/public') }}/web_assets_new/images/logo.png" alt="Logo" />
                                            </div>
                                            <h2>Sign up</h2>

                                            <form>
                                                <div class="input-add">
                                                    <div class="form-group">
                                                        <label>First Name</label>
                                                        <div class="input-icon-wrapper">
                                                            <i class="bi bi-person-fill"></i>
                                                            <input type="text" placeholder="First name" required />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Last Name</label>

                                                        <div class="input-icon-wrapper">
                                                            <i class="bi bi-person-fill"></i>
                                                            <input type="text" placeholder="Last name" required />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Mobile Number</label>

                                                    <div class="input-icon-wrapper">
                                                        <i class="bi bi-telephone"></i>
                                                        <input type="tel" placeholder="Enter your mobile number"
                                                            required />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>

                                                    <div class="input-icon-wrapper">
                                                        <i class="bi bi-envelope"></i>
                                                        <input type="email" placeholder="Enter your email address"
                                                            required />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Password</label>

                                                    <div class="input-icon-wrapper">
                                                        <i class="bi bi-lock-fill"></i>
                                                        <input type="password" placeholder="Enter your password"
                                                            required />
                                                        <i class="bi bi-eye-slash toggle-password"
                                                            id="togglePassword"></i>
                                                    </div>
                                                </div>
                                                <button type="submit" class="register-btn">Register</button>
                                            </form>

                                            <div class="signup-link">
                                                Already have an account?
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal"
                                                    data-bs-dismiss="modal">Login</a>
                                            </div>

                                            <div class="or-divider mt-3">or continue with</div>

                                            <div class="social-buttons">
                                                <a href="#"><img src="{{ url('/public') }}/web_assets_newimages/facebook.png" alt="facebook" /></a>
                                                <a href="#"><img src="{{ url('/public') }}/web_assets_new/images/apple.png" alt="apple" /></a>
                                                <a href="#"><img src="{{ url('/public') }}/web_assets_new/images/google.png" alt="google" /></a>
                                                <a href="#"><img src="{{ url('/public') }}/web_assets_new/images/linkedin.png" alt="linkedin" /></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            </div>
        </div>
    </nav>

    <!-- Secondary Navigation -->

    <nav class="navbar to-nav-add d-flex justify-content-between align-items-center px-3 py-2">
        <div class="container">
            <div class="nav-left">
                <button class="departments-btn"><i class="fas fa-bars"></i> All Departments <i
                        class="fas fa-caret-down"></i></button>
            </div>

            <ul class="nav-menu list-unstyled d-flex mb-0">
                <li class="mx-2"><a href="#">Home</a></li>
                <li class="mx-2"><a href="#">About Us</a></li>
                <li class="mx-2 dropdown">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle insurance-btn" type="button"
                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Insurance & Agencies
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Auto Insurance</a></li>
                            <li><a class="dropdown-item" href="#">Home Insurance</a></li>
                            <li><a class="dropdown-item" href="#">Health Agencies</a></li>
                        </ul>
                    </div>
                </li>

                <li class="mx-2"><a href="#">FAQ</a></li>
                <li class="mx-2"><a href="#">Blog</a></li>
                <li class="mx-2"><a href="#">Contact</a></li>
            </ul>

            <div class="nav-right">
                <button class="upload-btn"><i class="bi bi-car-front-fill"></i> Upload Inventory</button>
            </div>
        </div>
    </nav>


    <div class="seller-page-add">
        <!-- Banner Image -->
        <div class="banner">
            <h1 class="text-center text-white">Seller Shop Name</h1>
        </div>







        <div class="container seller-add-content">
            <div class="row mb-3">

                <div class="seller-grid">

                    <div class="seller-card">
                        <!-- Profile Section -->
                        <div class="profile">
                            <!-- Logo and Shop Info -->
                            <div class="logo-section">
                                <img src="https://votivetechnology.in/autopart/public/web_assets/images/logo.png"
                                    alt="Logo" class="logo" />
                                <div class="shop-info">
                                    <h2>Arab Car Part</h2>
                                    <p>Abu Dhabi, United Arab</p>
                                </div>

                                <!-- Call/Contact Icons -->
                                <div class="icons-call">
                                    <a href="#"><i class="bi bi-whatsapp"></i></a>
                                    <a href="#"><i class="bi bi-telephone-fill"></i></a>
                                </div>
                            </div>


                            <div class="inner-image-banner">
                                <img src="{{ url('/public') }}/web_assets_new/images/Inventory-banner-img.png" alt="Logo" class="logo" />
                            </div>

                            <!-- Description -->
                            <p class="description">“We specialize in Japanese vehicles; engines and gearboxes. Located
                                in
                                Riyadh Industrial Area.”</p>

                            <!-- Service Tags -->
                            <div class="service-tags">
                                <span class="tag"><i class="bi bi-truck"></i> Delivery inside country</span>
                                <span class="tag"><i class="bi bi-globe-americas"></i> Delivery outside country</span>
                                <span class="tag"><i class="bi bi-gear"></i> Installation available</span>
                                <span class="tag"><i class="bi bi-shield-check"></i> Warranty provided</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="buttons">
                                <a href="/inventory.html" class="btn primary">View Inventory</a>
                                <div class="mobile-btn-add">
                                    <a href="#" class="btn"><i class="bi bi-share"></i> Share Shop</a>
                                    <a href="#" class="btn"><i class="bi bi-download"></i> Download QR Code</a>
                                </div>
                            </div>

                            <div class="seller-location">
                                <i class="bi bi-geo-alt"></i>
                                <a href="https://www.google.com/maps?q=Riyadh+Industrial+Area" target="_blank">Riyadh
                                    Industrial Area</a>
                            </div>
                        </div>
                    </div>



                    <div class="seller-card">
                        <!-- Profile Section -->
                        <div class="profile">
                            <!-- Logo and Shop Info -->
                            <div class="logo-section">
                                <img src="https://votivetechnology.in/autopart/public/web_assets/images/logo.png"
                                    alt="Logo" class="logo" />
                                <div class="shop-info">
                                    <h2>Arab Car Part</h2>
                                    <p>Abu Dhabi, United Arab</p>
                                </div>

                                <!-- Call/Contact Icons -->
                                <div class="icons-call">
                                    <a href="#"><i class="bi bi-whatsapp"></i></a>
                                    <a href="#"><i class="bi bi-telephone-fill"></i></a>
                                </div>
                            </div>


                            <div class="inner-image-banner">
                                <img src="{{ url('/public') }}/web_assets_new/images/Inventory-banner-img.png" alt="Logo" class="logo" />
                            </div>
                            <!-- Description -->
                            <p class="description">“We specialize in Japanese vehicles; engines and gearboxes. Located
                                in
                                Riyadh Industrial Area.”</p>

                            <!-- Service Tags -->
                            <div class="service-tags">
                                <span class="tag"><i class="bi bi-truck"></i> Delivery inside country</span>
                                <span class="tag"><i class="bi bi-globe-americas"></i> Delivery outside country</span>
                                <span class="tag"><i class="bi bi-gear"></i> Installation available</span>
                                <span class="tag"><i class="bi bi-shield-check"></i> Warranty provided</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="buttons">
                                <a href="/inventory.html" class="btn primary">View Inventory</a>
                                <div class="mobile-btn-add">
                                    <a href="#" class="btn"><i class="bi bi-share"></i> Share Shop</a>
                                    <a href="#" class="btn"><i class="bi bi-download"></i> Download QR Code</a>
                                </div>
                            </div>

                            <div class="seller-location">
                                <i class="bi bi-geo-alt"></i>
                                <a href="https://www.google.com/maps?q=Riyadh+Industrial+Area" target="_blank">Riyadh
                                    Industrial Area</a>
                            </div>
                        </div>
                    </div>


                    <div class="seller-card">
                        <!-- Profile Section -->
                        <div class="profile">
                            <!-- Logo and Shop Info -->
                            <div class="logo-section">
                                <img src="https://votivetechnology.in/autopart/public/web_assets/images/logo.png"
                                    alt="Logo" class="logo" />
                                <div class="shop-info">
                                    <h2>Arab Car Part</h2>
                                    <p>Abu Dhabi, United Arab</p>
                                </div>

                                <!-- Call/Contact Icons -->
                                <div class="icons-call">
                                    <a href="#"><i class="bi bi-whatsapp"></i></a>
                                    <a href="#"><i class="bi bi-telephone-fill"></i></a>
                                </div>
                            </div>

                            <div class="inner-image-banner">
                                <img src="{{ url('/public') }}/web_assets_new/images/Inventory-banner-img.png" alt="Logo" class="logo" />
                            </div>

                            <!-- Description -->
                            <p class="description">“We specialize in Japanese vehicles; engines and gearboxes. Located
                                in
                                Riyadh Industrial Area.”</p>

                            <!-- Service Tags -->
                            <div class="service-tags">
                                <span class="tag"><i class="bi bi-truck"></i> Delivery inside country</span>
                                <span class="tag"><i class="bi bi-globe-americas"></i> Delivery outside country</span>
                                <span class="tag"><i class="bi bi-gear"></i> Installation available</span>
                                <span class="tag"><i class="bi bi-shield-check"></i> Warranty provided</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="buttons">
                                <a href="/inventory.html" class="btn primary">View Inventory</a>
                                <div class="mobile-btn-add">
                                    <a href="#" class="btn"><i class="bi bi-share"></i> Share Shop</a>
                                    <a href="#" class="btn"><i class="bi bi-download"></i> Download QR Code</a>
                                </div>
                            </div>

                            <div class="seller-location">
                                <i class="bi bi-geo-alt"></i>
                                <a href="https://www.google.com/maps?q=Riyadh+Industrial+Area" target="_blank">Riyadh
                                    Industrial Area</a>
                            </div>
                        </div>
                    </div>


                </div>


            </div>




            <div class="row mb-3">

                <div class="seller-grid">

                    <div class="seller-card">
                        <!-- Profile Section -->
                        <div class="profile">
                            <!-- Logo and Shop Info -->
                            <div class="logo-section">
                                <img src="https://votivetechnology.in/autopart/public/web_assets/images/logo.png"
                                    alt="Logo" class="logo" />
                                <div class="shop-info">
                                    <h2>Arab Car Part</h2>
                                    <p>Abu Dhabi, United Arab</p>
                                </div>

                                <!-- Call/Contact Icons -->
                                <div class="icons-call">
                                    <a href="#"><i class="bi bi-whatsapp"></i></a>
                                    <a href="#"><i class="bi bi-telephone-fill"></i></a>
                                </div>
                            </div>


                            <div class="inner-image-banner">
                                <img src="{{ url('/public') }}/web_assets_new/images/Inventory-banner-img.png" alt="Logo" class="logo" />
                            </div>

                            <!-- Description -->
                            <p class="description">“We specialize in Japanese vehicles; engines and gearboxes. Located
                                in
                                Riyadh Industrial Area.”</p>

                            <!-- Service Tags -->
                            <div class="service-tags">
                                <span class="tag"><i class="bi bi-truck"></i> Delivery inside country</span>
                                <span class="tag"><i class="bi bi-globe-americas"></i> Delivery outside country</span>
                                <span class="tag"><i class="bi bi-gear"></i> Installation available</span>
                                <span class="tag"><i class="bi bi-shield-check"></i> Warranty provided</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="buttons">
                                <a href="#" class="btn primary">View Inventory</a>
                                <div class="mobile-btn-add">
                                    <a href="#" class="btn"><i class="bi bi-share"></i> Share Shop</a>
                                    <a href="#" class="btn"><i class="bi bi-download"></i> Download QR Code</a>
                                </div>
                            </div>

                            <div class="seller-location">
                                <i class="bi bi-geo-alt"></i>
                                <a href="https://www.google.com/maps?q=Riyadh+Industrial+Area" target="_blank">Riyadh
                                    Industrial Area</a>
                            </div>
                        </div>
                    </div>



                    <div class="seller-card">
                        <!-- Profile Section -->
                        <div class="profile">
                            <!-- Logo and Shop Info -->
                            <div class="logo-section">
                                <img src="https://votivetechnology.in/autopart/public/web_assets/images/logo.png"
                                    alt="Logo" class="logo" />
                                <div class="shop-info">
                                    <h2>Arab Car Part</h2>
                                    <p>Abu Dhabi, United Arab</p>
                                </div>

                                <!-- Call/Contact Icons -->
                                <div class="icons-call">
                                    <a href="#"><i class="bi bi-whatsapp"></i></a>
                                    <a href="#"><i class="bi bi-telephone-fill"></i></a>
                                </div>
                            </div>


                            <div class="inner-image-banner">
                                <img src="{{ url('/public') }}/web_assets_new/images/Inventory-banner-img.png" alt="Logo" class="logo" />
                            </div>
                            <!-- Description -->
                            <p class="description">“We specialize in Japanese vehicles; engines and gearboxes. Located
                                in
                                Riyadh Industrial Area.”</p>

                            <!-- Service Tags -->
                            <div class="service-tags">
                                <span class="tag"><i class="bi bi-truck"></i> Delivery inside country</span>
                                <span class="tag"><i class="bi bi-globe-americas"></i> Delivery outside country</span>
                                <span class="tag"><i class="bi bi-gear"></i> Installation available</span>
                                <span class="tag"><i class="bi bi-shield-check"></i> Warranty provided</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="buttons">
                                <a href="#" class="btn primary">View Inventory</a>
                                <div class="mobile-btn-add">
                                    <a href="#" class="btn"><i class="bi bi-share"></i> Share Shop</a>
                                    <a href="#" class="btn"><i class="bi bi-download"></i> Download QR Code</a>
                                </div>
                            </div>

                            <div class="seller-location">
                                <i class="bi bi-geo-alt"></i>
                                <a href="https://www.google.com/maps?q=Riyadh+Industrial+Area" target="_blank">Riyadh
                                    Industrial Area</a>
                            </div>
                        </div>
                    </div>


                    <div class="seller-card">
                        <!-- Profile Section -->
                        <div class="profile">
                            <!-- Logo and Shop Info -->
                            <div class="logo-section">
                                <img src="https://votivetechnology.in/autopart/public/web_assets/images/logo.png"
                                    alt="Logo" class="logo" />
                                <div class="shop-info">
                                    <h2>Arab Car Part</h2>
                                    <p>Abu Dhabi, United Arab</p>
                                </div>

                                <!-- Call/Contact Icons -->
                                <div class="icons-call">
                                    <a href="#"><i class="bi bi-whatsapp"></i></a>
                                    <a href="#"><i class="bi bi-telephone-fill"></i></a>
                                </div>
                            </div>

                            <div class="inner-image-banner">
                                <img src="{{ url('/public') }}/web_assets_new/images/Inventory-banner-img.png" alt="Logo" class="logo" />
                            </div>

                            <!-- Description -->
                            <p class="description">“We specialize in Japanese vehicles; engines and gearboxes. Located
                                in
                                Riyadh Industrial Area.”</p>

                            <!-- Service Tags -->
                            <div class="service-tags">
                                <span class="tag"><i class="bi bi-truck"></i> Delivery inside country</span>
                                <span class="tag"><i class="bi bi-globe-americas"></i> Delivery outside country</span>
                                <span class="tag"><i class="bi bi-gear"></i> Installation available</span>
                                <span class="tag"><i class="bi bi-shield-check"></i> Warranty provided</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="buttons">
                                <a href="#" class="btn primary">View Inventory</a>
                                <div class="mobile-btn-add">
                                    <a href="#" class="btn"><i class="bi bi-share"></i> Share Shop</a>
                                    <a href="#" class="btn"><i class="bi bi-download"></i> Download QR Code</a>
                                </div>
                            </div>

                            <div class="seller-location">
                                <i class="bi bi-geo-alt"></i>
                                <a href="https://www.google.com/maps?q=Riyadh+Industrial+Area" target="_blank">Riyadh
                                    Industrial Area</a>
                            </div>
                        </div>
                    </div>


                </div>


            </div>








        </div>
    </div>





    <!-- listing-html-code-start -->

    <footer class="arab-footer-section text-white py-5">
        <div class="container">
            <div class="row arab-footer-inner">
                <div class="col-md-3 arab-footer-two">
                    <h5>Office Address</h5>

                    <p class="address-text">
                        Head office:<br />
                        <span>Address Will Goes Here</span>
                    </p>
                    <div class="social-icons">
                        <i class="fa fa-facebook" aria-hidden="true"></i>
                        <i class="fa fa-twitter" aria-hidden="true"></i>
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="col-md-3 arab-footer-three">
                    <h5>Contact</h5>
                    <div class="contact-profile">
                        <img src="{{ url('/public') }}/web_assets_new/images/footer-icons.png" alt="Profile" class="profile-img" />
                        <div class="profile-info">
                            <div class="name">Name Here</div>
                            <div class="phone">(+012) 345-67890</div>
                        </div>
                    </div>

                    <div class="contact-profile">
                        <img src="{{ url('/public') }}/web_assets_new/images/footer-icon-phone.png" alt="Profile" class="profile-img" />
                        <div class="profile-info">
                            <div class="name">Hotline:</div>
                            <div class="phone">(+012) 345-67890</div>
                        </div>
                    </div>

                    <div class="contact-profile">
                        <img src="{{ url('/public') }}/web_assets_new/images/footer-email.png" alt="Profile" class="profile-img" />
                        <div class="profile-info">
                            <div class="name">Email:</div>
                            <div class="phone">arabcarpart@info.com</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 arab-footer-three">
                    <h5>Our Company</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="#" class="text-decoration-none"> <i class="bi bi-chevron-right"></i>Home</a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none"> <i class="bi bi-chevron-right"></i>About</a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none"> <i class="bi bi-chevron-right"></i>Insurance &
                                Agencies</a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none"> <i class="bi bi-chevron-right"></i>FAQ</a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none"> <i class="bi bi-chevron-right"></i>Blog </a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none"> <i class="bi bi-chevron-right"></i>Upload
                                Inventory</a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none"> <i class="bi bi-chevron-right"></i>Contact Us</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-3 arab-footer-four">
                    <h5>Newsletter</h5>
                    <form class="">
                        <p class="text-white">Sign up to receive the latest articles</p>
                        <div class="mb-2">
                            <input type="email" class="form-control" placeholder="Your email address" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Sign Up</button>

                        <label class="form-check-box text-white">
                            <input type="checkbox" name="terms" required />
                            I have read and agree to the terms & conditions
                        </label>
                    </form>
                </div>
            </div>
        </div>
    </footer>

    <div class="rights-reserved">
        <p>© 2025 ArabCarPart. All rights reserved.</p>
    </div>
</body>

</html>