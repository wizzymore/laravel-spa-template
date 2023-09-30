<?php

namespace App\Http\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Lcobucci\JWT\UnencryptedToken;

class JWTGuard implements Guard
{
    use GuardHelpers;

    public function __construct(UserProvider $provider, protected Request $request)
    {
        $this->provider = $provider;
    }

    public function user()
    {
        $token = $this->request->bearerToken();
        if (!$token) {
            return null;
        }

        if (!jwt_valid($token)) {
            return null;
        }

        $token = jwtGetToken($token);
        assert($token instanceof UnencryptedToken);

        $id = $token->claims()->get('sub');
        if (!$id) {
            return null;
        }

        return $this->provider->retrieveById($id);
    }

    protected function createToken(Authenticatable $user)
    {
        return jwt_builder()
            ->relatedTo($user->getAuthIdentifier())
            ->getToken(jwt_configuration()->signer(), jwt_configuration()->signingKey());
    }

    public function attempt($credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user !== null && $this->provider->validateCredentials($user, $credentials)) {
            return $this->login($user);
        }

        return false;
    }

    public function login(Authenticatable $user)
    {
        return $this->createToken($user);
    }

    public function validate(array $credentials = [])
    {
        dd(2);
    }
}
