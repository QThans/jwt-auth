<?php


namespace thans\jwt\provider\JWT;

use Exception;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Ecdsa;
use Lcobucci\JWT\Signer\Ecdsa\Sha256 as ES256;
use Lcobucci\JWT\Signer\Ecdsa\Sha384 as ES384;
use Lcobucci\JWT\Signer\Ecdsa\Sha512 as ES512;
use Lcobucci\JWT\Signer\Hmac\Sha256 as HS256;
use Lcobucci\JWT\Signer\Hmac\Sha384 as HS384;
use Lcobucci\JWT\Signer\Hmac\Sha512 as HS512;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Signer\Rsa\Sha256 as RS256;
use Lcobucci\JWT\Signer\Rsa\Sha384 as RS384;
use Lcobucci\JWT\Signer\Rsa\Sha512 as RS512;
use ReflectionClass;
use thans\jwt\exception\JWTException;
use thans\jwt\exception\TokenInvalidException;

class Lcobucci extends Provider
{
    protected $signer;

    protected $signers
        = [
            'HS256' => HS256::class,
            'HS384' => HS384::class,
            'HS512' => HS512::class,
            'RS256' => RS256::class,
            'RS384' => RS384::class,
            'RS512' => RS512::class,
            'ES256' => ES256::class,
            'ES384' => ES384::class,
            'ES512' => ES512::class,
        ];
    protected $builder;
    protected $parser;

    public function __construct(Builder $builder, Parser $parser, $algo, $keys)
    {
        $this->builder = $builder;
        $this->parser  = $parser;
        $this->algo    = $algo;
        $this->keys    = $keys;
        $this->signer  = $this->getSign();
    }


    public function encode(array $payload)
    {
        $this->builder->unsign();
        try {
            foreach ($payload as $key => $val) {
                $this->builder->set($key, $val->getValue());
            }
            $this->builder->sign($this->signer, $this->getSigningKey());
        } catch (Exception $e) {
            throw new JWTException(
                'Could not create token :'.$e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        return (string)$this->builder->getToken();
    }

    public function decode($token)
    {
        try {
            $jwt = $this->parser->parse($token);
        } catch (Exception $e) {
            throw new TokenInvalidException('Could not decode token: '
                .$e->getMessage(), $e->getCode(), $e);
        }

        if (! $jwt->verify($this->signer, $this->getVerificationKey())) {
            throw new TokenInvalidException('Token Signature could not be verified.');
        }
        return $jwt->getClaims();
    }


    protected function isAsymmetric()
    {
        $reflect = new ReflectionClass($this->signer);

        return $reflect->isSubclassOf(Rsa::class)
            || $reflect->isSubclassOf(Ecdsa::class);
    }

    protected function getSigningKey()
    {
        return $this->isAsymmetric()
            ?
            (new Keychain())->getPrivateKey(
                $this->getPrivateKey(),
                $this->getPassword()
            )
            :
            $this->getSecret();
    }

    protected function getVerificationKey()
    {
        return $this->isAsymmetric()
            ?
            (new Keychain())->getPublicKey($this->getPublicKey())
            :
            $this->getSecret();
    }


    protected function getSign()
    {
        if (! isset($this->signers[$this->algo])) {
            throw new JWTException('Cloud not find '.$this->algo.' algo');
        }

        return new $this->signers[$this->algo];
    }
}
