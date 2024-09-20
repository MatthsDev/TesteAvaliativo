<?php
// ====== FATURAMENTO POR ESTADO ======
$faturamento = [
    "SP" => 67836.43,
    "RJ" => 36678.66,
    "MG" => 29229.88,
    "ES" => 27165.48,
    "Outros" => 19849.53
];

// ====== CALCULAR O TOTAL DE FATURAMENTO ======
$totalFaturamento = array_sum($faturamento);

// ====== EXIBIR PERCENTUAL DE REPRESENTACAO DE CADA ESTADO ======
echo "<h2>Percentual de Representação por Estado</h2>";
echo "<hr>";

foreach ($faturamento as $estado => $valor) {
    $percentual = ($valor / $totalFaturamento) * 100;
    echo "<p><strong>{$estado}:</strong> " . number_format($percentual, 2, ',', '.') . "%</p>";
}

echo "<hr>";
echo "<p><strong>Total de faturamento:</strong> R$ " . number_format($totalFaturamento, 2, ',', '.') . "</p>";
?>
