<?php namespace KevBaldwyn\LaravelCart\Models;

use \Eloquent;

class Basket extends Eloquent {

	public $table = 'laravel_cart';

	protected $guarded = array('id');

	public function newCollection(array $models = array()) {
		// attention local namespace
        return new Collection($models);
    }
}