<?php

namespace NWhale\FastRegistration\Plugin;

use NWhale\FastRegistration\Helper\Config as ConfigHelper;
use Magento\Customer\Model\Data\AttributeMetadata;

class AttributeMetadataPlugin
{
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
     * @param AttributeMetadata $subject
     * @param string $result
     * @return string
     */
    public function afterGetFrontendClass(AttributeMetadata $subject, $result)
    {
        if ($this->isActive()) {
            if ($this->isRemoveRequirement($subject->getAttributeCode())) {
                $result = trim($result) == 'required-entry' ? '' : $result;
            }
        }

        return $result;
    }

    /**
     * @return bool
     */
    private function isActive()
    {
        return $this->configHelper->isModuleEnabled() && $this->onRegisterPage();
    }

    /**
     * @return bool
     */
    private function onRegisterPage()
    {
        return $this->configHelper->onRegisterPage();
    }

    /**
     * @param $attributeCode
     * @return bool
     */
    private function isRemoveRequirement($attributeCode)
    {
        return !in_array($attributeCode, $this->configHelper->getRequiredLabels());
    }
}