// --- Get references to all necessary elements ---

// !!! IMPORTANT: The selector is updated to match your new class: .buyer
const loginTriggers = document.querySelectorAll('.buyer'); 

const loginModal = document.getElementById('loginModal');
const registerModal = document.getElementById('registerModal');
const switchToRegisterLink = document.getElementById('switchToRegister');
const switchToLoginLink = document.getElementById('switchToLogin');
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');


// --- 1. OPEN LOGIN MODAL when clicking the trigger element ---
if (loginTriggers.length > 0 && loginModal) {
    // Loop through ALL the found elements
    loginTriggers.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault(); 
            // Hide register just in case it was open
            if (registerModal) registerModal.style.display = 'none';
            // Show Login modal
            loginModal.style.display = 'flex';
        });
    });
}


// --- 2. SWITCH TO REGISTER MODAL (from Login) ---
if (switchToRegisterLink && loginModal && registerModal) {
    switchToRegisterLink.addEventListener('click', (e) => {
        e.preventDefault();
        // Hide Login modal
        loginModal.style.display = 'none';
        // Show Register modal
        registerModal.style.display = 'flex';
    });
}

// --- 3. SWITCH TO LOGIN MODAL (from Register) ---
if (switchToLoginLink && loginModal && registerModal) {
    switchToLoginLink.addEventListener('click', (e) => {
        e.preventDefault();
        // Hide Register modal
        registerModal.style.display = 'none';
        // Show Login modal
        loginModal.style.display = 'flex';
    });
}

// --- 4. CLOSE MODALS WHEN CLICKING OUTSIDE ---
window.addEventListener('click', (e) => {
    if (e.target === loginModal) {
        loginModal.style.display = 'none';
    }
    if (e.target === registerModal) {
        registerModal.style.display = 'none';
    }
});


// --- 5. Handle login form submission (AJAX) ---
if (loginForm) {
    loginForm.onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(loginForm);
        
        try {
            const response = await fetch('login.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            const messageDiv = document.getElementById('loginMessage');
            messageDiv.textContent = result.message;
            messageDiv.style.color = result.status === 'success' ? 'green' : 'red';
            messageDiv.style.marginTop = '10px';
            
            if (result.status === 'success') {
                // Login successful: close modal and reload page to update PHP header
                setTimeout(() => {
                    loginModal.style.display = "none";
                    alert('Login successful! Welcome ' + result.username); 
                    window.location.reload(); 
                }, 1000);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    };
}

// --- 6. Handle registration form submission (AJAX) ---
if (registerForm) {
    registerForm.onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(registerForm);
        
        // Simple password validation
        const password = formData.get('password');
        if (password.length < 6) {
            const messageDiv = document.getElementById('registerMessage');
            messageDiv.textContent = 'Password must be at least 6 characters';
            messageDiv.style.color = 'red';
            messageDiv.style.marginTop = '10px';
            return;
        }
        
        try {
            const response = await fetch('register.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            const messageDiv = document.getElementById('registerMessage');
            messageDiv.textContent = result.message;
            messageDiv.style.color = result.status === 'success' ? 'green' : 'red';
            messageDiv.style.marginTop = '10px';
            
            if (result.status === 'success') {
                // Registration successful: switch to login form
                setTimeout(() => {
                    registerModal.style.display = "none";
                    loginModal.style.display = "flex"; // Show login after successful register
                    registerForm.reset();
                    messageDiv.textContent = '';
                }, 2000);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    };
}