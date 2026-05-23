<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIBLIORA | Member Area</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { 
            background-color: #f8fafc; 
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: #0f172a;
        }
        .coming-soon-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        .coming-soon-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #38b000, #00b4d8);
        }
    </style>
</head>
<body>

    <div class="coming-soon-card text-center animate__animated animate__fadeIn">
        @if(session('success'))
            <div class="alert alert-success border-0 small py-2 px-3 mb-4 rounded-3 text-start" style="background-color: #ecfdf5; color: #065f46; font-size: 8.5pt;">
                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm mb-4" style="width: 64px; height: 64px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
            <i class="fa-solid fa-hourglass-half fs-4 animate__animated animate__pulse animate__infinite" style="color: #38b000;"></i>
        </div>
        
        <h3 class="fw-bold mb-2" style="letter-spacing: -0.5px;">User Dashboard Area</h3>
        <p class="text-muted small px-3 mb-4">The digital member terminal workspace is currently under tactical development parameters.</p>
        
        <div class="badge border px-3 py-2 text-uppercase font-monospace mb-4" style="color: #64748b; background-color: #f8fafc; font-size: 8pt; letter-spacing: 1px;">
            🚀 Status: Coming Soon
        </div>

        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0" id="userLogoutForm">
            @csrf
            <button type="submit" class="btn btn-light border py-2 px-4 fw-semibold rounded-3 text-secondary w-100" style="font-size: 9.5pt; background-color: #ffffff;">
                <i class="fa-solid fa-power-off text-danger me-2"></i> Close Session
            </button>
        </form>
    </div>

</body>
</html>