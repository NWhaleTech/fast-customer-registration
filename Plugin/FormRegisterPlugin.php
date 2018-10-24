<?php

namespace NWhale\FastRegistration\Plugin;

use NWhale\FastRegistration\Helper\Config as ConfigHelper;
use Magento\Customer\Block\Form\Register as FormRegister;

class FormRegisterPlugin
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
     * @param FormRegister $subject
     * @param string $result
     * @return string
     */
    public function afterToHtml(FormRegister $subject, $result)
    {
        if ($this->isActive()) {
            $availableLabels = $this->configHelper->getRequiredLabels();
            $removeRequiredFields = array_diff(['firstname', 'lastname'], $availableLabels);
            foreach ($removeRequiredFields as $field) {
                $result = str_replace("field-name-$field required", "name-$field", $result);
            }
        }

        return  $result;
    }

    /**
     * @return bool
     */
    private function isActive()
    {
        return $this->configHelper->isModuleEnabled();
    }
}