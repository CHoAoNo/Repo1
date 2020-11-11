<?php

// Реализовать функцию importXml($a). 
// $a – путь к xml файлу (структура файла приведена ниже). 
// Результат ее выполнения: прочитать файл $a и импортировать его в созданную БД.


// подключение к базе данных 
try {
	$db = new PDO('mysql:host=localhost;dbname=test_samson;charset=UTF8', 'root', '');

	// установить исключения при ошибках в базе данных 
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $е) {
	print "Ошибка соединения: " . $e->getMessage();
	exit();
}

function importXml($a) {
	
	global $db;
	try {
		//Подготовленные SQL-запросы к БД, $stmt для получения категории по имени
		$stmt_product = $db->prepare('INSERT INTO a_product (code, name) VALUES (?, ?) ');
		$stmt_price = $db->prepare('INSERT INTO a_price (product_id, type_price, price) VALUES (?, ?, ?) ');
		$stmt_property = $db->prepare('INSERT INTO a_property (product_id, property) VALUES (?, ?) ');
		$stmt_category = $db->prepare('INSERT INTO a_category (name) VALUES (?) ');
		$stmt_product_category = $db->prepare('INSERT INTO product_category (product_id, category_id) VALUES (?, ?) ');
		$stmt = $db->prepare('SELECT * FROM a_category WHERE name=? ');
	} 
	catch (PDOException $е) {
		print "Ошибка: " . $e->getMessage();
		exit();
	}


	// итератор для перемещения между товарами
	$sxi = new SimpleXmlIterator($a, null, true);
	$sxi->rewind();

	// перебор всех товаров, вначале в БД отправляется товар,
	// потом перебираются атрибуты товара: 1-2 это цена, 3 это свойства, 4 это разделы
	try {
		while ($sxi->valid()) {
			// импорт кода и названия товара и получение id
			$stmt_product->execute(array($sxi->current()['Код'], $sxi->current()['Название']));
			$product_id = $db->lastInsertId();

			// итератор атрибутов товара
			$sxi_atr = $sxi->getChildren();
			$sxi_atr->rewind();

			// счетчик указывающий к чему относятся текущие атрибуты
			$count = 1;

			//перебор атрибутов товара (цена, цена, свойства, категории)
			while ($sxi_atr->valid()) {

				// импорт цены товара
				if ($count < 3) {
					$stmt_price->execute(array($product_id, $sxi_atr->current()['Тип'], $sxi_atr->current()));
				}

				// импорт свойств товара
				if ($count == 3) {
					$sxi_prop = $sxi_atr->getChildren();
					$sxi_prop->rewind();
					while ($sxi_prop->valid()) {
						$stmt_property->execute(array($product_id, $sxi_prop->key() . " " .
							$sxi_prop->current() . $sxi_prop->current()['ЕдИзм']));

						$sxi_prop->next();
					}
				}

				// импорт информации о принадлежности товара к категории
				if ($count == 4) {
					$sxi_cat = $sxi_atr->getChildren();
					$sxi_cat->rewind();
					while ($sxi_cat->valid()) {

						// получение из БД категории с именем как текущая рассматриваемая в XML
						$stmt->execute([$sxi_cat->current()]);
						$cat = $stmt->fetch();

						// если такой категории в БД ещё нет, то создать такую и запомнить id 
						if (!$cat) {
							$stmt_category->execute(array($sxi_cat->current()));
							$category_id = $db->lastInsertId();
						}
						// если такая категория в БД есть, то взять id этой категории
						else {
							$category_id = $cat['id'];
						}

						// заполнение таблицы product_category 
						// с информацией о принадлежности товара категории
						$stmt_product_category->execute(array($product_id, $category_id));
						$sxi_cat->next();
					}
				}

				// следующие атрибуты товара
				$sxi_atr->next();
				$count++;
			}

			// следующий товар
			$sxi->next();
		}

		print "Вся информация успешно импортирована в БД";
	} catch (PDOException $e) {
		print "Не получилось внести информацию в БД";
	}
}
// конец функции xml importXml()


// тест
$a = __DIR__ . "\data.xml";
importXml($a);
?>