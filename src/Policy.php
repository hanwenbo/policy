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
	 * @var string
	 */
	protected $errMsg;

	/**
	 * 只支持2级
	 * @param $actionName
	 */
	public function verify( string $actionName ) : bool
	{
		$this->preParse();

		$arr = explode( '/', $actionName );
		array_pop( $arr );
		$controller = implode( '/', $arr );

		$allow = in_array( $actionName, $this->allowActions ) || in_array( "{$controller}/*", $this->allowActions ) || in_array( '*', $this->allowActions );

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
		$this->policyList[] = $policy;
	}

	/**
	 * 预解析
	 */
	protected function preParse() : void
	{
		foreach( $this->policyList as $policy ){
			if( $policy instanceof PolicyRequest ){
				$statement_list = $policy->getStatement();
				foreach( $statement_list as $statement_item ){
					$statement = new Statement( $statement_item );
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

	/**
	 * TODO 需要重写
	 * 验证数据结构
	 * 用于添加或者修改的时候判断是否符合本库数据结构的要求
	 * @param array $structure
	 * @return bool
	 */
	public function verifyStructure( array $structure ) : bool
	{
		if( isset( $structure['Statement'] ) && is_array( $structure['Statement'] ) ){
			$this->setErrMsg( 'Statement must be set or is array' );
			foreach( $structure['Statement'] as $statement ){
				if( !isset( $statement['Effect'] ) || !in_array( $statement['Effect'], ['Allow', 'Deny'] ) ){
					$this->setErrMsg( 'Effect must be in Allow|Deny' );
					return false;
				}
				if( !isset( $statement['Action'] ) || !is_array( $statement['Action'] ) || empty( $statement['Action'] ) ){
					$this->setErrMsg( 'Action must be set or array`s length > 0' );
					return false;
				}

				// 不允许重复
				if( count( $statement['Action'] ) != count( array_unique( $statement['Action'] ) ) ){
					$this->setErrMsg( 'Action must be unique' );
					return false;
				}
				// 语法要符合 * 或者 是 xx/xx
				foreach( $statement['Action'] as $action ){
					if( $action !== '*' && count( explode( '/', $action ) ) < 2 ){
						$this->setErrMsg( 'Action must be `*` or `controller/action` or `controller/*`' );
						return false;
					}
				}
			}
			return true;
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function getErrMsg() : string
	{
		return $this->errMsg;
	}

	/**
	 * @param string $errMsg
	 */
	public function setErrMsg( string $errMsg ) : void
	{
		$this->errMsg = $errMsg;
	}

}

