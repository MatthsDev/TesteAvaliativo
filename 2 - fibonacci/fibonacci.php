<?php

// ====== VERIFICAÇÃO PARA SABER SE UM NUMERO PERTECE A SEQUENCIA DE FIBO ======
function pertenceFibonacci($num) {
    $a = 0;
    $b = 1;
    
    if ($num == $a || $num == $b) {
        return true;
    }
    
    while ($b < $num) {
        $temp = $b;
        $b = $a + $b;
        $a = $temp;
    }
    
    return $b == $num;
}

// ====== NUMERO A SER VERIFICADO ======
$num = 4; 

// ====== CONDIÇÃO ======
if (pertenceFibonacci($num)) {
    echo "$num pertence a sequencia de Fibonacci.";
} else {
    echo "$num NÃO pertence a sequencia  de Fibonacci.";
}
?>
