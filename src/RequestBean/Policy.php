<?php
/**
 *
 * Copyright  FaShop
 * License    http://www.fashop.cn
 * link       http://www.fashop.cn
 * Created by FaShop.
 * User: hanwenbo
 * Date: 2019-02-20
 * Time: 13:25
 *
 */

namespace hanwenbo\policy\RequestBean;


class Policy
{
	public function __construct($Statement)
	{
		$this->Statement = $Statement;
	}

	/**
	 * @var array
	 */
	protected $Statement;

	/**
	 * @return array
	 */
	public function getStatement() : array
	{
		return $this->Statement;
	}

	/**
	 * @param array $Statement
	 */
	public function setStatement( array $Statement ) : void
	{
		$this->Statement = $Statement;
	}

}