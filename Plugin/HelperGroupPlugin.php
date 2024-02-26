<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types = 1);

namespace MageWorx\OrderEditorCustomerGroupPrice\Plugin;

use Magento\Framework\Registry;
use MageWorx\CustomerGroupPrices\Helper\Group as Subject;

class HelperGroupPlugin
{
    protected Registry $registry;

    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    /**
     * Retrieves the current customer group ID from the currently edited order if it's not set in the session.
     *
     * @param Subject $subject The subject instance.
     * @param callable $proceed The original method.
     * @return int|string|null The current customer group ID.
     */
    public function aroundGetCurrentCustomerGroupId(
        Subject  $subject,
        callable $proceed
    ) {
        $customerGroupId = $proceed();
        if ($customerGroupId === null) {
            /** @var \MageWorx\OrderEditor\Model\Order $currentlyEditedOrder */
            $currentlyEditedOrder = $this->registry->registry('ordereditor_order');
            if ($currentlyEditedOrder instanceof \Magento\Sales\Model\Order) {
                $customerGroupId = $currentlyEditedOrder->getCustomerGroupId();
            }
        }

        return $customerGroupId;
    }
}
