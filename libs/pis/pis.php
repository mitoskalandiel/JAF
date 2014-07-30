<?php
session_start();

/**
 * Copyright (c) 2006 Bryan English (bryan@bluelinecity.com)
 * 
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * PHP Installation System (PIS)
 *
 * PIS is an abstract class which when extended is made into a handy, web-based
 * installation package.
 *
 * @version 0.2
 * @package PIS
 * @author Bryan English
 * @url http://bluelinecity.com/pis
 */
class PIS {

    var $buffer;

    var $errors = array();

    var $failures = array();

    var $pages = array();

    var $page_titles = array();

    var $title;

    var $type;
    
    var $current_page;
    
    var $footer;

    var $version;
    
    var $direction;

    /**
     * Constructor for PIS - parses member methods looking for page definitions
     * and autocreates a step chain for them depending on their definition order.
     *
     * @param string $title Title of the installation program
     * @param string $footer Optional, Footer to add, defaults to PIS Plug
     */
    function PIS($title='',$footer=NULL){

        $this->title = $title;        
        $this->version = '0.2';

        if ($footer == NULL){
            $this->footer = 'PHP Installation System v'.$this->version.' &copy; 2006 - bluelinecity.com/pis';
        }

        $pages = get_class_methods(get_class($this));

        //auto create page chain//
        foreach ( $pages as $page ){
            if ( preg_match('/^page[A-Za-z_]+/',$page) ){
                $this->chain(preg_replace('/^page_?/','',$page));
            }
        }

        //look for/create completion page//
        if ( !preg_match("/complete/i",$this->pages[count($this->pages)-1]) ){
            $this->chain('complete');
        }

        $this->clear();
    }

    /**
     * Set session variable
     *
     * @param string $name Name of the variable
     * @param mixed $value Value of the variable
     * @return mixed Newly creatd session variable.
     */
    function set($name,$value){      
        $_SESSION[$name] = $value;
        return $_SESSION[$name];
    }

    /**
     * Get session variable
     *
     * @param string $name Name of the variable
     * @param mixed $default Value to return if requested variable is NULL
     * @return mixed Value of the session variable
     */
    function get($name,$default=NULL){      
        if ( $_SESSION[$name] === NULL ){
            return $default;
        } else {
            return $_SESSION[$name];
        }
    }

    /**
     * Checks for any errors on the current page.
     *
     * @return boolean Returns true if there were 1 or more errors as a result of page tests.
     */
    function areErrors(){
        return (count($this->errors) > 0);
    }

    /**
     * Set the title of the current page.
     *
     * @param string $title Title to set the page too.
     */
    function title($title){
		$this->page_titles[$this->current_page] = $title;
    }

    /**
     * Adds a message to the installation page. A simple line/block of text.
     *
     * @param string $text The text to display.
     * @param string $style Optional, css style to add to it. Defaults to 'default'
     * @returns true
     */
    function message($text,$style='default'){
        $this->buffer .= '<div class="message '. $style .'">'. $text .'</div>';
        return true;
    }

    /**
     * Creates the order in which the pages are displayed and traversed
     *
     * @param string $page Name of the page (method) to append to the chain.
     * @param string $title Optional, Title of the page, defaults to a formatted version of $page.
     * @return boolean Returns true on success.
     */
    function chain($page,$title=NULL){

        if ( empty($title) ){
            $title = preg_replace('/([a-z])([A-Z])/','\1 \2',$page);
            $title = preg_replace('/([a-z])_([a-z])/','\1 \2',$title);
            $title = ucwords($title);
        }
    
        $this->pages[] = $page;
        $this->page_titles[] = $title;

        return true;
    }

    /**
     * A test to attempt before continuing to the next page. If the test variable is true, 
     * it passes, else, it logs a message and performs an optional action.
     *
     * @param boolean $test Boolean resulted from a test.
     * @param string $message Error message to log.
     * @param string $action Optional, page to jump to as a result of the error.
     * @return true on successful test, false otherwise. Can be used to do conditional nesting in the installation
     */
    function test($test,$message,$action=NULL){

        if ( !$test ){
            $this->failures[] = $message;

            if ( $action ){
                $this->clear();
                $this->page($action);
            }
        }

        return $test;
    }

