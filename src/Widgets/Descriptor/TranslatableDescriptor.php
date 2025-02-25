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

namespace BadPixxel\Widgets\Widgets\Descriptor;

use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

/**
 * Define Widget User Information with Translation
 */
class TranslatableDescriptor extends SimpleDescriptor
{
    /**
     * Storage for Translator, Used when requesting information
     *
     * @var null|TranslatorInterface
     */
    private ?TranslatorInterface $translator = null;

    public function __construct(
        string  $title,
        string  $description,
        string  $icon,
        string  $origin,
        ?string  $subtitle = null,
        private readonly ?string $translationDomain = null,
        private readonly array   $translationParameters = array(),
    ) {
        parent::__construct($title, $description, $icon, $origin, $subtitle);
    }

    /**
     * Set Translator
     */
    public function setTranslator(TranslatorInterface $translator): static
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return $this->translate(parent::getTitle());
    }

    /**
     * @inheritdoc
     */
    public function getSubtitle(): string
    {
        return $this->translate(parent::getSubtitle());
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return $this->translate(parent::getDescription());
    }

    /**
     * @inheritdoc
     */
    public function getOrigin(): string
    {
        return $this->translate(parent::getOrigin());
    }

    /**
     * Get Translations Domain
     */
    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    /**
     * Get Translation Parameters
     */
    public function getTranslationParameters(): array
    {
        return $this->translationParameters;
    }

    /**
     * Translate a String
     */
    private function translate(string $value): string
    {
        if (null === $this->translationDomain) {
            return $value;
        }
        Assert::notEmpty($this->translator, "You must configure a translator.");

        return $this->translator->trans(
            $value,
            $this->getTranslationParameters(),
            $this->getTranslationDomain()
        );
    }
}
