<style>
    .auth-overlay {
        --sz-primary: #16a34a;
        --sz-primary-dark: #15803d;
        --sz-accent: #059669; 
        --sz-surface: #FFFFFF;
        --sz-bg: #F8FAFC;
        --sz-text-main: #0F172A;
        --sz-text-muted: #64748B;
        --sz-border: #E2E8F0;
        --sz-border-focus: #86efac;
        --sz-error: #EF4444;
    }
    .auth-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        z-index: 9999; display: flex; align-items: center; justify-content: center;
        opacity: 0; visibility: hidden; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .auth-overlay.active { opacity: 1; visibility: visible; }
    
    .auth-popup-split {
        background: var(--sz-surface); width: 100%; max-width: 1000px; min-height: 600px;
        border-radius: 24px; box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
        display: flex; overflow: hidden; position: relative;
        transform: scale(0.96) translateY(20px); transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .auth-overlay.active .auth-popup-split { transform: scale(1) translateY(0); }
    
    .auth-brand-side { 
        width: 45%; padding: 60px 40px; color: white; display: flex; flex-direction: column; justify-content: center; 
        background: linear-gradient(135deg, var(--sz-primary-dark) 0%, var(--sz-primary) 100%); 
        position: relative; overflow: hidden; 
    }
    .auth-brand-side::before {
        content: ''; position: absolute; top: -20%; left: -20%; width: 60%; height: 60%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%); border-radius: 50%;
    }
    .auth-brand-side::after {
        content: ''; position: absolute; bottom: -10%; right: -10%; width: 80%; height: 80%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 60%); border-radius: 50%;
    }
    
    .brand-content { position: relative; z-index: 2; }
    .brand-content h2 { font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 2.6rem; line-height: 1.1; margin-bottom: 20px; color: #ffffff; letter-spacing: -0.5px;}
    .brand-content p { font-size: 1.1rem; line-height: 1.6; color: rgba(255,255,255,0.85); font-weight: 500;}
    .brand-features { list-style: none; padding: 0; margin-top: 40px;}
    .brand-features li { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; font-weight: 600; font-size: 1.05rem; }
    .brand-features i { width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px); font-size: 1.1rem;}
    
    .auth-form-side { width: 55%; padding: 50px 60px; background: var(--sz-surface); display: flex; flex-direction: column; justify-content: center; position: relative; }
    
    .btn-close-popup { position: absolute; top: 25px; right: 25px; width: 40px; height: 40px; border-radius: 50%; background: var(--sz-bg); border: 1px solid var(--sz-border); color: var(--sz-text-muted); font-size: 1.2rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;}
    .btn-close-popup:hover { background: #FEE2E2; color: var(--sz-error); border-color: #FECACA; transform: rotate(90deg); }
    
    .auth-form-side h3 { font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 2.2rem; color: var(--sz-text-main); margin-bottom: 5px; letter-spacing: -0.5px;}
    .auth-form-side > p { font-size: 1rem; color: var(--sz-text-muted); margin-bottom: 35px; }

    .auth-input-group { margin-bottom: 22px; position: relative; }
    .auth-input-group label { font-size: 0.85rem; font-weight: 700; color: var(--sz-text-main); margin-bottom: 8px; display: block; }
    .input-icon-wrapper { position: relative; display: flex; align-items: center;}
    .input-icon-wrapper .input-icon { position: absolute; left: 16px; color: #94A3B8; font-size: 1.1rem; transition: color 0.3s; }
    
    .input-icon-wrapper input { 
        width: 100%; padding: 14px 16px 14px 45px; font-size: 1rem; font-weight: 500; color: var(--sz-text-main); 
        background: var(--sz-surface); border: 1.5px solid var(--sz-border); border-radius: 12px; transition: all 0.3s ease; 
    }
    .input-icon-wrapper input:hover { border-color: #CBD5E1; }
    .input-icon-wrapper input:focus { border-color: var(--sz-primary); background: var(--sz-surface); outline: none; box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.1); }
    .input-icon-wrapper input:focus + .input-icon { color: var(--sz-primary); }
    
    .password-toggle { position: absolute; right: 16px; color: #94A3B8; cursor: pointer; transition: color 0.3s; font-size: 1.1rem;}
    .password-toggle:hover { color: var(--sz-primary); }
    
    .pass-match-msg { font-size: 0.85rem; font-weight: 600; margin-top: 8px; display: none; display: flex; align-items: center; gap: 6px;}
    .match-success { color: var(--sz-accent); } 
    .match-error { color: var(--sz-error); }

    .btn-auth-submit { 
        width: 100%; padding: 16px; background: var(--sz-primary); color: white; border: none; border-radius: 12px; 
        font-weight: 700; font-size: 1.05rem; cursor: pointer; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2); 
        transition: all 0.3s ease; margin-top: 15px; display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-auth-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(22, 163, 74, 0.3); background: var(--sz-primary-dark);}
    .btn-auth-submit:disabled { opacity: 0.6; cursor: not-allowed; box-shadow: none; background: var(--sz-text-muted); }
    
    .btn-verify {
        background: var(--sz-text-main); color: white; border: none; border-radius: 12px; padding: 0 24px; font-weight: 700; transition: all 0.3s; white-space: nowrap;
    }
    .btn-verify:hover:not(:disabled) { background: #334155; }
    .btn-verify:disabled { opacity: 0.7; cursor: not-allowed; }

    #otpBoxSection {
        background: #F8FAFC; padding: 20px; border-radius: 16px; border: 1.5px solid #E2E8F0; margin-top: 15px; 
        animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1); display: none;
    }
    #signupOtpCode { background: var(--sz-surface); border: 1.5px solid var(--sz-border); border-radius: 10px; padding: 12px; font-size: 1.2rem; letter-spacing: 6px; text-align: center; font-weight: 700; color: var(--sz-text-main); transition: 0.3s;}
    #signupOtpCode:focus { border-color: var(--sz-primary); box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.1); outline: none;}
    
    .timer-text { font-size: 0.85rem; font-weight: 600; color: var(--sz-text-muted); margin-top: 12px; display: flex; justify-content: space-between; align-items: center;}
    .resend-link { color: var(--sz-primary); cursor: pointer; font-weight: 700; display: none; transition: 0.2s;}
    .resend-link:hover { color: var(--sz-primary-dark); text-decoration: underline; }

    .auth-switch-text { text-align: center; margin-top: 30px; font-size: 0.95rem; font-weight: 500; color: var(--sz-text-muted); }
    .auth-switch-text span { color: var(--sz-primary); font-weight: 700; cursor: pointer; transition: 0.3s; }
    .auth-switch-text span:hover { text-decoration: underline; color: var(--sz-primary-dark); }

    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 992px) { 
        .auth-brand-side { display: none; } 
        .auth-form-side { width: 100%; padding: 40px 30px; } 
        .auth-popup-split { height: auto; max-width: 90%; min-height: auto;} 
    }

