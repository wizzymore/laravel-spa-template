<?php

namespace App\Providers;

use App\Http\Guards\JWTGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\RelatedTo;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\JWT\Validation\Validator;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        app()->singleton('jwt', function () {
            $configuration = Configuration::forSymmetricSigner(new Sha256(), InMemory::base64Encoded(explode(':', config('app.key'))[1]));

            $configuration->setValidationConstraints(
                new SignedWith($configuration->signer(), $configuration->signingKey()),
                new StrictValidAt(SystemClock::fromUTC()),
                new IssuedBy(config('app.url'))
            );

            return $configuration;
        });

        Auth::extend('jwt', function (Application $app, string $name, array $config) {
            return new JWTGuard(Auth::createUserProvider($config['provider']), $app['request']);
        });
    }
}
