<?php
namespace Test3;

class newBase
{
    static private $count = 0;
    static private $arSetName = [];
    /**
     * @param string $name
     */
		 // тип аргумента name string
    function __construct(string $name="")
    {
        if (empty($name)) {
						// 	вместо array_search правильно in_array
            while (in_array(self::$count, self::$arSetName) != false) {
                ++self::$count;
            }
            $name = self::$count;
        }
        $this->name = $name;
        self::$arSetName[] = $this->name;
    }
		// это поле наследуется, вместо private -> protected
    protected $name;
    /**
     * @return string
     */
    public function getName(): string
    {
        return '*' . $this->name  . '*';
    }
    protected $value;
    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    /**
     * @return string
     */
    public function getSize()
    {
        $size = strlen(serialize($this->value));
				// strlen($size) не за чем прибавлять
        return $size;
    }
    public function __sleep()
    {
        return ['value'];
    }
    /**
     * @return string
     */
    public function getSave(): string
    {
				// вместо $value $this->value
        $value = serialize($this->value);
				// вместо sizeof() -> strlen()
        return $this->name . ':' . strlen($value) . ':' . $value;
    }
    /**
     * @return newBase
     */
    static public function load(string $value): newBase
    {
        $arValue = explode(':', $value);
				
       // разбито на части для лучшего понимания,
			 // к тому же в таком виде это работает
			 $res = new newBase($arValue[0]);
			 $val = unserialize(substr($value, (strlen($arValue[0]) + 1 + strlen($arValue[1]) + 1) ) );
				$res->setValue($val);
				
        return $res;
    }
}
class newView extends newBase
{
    private $type = null;
    private $size = 0;
    private $property = null;
    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        parent::setValue($value);
        $this->setType();
        $this->setSize();
    }
    public function setProperty($value)
    {
        $this->property = $value;
        return $this;
    }
    private function setType()
    {
				// gettype это встроенная функция php
				// переименована в 
        $this->type = myGetType($this->value);
    }
    private function setSize()
    {
        if (is_subclass_of($this->value, "Test3\newView")) {
            $this->size = parent::getSize() + 1 + strlen($this->property);
        } elseif ($this->type == 'test') {
            $this->size = parent::getSize();
        } else {
            $this->size = strlen($this->value);
        }
    }
    /**
     * @return string
     */
    public function __sleep()
    {
        return ['property'];
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        if (empty($this->name)) {
            throw new Exception('The object doesn\'t have name');
        }
        return '"' . $this->name  . '": ';
    }
    /**
     * @return string
     */
    public function getType(): string
    {
        return ' type ' . $this->type  . ';';
    }
    /**
     * @return string
     */
    public function getSize(): string
    {
        return ' size ' . $this->size . ';';
    }
    public function getInfo()
    {
        try {
            echo $this->getName()
                . $this->getType()
                . $this->getSize()
                . "\r\n";
        } catch (Exception $exc) {
            echo 'Error: ' . $exc->getMessage();
        }
    }
    /**
     * @return string
     */
    public function getSave(): string
    {
				// удален блок if
        return parent::getSave() . serialize($this->property);
    }
    /**
     * @return newView
     */
    static public function load(string $value): newBase
    {
        $arValue = explode(':', $value);
				
				// разбито на части для лучшего понимания,
				// к тому же в таком виде это работает
				$res = new newView($arValue[0]);
        $val = unserialize(substr($value, (strlen($arValue[0]) + 1 + strlen($arValue[1]) + 1), $arValue[1]) );
				$res->setValue($val);
				$prop = unserialize(substr($value, (strlen($arValue[0]) + 1 + strlen($arValue[1]) + 1 + $arValue[1]) ) );
				$res->setProperty($prop);
        return $res;
    }
}
// навзание функции вместо gettype -> myGetType
function myGetType($value): string
{ 
    if (is_object($value)) {
        $type = get_class($value);
        do {
						// вместо " -> '
            if (strpos($type, 'Test3\newBase') !== false) {
                return 'test';
            }
        } while ($type = get_parent_class($type));
    }
    return gettype($value);
}


$obj = new newBase('12345');
$obj->setValue('text');

$obj2 = new \Test3\newView('O9876');
$obj2->setValue($obj);
$obj2->setProperty('field');
$obj2->getInfo();

$save = $obj2->getSave();

$obj3 = newView::load($save);

var_dump($obj2->getSave() == $obj3->getSave());

