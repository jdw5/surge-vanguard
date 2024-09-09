<?php

namespace Conquest\Table\Concerns;

trait CanToggle
{
    /**
     * @var string
     */
    protected $cookie;

    /**
     * @var string
     */
    protected $cookieName;

    /**
     * @var int
     */
    protected $cookieExpiry;

    /**
     * @var string
     */
    protected $viewQueryParam;

    /**
     * @var bool
     */
    protected $view;

    public function toggle(): void
    {
        //
    }
}
