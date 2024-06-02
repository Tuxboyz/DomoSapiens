<?php
function validarTarjeta($num_tarjeta) {
    $num_tarjeta = preg_replace("/\D|\s/", "", $num_tarjeta);
    $length = strlen($num_tarjeta);
    
    if ($length < 16) {
        return false;
    } elseif ($length > 16) {
        return false;
    }

    $parity = $length % 2;
    $sum = 0;
    
    for ($i = 0; $i < $length; $i++) {
        $digit = $num_tarjeta[$i];
        if ($i % 2 == $parity) $digit = $digit * 2;
        if ($digit > 9) $digit = $digit - 9;
        $sum = $sum + $digit;
    }
    
    return ($sum % 10 == 0) ? true : false;
}

function getTipoTarjeta($cc) {
    $cc = str_replace(" ", "", $cc); // Quitar espacios
    
    // Validar longitud del número de tarjeta
    $length_error = validarTarjeta($cc);
    if ($length_error !== true) {
        return false;
    }
    
    $cards = array(
        'visa' => "(4\d{12}(?:\d{3})?)",
        'amex' => "(3[47]\d{13})",
        'jcb' => "(35[2-8]\d{12})",
        'maestro' => "((?:5020|5038|6304|6579|6761)\d{12}(?:\d{2})?)",
        'solo' => "((?:6334|6767)\d{12}(?:\d{2})?\d?)",
        'mastercard' => "(5[1-5]\d{14})",
        'switch' => "(?:(?:(?:4903|4905|4911|4936|6333|6759)\d{12})|(?:(?:564182|633110)\d{10})(\d{2})?\d?)"
    );
    
    $names = array('Visa', 'American Express', 'JCB', 'Maestro', 'Solo', 'Mastercard', 'Switch');
    
    $matches = array();
    
    $pattern = "#^(?:" . implode("|", $cards) . ")$#";
    
    $result = preg_match($pattern, $cc, $matches);
    
    if ($result > 0) {
        $result = (validarTarjeta($cc) === true) ? 1 : 0;
    }
    
    return ($result > 0) ? true : false;
}

// Ejemplo:
if (getTipoTarjeta("4111 1111 1111 1111") == true){
    echo 'salio bien1';
} else {
    echo 'salio mal1';
}

if (getTipoTarjeta("1234") == true){
    echo 'salio bien2';
} else {
    echo 'salio mal2';
}


// recursos obtenidos de https://www.ventics.com/validar-numero-de-tarjeta-de-credito-expresiones-regulares-en-php/
?>
