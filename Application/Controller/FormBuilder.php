<?php
namespace Vigas\Application\Controller;

use Vigas\Application;

/**
* Class FormBuilder.
* Builds HTML form
*/
class FormBuilder
{
	/**
    * @var string Contains all the form HTML fields
    */
	protected $html_fields = '';
	
	/**
    * @var string Page's URL that handles the form data
    */
	protected $target_url;
	
	/**
    * @var string The HTTP method to be used when submitting the form (GET or POST)
    */
    protected $method;
	
	/**
    * @var string The HTML class attribute name
    */
    protected $class;
	
	/**
    * Sets attributes $target_url, $method and $class if not null
	* @param string $target_url Page's URL that handles the form data
    * @param string $method The HTTP method to be used when submitting the form (GET or POST)
    * @param string $class The HTML class attribute name
    */
    public function __construct($target_url, $method, $class = null)
    {
        $this->target_url = $target_url;
        $this->method = $method;
        if($class != null)
        {
            $this->class = 'class="'.$class.'"';
        }
    }
    
	/**
    * Defines how the FormBuilder object will be displayed when called by echo
	* @return string The form to display
    */
    public function __toString()
    {
        return '<form '.$this->class.' action="'.$this->target_url.'" method="'.$this->method.'">'.$this->html_fields.'</form>';
    }
    
	/**
    * Surrounds a form field with label attribute and a div
	* @param string $element The form element
    * @param string $label The element label
    */
    private function surroundWithLabel($element, $label='')
    {
        $label != '' ? $label = "<label>".$label."</label>" : '';
        $this->html_fields .= "<div class=\"form-group\">".$label."".$element."</div>";
    }
    
	/**
    * Surrounds a form field with a div
	* @param string $element The form element
    */
    private function surround($element)
    {
        $this->html_fields .= "<div class=\"form-group\">".$element."</div>";
    }
     
	/**
    * Adds an input field to the html_fields attribute
	* @param string $id The input id attribute
    * @param string $label The element label
    * @param string $type The input type attribute
    * @param string $name The input name attribute
    * @param string $value The input default value
    * @param string $attribute Any other HTML attribute
    */	 
    public function addInputHTML($id, $label, $type, $name, $value = '', $attribute = '')
	{
		$this->html_fields .= $this->surround(
                            "<label for=\"".$id."\">".$label."</label>
                            <input type=\"".$type."\" class=\"form-control\" id=\"".$id."\" name=\"".$name."\" value=\"".$value."\" ".$attribute.">");
	}
    
	/**
    * Adds a textarea field to the html_fields attribute
	* @param string $id The textarea id attribute
    * @param string $label The element label
    * @param string $type The textarea type attribute
    * @param string $name The textarea name attribute
    * @param string $rows The textarea rows number
    * @param string $value The textarea default value
    * @param string $attribute Any other HTML attribute
    */
    public function addTextareaHTML($id, $label, $type, $name, $rows, $value = '', $attribute = '')
	{
        if($label != '')
        {
            $label = "<label for=\"".$id."\">".$label."</label>";
        }
		$this->html_fields .= $this->surround(
                            $label.
                            "<textarea class=\"form-control\" id=\"".$id."\" name=\"".$name."\" rows=\"".$rows."\" ".$attribute.">".$value."</textarea>");
	}
    
