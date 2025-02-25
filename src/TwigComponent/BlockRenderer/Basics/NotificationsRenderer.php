<?php

/*
 *  Copyright (C) BadPixxel <www.badpixxel.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace BadPixxel\Widgets\TwigComponent\BlockRenderer\Basics;

use BadPixxel\Widgets\Blocks\Basics\NotificationsBlock;
use BadPixxel\Widgets\Blocks\Bootstrap\AlertBlock;
use BadPixxel\Widgets\Interfaces\BlockInterface;
use BadPixxel\Widgets\Models\BlockRenderer\AbstractBlockRenderer;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Webmozart\Assert\Assert;

#[AsTwigComponent(
    name:       "Widgets:Block:Notifications",
    template:   "@BadpixxelWidgets/Components/Blocks/Basics/NotificationsBlock.html.twig",
)]
class NotificationsRenderer extends AbstractBlockRenderer
{
    /**
     * @inheritDoc
     */
    public function handle(BlockInterface $block): bool
    {
        return $block instanceof NotificationsBlock;
    }

    /**
     * Convert Received Alerts to Boostrap Alert Configurations
     *
     * @return array[]
     */
    public function getAlerts(): array
    {
        $alerts = array();
        //==============================================================================
        // Walk on Alert Levels
        foreach (AlertBlock::LEVELS as $level) {
            $levelAlerts = $this->data[$level] ?? array();
            Assert::isArray($levelAlerts);
            //==============================================================================
            // Walk on Configured Alerts
            foreach ($levelAlerts as $levelAlert) {
                //==============================================================================
                // Build Alert Contents
                if ($alert = $this->getAlertContents($level, $levelAlert)) {
                    $alerts[] = $alert;
                }
            }
        }

        return $alerts;
    }

    public function getAlertContents(string $level, mixed $levelAlert): ?array
    {
        //==============================================================================
        // Advanced Alerts
        if (is_array($levelAlert)) {
            return array_filter(array_merge(
                array('level' => $level),
                $levelAlert
            ));
        }
        //==============================================================================
        // Basic Text Alerts
        if (is_string($levelAlert)) {
            return array_filter(array_merge(
                array('level' => $level),
                array('text' => $levelAlert)
            ));
        }

        return null;
    }
}
