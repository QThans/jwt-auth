<?php

namespace thans\jwt;

use thans\jwt\exception\TokenBlacklistException;
use thans\jwt\provider\JWT\Provider;

class Manager
{
    protected $blacklist;

    protected $payload;

    protected $refresh;

    public function __construct(
        Blacklist $blacklist,
        Payload $payload,
        Provider $provider
    ) {
        $this->blacklist = $blacklist;
        $this->payload   = $payload;
        $this->provider  = $provider;
    }

    /**
     * Token编码
     *
     * @param $customerClaim
     *
     * @return Token
     */
    public function encode($customerClaim = [])
    {
        $payload = $this->payload->customer($customerClaim);
        $token   = $this->provider->encode($payload->get());

        return new Token($token);
    }

    /**
     * 解析Token
     *
     * @param  Token  $token
     *
     * @return mixed
     * @throws TokenBlacklistException
     */
    public function decode(Token $token)
    {
        $payload = $this->provider->decode($token->get());

        //blacklist verify
        if ($this->validate($payload)) {
            throw new TokenBlacklistException('The token is in blacklist.');
        }
        $this->payload->customer($payload)->check($this->refresh);

        return $payload;
    }

    /**
     * 刷新Token
     *
     * @param  Token  $token
     *
     * @return Token
     * @throws TokenBlacklistException
     */
    public function refresh(Token $token)
    {
        $this->setRefresh();
        $payload = $this->decode($token);

        $this->invalidate($token);

        $this->payload->customer($payload)
            ->check(true);

        return $this->encode($payload);
    }

    /**
     * 注销Token，使之无效
     *
     * @param  Token  $token
     *
     * @return Blacklist
     * @throws TokenBlacklistException
     */
    public function invalidate(Token $token)
    {
        return $this->blacklist->add($this->provider->decode($token->get()));
    }

    /**
     * 验证是否在黑名单
     *
     * @param $payload
     *
     * @return bool
     */
    public function validate($payload)
    {
        return $this->blacklist->has($payload);
    }

    public function setRefresh($refresh = true)
    {
        $this->refresh = true;

        return $this;
    }
}
