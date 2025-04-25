<?php

declare(strict_types=1);

namespace App\Entity\Subscription;

use App\Entity\Subscription;
use App\Entity\TariffInvoice;
use DateTimeImmutable;
use Spiral\Prototype\Annotation\Prototyped;
use Spiral\Prototype\Traits\PrototypeTrait;
use Throwable;

#[Prototyped('subscriptionService')]
class SubscriptionService
{
    use PrototypeTrait;

    public function findByMotherInvoice(TariffInvoice $invoice): ?Subscription
    {
        return $this->subscriptionRepository->findBy(['motherInvoice_id' => $invoice->getId()]);
    }

    public function activateSubscription(Subscription $subscription): void
    {
        $subscription->subscribedAt = new DateTimeImmutable();
        $subscription->isActive     = true;

        $this->subscriptionRepository->save($subscription);
    }

    public function deactivateSubscription(Subscription $subscription): void
    {
        $subscription->endedAt  = new DateTimeImmutable();
        $subscription->isActive = false;

        $this->subscriptionRepository->save($subscription);
    }

    public function addPayedInvoice(TariffInvoice $invoice): void
    {
        assert($invoice->subscription !== null, 'Tariff invoice must have a subscription');

        $subscription = $invoice->subscription;

        $workflow = $this->subscriptionWorkflowHandler->getRunningWorkflow($subscription);

        try {
            $workflow->addPayedInvoice($invoice->getId());

            $this->logger->info(sprintf(
                'Added payed invoice %s to subscription workflow for subscription %s',
                $invoice->getId(),
                $subscription->getId()
            ));
        } catch (Throwable $e) {
            $this->logger->error(sprintf(
                'Failed to add payed invoice %s to subscription workflow for subscription %s: %s',
                $invoice->getId(),
                $subscription->getId(),
                $e->getMessage()
            ));

            throw $e;
        }
    }

    public function updateEndDate(Subscription $subscription, DateTimeImmutable $endDate): void
    {
        $workflow = $this->subscriptionWorkflowHandler->getRunningWorkflow($subscription);

        try {
            $workflow->setEndDate($endDate->format('Y-m-d'));

            $this->logger->info("Updated subscription workflow for subscription {$subscription->getId()}");
        } catch (Throwable $e) {
            $this->logger->error(sprintf(
                'Failed to update subscription workflow for subscription %s: %s',
                $subscription->getId(),
                $e->getMessage()
            ));

            throw $e;
        }
    }
}
