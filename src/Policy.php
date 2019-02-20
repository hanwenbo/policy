<?php
/**
 *
 * Copyright  FaShop
 * License    http://www.fashop.cn
 * link       http://www.fashop.cn
 * Created by FaShop.
 * User: hanwenbo
 * Date: 2019-02-20
 * Time: 11:31
 *
 */

namespace hanwenbo\policy;

use hanwenbo\policy\RequestBean\Statement;
use hanwenbo\policy\RequestBean\Policy as PolicyRequest;

class Policy
{
	/**
	 * @var array
	 */
	protected $policyList = [];
	/**
	 * @var array
	 */
	protected $allowActions = [];
	/**
	 * @var array
	 */
	protected $denyActions = [];

	/**
	 * 只支持2级
	 * @param $actionName
	 */
	public function verify( string $actionName ) : bool
	{
		$this->preParse();

		list( $controller ) = explode( '/', $actionName );
		$allow = in_array( $actionName, $this->allowActions ) || in_array( "{$controller}/*", $this->allowActions ) || in_array( '*', $this->denyActions );

		$deny = in_array( $actionName, $this->denyActions ) || in_array( "{$controller}/*", $this->denyActions ) || in_array( '*', $this->denyActions );

		return $allow && !$deny;
	}

	/**
	 * @param array | string $actions
	 */
	public function setAllowActions( array $actions ) : void
	{
		$this->allowActions = $actions;
	}

	/**
	 * @param array $actions
	 */
	public function setDenyActions( array $actions ) : void
	{
		$this->denyActions = $actions;
	}

	public function addPolicy( PolicyRequest $policy )
	{
		array_push( $this->policyList, $policy );
	}

	/**
	 * 预解析
	 */
	protected function preParse() : void
	{
		foreach( $this->policyList as $policy ){
			if( $policy instanceof PolicyRequest ){
				$statement_list = $policy->getStatement();
				foreach($statement_list as $statement_item){
					$statement       = new Statement( $statement_item );
					if( $statement->getEffect() === 'Allow' ){
						$this->allowActions = array_unique( array_merge( $this->allowActions, $statement->getAction() ) );
					} else if( $statement->getEffect() === 'Deny' ){
						$this->denyActions = array_unique( array_merge( $this->denyActions, $statement->getAction() ) );
					}
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function getPolicyList() : array
	{
		return $this->policyList;
	}

	/**
	 * @param array $policyList
	 */
	public function setPolicyList( array $policyList ) : void
	{
		$this->policyList = $policyList;
	}

}

