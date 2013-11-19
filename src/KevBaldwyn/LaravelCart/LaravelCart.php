<?php namespace KevBaldwyn\LaravelCart;

use \Session;
use \Eloquent;

class LaravelCart {

	private $sessionName = 'laravel_cart';

	private $cartModel;
	private $items;

	public function __construct(Eloquent $cartModel) 
	{
		$this->cartModel = $cartModel;
	}


	public function getItems() 
	{
		if(is_null($this->items)) {
			$this->items = new Models\Collection( $this->cartModel->where('session_id', Session::getId())->get() );
		}
		return $this->items;
	}


	public function getTotalQuantity() 
	{
		return $this->getItems()->quantity();
	}


	public function getTotalShipping()
	{
		$shipping = 0;
		foreach($this->getItems() as $item) {
			$shipping += $item->getShippingCost();
		}
		return $shipping;
	}


	public function getTotalPrice() 
	{
		$value = 0;
		foreach($this->getItems() as $item) {
			$value += $item->getLinePrice() * $item->quantity;
		}
		return $value;
	}


	public function setDiscount($amount, $key = 'default')
	{
		Session::put($this->sessionName . '.discount.' . $key, $amount);
	}


	public function getTotalDiscounts($key = null) 
	{
		if(!is_null($key)) {
			return Session::get($this->sessionName . '.discount.' . $key, 0.00);
		}
		if(!is_null(Session::get($this->sessionName . '.discount'))) {
			return array_sum(Session::get($this->sessionName . '.discount'));
		}
		return 0.00;
	}


	public function getFinalPrice() 
	{
		return $this->getTotalPrice() + $this->getTotalShipping() - $this->getTotalDiscounts();
	}


	public function addModel($model) 
	{
		$modelName = get_class($model);
		$modelId   = $model->getKey();

		$this->add($modelName, $modelId);
	}


	public function add($modelName, $modelId, $quantity = 1) 
	{
		$exists = $this->getItem($modelName, $modelId);

		if(is_null($exists)) {
			$data = array('model'    => $modelName, 
						  'model_id' => $modelId,
						  'quantity' => $quantity);

			// save to db
			$data['session_id'] = Session::getId();
			$this->cartModel->create($data);
		}
	}


	public function update($modelName, $modelId, $quantity = 1) 
	{
		$item = $this->getItem($modelName, $modelId);

		if($quantity > 0) {
			$item->quantity = $quantity;
			$item->save();
		}else{
			$item->delete();
		}
	}


	public function hasItem($modelName, $modelId) 
	{
		$item = $this->getItem($modelName, $modelId);
		if(!is_null($item)) {
			return true;
		}else{
			return false;
		}
	}


	public function emptyCart()
	{
		$items = $this->cartModel->where('session_id', Session::getId())->get();
		if(count($items) > 0) {
			foreach($items as $item) {
				$item->delete();
			}
		}
		Session::put($this->sessionName, array());
	}


	private function getItem($modelName, $modelId) {
		return $this->cartModel->where('session_id', Session::getId())
								  ->where('model', $modelName)
								  ->where('model_id', $modelId)->first();
	}

}