    /**
     * Look for the named page in the class method list and execute it. If it can't be
     * found, show an error page.
     *
     * @param string $name Name of the method/page to call.
     */
     function callPage($name){

        if ( method_exists($this,'page' . $name) ){
            eval("\$this->page{$name}();");
        } elseif ( method_exists($this,'page_' . $name) ){
            eval("\$this->page_{$name}();");
        } elseif ( $name == 'complete' ){
            $this->completePage();
        } else {
            $this->errorPage("The page named {$name} could not be found.");
        }

    }

    /**
     * Call a page method as defined in the derived class and execute it, compiling
     * any failures, messages, tests, etc into the class buffer.
     *
     * @param string $name Name of the method/page to call.
     */
    function page($name){          

        $this->callPage($name);

        $failures = '';
        foreach ( $this->failures as $failure ){
            $failures .= '<div class="error">'. $failure .'</div>';
        }

        $this->buffer = '<h1>'. $this->title .'</h1><div id="page" class="'. $name .'"><h2>'. $this->page_titles[$this->current_page] .'</h2><div id="main">' . $failures . $this->buffer . '</div>';

        $this->buffer .= '<div id="navigation">';

        //if no serious errors then show next//
        if ( $failures == '' ){
            if ( $this->pages[$this->current_page+1] == 'complete' ){
                $this->input('complete');
            } elseif ( $this->current_page < (count($this->pages)-1) ) {
                $this->input('next');
            }
        }

        if ($this->current_page > 0 && $name != 'complete'){
            $this->input('back');
        }

        $this->buffer .= '</div><div style="clear:both"> </div></div>';

        if (!empty($this->footer)){
            $this->buffer .= '<div id="footer">' . $this->footer . '</div>';
        }

        if ($name == 'complete'){
            $this->cleanup();
        }
    }

    /**
     * Incase of a page not found error.
     *
     * @param string $error Error message to add to the page.
     */
    function errorPage($error){
    	$this->title('Page Not Found');
    	$this->message($error);
    }

    /**
     * Default Installation Complete page. If the page_complete() method is not defined in the
     * derived class, this function is called.
     */
    function completePage(){
        $this->title('Installation Complete');
        $this->message('Please remove the installation file ( <strong>'. $_SERVER['SCRIPT_NAME'] .'</strong> ).');
    }

    /**
     * Makes a configuration file from the session variables without a double underscore (__) prefix.
     *
     * @param string $template Optional, String template to use to make the config file. Evaled.
     * @param string $header Optional, config header text to add.
     * @param string $saveTo Optional, Path to save file too.
     * @param array $exclusions Optional, array of vars to leave out of the config file.
     * @return mixed, string if no file provided, true if file save okay, false if file write error.
     */
    function makeConfig($template="\\\${\$name} = {\$value};\n",$header='',$saveTo=NULL,$exclusions=NULL){
        
        if ( !$exclusions ){
        	$exclusions = array();
        }
        
        $config = "<?php\n/* Configuration file generated by a PHP Installation System (PIS) */\n\n";
		$config .= $header;

        foreach ( $_SESSION as $name => $value ){
            if ( !preg_match('/^__/',$name) && !in_array($name,$exclusions) && $name != session_name()){
                if ( is_numeric($value) ){
                    $value = intval($value);
                } elseif ( is_string($value) && !preg_match("/true|false/",$value)){
                    $value = "'" . addslashes($value) . "'";
                } elseif ( is_bool($value) ){
                    $value = (($value)?'true':'false');
                }

                eval("\$config .= \"{$template}\";");
            }
        }

        $config .= "\n?".'>';

        if ( $saveTo ){
            $f = fopen($saveTo,'w');
            if ( fwrite($f,$config) === false ){
                return false;
            }
            fclose($f);
            return true;
        }

        return $config;
    }

    /**
     * Clear the buffer
     *
     * @return string Old buffer
     */
    function clear(){
        $old = $this->buffer;
        $this->buffer = '';
        return $old;
    }

    /**
     * A simple horizontal rule added to the page buffer
     */
    function hr(){
        $this->buffer .= '<hr />';
    }

    /**
     * Destroy the session and clean any sensitive stuff
     */
    function cleanup(){
        session_destroy();
    }

