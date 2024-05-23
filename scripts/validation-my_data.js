document.addEventListener('DOMContentLoaded', function () {
    const namePattern = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
    const phonePattern = /^\d{9}$/;
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|es|org)$/;

    // Validate Name
    const nombreInput = document.getElementById('new_name');
    nombreInput.addEventListener('input', function() {
        validateName(nombreInput);
    });

    // Validate Surname
    const apellidoInput = document.getElementById('new_surname');
    apellidoInput.addEventListener('input', function() {
        validateName(apellidoInput);
    });

    // Validate Email
    const emailInput = document.getElementById('new_email');
    emailInput.addEventListener('blur', function() {
        validateEmail(emailInput);
    });

    // Validate Password
    const actualPassInput = document.getElementById('actual_pass');
    const newPassInput = document.getElementById('new_pass');
    const confirmNewPassInput = document.getElementById('confirm_new_pass');
    newPassInput.addEventListener('input', function() {
        validatePassword(newPassInput);
    });
    confirmNewPassInput.addEventListener('input', function() {
        validateConfirmPassword(newPassInput, confirmNewPassInput);
    });

    // Validate Birth Date
    const birthInput = document.getElementById('new_birth');
    birthInput.addEventListener('blur', function() {
        validateFechaNacimiento(birthInput);
    });

    // Validate Phone Number
    const phoneInput = document.getElementById('new_tel');
    phoneInput.addEventListener('input', function() {
        validatePhone(phoneInput);
    });

    function validateName(input) {
        const errorSpan = input.nextElementSibling.nextElementSibling;
        if (input.value === '') {
            setInvalid(input, errorSpan, 'Este campo es obligatorio.');
        } else if (!namePattern.test(input.value)) {
            setInvalid(input, errorSpan, 'El nombre contiene caracteres no permitidos.');
        } else if (input.value.length < 3) {
            setInvalid(input, errorSpan, 'Debe tener al menos 3 caracteres.');
        } else {
            setValid(input, errorSpan);
        }
    }

    function validateEmail(input) {
        const errorSpan = input.nextElementSibling.nextElementSibling;
        if (input.value === '') {
            setInvalid(input, errorSpan, 'Este campo es obligatorio.');
        } else if (!emailPattern.test(input.value)) {
            setInvalid(input, errorSpan, 'El correo electrónico debe tener un dominio válido (.com, .es, .org).');
        } else {
            setValid(input, errorSpan);
        }
    }

    function validatePassword(input) {
        const errorSpan = input.nextElementSibling.nextElementSibling;
        if (input.value === '') {
            setInvalid(input, errorSpan, 'Este campo es obligatorio.');
        } else if (input.value.length < 8) {
            setInvalid(input, errorSpan, 'La contraseña debe tener al menos 8 caracteres.');
        } else {
            setValid(input, errorSpan);
        }
    }

    function validateConfirmPassword(passwordInput, confirmPasswordInput) {
        const errorSpan = confirmPasswordInput.nextElementSibling.nextElementSibling;
        if (confirmPasswordInput.value === '') {
            setInvalid(confirmPasswordInput, errorSpan, 'Este campo es obligatorio.');
        } else if (confirmPasswordInput.value.length < 8) {
            setInvalid(confirmPasswordInput, errorSpan, 'La contraseña debe tener al menos 8 caracteres.');
        } else if (confirmPasswordInput.value !== passwordInput.value) {
            setInvalid(confirmPasswordInput, errorSpan, 'Las contraseñas no coinciden.');
        } else {
            setValid(confirmPasswordInput, errorSpan);
        }
    }

    function validatePhone(input) {
        const errorSpan = input.nextElementSibling.nextElementSibling;
        if (input.value === '') {
            setInvalid(input, errorSpan, 'Este campo es obligatorio.');
        } else if (!phonePattern.test(input.value)) {
            setInvalid(input, errorSpan, 'El número de teléfono debe tener 9 dígitos.');
        } else {
            setValid(input, errorSpan);
        }
    }

    function validateFechaNacimiento(input) {
        const errorSpan = input.nextElementSibling.nextElementSibling;
        const fecha = new Date(input.value);
        const hoy = new Date();
        let edad = hoy.getFullYear() - fecha.getFullYear();
        const mes = hoy.getMonth() - fecha.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fecha.getDate())) {
            edad--;
        }

        if (input.value === '') {
            setInvalid(input, errorSpan, 'Este campo es obligatorio.');
        } else if (edad < 18) {
            setInvalid(input, errorSpan, 'Debe tener al menos 18 años.');
        } else {
            setValid(input, errorSpan);
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