<?php 
$confirmation_token = '6043f2f5';
$token = '3e1927815c2952f3df349dd0db1e18475b0036403187f51d0b97f3e9244c4520f17c2cf1a51f164e1e77b';
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

function vk_msg_send($peer_id,$text){
    $request_params = array(
        'message' => $text, 
        'peer_id' => $peer_id, 
        'access_token' => "3e1927815c2952f3df349dd0db1e18475b0036403187f51d0b97f3e9244c4520f17c2cf1a51f164e1e77b",
        'v' => '5.87' 
    );
    $get_params = http_build_query($request_params); 
    file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
}


switch ($t->wind_dir) {
                case 'nw':
                    $t->wind_dir =  "северо-западное";
                    break;
                    case 'n':
                    $t->wind_dir =  "северное";
                    break;
                    case 'ne':
                    $t->wind_dir =  "северо-восточное";
                    break;
                    case 'e':
                    $t->wind_dir =  "восточное";
                    break;
                    case 'se':
                    $t->wind_dir =  "юго-восточное";
                    break;
                    case 's':
                    $t->wind_dir =  "южное";
                    break;
                    case 'sw':
                    $t->wind_dir =  "юго-западное";
                    break;
                    case 'w':
                    $t->wind_dir =  "штиль";
                    break;
            }

            switch ($t->daytime) {
                case 'n':
                    $t->daytime = "темное время суток";
                    break;
                    case 'd':
                    $t->daytime = "светлое время суток";
                    break;
            }

            switch ($t->prec_type) {
                case '0':
                    $t->prec_type = "без осадков";
                    break;
                    case '1':
                    $t->prec_type = "дождь";
                    break;
                    case '2':
                    $t->prec_type = "дождь со снегом";
                    break;
                    case '3':
                    $t->prec_type = "снег";
                    break;
                    case '4':
                    $t->prec_type = "град";
                    break;
            }

            


   switch ($data->type) {  
    case 'confirmation': 
        echo $confirmation_token; 
    break;  
    case 'message_new': 
        $message_text = $data->object->text;
        $chat_id = $data->object->peer_id;
        if ($message_text == ".погода"){

            vk_msg_send($chat_id, "Температура: ".$t->temp."°C (ощущается как ".$t->feels_like."°C)<br>"
   . "Скорость ветра: ".$t->wind_speed." м/с.<br>"
   . "Скорость порывов ветра: ".$t->wind_gust." м/с<br>"
   . "Направление ветра: ".$t->wind_dir."<br>"
   . "Давление: ".$t->pressure_mm." мм рт. ст.<br>"
   . "Влажность воздуха: ".$t->humidity."%<br>"
   . "Время суток: ".$t->daytime."<br>"
    . "Осадки: ".$t->prec_type."<br>");
            
        }
        echo 'ok';
    break;
}


?>
