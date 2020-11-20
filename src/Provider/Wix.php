<?php

namespace Dafyd\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;

class Wix extends AbstractProvider
{
	use BearerAuthorizationTrait;

	/**
	 * Get authorization url to begin OAuth flow
	 *
	 * @return string
	 */
	public function getBaseAuthorizationUrl()
	{
		return 'https://www.wix.com/installer/install';
	}

	/**
	 * Get access token url to retrieve token
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public function getBaseAccessTokenUrl(array $params)
	{
		return 'https://www.wix.com/oauth/access';
	}

	/**
	 * Get provider url to fetch user details
	 *
	 * @param AccessToken $token
	 *
	 * @return string
	 */
	public function getResourceOwnerDetailsUrl(AccessToken $token)
	{
		return 'https://www.wixapis.com/apps/v1/instance';
	}

	/**
	 * Get the default scopes used by this provider.
	 *
	 * @return array
	 */
	protected function getDefaultScopes()
	{
		return [];
	}

	/**
	 * Returns the string that should be used to separate scopes when building
	 * the URL for requesting an access token.
	 *
	 * @return string Scope separator, defaults to ','
	 */
	protected function getScopeSeparator()
	{
		return ' ';
	}

	/**
	 * Check a provider response for errors.
	 *
	 * @param  ResponseInterface $response
	 * @param  array|string $data
	 *
	 * @throws IdentityProviderException
	 */
	protected function checkResponse(ResponseInterface $response, $data)
	{
		return ($response->getStatusCode() == 200);
	}

	/**
	 * Generate a user object from a successful user details request.
	 *
	 * @param array $response
	 * @param AccessToken $token
	 *
	 * @return League\OAuth2\Client\Provider\ResourceOwnerInterface
	 */
	protected function createResourceOwner(array $response, AccessToken $token)
	{
		return new WixResourceOwner($response);
	}

	/**
	 * Returns a prepared request for requesting an access token.
	 *
	 * @param array $params
	 *
	 * @return Psr\Http\Message\RequestInterface
	 */
	protected function getAccessTokenRequest(array $params)
	{
		$request = parent::getAccessTokenRequest($params);
		$uri = $request->getUri()
			->withUserInfo($this->clientId, $this->clientSecret);
		return $request->withUri($uri);
	}
}