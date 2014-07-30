<?php require 'pis.php'; ?>
<?php

class MyInstaller extends PIS {
	
  function page_welcome(){		

    $this->message('Welcome to my installer. This is an '.
        'example of using the PIS class to extend a custom '.
        'class for installing a php script.');

    $this->message('The PHP Installation System provides '.
        'a flexable framework by seperating each installation '.
        'page into a class method. This allows the author to '.
        'stick PHP code into each page and not be restricted '.
        'by some additional, dumbed-down scripting language.');

    $this->message('The parent class defines methods which '.
        'make page interface creation easier such as error or '.
        'informational messages, system tests, input fields, '.
        'SQL Dump installer and more.');

    $this->message('For more information about using the'.
        'abstract PIS class, check out '.
        '<a href="http://bluelinecity.com/pis">http://bluelinecity.com/pis</a>.');

    $this->message('The next couple of pages will guide you '.
      'through the installation process.');

  }

  function page_license(){
     $this->input('agreement','agreement',array('required'=>true,'file'=>'mit.txt'));
  }

  function page_test(){
    
    $this->title("Requirements Check");

    if ( $this->test(true,'config file is writable') ){
        $this->message('Config file writable.','passed');
    }

    if ( $this->test(true,'PHP Version is 4') ){
        $this->message('PHP Version > 4','passed');
    }

    if ( $this->test(true,'config file is writable') ){
        $this->message('mcrypt extension installed.','passed');
    }


  }

  function page_config(){
    
    $this->title("Configuration Options");

    $this->input('text','db_host',array(
	   'required' => true,
	   'label' => 'Database Host',
       'size' => 25,
	   'help' => 'Commonly localhost.',
	   ));

    $this->input('text','db_user',array(
	   'required' => true,
	   'label' => 'Database Username',
       'size' => 25,
	   'help' => 'Your username password.',
	   ));

    $this->input('text','db_pass',array(
	   'required' => true,
	   'label' => 'Database Password',
       'size' => 25,
	   'help' => 'Your database password.',
	   ));

    $this->input('text','db_name',array(
	   'required' => true,
	   'label' => 'Database Name',
       'size' => 25,
	   'help' => 'Database name to created tables in.',
	   ));
  }


  function page_installation(){
    
    if ( $this->test(($this->get('db_host') == 'localhost'),'Database host must be localhost') ){
        $this->message('Copy the below text and put into your config.php file.<pre>'. htmlspecialchars($this->makeConfig()) .'</pre>');
    }


  }

}

$installer = new MyInstaller('My Installer');

?><?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $installer->title; ?></title>
<script type="text/javascript" src="launch-install/prototype.js"></script>
<script type="text/javascript" src="launch-install/rico.js"></script>
<style type="text/css">

/* launch style */

body {
	font-family: Arial; 
	font-size: 0.8em; 
	color: #E46D1D; 
	background-color: #CCAA00;
}

#page {
	background-color: #FCFD01; 
	background-image: url('launch-install/background.png'); 
	background-repeat: repeat-x;
	width: 550px;
	text-align: left;
	padding: 5px;
}

#main {
	padding: 10px;
}


#footer {
	border-top: 1px solid;
	width: 550px;
	padding: 5px;
	text-align: left;
	font-size: 0.8em;
    background-color: #ECCA00;
}

input, textarea, select { 
    border: 1px solid black; 
    float: left; 
    
}

#navigation {
	text-align: right;
	font-family: verdana;
}

#navigation input {
    margin-left: 10px;
    float: right;
}

h1 { 
    width: 550px;
    text-align: left;
    margin: 50px 0px 0px 0px;
	background-image: url('launch-install/background.png'); 
	padding: 5px;
    font-family: verdana;
}

h1:before {
    content: url('launch-install/logo32.png'); 
    vertical-align: middle;
    margin-right: 10px;
}

h2 {
	border-bottom: 1px solid;
	line-height: 0.8em;
	font-family: tahoma;
}


label {width: 150px; float: left; text-align: right; margin-right: 5px; font-family: verdana; }
label.required {font-weight: bold; font-style: italic;}


hr {border:1px solid red;}

.message { margin-bottom: 10px;}

.error:before {
    content: url('launch-install/button_cancel.png'); 
    vertical-align: middle;
    margin-right: 5px;
}

.error,
.field .error { color: red; font-weight: bold; font-size: 0.8em;}
.field .error { display: inline; margin-left: 10px; background-color: red; color: white; padding: 0px 5px 0px 5px;}
.field .error:before { content: ''; }

.passed {color: green; font-size: 0.8em;}
.passed:before {
    content: url('launch-install/button_ok.png'); 
    vertical-align: middle;
    margin-right: 5px;
}

.field {margin-top: 10px;}
.help {padding: 2px; float: left; font-weight: bold; margin-left: 10px; border: 1px solid black; line-height: 10px; font-family: courier; background-color: #f0f0f0;}
.help:hover {background-color: red;}
.help a {text-decoration: none;  color: black;}
.help a:hover {background-color: red;}

</style>
</head>
<body>
<div align="center">

<?php  $installer->run(); ?>

</div>
</body>
</html>