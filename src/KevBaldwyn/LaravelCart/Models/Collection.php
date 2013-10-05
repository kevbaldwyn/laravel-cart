<?php namespace KevBaldwyn\LaravelCart\Models;

use App;
use Illuminate\Database\Eloquent\Collection as BaseCollection;

class Collection extends BaseCollection {

	public function __construct($tmpItems = array())
	{
		$items = array();

		// hydrate and create each model
		// allows us to have lots of different types of model in the basket at the same time
		foreach($tmpItems as $item) {
			$obj = App::make($item->model)->find($item->model_id);
			$obj->quantity = $item->quantity;
			$items[] = $obj;
		}

		parent::__construct($items);
	}


	public function quantity() {
		$quantity = array(0);
		foreach ($this->items as $model) {
			$quantity[] = $model->quantity;
		}
		return array_sum($quantity);
	}

}