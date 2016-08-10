<?php

require 'NovaPoshtaApi2.php'; // подключаем класс для работы с API

$np = new NovaPoshtaApi2(
    'dab75bd1ebdaff1a30136bcfc28dcefb',
    'ru', // Язык возвращаемых данных: ru (default) | ua | en
    TRUE, // При ошибке в запросе выбрасывать Exception: FALSE (default) | TRUE
    'curl' // Используемый механизм запроса: curl (defalut) | file_get_content
);

	$warehouses = $np->getWarehouses($_POST['choise_city']); // получение данных о складах из выбранного города
	$count = count($warehouses['data']); // считаем количество отделений в выбранном городе

	echo json_encode(array ("1" => $count, "2" => $warehouses), JSON_UNESCAPED_UNICODE); // конвертируем данные в формат JSON + сразу проверяем длинну


?>