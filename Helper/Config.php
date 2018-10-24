<?php

namespace NWhale\FastRegistration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Request\Http as HttpRequest;

class Config extends AbstractHelper
{
    const XML_PATH_GENERAL = 'nwhale_fastregistration/general/';
    const XML_PATH_ATTRIBUTES = 'attributes';
    const XML_PATH_ENABLED = 'enabled';

    /**
     * @var HttpRequest
     */
    private $request;

    public function __construct(
        HttpRequest $request,
        Context $context
    ) {
        parent::__construct($context);
        $this->request = $request;
    }

    /**
     * @param $value
     * @return mixed
     */
    private function getValue($value)
    {
        return trim(
            $this->scopeConfig->getValue(static::XML_PATH_GENERAL . $value, ScopeInterface::SCOPE_STORE)
        );
    }

    /**
     * @return array
     */
    private function getCustomAttributes()
    {
        $customAttributes = $this->getValue(static::XML_PATH_ATTRIBUTES);
        $customAttributes = explode(',', $customAttributes);
        $customAttributes = array_filter($customAttributes);
        $customAttributes = array_map('trim', $customAttributes);

        return $customAttributes;
    }

    /**
     * @return string
     */
    private function getCurrentRoute()
    {
        $controller = $this->request->getControllerName();
        $action     = $this->request->getActionName();
        $route      = $this->request->getRouteName();
        return sprintf('%s/%s/%s', $route, $controller, $action);
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return (bool) $this->getValue(static::XML_PATH_ENABLED);
    }

    /**
     * @return bool
     */
    public function onRegisterPage()
    {
        return 'customer/account/create' == $this->getCurrentRoute();
    }

    /**
     * @return bool
     */
    public function onRegisterPost()
    {
        return 'customer/account/createpost' == $this->getCurrentRoute();
    }

    /**
     * @return bool
     */
    public function onCheckoutPage()
    {
        return strpos($this->getCurrentRoute(),'checkout') !== false;
    }

    /**
     * @return array
     */
    public function getRequiredLabels()
    {
        $labels = ['email_address', 'password', 'confirmation'];
        $customAttributes = $this->getCustomAttributes();
        return array_merge($labels, $customAttributes);
    }

    public function getRequiredAttributes()
    {
        $labels = ['email', 'password_hash'];
        $customAttributes = $this->getCustomAttributes();
        return array_merge($labels, $customAttributes);
    }
}