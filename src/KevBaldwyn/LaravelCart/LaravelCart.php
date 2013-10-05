<?php namespace KevBaldwyn\LaravelCart;

use \Session;
use \Eloquent;

class LaravelCart {

	private $cartModel;

	public function __construct(Eloquent $cartModel) 
	{
		$this->cartModel = $cartModel;
	}


	public function getItems() 
	{
		return new Models\Collection( $this->cartModel->where('session_id', Session::getId())->get() );
	}


	public function getTotalQuantity() 
	{
		return $this->getItems()->quantity();
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


	private function getItem($modelName, $modelId) {
		return $this->cartModel->where('session_id', Session::getId())
								  ->where('model', $modelName)
								  ->where('model_id', $modelId)->first();
	}

}