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

namespace BadPixxel\Widgets\Services\Widgets\Technical;

use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Blocks\Basics\TextBlock;
use BadPixxel\Widgets\Dictionary\Channels;
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetColors;
use BadPixxel\Widgets\Dictionary\Widgets\WidgetWidth;
use BadPixxel\Widgets\Models\AbstractWidget;
use BadPixxel\Widgets\Widgets\Descriptor\SimpleDescriptor;
use BadPixxel\Widgets\Widgets\Descriptor\TranslatableDescriptor;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

/**
 * Rendered when widget compilation fails
 */
#[AsStaticWidget(
    channels: array(Channels::SYSTEM),
    roles: array("ROLE_ADMIN"),
    options: array(
        Options::WIDTH => WidgetWidth::XL
    )
)]
class CompilationErrorWidget extends AbstractWidget
{
    private ?Throwable $exception = null;

    public function __construct(
        private readonly TranslatorInterface $translator,
        #[Autowire('%kernel.environment%')]
        private readonly string $environment
    ) {
        parent::__construct();
    }

    /**
     * Set the exception that caused the compilation error
     */
    public function setException(Throwable $exception): self
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDescriptor(): SimpleDescriptor
    {
        return new TranslatableDescriptor(
            title: "widgets.technical.compilation-error.title",
            description: "widgets.technical.compilation-error.description",
            icon: "fa fa-fw fa-exclamation-triangle",
            origin: "Technical Widgets",
            translationDomain: "BadPixxelWidgets",
        );
    }

    /**
     * @inheritdoc
     */
    public function build(): void
    {
        //==============================================================================
        // Configure Widget
        $this->mergeOptions(array(
            Options::COLOR_CLASS => WidgetColors::DANGER
        ));
        //==============================================================================
        // Create Text Block with Error Message
        $textBlock = new TextBlock();
        $errorMessage = sprintf(
            "<h5 class='text-danger'>%s</h5>",
            $this->translator->trans("widgets.technical.compilation-error.text", array(), "BadPixxelWidgets")
        );

        //==============================================================================
        // Add Exception Details if Available (only in dev and test environments)
        if ($this->exception && in_array($this->environment, array('dev', 'test'), true)) {
            $errorMessage .= sprintf(
                "<div class='alert alert-danger mt-3'><strong>%s:</strong> %s</div>",
                get_class($this->exception),
                htmlspecialchars($this->exception->getMessage())
            );
            $errorMessage .= sprintf(
                "<div class='text-muted small'>%s:%d</div>",
                htmlspecialchars($this->exception->getFile()),
                $this->exception->getLine()
            );
        }

        $textBlock
            ->setText($errorMessage)
            ->setWidth(WidgetWidth::XL)
            ->setSafe()
        ;
        $this->addBlock($textBlock);
    }
}
