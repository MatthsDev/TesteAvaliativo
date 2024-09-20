<?php
// HABILITAR EXIBIÇÃO DE ERROS
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CAMINHO BASE DOS ARQUIVOS JSON E XML
$url = $_SERVER['DOCUMENT_ROOT'] . '/TesteAvaliativo/config/';

// ====== CAMINHOS DOS ARQUIVOS JSON E XML ====== 
$caminhoJson = $url . 'dados.json'; 
$caminhoXml = $url . 'dados.xml';   

// ======  LER FATURAMENTO DOS ARQUIVOS ====== 
$faturamentoJson = lerFaturamentoJson($caminhoJson);
$faturamentoXml = lerFaturamentoXml($caminhoXml);

// ====== FUNCTION PARA LER O ARQUIVO JSON DE FATURAMENTO ====== 
function lerFaturamentoJson($caminhoJson) {
    $conteudoJson = file_get_contents($caminhoJson);
    if ($conteudoJson === false) {
        die("Erro ao ler o arquivo JSON: $caminhoJson");
    }
    return json_decode($conteudoJson, true);
}





// ====== FUNCTIOM PARA LER O ARQUIVO XML DE FATURAMENTO ====== 
function lerFaturamentoXml($caminhoXml) {
    $xml = simplexml_load_file($caminhoXml);
    if ($xml === false) {
        die("Erro ao carregar XML: $caminhoXml");
    }

    return array_map(function($row) {
        return [
            'dia' => (int) $row->dia,
            'valor' => (float) $row->valor
        ];
    }, iterator_to_array($xml->row));
}

// ====== FUNCTION PARA COMBINAR DADOS DE JSON E XML ====== 
function combinarFaturamento(array $dadosJson, array $dadosXml) {
    $dadosCombinados = [];

    // ====== ADICIONAR DADOS DO JSON ======
    foreach ($dadosJson as $diaJson) {
        $dadosCombinados[$diaJson['dia']] = $diaJson['valor'];
    }

    // ====== ADICIONAR OU COMBINAR DADOS DO XML ======
    foreach ($dadosXml as $diaXml) {
        if (isset($dadosCombinados[$diaXml['dia']])) {
            $dadosCombinados[$diaXml['dia']] += $diaXml['valor']; // SOMAR VALORES SE O DIA EXISTIR EM AMBOS
        } else {
            $dadosCombinados[$diaXml['dia']] = $diaXml['valor']; // ADICIONAR VALOR DO XML SE O DIA NÃO ESTIVER NO JSON
        }
    }

    return $dadosCombinados;
}

// ====== FUNCTION PARA CALCULAR MENOR, MAIOR E DIAS ACIMA DA MEDIA ====== 
function calcularEstatisticas(array $faturamento) {
    $totalFaturamento = 0;
    $diasComFaturamento = 0;
    $valoresValidos = [];

    // ======  FILTRAR DIAS COM FATURAMENTO VALIDO (> 0) E SOMAR VALORES ====== 
    foreach ($faturamento as $valor) {
        if ($valor > 0) {
            $valoresValidos[] = $valor;
            $totalFaturamento += $valor;
            $diasComFaturamento++;
        }
    }

    // ====== CALCULO DA MEDIA ====== 
    $mediaFaturamento = ($diasComFaturamento > 0) ? ($totalFaturamento / $diasComFaturamento) : 0;

    // ======  ENCONTRAR MENOR E MAIOR VALOR ====== 
    $menorFaturamento = !empty($valoresValidos) ? min($valoresValidos) : 0;
    $maiorFaturamento = !empty($valoresValidos) ? max($valoresValidos) : 0;

    // ====== CONTAR DIAS COM FATURAMENTO ACIMA DA MEDIA ====== 
    $diasAcimaMedia = 0;
    foreach ($valoresValidos as $valor) {
        if ($valor > $mediaFaturamento) {
            $diasAcimaMedia++;
        }
    }

    return [
        'menorFaturamento' => $menorFaturamento,
        'maiorFaturamento' => $maiorFaturamento,
        'diasAcimaMedia' => $diasAcimaMedia,
        'mediaFaturamento' => $mediaFaturamento
    ];
}



// ====== COMBINAR DADOS DE JSON E XML ====== 
$faturamentoCombinado = combinarFaturamento($faturamentoJson, $faturamentoXml);

// ====== CALCULAR OS DADOS DE FATURAMENTO ====== 
$estatisticasFaturamento = calcularEstatisticas($faturamentoCombinado);


// ====== EXIBIR OS RESULTADOS ====== 
echo "<h2>RESULTADOS</h2>";
echo "<hr>";
echo "<p><strong>Menor valor de faturamento:</strong> R$ " . number_format($estatisticasFaturamento['menorFaturamento'], 2, ',', '.') . "</p>";
echo "<p><strong>Maior valor de faturamento:</strong> R$ " . number_format($estatisticasFaturamento['maiorFaturamento'], 2, ',', '.') . "</p>";
echo "<p><strong>Dias com faturamento acima da média:</strong> " . $estatisticasFaturamento['diasAcimaMedia'] . "</p>";
echo "<p><strong>Média de faturamento:</strong> R$ " . number_format($estatisticasFaturamento['mediaFaturamento'], 2, ',', '.') . "</p>";
echo "<hr>";

?>
