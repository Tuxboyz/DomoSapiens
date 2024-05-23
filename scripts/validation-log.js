document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const email = document.getElementById('usuario');
    const password = document.getElementById('password');

    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|es|org)$/;

    email.addEventListener('input', function() {
        validateEmail();
    });

    password.addEventListener('input', function() {
        validatePassword();
    });

    form.addEventListener('submit', function(event) {
        if (!validateEmail() || !validatePassword()) {
            event.preventDefault();
        }
    });

    function validateEmail() {
        const errorSpan = email.nextElementSibling.nextElementSibling;
        if (email.value === '') {
            setInvalid(email, errorSpan, 'Este campo es obligatorio.');
            return false;
        } else if (!emailPattern.test(email.value)) {
            setInvalid(email, errorSpan, 'El correo electrónico debe tener un dominio válido (.com, .es, .org).');
            return false;
        } else {
            setValid(email, errorSpan);
            return true;
        }
    }

    function validatePassword() {
        const errorSpan = password.nextElementSibling.nextElementSibling;
        if (password.value === '') {
            setInvalid(password, errorSpan, 'Este campo es obligatorio.');
            return false;
        } else {
            setValid(password, errorSpan);
            return true;
        }
    }

    function setInvalid(input, errorSpan, message) {
        input.classList.remove('valid');
        input.classList.add('invalid');
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.style.display = 'block';
        }
    }

    function setValid(input, errorSpan) {
        input.classList.remove('invalid');
        input.classList.add('valid');
        if (errorSpan) {
            errorSpan.style.display = 'none';
        }
    }
});
