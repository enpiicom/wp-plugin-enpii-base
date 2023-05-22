<?php

namespace Enpii_Base\Deps\Illuminate\Mail\Transport;

use Enpii_Base\Deps\Illuminate\Support\Collection;
use Swift_Mime_SimpleMessage;

class ArrayTransport extends Transport
{
    /**
     * The collection of Swift Messages.
     *
     * @var \Enpii_Base\Deps\Illuminate\Support\Collection
     */
    protected $messages;

    /**
     * Create a new array transport instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->messages = new Collection;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $this->messages[] = $message;

        return $this->numberOfRecipients($message);
    }

    /**
     * Retrieve the collection of messages.
     *
     * @return \Enpii_Base\Deps\Illuminate\Support\Collection
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * Clear all of the messages from the local collection.
     *
     * @return \Enpii_Base\Deps\Illuminate\Support\Collection
     */
    public function flush()
    {
        return $this->messages = new Collection;
    }
}
