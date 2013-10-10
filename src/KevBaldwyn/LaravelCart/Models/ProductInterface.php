<?php namespace KevBaldwyn\LaravelCart\Models;

interface ProductInterface {
	
	public function getLinePrice();

	public function getShippingCost();

}