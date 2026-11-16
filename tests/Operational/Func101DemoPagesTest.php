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

namespace BadPixxel\Widgets\Tests\Operational;

use BadPixxel\Widgets\Demo\Dictionary\WidgetsDemoRoutes;
use BadPixxel\Widgets\Dictionary\Channels;
use BadPixxel\Widgets\Dictionary\Widgets\RenderingModes;
use BadPixxel\Widgets\Phpunit\Traits\BlocksAwareTestTrait;
use BadPixxel\Widgets\Phpunit\Traits\DatabaseAwareTestTrait;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsAwareTestTrait;
use BadPixxel\Widgets\Phpunit\Traits\WidgetsContainerTrait;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Test Demo Application Pages: Blocks, Widgets & Collections in all Rendering Modes
 */
class Func101DemoPagesTest extends WebTestCase
{
    use BlocksAwareTestTrait;
    use WidgetsAwareTestTrait;
    use DatabaseAwareTestTrait;
    use WidgetsContainerTrait;

    /**
     * Listing Pages are Rendered in all Rendering Modes
     */
    #[DataProvider("renderingModesProvider")]
    public function testListingPages(string $bsMode) : void
    {
        $this->assertPageWorks(WidgetsDemoRoutes::HOME, array("bsMode" => $bsMode));
        $this->assertPageWorks(WidgetsDemoRoutes::BLOCKS_LIST, array("bsMode" => $bsMode));
        $this->assertPageWorks(WidgetsDemoRoutes::WIDGETS_LIST, array(
            "bsMode" => $bsMode,
            "channel" => Channels::DEMO,
        ));
    }

    /**
     * Collections Pages are Rendered
     */
    public function testCollectionsPages() : void
    {
        static::ensureKernelShutdown();
        static::ensureWidgetsSchema();
        $this->assertPageWorks(WidgetsDemoRoutes::COLLECTION_COMPONENT, array("bsMode" => RenderingModes::BS5));
        $this->assertPageWorks(WidgetsDemoRoutes::SONATA_COLLECTION, array("bsMode" => RenderingModes::BS3));
        $this->assertPageWorks(WidgetsDemoRoutes::SONATA_WIDGET_LIST, array("bsMode" => RenderingModes::BS3));
    }

    /**
     * Blocks Preview Pages are Rendered
     */
    #[DataProvider("blockTypesProvider")]
    public function testBlockPreviewPage(string $blockType) : void
    {
        $this->assertPageWorks(WidgetsDemoRoutes::BLOCKS_PREVIEW, array(
            "bsMode" => RenderingModes::BS5,
            "type" => $blockType,
        ));
    }

    /**
     * Widgets Preview Pages are Rendered
     */
    #[DataProvider("widgetHashProvider")]
    public function testWidgetPreviewPage(string $widgetHash) : void
    {
        $this->assertPageWorks(WidgetsDemoRoutes::WIDGET_PREVIEW, array(
            "bsMode" => RenderingModes::BS5,
            "hash" => $widgetHash,
        ));
        $this->assertPageWorks(WidgetsDemoRoutes::SONATA_WIDGET_PREVIEW, array(
            "bsMode" => RenderingModes::BS3,
            "hash" => $widgetHash,
        ));
    }

    /**
     * Rendering Modes Provider
     *
     * @return array<string, array{string}>
     */
    public static function renderingModesProvider() : array
    {
        $modes = array();
        foreach (RenderingModes::all() as $mode) {
            $modes[$mode] = array($mode);
        }

        return $modes;
    }

    /**
     * Request a Demo Page & Check it is Rendered
     *
     * @param array<string, string> $parameters
     */
    private function assertPageWorks(string $route, array $parameters) : void
    {
        //====================================================================//
        // Kernel may be Booted by Data Providers
        static::ensureKernelShutdown();
        $client = static::createClient();
        $url = static::getWidgetsService(UrlGeneratorInterface::class)->generate($route, $parameters);
        $client->request("GET", $url);
        Assert::assertSame(
            200,
            $client->getResponse()->getStatusCode(),
            sprintf("Page %s failed: %s", $url, mb_substr((string) $client->getResponse()->getContent(), 0, 500))
        );
        Assert::assertNotEmpty($client->getResponse()->getContent());
    }
}
