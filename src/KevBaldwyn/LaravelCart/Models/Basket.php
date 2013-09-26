<?php namespace KevBaldwyn\LaravelCart\Models;

use \Eloquent;

class Basket extends Eloquent {

	public $table = 'laravel_cart';

	protected $guarded = array('id');

}