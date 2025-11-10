document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');
    const togglePw = document.getElementById('togglePw');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirm');
    const statusDiv = document.getElementById('status');

    // Password visibility toggle
    if (togglePw) {
        togglePw.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            confirmInput.type = type;
            togglePw.innerHTML = type === 'password' ? '👁️' : '👁️‍🗨️';
        });
    }

    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();

        const formData = new FormData(registerForm);
        
        try {
            const response = await fetch('register.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Show success message and redirect
                alert('Conta criada com sucesso!');
                window.location.href = 'index.html';
            } else {
                // Show error message
                alert(data.message || 'Erro ao criar conta');
                if (data.errors) {
                    showErrors(data.errors);
                }
            }
        } catch (err) {
            alert('Erro ao conectar ao servidor');
            console.error(err);
        }
    });

    function showErrors(errors) {
        Object.entries(errors).forEach(([field, message]) => {
            const small = document.querySelector(`[data-for="${field}"]`);
            if (small) small.textContent = message;
        });
    }

    function clearErrors() {
        document.querySelectorAll('.error').forEach(el => el.textContent = '');
    }
});