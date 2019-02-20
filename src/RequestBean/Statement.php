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

use EasySwoole\Spl\SplBean;
class Statement extends SplBean
{
	/**
	 * @var string
	 */
	protected $Effect;
	/**
	 * @var array
	 */
	protected $Action;

	/**
	 * @return string
	 */
	public function getEffect() : string
	{
		return $this->Effect;
	}

	/**
	 * @param string $Effect
	 */
	public function setEffect( string $Effect ) : void
	{
		$this->Effect = $Effect;
	}

	/**
	 * @return array
	 */
	public function getAction() : array
	{
		return $this->Action;
	}

	/**
	 * @param array $Action
	 */
	public function setAction( array $Action ) : void
	{
		$this->Action = $Action;
	}


}