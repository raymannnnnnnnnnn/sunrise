<?php

function isNightTimeInLisbon() {
    $url = "https://api.sunrise-sunset.org/json?lat=38.7169&lng=-9.1399&formatted=0";
    
    // Obtém os dados da API
    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Erro ao obter os dados da API");
    }
    
    // Converte a resposta para um array associativo
    $data = json_decode($response, true);
    if (!isset($data['status']) || $data['status'] !== 'OK') {
        die("Erro na resposta da API");
    }
    
    // Obtém horários de sunrise e sunset em UTC
    $sunriseUTC = strtotime($data['results']['sunrise']);
    $sunsetUTC = strtotime($data['results']['sunset']);
    
    // Obtém o horário atual de Lisboa
    $currentTimeLisbon = time();
    
    // Converte horários de sunrise e sunset para o fuso de Lisboa (UTC+1 ou UTC+0 dependendo do horário de verão)
    $timezone = new DateTimeZone("Europe/Lisbon");
    $now = new DateTime("now", $timezone);
    $offset = $timezone->getOffset($now); // Offset em segundos
    
    $sunriseLisbon = $sunriseUTC + $offset;
    $sunsetLisbon = $sunsetUTC + $offset;
    
    // Verifica se está de noite
    return ($currentTimeLisbon < $sunriseLisbon || $currentTimeLisbon > $sunsetLisbon);
}

if (isNightTimeInLisbon()) {
    echo "0";
} else {
    echo "1";
}

?>