    /**
     * Clear current page and Goto another page
     *
     * @todo Migrate this to bypass()
     * @param string $page Page to goto when going "forward"
     * @param string $back Page to goto when going "back"
     */
    function goto($page,$back=NULL){
        $this->clear();
 
        if ( $back ){	
        	$page = ($this->direction?$page:$back);
        }
        
	    $this->current_page = array_search($page,$this->pages);
	    $this->callPage($page);
    }

    /**
     * Create a bypass across any number of installation steps
     *
     * @param string $back Page to goto when going "back"
     * @param string $next Page to goto when going "forward"
     */
    function bypass($back,$next){
        $this->goto($next,$back);
    }

    /**
     * Form input generation method, adds input to the buffer
     *
     * @param string $type Type of input to create [textarea|select|agreement|next|back|complete|checkbox|text|password|password]
     * @param string $id Optional, id of the input, used to identify throughout the installation process. And is
     *        what identifies the session variable, ie $this->get('name') will return the value at any point of the installation
     * @param string $extra Optional, array of extra parameters, basically anything that goes into a form input.
     *        label,value,cols,rows,help,required,wrap,size,file
     * @return boolean. Usually true
     */
    function input($type,$id=NULL,$extra=NULL){


        $extra['value'] = $this->get($id,$extra['value']);

        switch ($type){
            case 'textarea':
                $this->buffer .= '<div class="field" id="div/'. $id .'">';
                $this->buffer .= '<label for="'.$id.'"'. ($extra['required']?' class="required">*':'>') . ($extra['label']?$extra['label']:$id).':</label> <textarea id="'. $id .'" name="'. $id .'" cols="'. (isset($extra['cols'])?$extra['cols']:30) .'" rows="'. (isset($extra['cols'])?$extra['rows']:7) .'" wrap="'. (isset($extra['cols'])?$extra['wrap']:'soft') .'">'. $extra['value'] .'</textarea>';
            break;

            case 'select':
                $this->buffer .= '<div class="field" id="div/'. $id .'">';
                $this->buffer .= '<label for="'.$id.'"'. ($extra['required']?' class="required">*':'>') . ($extra['label']?$extra['label']:$id).':</label> <select name="'.$id.'" id="'.$id.'" >';
                foreach ( $extra['options'] as $key => $value){
                    $this->buffer .= '<option value="'.$value.'"'. ($value==$extra['value']?' selected="selected"':'') .'>'. (is_numeric($key)?$value:$key) .'</option>';
                }
                $this->buffer .= '</select>'; 
            break;

            case 'boolean':
                if ( $extra['value'] === true ){
                    $extra['value'] = 'true';
                }

                if ( $extra['value'] === false ){
                    $extra['value'] = 'false';
                }
                $this->buffer .= '<div class="field" id="div/'. $id .'">';
                $this->buffer .= '<label for="'.$id.'"'. ($extra['required']?' class="required">*':'>') . ($extra['label']?$extra['label']:$id).':</label> <select name="'.$id.'" id="'.$id.'" >';
                $options = array('false'=>'No','true'=>'Yes');
                foreach ( $options as $key => $value){
                    $this->buffer .= '<option value="'.$key.'"'. ($key==$extra['value']?' selected="selected"':'') .'>'.$value.'</option>';
                }
                $this->buffer .= '</select>'; 
            break;

            case 'agreement':
                if ( isset( $extra['file'] )){
                    $agreement = implode("",file($extra['file']));
                }
                $id = '__agreement';
                $this->buffer .= '<div class="field" id="div/'. $id .'"><pre>'. $agreement .'</pre>';
                $this->buffer .= '<input type="checkbox" '. (($extra['value']=='Y')?'checked="checked" ':'') .'value="Y" name="'.$id.'" id="'.$id.'" /><strong> I agree to the license terms above.</strong>'; 
            break;

            case 'next':
            case 'back':
            case 'complete':
                $this->buffer .= '<input type="submit" name="__submit" value="'. ucfirst($type) .'" />';
                return true;
            break;

            case 'checkbox':
                $this->buffer .= 'This input is not working yet. <div class="field" id="div/'. $id .'">';
                $this->buffer .= '<label for="'.$id.'"'. ($extra['required']?' class="required">*':'>') . 
                    (isset($extra['label'])?$extra['label']:$id).':</label> <input type="checkbox" '. 
                    ($extra['value'] == 'true'?'checked ':'') .
                    'value="Y" name="__checkbox_fix_'.$id.'" id="'.$id.'" onClick="setTimeout(\'document.forms[0].elements.'.$id.'.value = (document.forms[0].elements.__checkbox_fix_'.$id.'.checked?\\\'true\\\':\\\'false\\\'); alert(document.forms[0].elements.'.$id.'.value);\',100)" /> <input type="hidden" value="'. ($extra['value'] == 'true'?'true':'false') .'" name="'.$id.'" />'; 
            break;

            case 'text':
            case 'radio':
            case 'password':
            case 'hidden':
            case 'button':
                $this->buffer .= '<div class="field" id="div/'. $id .'">';
                $this->buffer .= '<label for="'.$id.'"'. ($extra['required']?' class="required">*':'>') . (isset($extra['label'])?$extra['label']:$id).':</label> <input type="'.$type.'" '. ($type=='checkbox' && !empty($extra['value'])?'checked ':'') .'value="'.(isset($extra['value'])?$extra['value']:'').'" name="'.$id.'" id="'.$id.'" size="'.(!empty($extra['size'])?$extra['size']:35).'" />'; 
            break;

            default:
                if ( method_exists($this,'input_' . $type) ){
                    eval("\$this->input_{$type}(\$id,\$extra);");
                }
                return;
            break;
        }

        if ( $extra['required'] ){
            $this->buffer .= '<input type="hidden" id="__required_'. $id .'" name="__required_'. $id .'" value="'. $id .'" />';
        }
                      
        if ( !empty($extra['help']) ){
            $this->buffer .= '<div class="help"><a href="javascript:alert(\''. addslashes($extra['help']) .'\');">?</a></div> ';
        }

        if ( !empty($this->errors[$id]) ){
            $this->buffer .= '<div class="error">'. $this->errors[$id] .'</div> ';
        }

        $this->buffer .= '<div style="clear:both"> </div></div>';
        return true;
    }

