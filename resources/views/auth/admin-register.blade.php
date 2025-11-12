<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - Bitsa Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        :root {
            --bg-primary: #0a0a0a;
            --bg-secondary: #1a1a1a;
            --bg-card: #2a2a2a;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --accent-primary: #2563eb;
            --accent-danger: #ef4444;
            --accent-cyan: #00fff5;
            --border-color: #404040;
        }

        [data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --accent-primary: #2563eb;
            --accent-danger: #ef4444;
            --accent-cyan: #00b8b0;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Matrix Rain Effect */
        .matrix-rain {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.1;
            pointer-events: none;
        }

        .matrix-column {
            position: absolute;
            top: -100%;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: var(--accent-cyan);
            animation: matrixFall linear infinite;
        }

        @keyframes matrixFall {
            0% { top: -100%; }
            100% { top: 110%; }
        }

        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background: var(--accent-primary);
            color: white;
        }

        .back-button {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1000;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-button:hover {
            background: var(--accent-primary);
            color: white;
        }

        .register-container {
            max-width: 440px;
            width: 100%;
            margin: 2rem 0;
            position: relative;
            z-index: 10;
        }

        .register-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .register-header p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .form-control {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .form-control:focus {
            background: var(--bg-secondary);
            border-color: var(--accent-primary);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .password-input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
        }

        .btn-register {
            background: var(--accent-primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-register:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-register:disabled {
            background: var(--text-secondary);
            cursor: not-allowed;
            transform: none;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .login-link a {
            color: var(--accent-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
            border: none;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border-left: 4px solid var(--accent-danger);
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #86efac;
            border-left: 4px solid #22c55e;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: #93c5fd;
            border-left: 4px solid var(--accent-primary);
        }

        .verification-section {
            display: none;
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(59, 130, 246, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .verification-section.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        @media (max-width: 576px) {
            .register-container {
                margin: 1rem 0;
            }
            
            .register-card {
                padding: 1.5rem;
            }
            
            .theme-toggle,
            .back-button {
                top: 0.5rem;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Matrix Rain Background -->
    <div class="matrix-rain" id="matrixRain"></div>

    <!-- Back Button -->
    <a href="{{ route('welcome') }}" class="back-button">
        <i class="bi bi-arrow-left"></i>
    </a>

    <!-- Theme Toggle Button -->
    <button class="theme-toggle" id="themeToggle">
        <i class="bi bi-moon" id="themeIcon"></i>
    </button>

    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h2><i class="bi bi-shield-check"></i> Admin Registration</h2>
                <p>Create your administrator account</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Email Verification Alert -->
            <div class="alert alert-info" id="verificationAlert" style="display: none;">
                <i class="bi bi-envelope-check"></i>
                <span id="verificationMessage">Verification code sent to your email!</span>
            </div>

            <form action="{{ route('admin.register.submit') }}" method="POST" id="registerForm">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Choose a username" value="{{ old('username') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="password-input-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                        <button type="button" class="password-toggle" id="togglePasswordConfirmation">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Email Verification Section -->
                <div class="verification-section" id="verificationSection">
                    <h6 class="mb-3"><i class="bi bi-shield-check"></i> Admin Email Verification</h6>
                    <div class="mb-3">
                        <label for="verification_code" class="form-label">Verification Code</label>
                        <input type="text" class="form-control" id="verification_code" name="verification_code" placeholder="Enter verification code" maxlength="6">
                        <div class="form-text">Check your email for the admin verification code</div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="resendCode">
                        <i class="bi bi-arrow-clockwise"></i> Resend Code
                    </button>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms" style="font-size: 0.875rem;">
                        I agree to the admin terms and conditions
                    </label>
                </div>

                <button type="submit" class="btn-register" id="submitBtn">
                    <span id="submitText">Create Admin Account</span>
                    <span id="submitSpinner" class="spinner-border spinner-border-sm" style="display: none;"></span>
                </button>
            </form>

            <div class="login-link">
                Already have an account? <a href="{{ route('admin.login') }}">Login here</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize EmailJS
        (function() {
            emailjs.init("kBkG6pOzC0cI4zDFL"); // Replace with your EmailJS public key
        })();

        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;

        const currentTheme = localStorage.getItem('theme') || 'dark';
        html.setAttribute('data-theme', currentTheme);
        updateThemeIcon(currentTheme);

        themeToggle.addEventListener('click', () => {
            const theme = html.getAttribute('data-theme');
            const newTheme = theme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });

        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'bi bi-sun';
            } else {
                themeIcon.className = 'bi bi-moon';
            }
        }

        // Matrix Rain Effect
        function createMatrixRain() {
            const matrixRain = document.getElementById('matrixRain');
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789$#@%&*';
            
            for (let i = 0; i < 20; i++) {
                const column = document.createElement('div');
                column.className = 'matrix-column';
                column.style.left = `${Math.random() * 100}%`;
                column.style.animationDuration = `${8 + Math.random() * 4}s`;
                column.style.animationDelay = `${Math.random() * 5}s`;
                
                let text = '';
                for (let j = 0; j < 25; j++) {
                    text += chars[Math.floor(Math.random() * chars.length)] + '<br>';
                }
                column.innerHTML = text;
                matrixRain.appendChild(column);
            }
        }

        createMatrixRain();

        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        });

        togglePasswordConfirmation.addEventListener('click', function () {
            const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmation.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        });

        // Form validation and email verification
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const email = document.getElementById('email').value;
            const terms = document.getElementById('terms').checked;
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            const verificationSection = document.getElementById('verificationSection');
            const verificationAlert = document.getElementById('verificationAlert');
            const verificationMessage = document.getElementById('verificationMessage');

            // Basic validation
            if (!terms) {
                alert('You must agree to the terms and conditions!');
                return;
            }

            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }

            if (password.length < 8) {
                alert('Password must be at least 8 characters long!');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Sending Verification...';
            submitSpinner.style.display = 'inline-block';

            try {
                // Generate verification code
                const verificationCode = Math.floor(100000 + Math.random() * 900000).toString();

                // Send verification email using EmailJS
                const emailParams = {
                    to_email: email,
                    from_name: 'Bitsa Club Admin',
                    to_name: document.getElementById('name').value,
                    verification_code: verificationCode,
                    site_name: 'Bitsa Club',
                    account_type: 'Administrator'
                };

                await emailjs.send('service_0wpy66l','template_hw7kr58', emailParams);

                // Store verification code in session storage
                sessionStorage.setItem('admin_verification_code', verificationCode);
                sessionStorage.setItem('admin_email', email);

                // Show verification section
                verificationSection.classList.add('show');
                verificationAlert.style.display = 'block';
                verificationMessage.textContent = `Admin verification code sent to ${email}`;

                // Change form submission to include verification
                submitText.textContent = 'Verify & Create Admin Account';
                
            } catch (error) {
                console.error('Email sending failed:', error);
                alert('Failed to send verification email. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitSpinner.style.display = 'none';
            }
        });

        // Resend verification code
        document.getElementById('resendCode').addEventListener('click', async function() {
            const email = sessionStorage.getItem('admin_email');
            const resendBtn = this;

            if (!email) {
                alert('Please fill in the email field first.');
                return;
            }

            resendBtn.disabled = true;
            resendBtn.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm"></i> Sending...';

            try {
                const verificationCode = Math.floor(100000 + Math.random() * 900000).toString();

                const emailParams = {
                    to_email: email,
                    from_name: 'Bitsa Club Admin',
                    to_name: document.getElementById('name').value,
                    verification_code: verificationCode,
                    site_name: 'Bitsa Club',
                    account_type: 'Administrator'
                };

                await emailjs.send('YOUR_EMAILJS_SERVICE_ID', 'YOUR_EMAILJS_TEMPLATE_ID', emailParams);

                sessionStorage.setItem('admin_verification_code', verificationCode);
                alert('New admin verification code sent!');
            } catch (error) {
                console.error('Failed to resend code:', error);
                alert('Failed to resend verification code. Please try again.');
            } finally {
                resendBtn.disabled = false;
                resendBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Resend Code';
            }
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>