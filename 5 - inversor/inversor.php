<?php
// ====== STRING DEFINIDA ======
$string = "EstagioDEV";

// ====== FUNCTION PARA INVERTER STRING MANUALMENTE ======
function inverterString($input) {
    $tamanho = strlen($input);  
    $invertida = '';  

    // ====== PERCORRE A STRING DE TRAS PARA FRENTE ======
    for ($i = $tamanho - 1; $i >= 0; $i--) {
        $invertida .= $input[$i];  
    }

    return $invertida;
}

// ====== EXIBE A STRING ORIGINAL E A INVERTIDA ======
echo "<p><strong>String original:</strong> $string</p>";
echo "<p><strong>String invertida:</strong> " . inverterString($string) . "</p>";
?>
