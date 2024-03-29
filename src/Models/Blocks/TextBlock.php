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

namespace Splash\Widgets\Models\Blocks;

use ArrayObject;

/**
 * Widget Simple Text Block
 * Render text as Escaped or Raw Html
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class TextBlock extends BaseBlock
{
    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    /**
     * Define Standard Data Fields for this Widget Block
     *
     * @var array
     */
    public static array $DATA = array(
        "text" => null,
    );

    /**
     * Define Standard Options for this Widget Block
     * Uncomment to override défault options
     *
     * @var array
     */
    public static array $OPTIONS = array(
        'Width' => "col-xs-12 col-sm-12 col-md-12 col-lg-12",
        "AllowHtml" => false,
    );

    /**
     * @var string
     */
    protected $type = "TextBlock";

    //====================================================================//
    // *******************************************************************//
    //  Block Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//

    /**
     * Set Text
     *
     * @param mixed $text
     *
     * @return $this
     */
    public function setText($text) : self
    {
        if (is_scalar($text)) {
            $this->data["text"] = (string) $text;
        }

        return $this;
    }

    /**
     * Get Text
     *
     * @return string
     */
    public function getText() : string
    {
        return $this->data["text"];
    }

    /**
     * Set Block Contents
     *
     * @param null|array|ArrayObject $contents
     *
     * @return $this
     */
    public function setContents($contents) : self
    {
        //==============================================================================
        //  Safety Check
        if (!is_array($contents) && !($contents instanceof ArrayObject)) {
            return $this;
        }
        //==============================================================================
        //  Import Title
        if (!empty($contents["text"])) {
            $this->setText($contents["text"]);
        }

        return $this;
    }
}
