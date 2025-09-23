<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <title>Arab-Car-Part</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="icon" type="image/png" href="{{ url('/public') }}/web_assets/images/favicon.png" />
        <!-- Bootstrap 5.3.3 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />

        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />

        <!-- Font Awesome 6.5.0 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ url('/public') }}/web_assets/style.css" />
        <link rel="stylesheet" href="{{ url('/public') }}/web_assets/detail.css" />

        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    </head>

    <body>
        
        <!-- Header Navbar -->
        <nav class="navbar navbar-expand-lg arab-part-top-navber-add">
            <div class="container">
                <a class="navbar-brand navbar-logo-add" href="{{route('home')}}">
                    <img src="{{ url('/public') }}/web_assets/images/logo.png" alt="Logo" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <header class="header w-100">

                            <ul class="list-name">
                                <li><a href="{{route('web.index')}}">{{ __('messages.INDEX') }}</a></li>
                                <li><a href="{{route('web.matrix')}}">{{ __('messages.MATRIX') }}</a></li>
                                <li><a href="{{route('web.layer')}}">{{ __('messages.LAYER') }}</a></li>
                                <li>
                                    @auth
                                    <a href="{{route('web.logout')}}" class="login-btn">{{ __('messages.Logout') }}</a>
                                    @else  
                                    <button class="login-btn" onclick="openModal()">{{ __('messages.REGISTER/LOGIN') }}</button>
                                    @endauth
                                </li>
                                <!-- <li><a href="{{route('web.howWorks')}}">{{ __('messages.HOW IT WORKS') }}</a></li>
                                <li><a href="{{route('web.contact')}}">{{ __('messages.CONTACT') }}</a></li>
                                <li><a href="{{route('web.aboutUs')}}">{{ __('messages.ABOUT US') }}</a></li> -->
                            </ul>

                        <!-- Language & Login -->
                        <div class="language-login d-flex align-items-center">
                            <div class="dropdown english-drop me-3">
                                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ strtoupper(app()->getLocale()) }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" 
                                        href="{{ route('lang.switch', 'en') }}">
                                        EN
                                        @if(app()->getLocale() == 'en') @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}" 
                                        href="{{ route('lang.switch', 'ar') }}">
                                        AR
                                        @if(app()->getLocale() == 'ar') @endif
                                        </a>
                                    </li>
                                </ul>
                            </div>

                   

                            <!-- Modal -->
                            <!-- Login Modal -->
                            <div id="loginModal" class="modal">
                                <div class="modal-content">
                                    <span class="close" onclick="closeModal()">&times;</span>
                                    <div class="login-modal">
                                        <div class="logo">
                                            <img src="{{ url('/public') }}/web_assets/images/logo.png" alt="Logo" />
                                        </div>
                                        <h2>{{ __('messages.REGISTER/LOGIN') }}</h2>
                                        <form action="{{  route('web.login') }}" method="post">
                                            @csrf
                                            <div class="input-group" style="position: relative;">
                                                <label>{{ __('messages.username') }}</label>
                                                <i class="bi bi-envelope"></i>
                                                <input type="text" name="user_name" placeholder="{{ __('messages.username') }}" style="padding-left: 35px;" />
                                            </div>
                                            <div class="input-group">
                                                <label>{{ __('messages.password') }}</label>
                                                <div class="password-wrapper">
                                                    <i class="bi bi-lock-fill"></i>
                                                    <input type="password" name="password" id="loginPassword" placeholder="{{ __('messages.password') }}" />
                                                    <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                                                </div>
                                            </div>

                                            <!-- <div class="options">
                                                <label><input type="checkbox" name="remember" /> Remember me</label>
                                                <a href="#">Forgot Password?</a>
                                            </div> -->

                                            <button type="submit" class="login-btn">{{ __('messages.REGISTER/LOGIN') }}</button>
                                        </form>
                                        <div class="signup-link"><a href="#" data-bs-toggle="modal" data-bs-target="#signupModal" id="openSignupFromLogin">{{ __('messages.signup') }}</a></div>

                                        <!-- <div class="or-divider">or continue with</div>

                                        <div class="social-buttons">
                                            <a href="#"><img src="{{ url('/public') }}/web_assets/images/facebook.png" alt="facebook" /></a>
                                            <a href="#"><img src="{{ url('/public') }}/web_assets/images/apple.png" alt="apple" /></a>
                                            <a href="#"><img src="{{ url('/public') }}/web_assets/images/google.png" alt="google" /></a>
                                            <a href="#"><img src="{{ url('/public') }}/web_assets/images/linkedin.png" alt="linkedin" /></a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>

                            <!-- Signup Modal (Bootstrap) -->
                            <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true" data-bs-backdrop="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="login-modal">
                                                <div class="logo">
                                                    <img src="{{ url('/public') }}/web_assets/images/logo.png" alt="Logo" />
                                                </div>
                                                <h2>{{ __('messages.signup') }}</h2>
                                                <p>{{ __('messages.signup_note') }}</p>
                                                <form id="signupForm" method="post" action="{{route('web.signup')}}">
                                                    @csrf
                                                    <div class="input-add">
                                                        <div class="form-group">
                                                            <label>{{ __('messages.first_name') }}</label>
                                                            <div class="input-icon-wrapper">
                                                                <i class="bi bi-person-fill"></i>
                                                                <input type="text" placeholder="{{ __('messages.first_name') }}" name="first_name"  />
                                                            </div>
                                                            <span class="error-msg" id="error_first_name" style="font-size: 13px; display: inline; color: red;"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ __('messages.last_name') }}</label>
                                                            <div class="input-icon-wrapper">
                                                                <i class="bi bi-person-fill"></i>
                                                                <input type="text" placeholder="{{ __('messages.last_name') }}" name="last_name"  />
                                                            </div>
                                                            <span class="error-msg" id="error_last_name" style="font-size: 13px; display: inline; color: red;"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ __('messages.mobile_number') }}</label>
                                                        <div class="input-icon-wrapper">
                                                            <i class="bi bi-telephone"></i>
                                                            <input type="tel" name="mobile" placeholder="{{ __('messages.mobile_number') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" minlength="10" maxlength="15" required />
                                                        </div>
                                                        <span class="error-msg" id="error_mobile" style="font-size: 13px; display: inline; color: red;"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ __('messages.username') }}</label>
                                                        <div class="input-icon-wrapper">
                                                            <i class="bi bi-envelope"></i>
                                                            <input type="text" name="user_name" id="user_name_input" placeholder="{{ __('messages.username') }}"  oninput="checkUsernameAvailability()"/>
                                                        </div>
                                                        <small id="username_status" style="font-size: 13px; display: none;"></small>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ __('messages.password') }}</label>
                                                        <div class="input-icon-wrapper">
                                                            <i class="bi bi-lock-fill"></i>
                                                        <input type="password" name="password" placeholder="{{ __('messages.password') }}"  />
                                                            <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                                                        </div>
                                                    </div>
                                                        <!--div class="form-group register-add-text-input">
                                                            <label>Register As</label><br>
                                                            <div class="row register-radio-btn-add">
                                                                <div class="col-6">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="role" id="roleUser" value="user">
                                                                        <label class="form-check-label" for="roleUser">User</label>
                                                                </div>
                                                            </div>
                                                                <div class="col-6">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="role" id="roleSeller" value="seller" checked>
                                                                        <label class="form-check-label" for="roleSeller">Seller</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div-->
                                                        <input type="hidden" name="role" value="seller">
                                                    <button type="submit" class="register-btn">{{ __('messages.signup') }}</button>
                                                </form>

                                                <div class="signup-link">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">{{ __('messages.REGISTER/LOGIN') }}</a>
                                                </div>

                                                <!-- <div class="or-divider mt-3">or continue with</div>

                                                <div class="social-buttons">
                                                    <a href="#"><img src="{{ url('/public') }}/web_assets/images/facebook.png" alt="facebook" /></a>
                                                    <a href="#"><img src="{{ url('/public') }}/web_assets/images/apple.png" alt="apple" /></a>
                                                    <a href="#"><img src="{{ url('/public') }}/web_assets/images/google.png" alt="google" /></a>
                                                    <a href="#"><img src="{{ url('/public') }}/web_assets/images/linkedin.png" alt="linkedin" /></a>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <!-- <div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5>Select Location</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input id="searchInput" class="form-control mb-2" type="text" placeholder="Search location" autocomplete="off">

                                            <div id="map" style="height: 400px;"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="saveLocation" class="btn btn-success">OK</button>
                                        </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </header>
                </div>
            </div>
        </nav>

        
        @yield('content')

        <div class="rights-reserved">
            <p>{{ __('messages.footer1') }} <br> {{ __('messages.footer2') }} <br> {!! strtr(__('messages.footer3'), [':url' => route('web.getTermsConditions'),':url1' => route('web.getPrivacyPolicy'),]) !!} <br> {{ __('messages.footer4') }}</p>
            
            <p>Languages: 
                <span><a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" 
                                        href="{{ route('lang.switch', 'en') }}">English</a></span> | 
                <span><a class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}" 
                                        href="{{ route('lang.switch', 'ar') }}">لعربية</a> </span></p>
        </div>


    </body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
