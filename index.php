<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html" />
<script src="http://api-maps.yandex.ru/0.8/?lang=ru_RU&coordorder=longlat" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<script type="text/javascript" charset="utf-8">

$(document).ready (function () { // воспользоватся функцией можно только после полной загрузки страницы
	$("select[name='choise_city']").bind("change", function () {
		$("select[name='warehouses']").empty(); // очищаем склады при смене города
		$.post("warehouses.php", {choise_city: $("select[name='choise_city']").val()}, function(data) {
			data = JSON.parse(data); // конвертируем JSON файл в JS
			count = data[1]; // считаем количество складов для списка и карты
			// формируем с полученного массива список адресов
			for(var i = 0; i < count; i++){
				$("select[name='warehouses']").append($("<option value='" + data[2].data[i].Ref + "'>'" + data[2].data[i++].DescriptionRu + "</option>"));
			}
			
			// вызов карты и отображения меток
			var map;
			map = new YMaps.Map(document.getElementById("YMapsID"));
			map.setCenter(new YMaps.GeoPoint(data[2].data[0].Longitude, data[2].data[0].Latitude), 12, YMaps.MapType.MAP);
			map.addControl(new YMaps.TypeControl());
			map.addControl(new YMaps.ToolBar());
			map.addControl(new YMaps.Zoom());
			map.addControl(new YMaps.ScaleLine());
			 	
			 	// в этом цикле выводим все отделения города в виде меток + описание
				for(var j=0; j < count; j++) {
					var point = new YMaps.GeoPoint(data[2].data[j].Longitude, data[2].data[j].Latitude);
					var placemark = new YMaps.Placemark(point);
					placemark.description =  data[2].data[j].DescriptionRu; // описание метки
					map.addOverlay(placemark); // вывод

				}

		});
	});
});

</script>

</head>
<body>

<!-- Выводим карту -->
<div id="YMapsID" style="height:500px; width:750px;"></div>

<?php

require 'NovaPoshtaApi2.php'; // подключаем класс для работы с API новой почты

$np = new NovaPoshtaApi2(
    'dab75bd1ebdaff1a30136bcfc28dcefb',
    'ru', // Язык возвращаемых данных: ru (default) | ua | en
    TRUE, // При ошибке в запросе выбрасывать Exception: FALSE (default) | TRUE
    'curl' // Используемый механизм запроса: curl (defalut) | file_get_content
);

$city = $np->getCities(); // получаем список городов


$count = count($city['data']); // считаем количество городов для остановки цикла


echo '<select name="choise_city">';
	echo '<option value="0">Выберите город</option>';
	//перебираем массив с помощью цикла и выводим выпадающий список городов
	for($i=0; $i < $count; $i++) {
		echo '<option value="'. $city['data'][$i]['Ref'] .'">'. $city['data'][$i++]['DescriptionRu'] .'</option>';
	}
echo '</select><br />';

echo '<select name="warehouses">';
echo '<option value="0">Выберите отделение</option>';
echo '</select>';

?>


</body>
</html>