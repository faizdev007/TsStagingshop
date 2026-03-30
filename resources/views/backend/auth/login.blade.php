@extends('backend.layouts.auth')

@section('content')
<style>
.loader {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
    margin-left: 10px;
    vertical-align: middle;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.btn-loading {
    position: relative;
    opacity: 0.8;
    cursor: not-allowed;
}

.btn-black {
    background-color: #000;
    border-color: #000;
    color: #fff;
    height: 45px;
    font-size: 16px;
}

.btn-black:hover, .btn-black:focus {
    background-color: #333;
    border-color: #333;
    color: #fff;
}

.error-message {
    color: #dc3545;
    margin-top: 10px;
    display: none;
    text-align: center;
    font-size: 14px;
}

.timer-text {
    text-align: center;
    margin: 15px 0;
    font-size: 14px;
    color: #fff;
}

.resend-button {
    background: none;
    border: none;
    color: #fff;
    text-decoration: underline;
    cursor: pointer;
    padding: 0;
    font-size: 14px;
    display: none;
    margin: 15px auto;
    text-align: center;
    width: 100%;
}

.resend-button:disabled {
    color: rgba(255, 255, 255, 0.5);
    text-decoration: none;
    cursor: not-allowed;
}
</style>

<form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf
    <div id="credentials-section">
        <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus  autocomplete="off" placeholder="Email address">
        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="off" placeholder="Password">
        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
        <button type="button" onclick="submitCredentials()" name="submit" id="submit_btn" class="btn btn-black" style="width: 100%;">
            <span>LOG IN</span>
            <span class="loader" style="display: none;"></span>
        </button>
        <div id="login-error" class="error-message"></div>
        <p class="text-center">
            <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        </p>
    </div>

    <div id="otp-section" style="display: none;">
        <div class="alert alert-success">
            OTP has been sent to your email address.
        </div>
        <input id="otp" type="text" class="form-control{{ $errors->has('otp') ? ' is-invalid' : '' }}" name="otp" required placeholder="Enter OTP Code" maxlength="6">
        @if ($errors->has('otp'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('otp') }}</strong>
            </span>
        @endif
        <button type="button" onclick="verifyOTP()" name="verify" id="verify_btn" class="btn btn-black" style="width: 100%;">
            <span>VERIFY OTP</span>
            <span class="loader" style="display: none;"></span>
        </button>
        <div id="otp-error" class="error-message"></div>
        <div id="timer" class="timer-text">Time remaining: 5:00</div>
        <button type="button" onclick="resendOTP()" id="resend_btn" class="resend-button" disabled>Resend OTP</button>
        <p class="text-center">
            <a href="javascript:void(0)" onclick="showCredentials()" class="btn btn-link">Back to Login</a>
        </p>
    </div>
</form>

<script>
function setLoading(button, isLoading) {
    const loader = button.querySelector('.loader');
    const text = button.querySelector('span:not(.loader)');
    if (isLoading) {
        button.disabled = true;
        button.classList.add('btn-loading');
        loader.style.display = 'inline-block';
        text.style.opacity = '0.7';
    } else {
        button.disabled = false;
        button.classList.remove('btn-loading');
        loader.style.display = 'none';
        text.style.opacity = '1';
    }
}

let countdownTimer;

function startCountdown() {
    const timerDisplay = document.getElementById('timer');
    const resendButton = document.getElementById('resend_btn');
    let timeLeft = 300; // 5 minutes in seconds

    resendButton.style.display = 'block';
    resendButton.disabled = true;
    
    if (countdownTimer) {
        clearInterval(countdownTimer);
    }

    countdownTimer = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerDisplay.textContent = `Time remaining: ${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 0) {
            clearInterval(countdownTimer);
            timerDisplay.textContent = 'OTP expired';
            resendButton.disabled = false;
        }

        timeLeft--;
    }, 1000);
}

function resendOTP() {
    const resendButton = document.getElementById('resend_btn');
    if (resendButton.disabled) return;

    submitCredentials(true);
}

function submitCredentials(isResend = false) {
    const submitBtn = document.getElementById('submit_btn');
    if (!isResend && submitBtn.disabled) return;
    
    // Clear previous error message
    document.getElementById('login-error').style.display = 'none';

    const form = document.getElementById('loginForm');
    const formData = new FormData(form);

    setLoading(submitBtn, true);

    fetch('{{ route("login.send.otp") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (!isResend) {
                document.getElementById('credentials-section').style.display = 'none';
                document.getElementById('otp-section').style.display = 'block';
            }
            document.getElementById('otp').value = '';
            document.getElementById('otp').focus();
            startCountdown();
            
            setTimeout(() => {
                fetch('/runJobs').catch(() => {});
            }, 10000);
        } else {
            const errorDiv = document.getElementById('login-error');
            errorDiv.textContent = data.message || 'Incorrect email or password';
            errorDiv.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        setLoading(submitBtn, false);
    });
}

function verifyOTP() {
    const verifyBtn = document.getElementById('verify_btn');
    if (verifyBtn.disabled) return;
    
    // Clear previous error message
    document.getElementById('otp-error').style.display = 'none';

    const form = document.getElementById('loginForm');
    const formData = new FormData(form);

    setLoading(verifyBtn, true);

    fetch('{{ route("login.verify.otp") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route("admin.index") }}';
        } else {
            const errorDiv = document.getElementById('otp-error');
            errorDiv.textContent = data.message || 'Incorrect OTP code';
            errorDiv.style.display = 'block';
            setLoading(verifyBtn, false);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        setLoading(verifyBtn, false);
    });
}

function showCredentials() {
    document.getElementById('otp-section').style.display = 'none';
    document.getElementById('credentials-section').style.display = 'block';
}
</script>
@endsection
