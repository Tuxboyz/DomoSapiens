document.addEventListener('DOMContentLoaded', function () {
    const nombre = document.getElementById('nombre');
    const apellidos = document.getElementById('apellido');
    const email = document.getElementById('email');
    const confirmEmail = document.getElementById('confirm_email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const telefono = document.getElementById('telefono');
    const fechaNacimiento = document.getElementById('fecha_nacimiento');

    const namePattern = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
    const phonePattern = /^\d{9}$/;
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|es|org)$/;

    nombre.addEventListener('input', function() {
        validateName(nombre);
    });

    apellidos.addEventListener('input', function() {
        validateName(apellidos);
    });

    email.addEventListener('blur', function() {
        validateEmail();
    });

    confirmEmail.addEventListener('blur', function() {
        validateEmail();
    });

    password.addEventListener('input', function() {
        validatePassword();
    });

    confirmPassword.addEventListener('input', function() {
        validateConfirmPassword();
    });

    telefono.addEventListener('input', function() {
        validatePhone();
    });

    fechaNacimiento.addEventListener('blur', function() {
        validateFechaNacimiento();
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

    function validateEmail() {
        const emailErrorSpan = email.nextElementSibling.nextElementSibling;
        const confirmEmailErrorSpan = confirmEmail.nextElementSibling.nextElementSibling;
        if (email.value === '' || confirmEmail.value === '') {
            setInvalid(email, emailErrorSpan, 'Este campo es obligatorio.');
            setInvalid(confirmEmail, confirmEmailErrorSpan, 'Este campo es obligatorio.');
        } else if (!emailPattern.test(email.value)) {
            setInvalid(email, emailErrorSpan, 'El correo electrónico debe tener un dominio válido (.com, .es, .org).');
            setInvalid(confirmEmail, confirmEmailErrorSpan, '');
        } else if (email.value !== confirmEmail.value) {
            setValid(email, emailErrorSpan);
            setInvalid(confirmEmail, confirmEmailErrorSpan, 'Los correos electrónicos no coinciden.');
        } else {
            setValid(email, emailErrorSpan);
            setValid(confirmEmail, confirmEmailErrorSpan);
        }
    }

    function validatePassword() {
        const errorSpan = password.nextElementSibling.nextElementSibling;
        if (password.value === '') {
            setInvalid(password, errorSpan, 'Este campo es obligatorio.');
        } else if (password.value.length < 8) {
            setInvalid(password, errorSpan, 'La contraseña debe tener al menos 8 caracteres.');
        } else {
            setValid(password, errorSpan);
        }
    }

    function validateConfirmPassword() {
        const errorSpan = confirmPassword.nextElementSibling.nextElementSibling;
        if (confirmPassword.value === '') {
            setInvalid(confirmPassword, errorSpan, 'Este campo es obligatorio.');
        } else if (confirmPassword.value.length < 8) {
            setInvalid(confirmPassword, errorSpan, 'La contraseña debe tener al menos 8 caracteres.');
        } else if (confirmPassword.value !== password.value) {
            setInvalid(confirmPassword, errorSpan, 'Las contraseñas no coinciden.');
        } else {
            setValid(confirmPassword, errorSpan);
        }
    }

    function validatePhone() {
        const errorSpan = telefono.nextElementSibling.nextElementSibling;
        if (telefono.value === '') {
            setInvalid(telefono, errorSpan, 'Este campo es obligatorio.');
        } else if (!phonePattern.test(telefono.value)) {
            setInvalid(telefono, errorSpan, 'El número de teléfono debe tener 9 dígitos.');
        } else {
            setValid(telefono, errorSpan);
        }
    }

    function validateFechaNacimiento() {
        const errorSpan = fechaNacimiento.nextElementSibling.nextElementSibling;
        const fecha = new Date(fechaNacimiento.value);
        const hoy = new Date();
        let edad = hoy.getFullYear() - fecha.getFullYear();
        const mes = hoy.getMonth() - fecha.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fecha.getDate())) {
            edad--;
        }

        if (fechaNacimiento.value === '') {
            setInvalid(fechaNacimiento, errorSpan, 'Este campo es obligatorio.');
        } else if (edad < 18) {
            setInvalid(fechaNacimiento, errorSpan, 'Debe tener al menos 18 años.');
        } else {
            setValid(fechaNacimiento, errorSpan);
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
