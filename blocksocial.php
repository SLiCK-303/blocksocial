<?php
/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author     PrestaShop SA <contact@prestashop.com>
 * @copyright  2007-2016 PrestaShop SA
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_CAN_LOAD_FILES_') || !defined('_TB_VERSION_')) {
    exit;
}

/**
 * Class blocksocial
 */
class blocksocial extends Module
{
    /**
     * blocksocial constructor.
     */
    public function __construct()
    {
        $this->name = 'blocksocial';
        $this->tab = 'front_office_features';
        $this->version = '2.1.0';
        $this->author = 'thirty bees';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Social networking block');
        $this->description = $this->l('Allows you to add information about your brand\'s social networking accounts.');
        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => '1.6.99.99'];
        $this->tb_versions_compliancy = '~1.0.0';
    }

    /**
     * Install the module
     *
     * @return bool
     */
    public function install()
    {
        return (parent::install() AND Configuration::updateValue('BLOCKSOCIAL_FACEBOOK', '') &&
            Configuration::updateValue('BLOCKSOCIAL_TWITTER', '') &&
            Configuration::updateValue('BLOCKSOCIAL_RSS', '') &&
            Configuration::updateValue('BLOCKSOCIAL_YOUTUBE', '') &&
            Configuration::updateValue('BLOCKSOCIAL_GOOGLE_PLUS', '') &&
            Configuration::updateValue('BLOCKSOCIAL_PINTEREST', '') &&
            Configuration::updateValue('BLOCKSOCIAL_VIMEO', '') &&
            Configuration::updateValue('BLOCKSOCIAL_INSTAGRAM', '') &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayFooter'));
    }

    /**
     * Uninstall the module
     *
     * @return bool
     */
    public function uninstall()
    {
        //Delete configuration
        return (Configuration::deleteByName('BLOCKSOCIAL_FACEBOOK')
            && Configuration::deleteByName('BLOCKSOCIAL_TWITTER')
            && Configuration::deleteByName('BLOCKSOCIAL_RSS')
            && Configuration::deleteByName('BLOCKSOCIAL_YOUTUBE')
            && Configuration::deleteByName('BLOCKSOCIAL_GOOGLE_PLUS')
            && Configuration::deleteByName('BLOCKSOCIAL_PINTEREST')
            && Configuration::deleteByName('BLOCKSOCIAL_VIMEO')
            && Configuration::deleteByName('BLOCKSOCIAL_INSTAGRAM')
            && parent::uninstall());
    }

    /**
     * Get the module's config page
     *
     * @return string
     */
    public function getContent()
    {
        // If we try to update the settings
        $output = '';
        if (Tools::isSubmit('submitModule')) {
            Configuration::updateValue('BLOCKSOCIAL_FACEBOOK', Tools::getValue('blocksocial_facebook', ''));
            Configuration::updateValue('BLOCKSOCIAL_TWITTER', Tools::getValue('blocksocial_twitter', ''));
            Configuration::updateValue('BLOCKSOCIAL_RSS', Tools::getValue('blocksocial_rss', ''));
            Configuration::updateValue('BLOCKSOCIAL_YOUTUBE', Tools::getValue('blocksocial_youtube', ''));
            Configuration::updateValue('BLOCKSOCIAL_GOOGLE_PLUS', Tools::getValue('blocksocial_google_plus', ''));
            Configuration::updateValue('BLOCKSOCIAL_PINTEREST', Tools::getValue('blocksocial_pinterest', ''));
            Configuration::updateValue('BLOCKSOCIAL_VIMEO', Tools::getValue('blocksocial_vimeo', ''));
            Configuration::updateValue('BLOCKSOCIAL_INSTAGRAM', Tools::getValue('blocksocial_instagram', ''));
            $this->_clearCache('blocksocial.tpl');
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&conf=4&module_name='.$this->name);
        }

        return $output.$this->renderForm();
    }

    public function renderForm()
    {
        $fieldsForm = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                    'icon'  => 'icon-cogs',
                ],
                'input'  => [
                    [
                        'type'  => 'text',
                        'label' => $this->l('Facebook URL'),
                        'name'  => 'blocksocial_facebook',
                        'desc'  => $this->l('Your Facebook fan page.'),
                    ],
                    [
                        'type'  => 'text',
                        'label' => $this->l('Twitter URL'),
                        'name'  => 'blocksocial_twitter',
                        'desc'  => $this->l('Your official Twitter account.'),
                    ],
                    [
                        'type'  => 'text',
                        'label' => $this->l('RSS URL'),
                        'name'  => 'blocksocial_rss',
                        'desc'  => $this->l('The RSS feed of your choice (your blog, your store, etc.).'),
                    ],
                    [
                        'type'  => 'text',
                        'label' => $this->l('YouTube URL'),
                        'name'  => 'blocksocial_youtube',
                        'desc'  => $this->l('Your official YouTube account.'),
                    ],
                    [
                        'type'  => 'text',
                        'label' => $this->l('Google+ URL:'),
                        'name'  => 'blocksocial_google_plus',
                        'desc'  => $this->l('Your official Google+ page.'),
                    ],
                    [
                        'type'  => 'text',
                        'label' => $this->l('Pinterest URL:'),
                        'name'  => 'blocksocial_pinterest',
                        'desc'  => $this->l('Your official Pinterest account.'),
                    ],
                    [
                        'type'  => 'text',
                        'label' => $this->l('Vimeo URL:'),
                        'name'  => 'blocksocial_vimeo',
                        'desc'  => $this->l('Your official Vimeo account.'),
                    ],
                    [
                        'type'  => 'text',
                        'label' => $this->l('Instagram URL:'),
                        'name'  => 'blocksocial_instagram',
                        'desc'  => $this->l('Your official Instagram account.'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFieldsValues(),
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        ];

        return $helper->generateForm([$fieldsForm]);
    }

    public function getConfigFieldsValues()
    {
        return [
            'blocksocial_facebook'    => Tools::getValue('blocksocial_facebook', Configuration::get('BLOCKSOCIAL_FACEBOOK')),
            'blocksocial_twitter'     => Tools::getValue('blocksocial_twitter', Configuration::get('BLOCKSOCIAL_TWITTER')),
            'blocksocial_rss'         => Tools::getValue('blocksocial_rss', Configuration::get('BLOCKSOCIAL_RSS')),
            'blocksocial_youtube'     => Tools::getValue('blocksocial_youtube', Configuration::get('BLOCKSOCIAL_YOUTUBE')),
            'blocksocial_google_plus' => Tools::getValue('blocksocial_google_plus', Configuration::get('BLOCKSOCIAL_GOOGLE_PLUS')),
            'blocksocial_pinterest'   => Tools::getValue('blocksocial_pinterest', Configuration::get('BLOCKSOCIAL_PINTEREST')),
            'blocksocial_vimeo'       => Tools::getValue('blocksocial_vimeo', Configuration::get('BLOCKSOCIAL_VIMEO')),
            'blocksocial_instagram'   => Tools::getValue('blocksocial_instagram', Configuration::get('BLOCKSOCIAL_INSTAGRAM')),
        ];
    }

    public function hookDisplayHeader()
    {
        Media::addJsDef(
            [
                'blocksocial_facebook_url'    => Configuration::get('BLOCKSOCIAL_FACEBOOK'),
                'blocksocial_twitter_url'     => Configuration::get('BLOCKSOCIAL_TWITTER'),
                'blocksocial_rss_url'         => Configuration::get('BLOCKSOCIAL_RSS'),
                'blocksocial_youtube_url'     => Configuration::get('BLOCKSOCIAL_YOUTUBE'),
                'blocksocial_google_plus_url' => Configuration::get('BLOCKSOCIAL_GOOGLE_PLUS'),
                'blocksocial_pinterest_url'   => Configuration::get('BLOCKSOCIAL_PINTEREST'),
                'blocksocial_vimeo_url'       => Configuration::get('BLOCKSOCIAL_VIMEO'),
                'blocksocial_instagram_url'   => Configuration::get('BLOCKSOCIAL_INSTAGRAM'),
            ]
        );

        $this->context->controller->addJS(($this->_path).'views/js/blocksocial.js');
        $this->context->controller->addCSS(($this->_path).'blocksocial.css', 'all');
    }

    public function hookDisplayFooter()
    {
        if (!$this->isCached('blocksocial.tpl', $this->getCacheId())) {
            $this->smarty->assign(
                [
                    'facebook_url'    => Configuration::get('BLOCKSOCIAL_FACEBOOK'),
                    'twitter_url'     => Configuration::get('BLOCKSOCIAL_TWITTER'),
                    'rss_url'         => Configuration::get('BLOCKSOCIAL_RSS'),
                    'youtube_url'     => Configuration::get('BLOCKSOCIAL_YOUTUBE'),
                    'google_plus_url' => Configuration::get('BLOCKSOCIAL_GOOGLE_PLUS'),
                    'pinterest_url'   => Configuration::get('BLOCKSOCIAL_PINTEREST'),
                    'vimeo_url'       => Configuration::get('BLOCKSOCIAL_VIMEO'),
                    'instagram_url'   => Configuration::get('BLOCKSOCIAL_INSTAGRAM'),
                ]
            );
        }

        return $this->display(__FILE__, 'blocksocial.tpl', $this->getCacheId());
    }
}