	/**
    * Adds a select field to the html_fields attribute
	* @param string $id The element id attribute
    * @param string $label The element label
    * @param string $name The element name attribute
    * @param array $options List of options
    * @param string $selected The default selected option
    */
    public function addSelectHTML($id, $label, $name, array $options, $selected = '')
	{
        $options_html = '';
        foreach($options as $option)
        {
            if($selected == $option)
            {
                $options_html .= "<option selected>".$option."</option>";
            }
            else
            {
                $options_html .= "<option>".$option."</option>";
            }        
        }
		$this->html_fields .= $this->surround(
                            "<label for=\"".$id."\">".$label."</label>
                            <select class=\"form-control\" id=\"".$id."\" name=\"".$name."\">
                                ".$options_html."
                            </select>");
	}
    
	/**
    * Creates an HTML checkbox field
	* @param string $id The checkbox id attribute
    * @param string $label The checkbox label
    * @param string $type The checkbox type attribute
    * @param string $name The checkbox name attribute
    * @param string $class The HTML class attribute
    * @param string $attribute Any other HTML attribute
	* @return string An HTML checkbox field
    */
    public function addCheckboxHTML($id, $label, $type, $name, $class, $attribute = '')
	{
		return  "<div class=\"".$class."\">
                <label class=\"".$class."\"><input type=\"".$type."\" id=\"".$id."\" name=\"".$name."\"  ".$attribute.">".$label."</label>
                </div>";
	}
    
	/**
    * Adds multiple checkbox fields to the html_fields attribute
	* @param string $checkbox_array Contains all the checkboxes
    * @param string $main_label The element label
    */
    public function addMultipleCheckboxHTML(array $checkbox_array, $main_label)
    {
        $checkbox_html = '';
        foreach ($checkbox_array as $checkbox)
        {
            isset($checkbox[5]) ? $checkbox[5] = $checkbox[5] : $checkbox[5] = '';
            $checkbox_html .= $this->addCheckboxHTML($checkbox[0], $checkbox[1], $checkbox[2], $checkbox[3], $checkbox[4], $checkbox[5]);
        }
        $this->html_fields .= $this->surroundWithLabel($checkbox_html, $main_label);
    }
    
	/**
    * Adds a checkbox field to the html_fields attribute
	* @param string $id The checkbox id attribute
    * @param string $label The checkbox label
    * @param string $type The checkbox type attribute
    * @param string $name The checkbox name attribute
    * @param string $class The HTML class attribute
    * @param string $attribute Any other HTML attribute
	* @param string $main_label The main element label	
    */
    public function addOneCheckboxHTML($id, $label, $type, $name, $class, $attribute = '', $main_label='')
    {
        $checkbox_html = $this->addCheckboxHTML($id, $label, $type, $name, $class, $attribute);
        $this->html_fields .= $this->surroundWithLabel($checkbox_html, $main_label);
    }
    
	/**
    * Adds radios fields to the html_fields attribute
	* @param string $id The radio id attribute
    * @param string $main_label The radio main label
    * @param string $type The radio type attribute
    * @param array $radios All radios name and label attribute
    * @param string $class The HTML class attribute
    * @param string $attribute Any other HTML attribute
    */
    public function addRadioHTML($id, $main_label, $type, $radios, $class, $attribute = '')
	{
        $radio_html = '';
        foreach($radios as $name => $label)
        {
            $radio_html .=  "<div class=\"".$class."\">
                            <label class=\"".$class."\"><input type=\"".$type."\" name=\"".$name."\" ".$attribute.">".$label."</label>
                            </div>";
        }
		$this->html_fields .= $this->surroundWithLabel($radio_html, $main_label);
	}
    
	/**
    * Adds a Google captcha field to the html_fields attribute
    */
    public function addCaptcha()
    {
		$captcha_config = Application\Application::getConfigFromXML(__DIR__.'/../config.xml', 'captcha');
        $this->html_fields .= '<div class="g-recaptcha" data-sitekey="'.$captcha_config['siteKey'].'"></div>';
    }
    
	/**
    * Adds a submit button to the html_fields attribute
	* @param string $label The submit button label
    * @param string $name The submit button name attribute
    * @param string $class The HTML class attribute
    */
    public function addSubmitButton($label, $name, $class)
    {
        $this->html_fields .= '<button name="'.$name.'" type="submit" class="'.$class.'">'.$label.'</button>';
    }
    
	/**
    * Adds a text to the html_fields attribute
	* @param string $text The text to add
    */
    public function addTextHTML($text)
    {
        $this->html_fields .= $text;
    }
    
}