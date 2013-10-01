<?php namespace KevBaldwyn\LaravelCart;

use \Session;
use \Eloquent;

class LaravelCart {

	private $cartModel;

	public function __construct(Eloquent $cartModel) 
	{
		$this->cartModel = $cartModel;
	}


	public function get() 
	{
		return $this->cartModel->where('session_id', Session::getId())->get();
	}


	public function addModel($model) 
	{
		$modelName = get_class($model);
		$modelId   = $model->getKey();

		$this->add($modelName, $modelId);
	}


	public function add($modelName, $modelId, $quantity = 1) 
	{
		$sessionId = Session::getId();
		$exists = $this->cartModel->where('session_id', $sessionId)
								  ->where('model', $modelName)
								  ->where('model_id', $modelId)->first();

		if(is_null($exists)) {
			$data = array('model'    => $modelName, 
						  'model_id' => $modelId,
						  'quantity' => $quantity);

			// save to session
			Session::push('laravel_cart.items', $data);

			// save to db
			$data['session_id'] = $sessionId;
			$this->cartModel->create($data);
		}
	}

}