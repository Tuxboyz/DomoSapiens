document.addEventListener('DOMContentLoaded', function() {
    const nombre = document.getElementById('tarjeta-nombre');
    const tarjetaNumero = document.getElementById('tarjeta-numero');
    const tarjetaCaducidad1 = document.getElementById('tarjeta-caducidad1');
    const tarjetaCaducidad2 = document.getElementById('tarjeta-caducidad2');
    const tarjetaCVV = document.getElementById('tarjeta-cvv');

    const namePattern = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;

    nombre.addEventListener('input', function() {
        validateName(nombre);
    });

    tarjetaNumero.addEventListener('input', function() {
        validateTarjetaNumero(tarjetaNumero);
    });

    tarjetaCaducidad1.addEventListener('input', function() {
        validateFechaCaducidad();
    });

    tarjetaCaducidad2.addEventListener('input', function() {
        validateFechaCaducidad();
    });

    tarjetaCVV.addEventListener('input', function() {
        validateCVV(tarjetaCVV);
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

    function validateTarjetaNumero(input) {
        const num_tarjeta = input.value.replace(/\D|\s/g, '');
        const length = num_tarjeta.length;
        const errorSpan = document.getElementById('tarjeta-numero-feedback');
        
        let message = '';

        if (length < 16) {
            message = 'Error: El número de tarjeta tiene menos de 16 dígitos.';
        } else if (length > 16) {
            message = 'Error: El número de tarjeta tiene más de 16 dígitos.';
        } else if (!isValidLuhn(num_tarjeta)) {
            message = 'Error: El número de tarjeta no es válido.';
        } else {
            const tipoTarjeta = getTipoTarjeta(num_tarjeta);
            if (tipoTarjeta.includes("Error")) {
                message = tipoTarjeta;
            } else {
                message = 'Tipo de tarjeta: ' + tipoTarjeta;
                setValid(input, errorSpan);
                return;
            }
        }
        setInvalid(input, errorSpan, message);
    }

    function validateFechaCaducidad() {
        const cardMonth = parseInt(tarjetaCaducidad1.value);
        const cardYear = parseInt(tarjetaCaducidad2.value);
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth() + 1;
        const currentYear = currentDate.getFullYear();
        const errorSpan1 = document.getElementById('caducidad1-feedback');
        const errorSpan2 = document.getElementById('caducidad2-feedback');

        let message = '';

        if (cardYear < currentYear || (cardYear === currentYear && cardMonth < currentMonth)) {
            message = 'La fecha de vencimiento no puede ser pasada.';
            setInvalid(tarjetaCaducidad1, errorSpan1, message);
            setInvalid(tarjetaCaducidad2, errorSpan2, message);
        } else {
            setValid(tarjetaCaducidad1, errorSpan1);
            setValid(tarjetaCaducidad2, errorSpan2);
        }
    }

    function isValidLuhn(num_tarjeta) {
        let sum = 0;
        let shouldDouble = false;

        for (let i = num_tarjeta.length - 1; i >= 0; i--) {
            let digit = parseInt(num_tarjeta.charAt(i));

            if (shouldDouble) {
                digit *= 2;
                if (digit > 9) digit -= 9;
            }

            sum += digit;
            shouldDouble = !shouldDouble;
        }

        return sum % 10 === 0;
    }

    function getTipoTarjeta(cc) {
        const cards = {
            'visa': /^4\d{15}$/,
            'amex': /^3[47]\d{13}$/,
            'mastercard': /^5[1-5]\d{14}$/
        };

        for (let key in cards) {
            if (cards[key].test(cc)) {
                return key.charAt(0).toUpperCase() + key.slice(1);
            }
        }
        return "Error: El número de tarjeta no pertenece a Visa, Amex o Mastercard.";
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

    function validateCVV(input) {
        const cvvPattern = /^[0-9]{3,4}$/;
        const errorSpan = input.nextElementSibling.nextElementSibling;
        
        if (input.value === '') {
            setInvalid(input, errorSpan, 'Este campo es obligatorio.');
        } else if (!cvvPattern.test(input.value)) {
            setInvalid(input, errorSpan, 'El CVV debe tener entre 3 y 4 dígitos numéricos.');
        } else {
            setValid(input, errorSpan);
        }
    }
});