function checkUsernameAvailability() {
    const input = document.getElementById("user_name_input");
    const statusEl = document.getElementById("username_status");
    const username = input.value.trim();

    // If empty: show warning only if user had typed before
    if (username == "") {
        statusEl.style.display = input.dataset.touched === "true" ? "inline" : "none";
        statusEl.textContent = input.dataset.touched === "true" ? "⚠️ Please enter user name" : "";
        statusEl.style.color = "orange";
        return; // ❌ Stop here: don't run fetch for empty input
    }
    if (username != "") {
    // ✅ Mark as touched (user typed something)
    input.dataset.touched = "true";

    // ✅ Now run the fetch
    fetch(`{{ url('/check-username') }}?username=${encodeURIComponent(username)}`)
        .then(response => response.json())
        .then(data => {
            statusEl.style.display = 'inline';
            if (data.available) {
                statusEl.textContent = '✅ Username is available';
                statusEl.style.color = 'green';
            } else {
                statusEl.textContent = '❌ Username is already taken';
                statusEl.style.color = 'red';
            }
        })
        .catch(err => {
            console.error("Error:", err);
            statusEl.textContent = '⚠️ Error checking username';
            statusEl.style.color = 'orange';
            statusEl.style.display = 'inline';
        });
    }
}
</script>



    <script>
        $(document).ready(function () {
            $('#signupForm').validate({
                rules: {
                    first_name: "required",
                    last_name: "required",
                    mobile: {
                        required: true,
                        digits: true,
                        minlength: 10
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: "required",
                    role: "required"
                },
                messages: {
                    first_name: "Please enter your first name",
                    last_name: "Please enter your last name",
                    mobile: {
                        required: "Please enter your mobile number",
                        digits: "Please enter a valid number",
                        minlength: "Mobile number must be at least 10 digits"
                    },
                    email: "Please enter a valid email address",
                    password: "Please provide a password",
                    role: "Please select a role"
                },
                errorElement: "span", // This is important so CSS applies
                errorPlacement: function(error, element) {
                    const name = element.attr("name");
                    if (name) {
                        $(`#error_${name}`).html(error); // Inject into specific span
                    } else {
                        error.insertAfter(element); // fallback
                    }
                }
            });

            $('#loginForm').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: "required"
                },
                messages: {
                    email: "Please enter a valid email address",
                    password: "Please provide a password"
                },
                errorElement: "span", // This is important so CSS applies
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                }
            });
        });
        </script>
        <script>
            @if(session('registration_success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('registration_success') }}',
                    text: 'Account created successfully',
                    confirmButtonColor: '#3085d6',
                    timer: 5000
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33',
                    timer: 5000
                });
            @endif
        </script>
    <script>
        document.getElementById("togglePassword").addEventListener("click", function () {
            const passwordInput = document.getElementById("loginPassword");
            const icon = this;

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        });
    </script>

    <script>
        function openModal() {
            document.getElementById("loginModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("loginModal").style.display = "none";
        }

        // Optional: Close modal when clicking outside the modal content
        window.onclick = function (event) {
            const modal = document.getElementById("loginModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".sign-up-log").forEach(function (btn) {
                btn.addEventListener("click", function (e) {
                    e.preventDefault();
                    // Hide the login modal
                    var loginModal = document.getElementById("loginModal");
                    if (loginModal) {
                        var bootstrapModal = bootstrap.Modal.getInstance(loginModal) || new bootstrap.Modal(loginModal);
                        bootstrapModal.hide();
                    }

                    // Show the signup modal
                    var signupModalEl = document.getElementById("signupModal");
                    if (signupModalEl) {
                        var signupModal = bootstrap.Modal.getInstance(signupModalEl) || new bootstrap.Modal(signupModalEl);
                        signupModal.show();
                    }
                });
            });
        });
    </script>

    <script>
        document.getElementById("openSignupFromLogin").addEventListener("click", function (e) {
            e.preventDefault();

            // Close the custom login modal
            document.getElementById("loginModal").style.display = "none";

            // Open the Bootstrap signup modal
            const signupModal = new bootstrap.Modal(document.getElementById("signupModal"));
            signupModal.show();
        });
    </script>

    <script>
        function closeModal() {
            document.getElementById("loginModal").style.display = "none";
        }

        // OPTIONAL: Close when clicking outside the modal content
        window.onclick = function (event) {
            const modal = document.getElementById("loginModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };
    </script>

    
    
    
    @stack('scripts')
</html>
