<?php

class Car
{
    private $brand;
    private $color;
    private $engineVolume;
    private $horsePower;
    
    public function __construct ($brand, $color, $engineVolume, $horsePower)
    {
        $this->brand = $brand;
        $this->color = $color;
        $this->engineVolume = $engineVolume;
        $this->horsePower = $horsePower;
    }
    
    public function getBrand ()
    {
        return $this->brand;
    }
    
    public function getColor ()
    {
        return $this->color;
    }
    
    public function getPrice ()
    {
        return (int) $this->engineVolume * 10000 + $this->horsePower * 100;
    }
}

class Television
{
    private $width = 0;
    private $height = 0;
    
    public function setDimensions ($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }
    
    public function getDiagonal ()
    {
        return (int) sqrt($this->width * $this->width + $this->height * $this->height);
    }
}

class Pen
{
    private $color;
    private $length;
    
    public function __construct ($color, $length)
    {
        $this->color = $color;
        $this->length = $length;
    }
    
    public function write ($text)
    {
        echo '<p style="color:', $this->color, ';">', $text, '</p>';        
    }
}

class Duck
{
    public static $kingdom = 'Animalia';
    public static $order = 'Anseriformes';
    public static $family = 'Anseriformes';
    
    private $gender;
    private $weight;
    private $isDomestic = false;
    
    public function __construct ($gender, $weight)
    {
        $this->gender = $gender;
        $this->weight = $weight;
    }
    
    public function domesticate ()
    {
        $isDomestic = true;
    }
}

class Article
{
    private $name;
    private $price;
    private $amount;
    private $discountPercent = 0;
    
    public function __construct ($name, $price, $amount = 0)
    {
        $this->name = $name;
        $this->price = $price;
        $this->amount = $amount;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getAmount()
    {
        return $this->amount;
    }
    
    public function addAmount($amountIncrease)
    {
        $this->amount+= $amountIncrease;
    }
    
    public function descreaseAmount($amountDecrease)
    {
        if ($this->amount > $amountDecrease)
        {
            $this->amount -= $amountDecrease;
        }
        else
        {
            $this->amount = 0;
        }
    }
    
    public function setDiscount ($discountPercent)
    {
        $this->discountPercent = $discountPercent;
    }
    
    public function totalCost()
    {
        $priceWithDiscount = (int) ($this->price * (100.0 - $this->discountPercent) / 100.0);
        return $priceWithDiscount * $this->amount;
    }
}

$car1 = new Car ('volvo', 'black', 2.0, 160.0);
echo 'price for ', $car1->getColor(), ' ', $car1->getBrand(), ' is ', $car1->getPrice(), '<br/>'; 

$car2 = new Car ('hiundai', 'red', 1.6, 120.0);
echo 'price for ', $car2->getColor(), ' ', $car2->getBrand(), ' is ', $car2->getPrice(), '<br/><br/>';

$bigTv = new Television;
$bigTv->setDimensions (40, 30);
echo 'bigTv diagonal is ', $bigTv->getDiagonal(), '<br/>';

$smallTv = new Television;
$smallTv->setDimensions (30, 20);
echo 'smallTv diagonal is ', $smallTv->getDiagonal(), '<br/><br/>';

$redPen = new Pen ('red', 20);
$bluePen = new Pen ('blue', 20);

$redPen->write('Red text');
$bluePen->write('Blue text');

$wildDuck = new Duck('male', 4);
$domesticDuck = new Duck('female', 3);
$domesticDuck->domesticate();

$article1 = new Article('Shoes', 500);
$article1->addAmount(200);
echo 'Total cost for ', $article1->getAmount(), ' ', $article1->getName(), ' is ', $article1->totalCost(), '<br/>';

$article2 = new Article('Trousers', 250, 300);
$article2->setDiscount(5);
echo 'Total cost for ', $article2->getAmount(), ' ', $article2->getName(), ' is ', $article2->totalCost(), '<br/>';

?>