<?php

namespace Enpii\WP_Plugin\Enpii_Base\Dependencies\Illuminate\Mail\Transport;

use Enpii\WP_Plugin\Enpii_Base\Dependencies\Psr\Log\LoggerInterface;
use Swift_Mime_SimpleMessage;
use Swift_Mime_SimpleMimeEntity;

class LogTransport extends Transport
{
    /**
     * The Logger instance.
     *
     * @var \Enpii\WP_Plugin\Enpii_Base\Dependencies\Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Create a new log transport instance.
     *
     * @param  \Enpii\WP_Plugin\Enpii_Base\Dependencies\Psr\Log\LoggerInterface  $logger
     * @return void
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $this->logger->debug($this->getMimeEntityString($message));

        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }

    /**
     * Get a loggable string out of a Swiftmailer entity.
     *
     * @param  \Swift_Mime_SimpleMimeEntity  $entity
     * @return string
     */
    protected function getMimeEntityString(Swift_Mime_SimpleMimeEntity $entity)
    {
        $string = (string) $entity->getHeaders().PHP_EOL.$entity->getBody();

        foreach ($entity->getChildren() as $children) {
            $string .= PHP_EOL.PHP_EOL.$this->getMimeEntityString($children);
        }

        return $string;
    }

    /**
     * Get the logger for the LogTransport instance.
     *
     * @return \Enpii\WP_Plugin\Enpii_Base\Dependencies\Psr\Log\LoggerInterface
     */
    public function logger()
    {
        return $this->logger;
    }
}
