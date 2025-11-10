document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const togglePw = document.getElementById('togglePw');
    const passwordInput = document.getElementById('password');
    const statusDiv = document.getElementById('status');

    // Password visibility toggle
    if (togglePw) {
        togglePw.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            togglePw.innerHTML = type === 'password' ? '👁️' : '👁️‍🗨️';
        });
    }

    // Form submission
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearErrors();

            const email = loginForm.querySelector('#email').value;
            const password = loginForm.querySelector('#password').value;

            // Basic validation
            if (!email || !password) {
                showErrors({
                    email: !email ? 'Email é obrigatório' : '',
                    password: !password ? 'Senha é obrigatória' : ''
                });
                return;
            }

            try {
                statusDiv.textContent = 'Verificando...';
                
                const formData = new FormData(loginForm);
                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Login response:', data); // Debug logging

                if (data.success) {
                    statusDiv.textContent = 'Login realizado com sucesso!';
                    sessionStorage.setItem('user_id', data.user_id);
                    sessionStorage.setItem('user_name', data.user_name);
                    
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = 'dashboard.html';
                    }, 500);
                } else {
                    statusDiv.textContent = data.message || 'Erro ao fazer login';
                    if (data.errors) {
                        showErrors(data.errors);
                    }
                }
            } catch (err) {
                console.error('Login error:', err);
                statusDiv.textContent = 'Erro ao conectar ao servidor';
            }
        });
    }

    function showErrors(errors) {
        Object.entries(errors).forEach(([field, message]) => {
            if (message) { // Only show if there's an actual error message
                const small = document.querySelector(`[data-for="${field}"]`);
                if (small) small.textContent = message;
            }
        });
    }

    function clearErrors() {
        document.querySelectorAll('.error').forEach(el => el.textContent = '');
        if (statusDiv) statusDiv.textContent = '';
    }
});