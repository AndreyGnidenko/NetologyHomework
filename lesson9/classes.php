<?php

interface IArticle
{
    public function getPrice();
    public function getAmount();
    
    public function setDiscount($discountPercent);
}

function computeTotalCost(IArticle $article)
{
    return $article->getPrice() * $article->getAmount();
}

interface IBrand
{
    public function getBrandName();
    public function getProducerCountry();
}

interface IRectangle
{
    public function setDimensions($width, $height);
    public function getDiagonal();
}

interface IGraphicObject 
{
    public function getColor ();
    public function write ($text);
}

abstract class Animal
{
    protected $gender;
    
    public function __construct($gender)
    {
        $this->gender = $gender;
    }
    
    public function getGender()
    {
        return $gender;
    }
    
    public static $kingdom = 'Animalia';
    
    public static abstract function getOrder();
    
    public static abstract function getFamily();
    
    public abstract function domesticate();
}
    
class Article implements IArticle
{
    private $name;
    private $price;
    private $amount;
    private $discountPercent = 0;
    
    public function __construct ($name, $price = null, $amount = 0)
    {
        $this->name = $name;
        $this->price = $price;
        $this->amount = $amount;
    }
    
    public function getPrice()
    {
        return $this->price * (100.0 - $this->discountPercent) / 100.0;
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
        $this->amount += $amountIncrease;
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
        if ($discountPercent >= 0)
            $this->discountPercent = $discountPercent;
        else
        {
            throw New Exception('Invalid discount specified');
        }
    }
    
    public function totalCost()
    {
        return computeTotalCost($this);
    }
}


class Car extends Article implements IBrand
{
    private $brand;
    private $color;
    private $engineVolume;
    private $horsePower;
    private $producerCountry;
    
    public function __construct ($brand, $color, $engineVolume, $horsePower, $producerCountry)
    {
        parent::__construct('Car');
        
        $this->brand = $brand;
        $this->color = $color;
        $this->engineVolume = $engineVolume;
        $this->horsePower = $horsePower;
        $this->producerCountry = $producerCountry;
    }
    
    public function getProducerCountry ()
    {
        return $this->producerCountry;
    }
    
    public function getBrandName ()
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

class Television extends Article implements IRectangle, IBrand
{
    private $width = 0;
    private $height = 0;
    private $producerCountry;
    private $brandName;     
    
    public function __construct ($price, $brandName, $producerCountry)
    {
        parent::__construct('Television', $price);
        $this->brandName = $brandName;
        $this->producerCountry = $producerCountry;
    }
        
    public function setDimensions ($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }
    
    public function getDiagonal ()
    {
        return (int) sqrt($this->width * $this->width + $this->height * $this->height);
    }
    
    public function getBrandName()
    {
        return $this->brandName;
    }
    
    public function getProducerCountry()
    {
        return $this->producerCountry;
    }
}

class Pen extends Article implements IGraphicObject
{
    private $color;
    private $length;
    
    public function __construct ($color, $length)
    {
        $this->color = $color;
        $this->length = $length;
    }
    
    public function getColor ()
    {
        return $this->color;
    }
    
    public function write ($text)
    {
        echo '<p style="color:', $this->color, ';">', $text, '</p>';        
    }
}

class Duck extends Animal
{
    public static function getOrder()
    {
        return 'Anseriformes';
    }
    
    public static function getFamily()
    {
        return 'Anseriformes';
    }
    
    private $weight;
    private $isDomestic = false;
    
    public function __construct ($gender, $weight)
    {
        parent::__construct($gender);
        $this->weight = $weight;
    }
    
    public function domesticate ()
    {
        $isDomestic = true;
    }
}

$car1 = new Car ('volvo', 'black', 2.0, 160.0, 'Sweden');
echo 'price for ', $car1->getColor(), ' ', $car1->getBrandName(), ' from ', $car1->getProducerCountry(), ' is ', $car1->getPrice(), '<br/>'; 

$car2 = new Car ('hiundai', 'red', 1.6, 120.0, 'South Korea');
echo 'price for ', $car2->getColor(), ' ', $car2->getBrandName(), ' from ', $car2->getProducerCountry(), ' is ', $car2->getPrice(), '<br/><br/>';

$bigTv = new Television(40000, 'Toshiba', 'Japan');
$bigTv->setDimensions (40, 30);
echo $bigTv->getBrandName().' TV  costs ', $bigTv->getPrice(), ' diagonal is ', $bigTv->getDiagonal(), '<br/>';

$smallTv = new Television(25000, 'Samsung', 'South Korea');
$smallTv->setDimensions (30, 20);
echo $smallTv->getBrandName().' TV costs ', $smallTv->getPrice(), ' diagonal is ', $smallTv->getDiagonal(), '<br/><br/>';

$redPen = new Pen ('red', 20);
$bluePen = new Pen ('blue', 20);

$redPen->write('Red text');
$bluePen->write('Blue text');

$wildDuck = new Duck('male', 4);
$domesticDuck = new Duck('female', 3);
echo 'Duck order is '.Duck::getOrder().'<br/>';
$domesticDuck->domesticate();

$article1 = new Article('Shoes', 500);
$article1->addAmount(200);
echo 'Total cost for ', $article1->getAmount(), ' ', $article1->getName(), ' is ', $article1->totalCost(), '<br/>';

$article2 = new Article('Trousers', 250, 300);
$article2->setDiscount(5);
echo 'Total cost for ', $article2->getAmount(), ' ', $article2->getName(), ' is ', $article2->totalCost(), '<br/>';

?>