<?php

namespace Enqueue\Bundle\Consumption\Extension;

use Enqueue\Consumption\Context\PostMessageReceived;
use Enqueue\Consumption\PostMessageReceivedExtensionInterface;
use Symfony\Contracts\Service\ResetInterface;

class ResetServicesExtension implements PostMessageReceivedExtensionInterface
{
    public function __construct(private ResetInterface $resetter)
    {
    }

    public function onPostMessageReceived(PostMessageReceived $context): void
    {
        $context->getLogger()->debug('[ResetServicesExtension] Resetting services.');

        $this->resetter->reset();
    }
}
