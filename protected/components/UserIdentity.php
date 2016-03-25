<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$res = TheMovieDB::authentication($this->password);
		if(isset($res['success'])){
			$this->errorCode=self::ERROR_NONE;
			$this->setState('guest_session_id', $res['guest_session_id']);
			$this->setState('expires_at', $res['expires_at']);
			$this->setState('api_key', $this->password);
		}
		else{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}

		return !$this->errorCode;
	}
}