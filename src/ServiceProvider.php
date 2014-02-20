<?php
namespace Development;

class ServiceProvider
{
	/**
	 * @var ServiceProvider
	 */
	private static $instance = null;

	/**
	 * @var array
	 */
	private $services = array();

	/**
	 * @return ServiceProvider
	 */
	public static function getInstance()
	{
		if ( self::$instance == null )
		{
			self::$instance = new ServiceProvider();
		}

		return self::$instance;
	}

	/**
	 * @param $object_name
	 */
	public function setService( $object_name )
	{
		$this->services[ $object_name ] = new $object_name;
	}

	/**
	 * @param $object_name
	 * @return mixed
	 * @throws \RuntimeException
	 */
	public function getService( $object_name )
	{
		if ( !empty( $this->services[ $object_name ] ) )
		{
			return $this->services[ $object_name ];
		}
		throw new \RuntimeException( 'Invalid Service Request', 10 );
	}
}