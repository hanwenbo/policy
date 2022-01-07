<?php
/**
 *
 * Copyright  FaShop
 * License    http://www.fashop.cn
 * link       http://www.fashop.cn
 * Created by FaShop.
 * User: hanwenbo
 * Date: 2019-02-20
 * Time: 13:11
 *
 */
namespace hanwenbo\policy\RequestBean;

class Statement
{
	private $Statement ;
	public function __construct($Statement)
	{
		$this->Statement = $Statement;
	}

	/**
	 * @return string
	 */
	public function getEffect() : string
	{
		return $this->Statement->Effect;
	}


	/**
	 * @return array
	 */
	public function getAction() : array
	{
		return $this->Statement->Action;
	}

}