.zyne-toast-container {
    position: fixed; top: 30px; left: 50%; transform: translateX(-50%);
    z-index: 999999; display: flex; flex-direction: column; gap: 12px; pointer-events: none;
}
.zyne-toast {
    background: rgba(15, 23, 42, 0.85); 
    backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
    color: white; padding: 14px 28px; border-radius: 50px;
    font-family: 'Poppins', sans-serif; font-size: 0.95rem; font-weight: 600;
    display: flex; align-items: center; gap: 12px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.1);
    animation: slideDownFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.zyne-toast.error { 
    border-color: rgba(239, 68, 68, 0.5); 
    box-shadow: 0 10px 30px rgba(239, 68, 68, 0.2); 
}
.zyne-toast.hiding { animation: slideUpFade 0.4s ease forwards; }

@keyframes slideDownFade {
    0% { opacity: 0; transform: translateY(-40px) scale(0.9); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes slideUpFade {
    100% { opacity: 0; transform: translateY(-30px) scale(0.9); }
}
</style>

<div class="auth-overlay" id="loginModal">
    <div class="auth-popup-split">
        <div class="auth-brand-side">
            <div class="brand-content">
                <h2>Welcome Back</h2>
                <p>Access your HarvestIQ dashboard for real-time market prices, accurate weather forecasts, and smart crop advisories.</p>
                <ul class="brand-features">
                    <li><i class="fa-solid fa-leaf"></i> Free Advisory</li>
                    <li><i class="fa-solid fa-shield-halved"></i> Secure Platform</li>
                </ul>
            </div>
        </div>
        <div class="auth-form-side">
            <button class="btn-close-popup" onclick="closeAuthModals()" title="Close"><i class="fa-solid fa-xmark"></i></button>
            <h3>Sign In</h3>
            <p>Enter your credentials to access your dashboard.</p>
<form id="mainLoginForm">
                    <div class="auth-input-group">
                    <label>Email Address</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-regular fa-envelope input-icon"></i>
                        <input type="email" name="email" placeholder="name@example.com" required>
                    </div>
                </div>
                <div class="auth-input-group" style="margin-bottom: 25px;">
                    <label>Password</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" id="loginPassword" name="password" placeholder="••••••••" required>
                        <i class="fa-regular fa-eye-slash password-toggle" onclick="togglePass('loginPassword', this)"></i>
                    </div>
                </div>

                <div style="text-align: right; margin-top: -15px; margin-bottom: 25px;">
                    <span onclick="switchAuthModal('forgotModal')" style="font-size: 0.85rem; color: var(--sz-primary); cursor: pointer; font-weight: 700; transition: 0.3s;">Forgot Password?</span>
                </div>

                <button type="submit" class="btn-auth-submit">Access Account <i class="fa-solid fa-arrow-right"></i></button>
            </form>
            <div class="auth-switch-text">New to HarvestIQ? <span onclick="switchAuthModal('signupModal')">Create an account</span></div>
        </div>
    </div>
</div>

<div class="auth-overlay" id="signupModal">
    <div class="auth-popup-split">
        <div class="auth-brand-side">
            <div class="brand-content">
                <h2>Join<br>HarvestIQ</h2>
                <p>Empower your farming with data-driven decisions. Get live market rates, weather alerts, and AI-based crop recommendations for free.</p>
                <ul class="brand-features">
                    <li><i class="fa-solid fa-cloud-sun-rain"></i> Weather Alerts</li>
                    <li><i class="fa-solid fa-chart-line"></i> Live Market Tracking</li>
                </ul>
            </div>
        </div>
        <div class="auth-form-side">
            <button class="btn-close-popup" onclick="closeAuthModals()" title="Close"><i class="fa-solid fa-xmark"></i></button>
            <h3>Create Account</h3>
            <p>Fill in your details below to get started for free.</p>
            
            <form action="process_signup.php" method="POST" id="mainSignupForm" onsubmit="return validateFinalSignup()">
                
                <div class="auth-input-group">
                    <label>Full Name</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-regular fa-user input-icon"></i>
                        <input type="text" name="full_name" placeholder="e.g., John Doe" required>
                    </div>
                </div>
                
                <div class="auth-input-group mb-2">
                    <label>Email Address</label>
                    <div class="d-flex gap-2">
                        <div class="input-icon-wrapper flex-grow-1">
                            <i class="fa-regular fa-envelope input-icon"></i>
                            <input type="email" id="signupEmail" name="email" placeholder="you@example.com" required>
                        </div>
                        <button type="button" id="sendOtpBtn" class="btn-verify">Verify</button>
                    </div>
                </div>

                <div id="otpBoxSection">
                    <label style="color: var(--sz-primary); font-size: 0.85rem; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-paper-plane"></i> Verification code sent</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="signupOtpCode" class="form-control" placeholder="000000" maxlength="6">
                        <button type="button" id="verifyOtpBtn" class="btn-verify" style="background: var(--sz-primary);">Check</button>
                    </div>
                    <div class="timer-text" id="timerDisplay">
                        <span>Resend code in <span id="countdownText" style="color: var(--sz-primary); font-weight: 800;">60</span>s</span>
                        <span class="resend-link" id="resendOtpBtn" onclick="resendSignupOtp()">Resend Code</span>
                    </div>
                </div>

                <div id="passwordSection" style="display: none; opacity: 0; transition: opacity 0.5s ease; margin-top: 20px;">
                    <div class="row g-3">
                        <div class="col-md-6 auth-input-group mb-0">
                            <label>Secure Password</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-lock input-icon"></i>
                                <input type="password" id="regPassword" name="password" placeholder="Min 8 chars" required onkeyup="checkPasswordMatch()">
                            </div>
                        </div>
                        <div class="col-md-6 auth-input-group mb-0">
                            <label>Confirm Password</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-shield-check input-icon"></i>
                                <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm" required onkeyup="checkPasswordMatch()">
                                <i class="fa-regular fa-eye-slash password-toggle" onclick="togglePass('confirmPassword', this)"></i>
                            </div>
                        </div>
                    </div>
                    <div id="passMatchMsg" class="pass-match-msg"></div>
                </div>
                
                <button type="submit" class="btn-auth-submit" id="signupBtn" disabled>Complete Sign Up <i class="fa-solid fa-check"></i></button>
            </form>
            
            <div class="auth-switch-text">Already have an account? <span onclick="switchAuthModal('loginModal')">Sign In here</span></div>
        </div>
    </div>
</div>

<div class="auth-overlay" id="forgotModal">
    <div class="auth-popup-split">
        <div class="auth-brand-side">
            <div class="brand-content">
                <h2>Lost Your Key?</h2>
                <p>Don't worry! It happens to the best of us. Let's get you back into your account securely.</p>
            </div>
        </div>
        <div class="auth-form-side">
            <button class="btn-close-popup" onclick="closeAuthModals()" title="Close"><i class="fa-solid fa-xmark"></i></button>
            <h3>Reset Password</h3>
            <p>Enter your registered email address and we'll send you a 6-digit OTP to reset your password.</p>
            
            <form action="process_forgot.php" method="POST">
                <div class="auth-input-group" style="margin-bottom: 30px;">
                    <label>Registered Email</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-regular fa-envelope input-icon"></i>
                        <input type="email" name="email" placeholder="you@example.com" required>
                    </div>
                </div>
                <button type="submit" class="btn-auth-submit">Send Reset Link <i class="fa-solid fa-paper-plane ms-1"></i></button>
            </form>
            <div class="auth-switch-text"><span onclick="switchAuthModal('loginModal')"><i class="fa-solid fa-arrow-left me-1"></i> Back to Sign In</span></div>
        </div>
    </div>
</div>


<script>
    function showZyneToast(message, type = 'error') {
        let container = document.getElementById('zyneToastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'zyneToastContainer';
            container.className = 'zyne-toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `zyne-toast ${type}`;
        
        const icon = type === 'error' ? '<i class="fa-solid fa-triangle-exclamation" style="color: #EF4444;"></i>' : '<i class="fa-solid fa-circle-check" style="color: #10B981;"></i>';
        
        toast.innerHTML = `${icon} <span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 400); 
        }, 3500);
    }

    function togglePass(inputId, iconElement) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            iconElement.classList.replace("fa-eye-slash", "fa-eye");
            iconElement.style.color = "var(--sz-primary)"; 
        } else {
            input.type = "password";
            iconElement.classList.replace("fa-eye", "fa-eye-slash");
            iconElement.style.color = "#94A3B8"; 
        }
    }

    function openAuthModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => { modal.classList.add('active'); }, 10);
            document.body.style.overflow = "hidden";
        }
    }

    function closeAuthModals() {
        const modals = document.querySelectorAll('.auth-overlay');
        modals.forEach(modal => {
            modal.classList.remove('active');
            setTimeout(() => { modal.style.display = 'none'; }, 400);
        });
        document.body.style.overflow = "auto";
    }

    function switchAuthModal(targetId) {
        closeAuthModals();
        setTimeout(() => { openAuthModal(targetId); }, 400);
    }

    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const emailInput = document.getElementById('signupEmail');
    const otpBoxSection = document.getElementById('otpBoxSection');
    
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const otpInput = document.getElementById('signupOtpCode');
    
    const passwordSection = document.getElementById('passwordSection');
    const signupBtn = document.getElementById('signupBtn');
    
    const countdownText = document.getElementById('countdownText');
    const resendOtpBtn = document.getElementById('resendOtpBtn');
    
    let isEmailVerified = false;
    let timerInterval;

    function startOtpTimer() {
        let timeLeft = 60;
        countdownText.parentElement.style.display = 'inline';
        resendOtpBtn.style.display = 'none';
        countdownText.innerText = timeLeft;

        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            timeLeft--;
            countdownText.innerText = timeLeft;
            if(timeLeft <= 0) {
                clearInterval(timerInterval);
                countdownText.parentElement.style.display = 'none';
                resendOtpBtn.style.display = 'inline-block';
            }
        }, 1000);
    }

    // 🚀 REAL OTP LOGIC (PHPMailer কল করবে)
    async function requestOtp(isResend = false) {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if(!email || !emailRegex.test(email)) { 
            showZyneToast("Hold on! That doesn't look like a valid email.", "error"); 
            return; 
        }

        const originalBtn = isResend ? resendOtpBtn : sendOtpBtn;
        const originalText = originalBtn.innerText;
        
        if(!isResend) {
            sendOtpBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            sendOtpBtn.disabled = true;
        } else {
            resendOtpBtn.innerText = 'Sending...';
            resendOtpBtn.style.pointerEvents = 'none';
        }

        try {
            const response = await fetch('ajax_send_otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email=${encodeURIComponent(email)}`
            });
            const data = await response.json();

            if(data.success) {
                if(!isResend) {
                    otpBoxSection.style.display = 'block'; 
                    emailInput.readOnly = true; 
                    emailInput.style.background = '#F8FAFC';
                    emailInput.style.borderColor = '#E2E8F0';
                    emailInput.style.color = '#94A3B8'; 
                    sendOtpBtn.innerHTML = '<i class="fa-solid fa-check"></i>';
                    sendOtpBtn.style.background = 'var(--sz-accent)'; 
                    sendOtpBtn.style.color = '#fff';
                }
                
                showZyneToast("OTP has been sent to your email!", "success"); // Success Toast
                
                otpInput.value = ''; 
                otpInput.focus();
                startOtpTimer(); 
                
                if(isResend) {
                    resendOtpBtn.style.pointerEvents = 'auto';
                }
            } else {
                showZyneToast(data.message, "error");
                
                if(!isResend) {
                    sendOtpBtn.innerHTML = originalText;
                    sendOtpBtn.disabled = false;
                } else {
                    resendOtpBtn.innerText = originalText;
                    resendOtpBtn.style.pointerEvents = 'auto';
                }
            }
        } catch (err) {
            showZyneToast("Network error! Please check your connection and try again.", "error");
            
            if(!isResend) {
                sendOtpBtn.innerHTML = originalText;
                sendOtpBtn.disabled = false;
            } else {
                resendOtpBtn.innerText = originalText;
                resendOtpBtn.style.pointerEvents = 'auto';
            }
        }
    }

    sendOtpBtn.addEventListener('click', () => requestOtp(false));

    function resendSignupOtp() {
        requestOtp(true);
    }

    // 🚀 REAL VERIFY LOGIC
    verifyOtpBtn.addEventListener('click', async function() {
        const otp = otpInput.value.trim();
        if(otp.length !== 6) { 
            showZyneToast("Please enter the 6-digit OTP.", "error"); 
            return; 
        }

        const originalText = verifyOtpBtn.innerText;
        verifyOtpBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        verifyOtpBtn.disabled = true;

        try {
            const response = await fetch('ajax_verify_otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `otp=${encodeURIComponent(otp)}`
            });
            const data = await response.json();

            if(data.success) {
                isEmailVerified = true;
                clearInterval(timerInterval); 
                
                otpBoxSection.innerHTML = '<div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); padding: 12px 20px; border-radius: 12px; color: var(--sz-accent); font-weight: 700; display: flex; align-items: center; gap: 10px;"><i class="fa-solid fa-shield-check fs-5"></i> Email Verified Successfully!</div>';
                
                passwordSection.style.display = 'block';
                setTimeout(() => { passwordSection.style.opacity = '1'; }, 50);
                document.getElementById('regPassword').focus(); 
                
            } else {
                showZyneToast(data.message, "error");
                verifyOtpBtn.innerHTML = originalText;
                verifyOtpBtn.disabled = false;
            }
        } catch (err) {
            showZyneToast("Network error occurred. Please try again.", "error");
            verifyOtpBtn.innerHTML = originalText;
            verifyOtpBtn.disabled = false;
        }
    });

    function checkPasswordMatch() {
        const pass1 = document.getElementById("regPassword").value;
        const pass2 = document.getElementById("confirmPassword").value;
        const msg = document.getElementById("passMatchMsg");

        if (pass2.length > 0) {
            msg.style.display = "flex";
            if (pass1 === pass2 && pass1.length >= 8) {
                msg.innerHTML = "<i class='fa-solid fa-circle-check'></i> Ready to go!";
                msg.className = "pass-match-msg match-success";
                
                 signupBtn.classList.remove('locked');
                signupBtn.disabled = false; 
                signupBtn.style.background = 'linear-gradient(135deg, var(--sz-primary), #10b981)';
                
            } else if (pass1 !== pass2) {
                msg.innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Passwords do not match.";
                msg.className = "pass-match-msg match-error";
                
                 signupBtn.classList.add('locked');
                signupBtn.disabled = true;
                signupBtn.style.background = '';  

            } else if (pass1.length < 8) {
                 msg.innerHTML = "<i class='fa-solid fa-circle-info'></i> Minimum 8 characters required.";
                 msg.className = "pass-match-msg match-error";
                 
                  signupBtn.classList.add('locked');
                 signupBtn.disabled = true;
                 signupBtn.style.background = ''; 
            }
        } else {
            msg.style.display = "none";
            signupBtn.classList.add('locked');
            signupBtn.disabled = true;
            signupBtn.style.background = '';
        }
    }

    function validateFinalSignup() {
        if (!isEmailVerified) {
            showZyneToast("Security Check: Please verify your email first.", "error");
            return false;
        }
        
        signupBtn.classList.remove('locked');
        signupBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Setting up workspace...';
        emailInput.readOnly = false; // সাবমিটের জন্য ফিল্ড এনাবল করে দেওয়া হলো
        return true;
    }

    const loginForm = document.getElementById('mainLoginForm');
    
    if(loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault(); 
            
            const submitBtn = this.querySelector('.btn-auth-submit');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Verifying...';
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('process_login.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if(data.success) {
                    submitBtn.innerHTML = '<i class="fa-solid fa-check me-2"></i> Success!';
                    submitBtn.style.background = 'var(--sz-accent)';
                    window.location.href = data.redirect;
                } else {
                    showZyneToast(data.message, "error");
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            } catch(err) {
                showZyneToast("Network error! Please try again.", "error");
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }
</script>