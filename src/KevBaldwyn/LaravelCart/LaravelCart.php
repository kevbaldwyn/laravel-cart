<?php namespace KevBaldwyn\LaravelCart;

use \Session;
use \Eloquent;

class LaravelCart {

	private $cartModel;

	public function __construct(Eloquent $cartModel) 
	{
		$this->cartModel = $cartModel;
	}


	public function addModel($model) 
	{
		$modelName = get_class($model);
		$modelId   = $model->getKey();

		$this->add($modelName, $modelId);
	}


	public function add($modelName, $modelId, $quantity = 1) 
	{
		$exists = $this->cartModel->where('session_id', Session::getId())
								  ->where('model', $modelName)
								  ->where('model_id', $modelId)->first();

		if(is_null($exists)) {
			$data = array('model'    => $modelName, 
						  'model_id' => $modelId,
						  'quantity' => $quantity);

			// save to session
			Session::push('laravel_cart.items', $data);

			// save to db
			$data['session_id'] = Session::getId();
			$this->cartModel->create($data);
		}
	}

}