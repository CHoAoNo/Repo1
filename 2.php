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
	$a = preg_replace_callback("/". $b . "/", function ($m) use (&$counter, $rb) {

		if ($counter++ == 2) {
			return $rb;
		}

		return $m[0];
	}, $a);

	return $a;

}

//тест
//$a = "abc dfg abc7 qwerty zxc 1abc8 fgh";
//$b = "abc";
//echo convertString($a, $b);


?>