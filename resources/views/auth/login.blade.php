<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIBLIORA | Secure Authentication Gate</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --main-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-color: #f1f5f9;
            --input-bg: #f8fafc;
        }

        body { 
            background-color: var(--main-bg); 
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: var(--text-dark);
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .card-login {
            background-color: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03) !important;
            position: relative;
            overflow: hidden;
        }

        /* Top Accent Glow - Khas Aksen Hijau BIBLIORA */
        .card-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #38b000, #00b4d8);
        }

        /* Input Customizations */
        .form-control {
            background-color: var(--input-bg) !important;
            border-color: var(--border-color) !important;
            color: var(--text-dark) !important;
            font-size: 9.5pt;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #38b000 !important;
            box-shadow: 0 0 0 4px rgba(56, 176, 0, 0.08) !important;
            background-color: #ffffff !important;
        }

        .form-control::placeholder {
            color: var(--text-muted);
            opacity: 0.5;
        }
    </style>
</head>
<body>

    <div class="login-container animate__animated animate__fadeIn">
        
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm mb-3" style="width: 54px; height: 54px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <i class="fa-solid fa-book-bookmark fs-4" style="color: #38b000;"></i>
            </div>
            <h4 class="fw-bold m-0" style="letter-spacing: -0.5px;">Welcome Back</h4>
            <p class="text-muted small mt-1 mb-0">Sign in to access your library dashboard workspace.</p>
        </div>

        <div class="card card-login border-0 p-4">
            
            @if(session('success'))
                <div class="alert alert-success border-0 small py-2 px-3 mb-3 rounded-3" style="background-color: #ecfdf5; color: #065f46; font-size: 8.5pt;">
                    <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Identity Credential</label>
                    <input type="text" name="username" class="form-control py-2.5 rounded-3 @error('username') is-invalid @enderror" required placeholder="Username" value="{{ old('username') }}">
                    @error('username')
                        <span class="text-danger d-block mt-1" style="font-size: 8pt; font-weight: 500;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Security Password</label>
                    <input type="password" name="password" class="form-control py-2.5 rounded-3 @error('password') is-invalid @enderror" required placeholder="••••••••">
                    @error('password')
                        <span class="text-danger d-block mt-1" style="font-size: 8pt; font-weight: 500;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold border-0 shadow-sm rounded-3 mb-2" style="font-size: 9.5pt; background-color: #0f172a;">
                    Authenticate Session
                </button>
            </form>
        </div>

        <div class="text-center mt-4">
            <small class="text-muted" style="font-size: 7.5pt; letter-spacing: 0.3px;">BIBLIORA ECOSYSTEM © 2026 • ALL RIGHTS RESERVED</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>