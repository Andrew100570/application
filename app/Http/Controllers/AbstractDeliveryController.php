<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class AbstractDeliveryController extends Controller
{
    /**
     * api_key
     *
     * @var  string  $api_key
     */
     protected $api_key = "c0add372d2cd2654fb2a44879735a06a";

    /**
     * кладр откуда везем.
     *
     * @var  string  $sourceKladr
     */
    public $sourceKladr;  //кладр откуда везем

    /**
     * кладр куда везем.
     *
     * @var  string  $targetKladr
     */
    public $targetKladr;  //кладр куда везем

    /**
     * вес отправления в кг.
     *
     * @var  float $weight
     */
    public $weight; //вес отправления в кг

    /**
     * цена за кг.
     *
     * @var integer $price_kg
     */
    public $price_kg; //вес отправления в кг


    /**
     * Ошибка
     *
     * @var string $error
     */
    public $error = "Ошибок нет";

    /**
     * километры в день
     *
     * @var int $km_day
     */
    public $km_day = 20;

    /**
     * цена кг
     *
     * @var int $kg_price
     */
    public $kg_price = 10;

    public $badResult = [
        'price' => "Цена не найдена",
        'period' => "Кол-во дней неизвестно",
        'error' => "Не удалось получить информацию"
    ];

    abstract public function calculation(Request $request);

}