    /**
     * Import SQL File, this will open an sql dump file and execute it on the provided connection
     *
     * @param string $src Path to the sql file to import
     * @param resource $conn Mysql Database connection handle.
     * @param mixed $patterns Array or string of RegExp patterns to look for in the sql dump.
     * @param mixed $replaces Array or string of RegExp replacments to use with the patterns above.
     * @return boolean True on success, False on failure
     */
    function importSQLFile($src,$conn,$patterns=NULL,$replaces=NULL){

        $file_content = file($src);
        $query = "";
        foreach ($file_content as $sql_line) {
            $tsl = trim($sql_line);
            if (($tsl != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
                $query .= $sql_line;
                if(preg_match('/;\s*$/', $sql_line)) {
                    if ( $patterns != NULL && $replaces != NULL){
                        $sql = preg_replace($patterns,$replaces,$query);
                    }
                    $result = mysql_query($sql,$conn);
                if (!$result && !$ignoreerrors) 
                    return false;
                    $query = "";               
                }
            }
        }
        return true;
    }

    /**
     * Run method which runs the installation process.
     *
     * @param boolean $noecho Optional, toggle if page is auto echoed. This is to open up for future ajax calls.
     * @return mixed
     */
    function run($noecho=false){

        if ( isset($_POST['__page']) ){
            $this->current_page = intval($_POST['__page']);
        } else {
            $this->current_page = 0;
        }

        //process a submit//
        if ( !empty($_POST['__submit']) ){            

            //save any posted vars//
            foreach ($_POST as $key => $value){
                //check for required fields//
                if ( preg_match('/^__required_/',$key) ){
                    if ( empty($_POST[$value]) ){
                        $this->errors[$value] = 'This is a required field!';
                    }
                } else {
                    $this->set($key,$value);
                }
            }            

            if ( ($_POST['__submit'] == 'Next' || $_POST['__submit'] == 'Complete') && count($this->errors) <= 0 ){
                $this->current_page++;
                $this->direction = true;
            }

            if ( $_POST['__submit'] == 'Back'){
                $this->current_page--;                            	
                $this->direction = false;
            }
        }

		//call the page function//
        $this->page($this->pages[$this->current_page]);
        $page = '<form action="" method="post"><input type="hidden" value="'. $this->current_page .'" name="__page" />' . $this->buffer . '</form>';

        if ( $noecho ){
            return $page;
        } else {
            echo $page;
        }
    }
}


?>