<?php
namespace Development;

class FacebookAdapter
{
	const ALL = 1;
	const MEDIUM = 2;
	const DENIED = 3;

	/**
	 * Retorna un array con la información de usuario de Facebook: nombre, email, profile picture y valid_token.
	 * valid_token es un bool que indica si el usuario es válido o no.
	 * @param $email
	 * @return array
	 */
	public function getFacebookData( $email )
	{
		return array();
	}

	/**
	 * Este método nos dice que tipo de privacidad tienen nuestros usuarios en función del email:
	 * - Si es @gmail -> retorna FacebookAdapter::ALL
	 * - Si es @salleurl.edu -> retorna FacebookAdapter::MEDIUM
	 * - Si es @hotmail.com -> retorna FacebookAdapter::DENIED
	 * @param $email
	 * @return int
	 */
	public function getPrivacityLevel( $email )
	{
		return 0;
	}
}