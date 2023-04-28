<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SlowDeliveryController extends AbstractDeliveryController
{
    /**
     * начальная цена
     *
     * @var int $base_price
     */
    public $base_price = 150;

    /**
     * начальная цена
     *
     * @var int $base_price
     */
    public $coefficient = 0.2;


    public function calculation(Request $request) {

        $this->sourceKladr = $request->get('sourceKladr');
        $this->targetKladr = $request->get('targetKladr');
        $this->weight = $request->get('weight');


        return response()->json(
            [
                "price"  => $this->priceAndDays($this->sourceKladr, $this->targetKladr,$this->weight)['price'],
                "period" => $this->priceAndDays($this->sourceKladr, $this->targetKladr,$this->weight)['period'],
                "error" => $this->priceAndDays($this->sourceKladr, $this->targetKladr, $this->weight)['error']
            ],
            200,
            ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function priceAndDays() {

        //https://htmlweb.ru/geo/api.php - нашел ресурс для рассчета расстояния между городами
        //В этом ресурсе используется название города по id.Явно не вижу,какой из api_url переводит название города в id,поэтому
        //пример с 30 городами России


        $IdTown = [];
        $idTowns = Http::get('https://htmlweb.ru/json/geo/city_list?country=Россия&api_key=' . $this->api_key);

        if ($idTowns->status() === 200) {
            foreach (json_decode($idTowns->body())->items as $id) {
                if ($id->name === $this->sourceKladr or $id->name === $this->targetKladr) {
                    $IdTown[] = $id->id;
                }
            }
            $distance = Http::get('https://htmlweb.ru/geo/api.php?city1=' . $IdTown[0] . '&city2=' . $IdTown[1] . '&json&api_key=' . $this->api_key);
            if ($distance->status() === 200) {

                $allDistance = json_decode($distance->body())->distance; //получение расстояния

                //Для примера рассчета стоимости буду считать,что путь будет 20км в день
                $amountOfDays = $allDistance / $this->km_day;

                //пример рассчета (учтем вес посылки)
                $all_price = $this->base_price * $this->coefficient + $this->kg_price * $this->weight;
            } else{
                return response()->json($this->badResult);
            }


        } else {
            return response()->json($this->badResult);
        }
        return [
            'price'  => $all_price,
            'period' => $amountOfDays,
            'error'  => $this->error
        ];

    }
}
