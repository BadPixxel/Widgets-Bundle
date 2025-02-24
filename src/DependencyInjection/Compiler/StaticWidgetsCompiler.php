<?php

namespace BadPixxel\Widgets\DependencyInjection\Compiler;

use BadPixxel\Widgets\Attribute\AsStaticWidget;
use BadPixxel\Widgets\Helpers\TagsEncoder;
use BadPixxel\Widgets\Interfaces\WidgetInterface;
use BadPixxel\Widgets\Services\Widgets\Loaders\StaticWidgetsLoader;
use BadPixxel\Widgets\Services\Widgets\WidgetsResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webmozart\Assert\Assert;

class StaticWidgetsCompiler implements CompilerPassInterface
{
    /**
     * Compiled array of Widget Service Configurations
     *
     * @var array[]
     */
    private array $widgets = array();

    public function process(ContainerBuilder $container): void
    {
        //==============================================================================
        // Build List of Widgets Services
        foreach ($container->findTaggedServiceIds(AsStaticWidget::TAG) as $id => $tags) {
            foreach ($tags as $tag) {
                Assert::classExists($class = (string) $container->getDefinition($id)->getClass());
                $this->register($id, $tag, $class);
            }
        }
        //==============================================================================
        // Configure Widgets Services for Resolver
        $container
            ->getDefinition(StaticWidgetsLoader::class)
            ->addMethodCall('configure', array('$configurations' => $this->widgets))
        ;
    }

    /**
     * Register a Widget tagged Service
     */
    public function register(string $id, array $tag, string $serviceClass): void
    {
        //==============================================================================
        // Verify Service
        Assert::implementsInterface(
            $serviceClass,
            WidgetInterface::class,
            sprintf("Widget Service must implement %s", WidgetInterface::class)
        );
        //==============================================================================
        // Verify Tag Configuration
        Assert::allStringNotEmpty(
            $tag["channels"] = TagsEncoder::decode($tag["channels"]),
            sprintf("%s : Widget Channel must be a non empty string", $id)
        );
        Assert::allStringNotEmpty(
            $tag["roles"] = TagsEncoder::decode($tag["roles"]),
            sprintf("%s : Widget Role must be a non empty string", $id)
        );
        Assert::isArray(
            $tag["options"] = TagsEncoder::decode($tag["options"]),
            sprintf("%s : Widget Options must be an array", $id)
        );
        Assert::integer($tag["priority"], sprintf("%s : Widget Priority must be an integer", $id));
        //==============================================================================
        // Register Tag Configuration
        $this->widgets[] = array(
            "id" => $id,
            "channels" => $tag["channels"],
            "roles" => $tag["roles"],
            "priority" => $tag["priority"],
            "options" => $tag["options"],
        );
        //==============================================================================
        // Sort Services by Priority
        uasort($this->widgets, function ($itemA, $itemB): int {
            return ($itemA["priority"] > $itemB["priority"]) ? 1 : -1;
        });
    }
}