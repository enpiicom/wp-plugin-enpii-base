<?php

namespace Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Foundation\Http\Events;

class RequestHandled
{
    /**
     * The request instance.
     *
     * @var \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Http\Request
     */
    public $request;

    /**
     * The response instance.
     *
     * @var \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Http\Response
     */
    public $response;

    /**
     * Create a new event instance.
     *
     * @param  \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Http\Request  $request
     * @param  \Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Http\Response  $response
     * @return void
     */
    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
