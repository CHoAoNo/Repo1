<?php
// Реализовать функцию exportXml($a, $b). 
// $a – путь к xml файлу вида (структура файла приведена ниже), $b – код рубрики. 
// Результат ее выполнения: выбрать из БД товары (и их характеристики, 
// необходимые для формирования файла) входящие в рубрику $b или в любую из 
// всех вложенных в нее рубрик, сохранить результат в файл $a.


// подключение к базе данных 
try {
	$db = new PDO('mysql:host=localhost;dbname=test_samson;charset=UTF8', 'root', '');

	// установить исключения при ошибках в базе данных 
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// установить режим извлечения строк таблицы в виде объектов 
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} 
catch (PDOException $е) {
	print "Ошибка соединения: " . $e->getMessage();
	exit();
}

// массив с id обработаных товаров, для отсева повторов
$processed_products = array();

// инициализация XML файла
$xmlstr = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<Товары>
</Товары>
XML;
$sxe = new SimpleXMLElement($xmlstr);



// функция формирования блока XML для 1 товара
function product_xml($product_id) {
	global $sxe;
	global $db;
	global $processed_products;

	// проверка на повтор текущего товара
	if (!in_array($product_id, $processed_products)) {

		$stmt = $db->prepare('SELECT a_product.code, a_product.name, a_price.type_price, '
		. 'a_price.price FROM a_product JOIN a_price '
		. 'ON a_product.id = a_price.product_id AND a_product.id = ?');
		$stmt->execute([$product_id]);
		$product_info = $stmt->fetch();

		// внесение информации о названии товара и цене
		$product = $sxe->addChild('Товар');
		$product->addAttribute('Код', $product_info->code);
		$product->addAttribute('Название', $product_info->name);
		$price = $product->addChild('Цена', $product_info->price);
		$price->addAttribute('Тип', $product_info->type_price);
		$product_info = $stmt->fetch();
		$price = $product->addChild('Цена', $product_info->price);
		$price->addAttribute('Тип', $product_info->type_price);


		// перебор свойств
		$properties = $product->addChild('Свойства');
		$stmt = $db->prepare('SELECT property, property_val, unit FROM a_property WHERE product_id=? ');
		$stmt->execute([$product_id]);

		while ( $product_prop = $stmt->fetch() ) {
			$cur_prop = $properties->addChild($product_prop->property, $product_prop->property_val);
			if ($product_prop->unit) {
				$cur_prop->addAttribute('ЕдИзм', $product_prop->unit);
			}
		}
		
		// перебор категорий
		$categories = $product->addChild('Разделы');
		$stmt = $db->prepare('SELECT a_category.name FROM a_category, product_category '
		. 'WHERE a_category.id = product_category.category_id AND product_category.product_id = ?');
		$stmt->execute([$product_id]);
		
		while ( $cat = $stmt->fetch() ) {
			$categories->addChild('Раздел', $cat->name);

		}

		// добавление id обработанного товара в массив
		$processed_products[] = $product_id;
	}
}

// функция перебора всех товаров в категории
// (с вызовом внутри product_xml для вывода каждого товара)
function products_in_cat($cat_id) {
	global $db;

	$stmt = $db->prepare('SELECT a_product.id FROM a_product JOIN product_category '
	. 'ON a_product.id = product_category.product_id AND product_category.category_id = ? ');
	$stmt->execute([$cat_id]);
	
	while ( $product = $stmt->fetch() ) {
		product_xml($product->id);
	}
}

// рекурсивная функция перебора подкатегорий
function rec_cat_search($cat_id) {
	global $db;

	// вывод всех товаров в текущей категории
	products_in_cat($cat_id);

	// определение всех подкатегорий и их циклический перебор с рекурсивным вызовом функции
	$stmt = $db->prepare('SELECT a_category.id FROM a_category WHERE a_category.parent_id = ? ');
	$stmt->execute([$cat_id]);
	
	while ( $cat = $stmt->fetch() ) {
		products_in_cat($cat->id);
		rec_cat_search($cat->id);
	}
}




// функция экспорта из БД в xml информации о товарах из категории и подкатегории
// $a - путь, $b - индекс категории
function exportXml($a, $b) {
	global $sxe;
	rec_cat_search($b);
	
	$sxe->asXML($a);
	print "Результат экспорта в файле " . $a;
}

// тест
$a = __DIR__ . '\export.xml';
exportXml($a, 0);

?>
