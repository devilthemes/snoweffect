<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@devilthemes.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.devilthemes.com for more information.
*
*  @author Devil Themes SA <contact@devilthemes.com>
*  @copyright  2007-2013 Devil Themes
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class snoweffect extends Module
{
	public function __construct()
	{
		$this->name = 'snoweffect';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'Devil Themes';

		parent::__construct();

		$this->displayName = $this->l('Snow Effect');
		$this->description = $this->l('Shows Snow Effect in website');
	}
	
	public function install()
	{
		return (parent::install() AND Configuration::updateValue('snoweffect_color', '#ffffff') && Configuration::updateValue('snoweffect_flakesMaxActive', '80') && Configuration::updateValue('snoweffect_twinkle', 'true') && $this->registerHook('displayHeader') && $this->registerHook('displayFooter'));
	}
	
	public function uninstall()
	{
		//Delete configuration			
		return (Configuration::deleteByName('snoweffect_color') AND Configuration::deleteByName('snoweffect_flakesMaxActive') AND Configuration::deleteByName('snoweffect_twinkle') AND parent::uninstall());
	}
	
	public function getContent()
	{
		// If we try to update the settings
		$output = '';
		if (isset($_POST['submitModule']))
		{	
			Configuration::updateValue('snoweffect_color', (($_POST['snow_color'] != '') ? $_POST['snow_color']: ''));
			Configuration::updateValue('snoweffect_flakesMaxActive', (($_POST['flakesMaxActive'] != '') ? $_POST['flakesMaxActive']: ''));		
			Configuration::updateValue('snoweffect_twinkle', (($_POST['twinkle'] != '') ? $_POST['twinkle']: ''));				
			$this->_clearCache('snoweffect.tpl');
			$output = '<div class="conf confirm">'.$this->l('Configuration updated').'</div>';
		}
		
		$o_data =  '
		<h2>'.$this->displayName.'</h2>
		'.$output.'
		<script src="../js/jquery/plugins/jquery.colorpicker.js"></script>
		<iframe src="http://www.devilthemes.com/ads/index.html" width="100%" height="80" frameborder="0"></iframe>
		<form action="'.Tools::htmlentitiesutf8($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset class="width2">				
				<label for="snow_color">'.$this->l('Snow Color: ').'</label>
				<input type="color" id="snow_color" name="snow_color" class="mColorPicker" value="'.Tools::safeOutput((Configuration::get('snoweffect_color') != "") ? Configuration::get('snoweffect_color') : "").'" />
				<div class="clear">&nbsp;</div>		
				<label for="flakesMaxActive">'.$this->l('Flakes Max Active: ').'</label>
				<select id="flakesMaxActive" name="flakesMaxActive" >';
				
				$flakes_max = Configuration::get('snoweffect_flakesMaxActive');
				foreach( array(50,80,100,150,200,250,300,350) as $d){
					$selected = $flakes_max == $d ? "selected='selected'": "";
					$o_data .= "<option value='$d' $selected>$d</option>";
				}
		$o_data .= '
				</select> 
				
				
				<div class="clear">&nbsp;</div>		
				<label for="twinkle">'.$this->l('Twinkle Effect: ').'</label>';
				$twinkle = Configuration::get('snoweffect_twinkle');
				foreach( array("true","false") as $d){
					$selected = $twinkle == $d ? "checked='checked'": "";
					$o_data .= "<input type='radio' value='$d' $selected name='twinkle' />$d";
				}
				$o_data .= '
				
				
				<div class="clear">&nbsp;</div>						
				<br /><center><input type="submit" name="submitModule" value="'.$this->l('Update settings').'" class="button" /></center>
			</fieldset>
		</form>';
		return $o_data;
	}
	
	public function hookDisplayHeader()
	{
		$this->context->controller->addJS(($this->_path).'js/snowstorm.js');
		
	}
		
	public function hookDisplayFooter()
	{
		if (!$this->isCached('snoweffect.tpl', $this->getCacheId()))
			$this->smarty->assign(array(
				'snow_color' => Configuration::get('snoweffect_color'),
				'flakesMaxActive' => Configuration::get('snoweffect_flakesMaxActive'),
				'twinkle' => Configuration::get('snoweffect_twinkle')
			));

		return $this->display(__FILE__, 'snoweffect.tpl', $this->getCacheId());
	}
}
?>
