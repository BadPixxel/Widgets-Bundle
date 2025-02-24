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

namespace BadPixxel\Widgets\Blocks\Bootstrap;

use BadPixxel\Widgets\Attribute\AsWidgetBlock;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\Interfaces\Widgets\OptionsAwareWidgetInterface;
use BadPixxel\Widgets\Models\AbstractBlock;
use BadPixxel\Widgets\Models\Commons\OptionsSafeAwareTrait;
use BadPixxel\Widgets\OptionResolver\BlockOptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

/**
 * Bootstrap Alert Block
 *
 * Render a Bootstrap Alert Blocks
 */
#[AsWidgetBlock]
class AlertBlock extends AbstractBlock implements BlockWithDemoInterface
{
    use OptionsSafeAwareTrait;

    const TYPE = "AlertBlock";

    /**
     * Success Alert Message
     */
    const SUCCESS = "success";

    /**
     * Info Alert Message
     */
    const INFO = "info";

    /**
     * Warning Alert Message
     */
    const WARNING = "warning";

    /**
     * Error Alert Message
     */
    const ERROR = "error";

    /**
     * Alert Messages Levels
     */
    const LEVELS = array(
        self::ERROR,
        self::WARNING,
        self::SUCCESS,
        self::INFO,
    );

    public function __construct(array $data = array(), array $options = array())
    {
        parent::__construct(self::TYPE, $data, $options);
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Render a Bootstrap Alert.";
    }

    /**
     * Set Alert Type
     */
    public function setLevel(string $level) : static
    {
        Assert::inArray($level, self::LEVELS, "Alert Level must be one of ".implode(", ", self::LEVELS));

        return $this->set("level", $level);
    }

    /**
     * Set Title
     */
    public function setTitle(string $title) : static
    {
        return $this->set("title", $title);
    }

    /**
     * Set Text
     */
    public function setText(string $text) : static
    {
        return $this->set("text", $text);
    }

    /**
     * Set Icon
     */
    public function setIcon(string $iconClass) : static
    {
        return $this->set("icon", $iconClass);
    }

    /**
     * Set Dismissible
     */
    public function setDismissible(bool $dismissible = true) : static
    {
        return $this->set("dismissible", $dismissible);
    }

    /**
     * @inheritdoc
     */
    public function getDataResolver() : ?OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault("level", self::ERROR);
        $resolver->addAllowedTypes("level", "string");
        $resolver->addAllowedValues("level", self::LEVELS);
        $resolver->setDefault("title", null);
        $resolver->addAllowedTypes("title", array("null", "string"));
        $resolver->setDefault("text", "");
        $resolver->addAllowedTypes("text", "string");
        $resolver->setDefault("icon", null);
        $resolver->addAllowedTypes("icon", array("null", "string"));
        $resolver->setDefault("dismissible", false);
        $resolver->addAllowedTypes("dismissible", "bool");

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
        $level = array_rand(array_flip(self::LEVELS), 1);

        $this
            ->setLevel($level)
            ->setIcon("fa fa-question")
            ->setTitle(
                sprintf("%s Alert", ucfirst($level))
            )
            ->setText(
                sprintf("I'm just a %s alert!", ucfirst($level))
            )
            ->setDismissible()
            ->setSafe()
        ;
    }
}
