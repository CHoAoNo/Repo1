<?php

// функция convertString($a, $b). 
// Результат ее выполнения: если в строке $a содержится 2 и более подстроки $b, 
// то во втором месте заменить подстроку $b на инвертированную подстроку.
function convertString($a, $b) {

	// инвертированная подстрока поиска
	$rb = strrev($b);

	$counter = 1;
	// поиск по регулярному выражению и замена с использованием callback-функции
	// при счетчике равном 2 сделать замену
	$a = preg_replace_callback("/$b/", function ($m) use (&$counter, $rb) {

		if ($counter++ == 2) {
			return $rb;
		}

		return $m[0];
	}, $a);

	return $a;

}

//тест
//$a = "abc dfgh abc7 qwerty zxc 1abc8 fgh";
//$b = "fgh";
//echo convertString($a, $b);






// функия mySortForKey($a, $b). 
// $a – двумерный массив вида [['a'=>2,'b'=>1],['a'=>1,'b'=>3]], 
// $b – ключ вложенного массива. 
// Результат ее выполнения: двумерный массив $a отсортированный 
// по возрастанию значений для ключа $b. 
// В случае отсутствия ключа $b в одном из вложенных массивов, выбросить
// ошибку класса Exception с индексом неправильного массива.

// вспомогательная функция сортировки по ключу
function build_sorter($key) {
    return function ($a, $b) use ($key) {
        return $a[$key] <=> $b[$key];
    };
}


function mySortForKey(&$a, $b){
	foreach($a as $key => $arr)
	{
		if( !array_key_exists($b, $arr) )
		throw new Exception("В массиве с индексом [" . $key 
		. "] отсутствует необходимый ключ '" . $b . "'");
	}
	
		usort($a, build_sorter($b));
}

//тест
//try {
//	$a = array( array("a" => 11, "b" => 33),
//            array("a" => 5,  "b" => 11),
//            array("a" => 15, "b" => 88),
//						array("a" => 43, "b" => 34),
//						array("a" => 27, "b" => 17) ); 
//
//	$b = "b";
//	mySortForKey($a, $b);	
//	var_dump($a);
//} 
//catch (Exception $e) {
//    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
//}





?>