<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT

 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class he_header_google_search {
    public string $code;
    public string $group;
    public $title;
    public $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);
      $this->title = CLICSHOPPING::getDef('module_header_google_search_title');
      $this->description = CLICSHOPPING::getDef('module_header_google_search_description');

      if (\defined('MODULE_HEADER_GOOGLE_SEARCH_STATUS')) {
        $this->sort_order = MODULE_HEADER_GOOGLE_SEARCH_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_GOOGLE_SEARCH_STATUS == 'True');
      }
    }

    public function execute() {
      $CLICSHOPPING_Template = Registry::get('Template');

      $content_width = MODULE_HEADER_GOOGLE_SEARCH_CONTENT_WIDTH;
      $key = MODULE_HEADER_GOOGLE_SEARCH_KEY;
      
      $header_template = '<!-- header google search start -->';

      $header_template .= '<script >';
      $header_template .= '(function() {';
      $header_template .= 'var cx = \'' . MODULE_HEADER_GOOGLE_SEARCH_KEY . '\';';
      $header_template .= 'var gcse = document.createElement(\'script\');';
      $header_template .= 'gcse.type = \'text/javascript\';';
      $header_template .= 'gcse.async = true;';
      $header_template .= 'gcse.src = \'https://cse.google.com/cse.js?cx=\' + cx;';
      $header_template .= 'var s = document.getElementsByTagName(\'script\')[0];';
      $header_template .= 's.parentNode.insertBefore(gcse, s);';
      $header_template .= '})();';
      $header_template .= '</script>';
      $header_template .= '<gcse:search></gcse:search>';

      $header_template .= '<!-- header google search end -->' . "\n";

      $CLICSHOPPING_Template->addBlock($header_template, $this->group);
    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return \defined('MODULE_HEADER_GOOGLE_SEARCH_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to activate this module ?',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_SEARCH_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'You must have a registration on https://cse.google.co.uk/ to use this module',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Indicate the content with',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_SEARCH_CONTENT_WIDTH',
          'configuration_value' => '12',
          'configuration_description' => 'Content width',
          'configuration_group_id' => '6',
          'sort_order' => '2',
          'set_function' => 'clic_cfg_set_content_module_width_pull_down',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Indicate the google key',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_SEARCH_KEY',
          'configuration_value' => '12',
          'configuration_description' => 'The google key',
          'configuration_group_id' => '6',
          'sort_order' => '0',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort order',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_SEARCH_SORT_ORDER',
          'configuration_value' => '150',
          'configuration_description' => 'Sort order (lower is displaying in first)',
          'configuration_group_id' => '6',
          'sort_order' => '0',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array('MODULE_HEADER_GOOGLE_SEARCH_STATUS',
                   'MODULE_HEADER_GOOGLE_SEARCH_CONTENT_WIDTH',
                   'MODULE_HEADER_GOOGLE_SEARCH_KEY',
                   'MODULE_HEADER_GOOGLE_SEARCH_SORT_ORDER'
                  );
    }
  }
