<?php
namespace Development;

class User
{
	/**
	 * @var array
	 */
	private $errors = array();

	/**
	 * @var ServiceProvider
	 */
	private $service_provider;

	/**
	 * @param ServiceProvider $service_provider
	 */
	public function __construct( $service_provider )
	{
		$this->service_provider = $service_provider;
	}

	/**
	 * @param $user_data
	 * @throws \InvalidArgumentException
	 */
	public function newUser( $user_data )
	{
		if ( $this->validateData( $user_data ) )
		{
			// Check user origin.
			$this->checkUserOrigin( $user_data );

			// Create activation key.
			$user_data['activation_key'] = md5( uniqid() );

			// Insert user.
			/* @var $user_model UserModel */
			$user_model = $this->service_provider->getService( 'UserModel' );
			$user_model->addNewUser( $user_data );

			// Send email to user.
			/* @var $mail_class Mail */
			$mail_class = $this->service_provider->getService( 'Mail' );
			$mail_class->setSender( $user_data['email'] );
			$mail_class->setContent( 'Congrats! You are the most amazing new user of MPWAR Site!' );
			$mail_class->send();
		}
		else
		{
			throw new \InvalidArgumentException( 'User data is invalid' );
		}
	}

	/**
	 * @param $user_data
	 * @return bool
	 */
	private function validateData( $user_data )
	{
		/* @var $user_model UserModel */
		$user_model = $this->service_provider->getService( 'UserModel' );

		// Check user name.
		if ( empty( $user_data['user_name'] ) )
		{
			$this->errors[] = 'User name is required.';
		}
		elseif ( $user_model->existsUserName( $user_data['user_name'] ) )
		{
			$this->errors[] = 'User name already exists.';
		}

		// Check email.
		if ( empty( $user_data['email'] ) )
		{
			$this->errors[] = 'Email is required.';
		}

		// Check Password.
		if ( empty( $user_data['password'] ) )
		{
			$this->errors[] = 'Password is required.';
		}
		elseif ( $user_data['password'] < 6 && $user_data['password'] > 12 )
		{
			$this->errors[] = 'Password length must be between 6 and 12.';
		}

		return empty( $this->errors );
	}

	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @param array $user_data
	 * @return array
	 * @throws \UnexpectedValueException
	 */
	private function checkUserOrigin( $user_data )
	{
		switch( $user_data['origin'] )
		{
			case 'website':
				break;
			case 'facebook':
				$user_data = $this->facebookUser( $user_data );

				break;
			default:
				$this->errors[] = 'Invalid user origin';
				throw new \UnexpectedValueException( 'Invalid user origin', 20 );
		}

		return $user_data;
	}

	/**
	 * @param array $user_data
	 * @return array
	 */
	private function facebookUser( $user_data )
	{
		/* @var $facebook_adapter FacebookAdapter */
		$facebook_adapter = $this->service_provider->getService( 'FacebookAdapter' );

		$facebook_user_data = $facebook_adapter->getFacebookData( $user_data[ 'email' ] );

		if ( $facebook_user_data[ 'valid_token' ] )
		{
			switch( $facebook_adapter->getPrivacityLevel( $facebook_user_data[ 'email' ] ) )
			{
				case FacebookAdapter::ALL:
					$user_data = array_merge( $user_data, $facebook_user_data );
					break;
				case FacebookAdapter::MEDIUM:
					$user_data['profile_picture'] = $facebook_user_data['profile_picture'];
					break;
				case FacebookAdapter::DENIED:
					break;
			}
		}

		return $user_data;
	}
}