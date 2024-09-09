<?php

namespace Conquest\Table\Concerns;

trait CanChangeView
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