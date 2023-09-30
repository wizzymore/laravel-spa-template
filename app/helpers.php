<?php

use Lcobucci\JWT\Token;

function jwt_builder()
{
    return jwt_configuration()
        ->builder()
        ->issuedAt(now()->toDateTimeImmutable())
        ->expiresAt(now()->addDay()->toDateTimeImmutable())
        ->issuedBy(config('app.url'));
}

function jwt_valid(string|Token $token)
{
    if (is_string($token)) {
        $token = jwt_configuration()->parser()->parse($token);
    }

    return jwt_configuration()
        ->validator()
        ->validate($token);
}

function jwtGetToken(string $token)
{
    return jwt_configuration()->parser()->parse($token);
}

function jwt_configuration()
{
    /** @var \Lcobucci\JWT\Configuration */
    $configuration = app('jwt');

    return $configuration;
}
