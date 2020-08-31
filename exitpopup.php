<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class ExitPopup extends Module {
    
    public function __construct() 
    {
        $this->name = 'exitpopup';
        $this->tab = 'front_office_features';
        $this->author = 'Alexis Dardenne';
        $this->version = '0.0.1';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Exit Popup');
        $this->description = $this->l('Display an exit popup and tab animation when the customer leaves the page');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }


    /**
     * 
     */

    public function install() 
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (parent::install() &&
            $this->registerHook('displayWrapperBottom') &&
            $this->registerHook('actionFrontControllerSetMedia') &&
            Configuration::updateValue('EXITPOPUP_HOOK', 'Don\'t miss this opportunity !') &&
            Configuration::updateValue('EXITPOPUP_TAB', 'We miss you !')
        ) {
            return false;
        }

        return true;
    }

    /**
     * 
     */

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }

    /**
     * 
     */

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name)) {
            $exitpopupHook = strval(Tools::getValue('EXITPOPUP_HOOK'));
            $exitpopupTab= strval(Tools::getValue('EXITPOPUP_TAB'));

            if (
                !$exitpopupHook ||
                empty($exitpopupHook) ||
                !Validate::isGenericName($exitpopupHook) ||
                !$exitpopupTab ||
                empty($exitpopupTab) ||
                !Validate::isGenericName($exitpopupTab)
            ) {
                $output .= $this->displayError($this->l('Invalid Configuration values'));
            } else {
                Configuration::updateValue('EXITPOPUP_HOOK', $exitpopupHook);
                Configuration::updateValue('EXITPOPUP_TAB', $exitpopupTab);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        return $output.$this->displayForm();
    }

    /**
     * 
     */

    public function displayForm()
    {
        // Get default language
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        //Init Fields form array
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Exit Popup Hook'),
                    'name' => 'EXITPOPUP_HOOK',
                    'size' => 20,
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Exit Popup Tab'),
                    'name' => 'EXITPOPUP_TAB',
                    'size' => 20,
                    'required' => true
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];
        
        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        //Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        // title and Toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];

        // Load current value
        $helper->fields_value['EXITPOPUP_HOOK'] = Tools::getValue('EXITPOPUP_HOOK', Configuration::get('EXITPOPUP_HOOK'));
        $helper->fields_value['EXITPOPUP_TAB'] = Tools::getValue('EXITPOPUP_TAB', Configuration::get('EXITPOPUP_TAB'));

        return $helper->generateForm($fieldsForm);
    }

    /**
     * 
     */

    public function hookDisplayWrapperBottom()
    {
        $this->context->smarty->assign([
            'exitpopup_hook' => Configuration::get('EXITPOPUP_HOOK'),
            'exitpopup_image' => 'modules/'.$this->name.'/images/cart-haert.jpg',
            'cart_url' => $this->getCartSummaryURL()
        ]);

        return $this->display(__FILE__, 'exitpopup.tpl');
    }

    /**
     * 
     */

    public function hookActionFrontControllerSetMedia()
    {
        

        $this->context->controller->registerStylesheet(
            'exitpopup-style',
            'modules/'.$this->name.'/views/css/exitpopup.css',
            [
                'media' => 'all',
                'priority' => 1000,
            ]
        );

        Media::addJsDef([
            'exitpopup_tab' => Configuration::get('EXITPOPUP_TAB'),
            'exitpopup_icon' => 'modules/'.$this->name.'/images/favicon.ico'
        ]);

        $this->context->controller->registerJavascript(
            'exitpopup-javascript',
            'modules/'.$this->name.'/views/js/exitpopup.js',
            [
                'position' => 'bottom',
                'priority' => 1000,
            ]
        );
    }

    private function getCartSummaryURL()
    {
        return $this->context->link->getPageLink(
            'cart',
            null,
            $this->context->language->id,
            array(
                'action' => 'show',
            ),
            false,
            null,
            true
        );
    }
}

