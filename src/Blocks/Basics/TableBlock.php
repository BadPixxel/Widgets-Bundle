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
use BadPixxel\Widgets\Dictionary\Options;
use BadPixxel\Widgets\Interfaces\Blocks\BlockWithDemoInterface;
use BadPixxel\Widgets\Models\AbstractBlock;
use BadPixxel\Widgets\Models\Commons\OptionsSafeAwareTrait;
use BadPixxel\Widgets\OptionResolver\BlockOptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

/**
 * Widget Table Block
 *
 * Render a Html Table wit Escaped or Raw Html Cells
 */
#[AsWidgetBlock]
class TableBlock extends AbstractBlock implements BlockWithDemoInterface
{
    use OptionsSafeAwareTrait;

    const TYPE = "TableBlock";

    public function __construct(array $data = array(), array $options = array())
    {
        parent::__construct(self::TYPE, $data, $options);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return "Render a Html Table wit Escaped or Raw Html Cells";
    }

    /**
     * @inheritdoc
     */
    public function getDataResolver() : ?OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault("rows", array());
        $resolver->addAllowedTypes("rows", "array[]");

        return $resolver;
    }

    /**
     * @inheritdoc
     */
    public function getOptionsResolver() : BlockOptionsResolver
    {
        $resolver = parent::getOptionsResolver();

        $resolver->setDefault("Layout", "table-bordered table-hover table-sm no-margin my-0");
        $resolver->addAllowedTypes("Layout", "string");
        $resolver->setDefault("HeadingRows", 1);
        $resolver->addAllowedTypes("HeadingRows", "integer");
        $resolver->setDefault("HeadingColumns", 1);
        $resolver->addAllowedTypes("HeadingColumns", "integer");

        return $resolver;
    }

    /**
     * Add a Row to Table
     */
    public function addRow(array $row) : self
    {
        Assert::allScalar($row, "Table Row must be filled of Scalar Values");
        Assert::isArray(
            $data = $this->getData()["rows"] ?? array(),
            "Table Rows must be an Array"
        );

        $data[] = $row;
        $this->set("rows", $data);

        return $this;
    }

    /**
     * Add Multiple Rows to Table
     *
     * @param array[] $rows
     *
     * @return $this
     */
    public function addRows(array $rows) : self
    {
        foreach ($rows as $row) {
            Assert::isArray($row, "Table Row must an Array");
            $this->addRow($row);
        }

        return $this;
    }

    //==============================================================================
    // DEMONSTRATION
    //==============================================================================

    /**
     * @inheritDoc
     */
    public function setupForDemo(): void
    {
        $this->addRow(array("Title", "Int Value", "Html Value"));
        $this->addRow(array("Line 1", random_int(10, 100), "<b>Line 1</b> Random Int Value"));
        $this->addRow(array("Line 2", random_int(10, 100) / 10, "<b>Line 2</b> Random Float Value"));
        $this->addRow(array("Line 3", uniqid(), "<b>Line 3</b> UniqId Value"));

        $this->setSafe(true);
    }
}
