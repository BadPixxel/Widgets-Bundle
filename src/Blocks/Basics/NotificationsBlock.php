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

namespace BadPixxel\Widgets\Blocks\Basics;

use BadPixxel\Widgets\Attribute\AsWidgetBlock;
use BadPixxel\Widgets\Blocks\Bootstrap\AlertBlock;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\Interfaces\Widgets\OptionsAwareWidgetInterface;
use BadPixxel\Widgets\Models\AbstractBlock;
use BadPixxel\Widgets\Models\Commons\OptionsSafeAwareTrait;
use BadPixxel\Widgets\OptionResolver\BlockOptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

/**
 * Widget Alert / Notifications Block
 *
 * Render Log Messages as Alert Blocks
 */
#[AsWidgetBlock]
class NotificationsBlock extends AbstractBlock implements BlockWithDemoInterface
{
    use OptionsSafeAwareTrait;

    const TYPE = "NotificationsBlock";

    public function __construct(array $data = array(), array $options = array())
    {
        parent::__construct(self::TYPE, $data, $options);
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Render Alert / Notifications.";
    }

    /**
     * Add Notification / Alert
     */
    public function addAlert(
        string $level,
        string $text,
        ?string $title = null,
        ?string $icon = null,
        bool $dismissible = false
    ) : static
    {
        Assert::inArray(
            $level,
            AlertBlock::LEVELS,
            "Alert Level must be one of ".implode(", ", AlertBlock::LEVELS)
        );

        $alerts = $this->getData()[$level] ?? array();
        Assert::isArray($alerts, "Alert Level must be an Array");

        if (empty($title) && empty($icon) && empty($dismissible)) {
            $alerts[] = $text;
        } else {
            $alerts[] = array(
                "title" => $title,
                "icon" => $icon,
                "text" => $text,
                "dismissible" => $dismissible,
            );
        }

        return $this->set($level, $alerts);
    }

    /**
     * @inheritdoc
     */
    public function getDataResolver() : ?OptionsResolver
    {
        $resolver = new OptionsResolver();
        foreach (AlertBlock::LEVELS as $level) {
            $resolver->setDefault($level, null);
            $resolver->addAllowedTypes($level, array("null", "string[]", "array"));

        }

        return $resolver;
    }

    //==============================================================================
    // DEMONSTRATION
    //==============================================================================

    /**
     * @inheritDoc
     */
    public function setupForDemo(): void
    {
        //==============================================================================
        // Add Basic Alerts
        foreach (AlertBlock::LEVELS as $level) {
            $this->addAlert(
                $level,
                sprintf("I'm just a simple %s notification!", ucfirst($level)),
            );
        }
        //==============================================================================
        // Add Advanced Alerts
        foreach (AlertBlock::LEVELS as $level) {
            $this->addAlert(
                $level,
                sprintf("%s Alert", ucfirst($level)),
                sprintf("I'm just a %s notification!", ucfirst($level)),
                "fa fa-fw fa-info-circle",
                in_array($level, array(AlertBlock::SUCCESS, AlertBlock::INFO)),
            );
        }
        $this->setSafe(true);
    }
}
