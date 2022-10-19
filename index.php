<?php 
$confirmation_token = '46498634';
$token = 'ce7f72e50cf12c397e23f2e1f3d18656c5ca0cbd0424ed4f341b2db1fdd71d58846d864a5d5cf77bc6b69';
$data = json_decode(file_get_contents('php://input'));


$opts = array(
  'http' => array(
    'method' => "GET",
    'header' => "X-Yandex-API-Key:0654bd89-a873-429d-8845-b94c1531cbfc"."\r\n",
    'lang' => "ru_RU"
    )
);

$context = stream_context_create($opts);
$f=file_get_contents("https://api.weather.yandex.ru/v2/forecast/?lat=56.852677&lon=53.206896&lang=ru_RU",false, $context);

$f=json_decode($f);
$t=$f->fact;




   $data = json_decode(file_get_contents('php://input'));

function vk_msg_send($peer_id,$text){
    $request_params = array(
        'message' => $text, 
        'peer_id' => $peer_id, 
        'access_token' => "TOKEN",
        'v' => '5.87' 
    );
    $get_params = http_build_query($request_params); 
    file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
}

   switch ($data->type) {  
    case 'confirmation': 
        echo $confirmation_token; 
    break;  
    case 'message_new': 
        $message_text = $data -> object -> text;
        $message_text = $data -> object -> peer_id;
        if ($message_text == "погода"){
            echo "Температура: ".$t->temp."°C (ощущается как ".$t->feels_like."°C)<br>"
   . "Скорость ветра: ".$t->wind_speed." м/с.<br>"
   . "Скорость порывов ветра:".$t->wind_gust." м/с<br>"
   . "Направление ветра: ".$t->wind_dir."<br>"
   . "Давление: ".$t->pressure_mm." мм рт. ст.<br>"
   . "Влажность воздуха: ".$t->humidity."%<br>"
   . "Время суток: ".$t->daytime."<br>";
        }
        echo 'ok';
    break;
}


?>
