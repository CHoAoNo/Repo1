<?php

// Реализовать функцию findSimple ($a, $b). $a и $b – целые положительные числа. 
// Результат ее выполнение: массив простых чисел от $a до $b.
function findSimple($a, $b){
	$res = array();
	
	if($a>$b){
		$c = $a;
		$a = $b;
		$b = $c;
	}

	$sqrt_b = floor(sqrt($b));
	
	$S = str_repeat("1", $b+1);
	$S[0]=$S[1]="0";
	$res = array();
	
	for($i=2;$i<=$sqrt_b;$i++){
		if($S[$i]==="1"){
			for($j=$i*$i; $j<=$b; $j+=$i){
				$S[$j]="0";
			}
		}
	}
		
	for($i=$a;$i<=$b;$i++){
		if($S[$i]==="1")
			$res[] = $i;
		}

	return $res;

}

// тест
// var_dump (findSimple(5000, 100000));




// Реализовать функцию createTrapeze($a). $a – массив положительных чисел, количество элементов кратно 3. 
// Результат ее выполнения: двумерный массив (массив состоящий из ассоциативных массива с ключами a, b, c). 
// Пример для входных массива [1, 2, 3, 4, 5, 6] результат [[‘a’=>1,’b’=>2,’с’=>3],[‘a’=>4,’b’=>5 ,’c’=>6]].
function createTrapeze(array $a){
	
	$res = array();
	$keys = array('a', 'b', 'c');
	
	$arrs = array_chunk($a, 3);
	foreach($arrs as $arr){	
		$res[] = array_combine($keys, (array)$arr);
	}
	return $res;
	
}



// Реализовать функцию squareTrapeze($a). $a – массив результата выполнения функции createTrapeze(). 
// Результат ее выполнения: в исходный массив для каждой тройки чисел добавляется 
// дополнительный ключ s, содержащий результат расчета площади трапеции со сторонами a и b, и высотой c.
function squareTrapeze(&$a){
	
	foreach($a as &$arr){
		$arr['s'] = 0.5 * ($arr['a'] + $arr['b']) * $arr['c'];
	}
	return $a;
	
}


// Реализовать функцию getSizeForLimit($a, $b). $a – массив результата выполнения функции squareTrapeze(), 
// $b – максимальная площадь. 
// Результат ее выполнения: массив размеров трапеции с максимальной площадью, но меньше или равной $b.

// вспомогательная функция для сортировки
function cmp($a, $b){
    return $b["s"] <=> $a["s"];
}

function getSizeForLimit($a, $b){
	
	usort($a, "cmp");
	
	foreach($a as $val)
	{
		if($val['s'] <= $b)
			return $val;
	}
	
	return false;
}


// тест предыдущих 3 функций
/* 
$arr = [331,2,5,6,7,33,11,43,12,434,33,77];
$arr = createTrapeze($arr);

squareTrapeze($arr);
echo '<pre>' . print_r($arr, true) . '</pre>';

print_r(getSizeForLimit($arr, 350));
*/



// Реализовать функцию getMin($a). $a – массив чисел. 
// Результат ее выполнения: минимальное число в массиве 
// (не используя функцию min, ключи массива могут быть ассоциативными).
function getMin($a){
	$min = $a[array_key_first($a)];
	foreach($a as $key => $val){
		if($val < $min){
			$min = $val;
		}
	}
	return $min;
}

// тесты функции
/* 
$arr = [331,2,5,6,7,33,11,43,12,434,33, -3,77];
print_r(getMin($arr));
$arr = array(	"Sun City" => 43,
							"Surprise" => 13,
							"Tempe" => 7,
							"Tucson" => 345,
							"Wickenburg" => 56,);
							
echo '<br>';
print_r(getMin($arr));
*/ 




// Реализовать функцию printTrapeze($a). $a – массив результата выполнения функции squareTrapeze(). 
// Результат ее выполнение: вывод таблицы с размерами трапеций, 
// строки с нечетной площадью трапеции отметить любым способом.
function printTrapeze($a){
	
	 echo '<table class="table" border="1">';
	 echo '<tr><th>сторона a</th><th>сторона b</th><th>высота</th><th>площадь</th></tr>';
	 
		$tr="";
		foreach($a as $val){
			if( $val['s']%2 ){
				$tr='<tr style="background: #fffff0;">';
			}
			else{
				$tr="<tr>";
			}
				
			printf("%s<td>%d</td><td>%d</td><td>%d</td><td>%d</td></tr>",
				$tr, $val['a'], $val['b'], $val['c'], $val['s']); 
		}
		
	echo '</table>';
	
}

// тест функции
/*
$arr = [331,2,5,6,5,67,23,7,33,11,43,12,434,33,77];
$arr = createTrapeze($arr);
squareTrapeze($arr);
printTrapeze($arr);
*/




// Реализовать абстрактный класс BaseMath содержащий 3 метода: exp1($a, $b, $c) и exp2($a, $b, $c),getValue(). 
// Метода exp1 реализует расчет по формуле a*(b^c). 
// Метода exp2 реализует расчет по формуле (a/b)^c. 
// Метод getValue() возвращает результат расчета класса наследника.
abstract class BaseMath{
	

    abstract protected function getValue();


    protected function exp1($a, $b, $c){
        return $a * ($b ** $c);
    }
		
		protected function exp2($a, $b, $c){
        return ($a / $b) ** $c;
    }
}



// Реализовать класс F1 наследующий методы BaseMath, 
// содержащий конструктор с параметрами ($a, $b, $c) и метод getValue(). 
// Класс реализует расчет по формуле f=(a*(b^c)+(((a/c)^b)%3)^min(a,b,c)).

// если я правильно понял то символ ^ это возведение в степень
// символ % я принял за операцию php деление по модулю
class F1 extends BaseMath{
	
		public int $a;
		public int $b;
		public int $c;
		
		public function __construct ($a, $b, $c){
			 $this->a = $a;
			 $this->b = $b;
			 $this->c = $c;
		 }
		 

	
    public function getValue() {
        return parent::exp1($this->a, $this->b, $this->c) + 
				( (($this->a/$this->c) ** $this->b) % 3 ) ** min($this->a, $this->b, $this->c);

    }

}

// тест
/*
$a = new F1(8,5,4);
//5000 + (32%3)^4 = 5016
echo $a->getValue();
*/

?>