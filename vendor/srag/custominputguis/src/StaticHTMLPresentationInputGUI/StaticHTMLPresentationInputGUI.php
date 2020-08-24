<?php

namespace srag\CustomInputGUIs\AttendanceList\StaticHTMLPresentationInputGUI;

use ilFormException;
use ilFormPropertyGUI;
use ilTemplate;
use srag\CustomInputGUIs\AttendanceList\Template\Template;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class StaticHTMLPresentationInputGUI
 *
 * @package srag\CustomInputGUIs\AttendanceList\StaticHTMLPresentationInputGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class StaticHTMLPresentationInputGUI extends ilFormPropertyGUI
{

    use DICTrait;

    /**
     * @var string
     */
    protected $html = "";


    /**
     * StaticHTMLPresentationInputGUI constructor
     *
     * @param string $title
     */
    public function __construct($title = "")
    {
        parent::__construct($title, "");
    }


    /**
     * @inheritDoc
     */
    public function checkInput()
    {
        return true;
    }


    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }


    /**
     * @param string $html
     *
     * @return self
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }


    /**
     * @return string
     */
    public function getValue()
    {
        return "";
    }


    /**
     * @param ilTemplate $tpl
     */
    public function insert(ilTemplate $tpl)/*: void*/
    {
        $html = $this->render();

        $tpl->setCurrentBlock("prop_generic");
        $tpl->setVariable("PROP_GENERIC", $html);
        $tpl->parseCurrentBlock();
    }


    /**
     * @return string
     */
    public function render()
    {
        $iframe_tpl = new Template(__DIR__ . "/templates/iframe.html");

        $iframe_tpl->setVariableEscaped("URL", $this->getDataUrl());

        return self::output()->getHTML($iframe_tpl);
    }


    /**
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }


    /**
     * @param string $value
     *
     * @throws ilFormException
     */
    public function setValue(/*string*/ $value)/*: void*/
    {
        //throw new ilFormException("StaticHTMLPresentationInputGUI does not support set screenshots!");
    }


    /**
     * @param array $values
     *
     * @throws ilFormException
     */
    public function setValueByArray(/*array*/ $values)/*: void*/
    {
        //throw new ilFormException("StaticHTMLPresentationInputGUI does not support set screenshots!");
    }


    /**
     * @return string
     */
    protected function getDataUrl()
    {
        return "data:text/html;charset=UTF-8;base64," . base64_encode($this->html);
    }
}
