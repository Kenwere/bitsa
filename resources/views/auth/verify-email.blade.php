<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Bitsa Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap');

        :root {
            --bg-primary: #000000;
            --text-primary: #ffffff;
            --accent-cyan: #00fff5;
            --card-bg: rgba(15, 15, 25, 0.7);
        }

        [data-theme="light"] {
            --bg-primary: #f8f9fa;
            --text-primary: #000000;
            --accent-cyan: #00b8b0;
            --card-bg: rgba(255, 255, 255, 0.9);
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-primary);
            color: var(--text-primary);
            padding: 2rem 1rem;
            position: relative;
            overflow: hidden;
        }

        .verification-container {
            max-width: 500px;
            width: 100%;
            z-index: 10;
        }

        .verification-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .verification-icon {
            font-size: 4rem;
            color: var(--accent-cyan);
            margin-bottom: 1.5rem;
        }

        .btn-verify {
            background: var(--accent-cyan);
            color: #000;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 255, 245, 0.3);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #d4edda;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #f8d7da;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <div class="verification-card">
            <div class="verification-icon">
                <i class="bi bi-envelope-check"></i>
            </div>
            
            <h2 class="mb-3">Verify Your Email Address</h2>
            
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <p class="mb-4">
                We've sent a verification link to your email address. 
                Please check your inbox and click the link to verify your account.
            </p>

            <p class="mb-4 text-muted">
                If you didn't receive the email, check your spam folder or request a new verification link.
            </p>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-verify">
                    <i class="bi bi-send"></i> Resend Verification Email
                </button>
            </form>

            <div class="mt-4">
                <a href="{{ route('user.dashboard') }}" class="text-muted">Skip for now</a>
            </div>
        </div>
    </div>
</body>
</html>