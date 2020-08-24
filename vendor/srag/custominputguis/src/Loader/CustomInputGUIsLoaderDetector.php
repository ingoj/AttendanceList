<?php

namespace srag\CustomInputGUIs\AttendanceList\Loader;

use Closure;
use ILIAS\UI\Component\Component;
use ILIAS\UI\Implementation\DefaultRenderer;
use ILIAS\UI\Implementation\Render\ComponentRenderer;
use ILIAS\UI\Implementation\Render\Loader;
use ILIAS\UI\Renderer;
use Pimple\Container;
use srag\CustomInputGUIs\AttendanceList\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\AttendanceList\InputGUIWrapperUIInputComponent\Renderer as InputGUIWrapperUIInputComponentRenderer;
use srag\DIC\AttendanceList\Loader\AbstractLoaderDetector;

/**
 * Class CustomInputGUIsLoaderDetector
 *
 * @package srag\CustomInputGUIs\AttendanceList\Loader
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CustomInputGUIsLoaderDetector extends AbstractLoaderDetector
{

    /**
     * @var bool
     */
    protected static $has_fix_ctrl_namespace_current_url = false;


    /**
     * @return callable
     */
    public static function exchangeUIRendererAfterInitialization()
    {
        self::fixCtrlNamespaceCurrentUrl();

        $previous_renderer = Closure::bind(function () {    return $this->raw("ui.renderer");
}, self::dic()->dic(), Container::class)();

        return function () use($previous_renderer) {
    $previous_renderer = $previous_renderer(self::dic()->dic());
    if ($previous_renderer instanceof DefaultRenderer) {
        $previous_renderer_loader = Closure::bind(function () {
            return $this->component_renderer_loader;
        }, $previous_renderer, DefaultRenderer::class)();
    } else {
        $previous_renderer_loader = null;
    }
    return new DefaultRenderer(new self($previous_renderer_loader));
};
    }


    /**
     *
     */
    private static function fixCtrlNamespaceCurrentUrl()/*:void*/
    {
        if (!self::$has_fix_ctrl_namespace_current_url) {
            self::$has_fix_ctrl_namespace_current_url = true;

            // Fix language select meta bar which current ctrl gui has namespaces (public page)
            $_SERVER["REQUEST_URI"] = str_replace("\\", "%5C", $_SERVER["REQUEST_URI"]);
        }
    }


    /**
     * @inheritDoc
     */
    public function getRendererFor(Component $component, array $contexts)
    {
        if ($component instanceof InputGUIWrapperUIInputComponent) {
            if (self::version()->is6()) {
                return new InputGUIWrapperUIInputComponentRenderer(self::dic()->ui()->factory(), self::dic()->templateFactory(), self::dic()->language(), self::dic()->javaScriptBinding(),
                    self::dic()->refinery());
            } else {
                return new InputGUIWrapperUIInputComponentRenderer(self::dic()->ui()->factory(), self::dic()->templateFactory(), self::dic()->language(), self::dic()->javaScriptBinding());
            }
        }

        return parent::getRendererFor($component, $contexts);
    }
}
