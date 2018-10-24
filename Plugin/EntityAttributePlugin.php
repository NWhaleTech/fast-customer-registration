<?php

namespace NWhale\FastRegistration\Plugin;

use Magento\Eav\Model\Entity\Attribute;
use NWhale\FastRegistration\Helper\Config as ConfigHelper;

class EntityAttributePlugin
{
    /**
     * @var array
     */
    private $validatedAttributes = [];
    
    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * FormRegisterPlugin constructor.
     * @param ConfigHelper $configHelper
     */
    public function __construct(ConfigHelper $configHelper)
    {
        $this->configHelper = $configHelper;
    }

    /**
     * @param Attribute $subject
     * @param string $result
     * @return string
     */
    public function afterGetIsRequired($subject, $result)
    {
        if ($subject->getEntityTypeId() == 1 && $this->isActive()) {
            if ($this->isRemoveRequirement($subject->getAttributeCode())) {
                $this->validatedAttributes[] = $subject->getAttributeCode();
                $result = '0';
            }
        }

        return $result;
    }

    private function isRemoveRequirement($attributeCode)
    {

        return ($this->onRegisterPost() || !in_array($attributeCode, $this->validatedAttributes))
            && !in_array($attributeCode, $this->configHelper->getRequiredAttributes());
    }
    
    /**
     * @return bool
     */
    private function isActive()
    {
        return $this->configHelper->isModuleEnabled() && ($this->onRegisterPost() || $this->onCheckoutPage());
    }

    /**
     * @return bool
     */
    private function onRegisterPost()
    {
        return $this->configHelper->onRegisterPost();
    }

    /**
     * @return bool
     */
    private function onCheckoutPage()
    {
        return $this->configHelper->onCheckoutPage();
    }

}