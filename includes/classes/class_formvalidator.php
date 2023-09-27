<?PHP
/*
  -------------------------------------------------------------------------
	PHP Form Validator (formvalidator.php)  Version 1.0
	Developed and upgraded by Kaustav Ghosh
  -------------------------------------------------------------------------  
*/

/** Default error messages*/
define("E_VAL_REQUIRED_VALUE","Please enter the value for %s");
define("E_VAL_MAXLEN_EXCEEDED","Maximum length(%d) exceeded for %s.");
define("E_VAL_MINLEN_CHECK_FAILED","Please enter input with length more than %d for %s");
define("E_VAL_ALNUM_CHECK_FAILED","Please provide an alpha-numeric input for %s");
define("E_VAL_ALNUM_S_CHECK_FAILED","Please provide an alpha-numeric input for %s (space allow)");
define("E_VAL_NUM_CHECK_FAILED","Please provide numeric input for %s");
define("E_VAL_FLOAT_CHECK_FAILED","Please provide numeric or float input for %s");
define("E_VAL_ALPHA_CHECK_FAILED","Please provide alphabetic input for %s");
define("E_VAL_ALPHA_S_CHECK_FAILED","Please provide alphabetic input for %s (space allow)");
define("E_VAL_EMAIL_CHECK_FAILED","Please provide a valid email address for %s");
define("E_VAL_LESSTHAN_CHECK_FAILED","Enter a value less than %f for %s");
define("E_VAL_GREATERTHAN_CHECK_FAILED","Enter a value greater than %f for %s");
define("E_VAL_REGEXP_CHECK_FAILED","Please provide a valid input for %s");
define("E_VAL_DONTSEL_CHECK_FAILED","Wrong option selected for %s");
define("E_VAL_SELMIN_CHECK_FAILED","You have to select minimum %d options for %s");
define("E_VAL_SELMAX_CHECK_FAILED","You may select maximum %d options for %s");
define("E_VAL_SELMAXMIN_CHECK_FAILED","You must select in between minimum %d and maximum %d options for %s");
define("E_VAL_SELONE_CHECK_FAILED","Please select an option for %s");
define("E_VAL_EQELMNT_CHECK_FAILED","Value of %s should be same as that of %s");
define("E_VAL_NEELMNT_CHECK_FAILED","Value of %s should not be same as that of %s");
define("E_VAL_DATEFORMAT_CHECK_FAILED","Invalid date format for %s");
define("E_VAL_SHOULD_SEL_CHECK_FAILED","You should check %s");
define("E_VAL_URL_CHECK_FAILED","Please provide a valid URL for %s");

define("E_VAL_CCTYPE_CHECK_FAILED","Unknown card type for %s");
define("E_VAL_CCNO_REQUIRED","No card number provided for %s");
define("E_VAL_CCNO_FORMAT_CHECK_FAILED","Credit card number has invalid format for %s");
define("E_VAL_CCNO_CHECK_FAILED","Credit card number is invalid for %s");
define("E_VAL_CCNO_LENGTH_CHECK_FAILED","Credit card number is wrong length for %s");



/**
* Carries information about each of the form validations
*/
class ValidatorObj
{
	var $variable_name;
	var $validator_string;
	var $error_string;
	var $field_label;
}

/**
* Base class for custom validation objects
**/
class CustomValidator 
{
	function DoValidate(&$formars,&$error_hash)
	{
		return true;
	}
}

/**
* FormValidator: The main class that does all the form validations
**/
class FormValidator 
{
	var $validator_array;
    var $error_hash;
	var $custom_validators;
	var $date_format;
	
	function __construct()
	{
		$this->validator_array = array();
        $this->error_hash = array();
		$this->custom_validators=array();
		$this->date_format="mm-dd-yyyy";
	}
	function setDateFormat($format)
	{
		$this->date_format=$format;
	}
	
	function AddCustomValidator(&$customv)
	{
		array_push($this->custom_validators,$customv);
	}

	function addValidation($variable,$validator="",$label="",$error="")
	{
		if(empty($validator))
		{
			$validator="req";
		}
		$validator_obj = new ValidatorObj();
		$validator_obj->variable_name = $variable;
		$validator_obj->validator_string = $validator;
		$validator_obj->error_string = $error;
		$validator_obj->field_label=$label;
		array_push($this->validator_array,$validator_obj);
	}
    function GetErrors()
    {
        return $this->error_hash;
    }

	function ValidateForm()
	{
		$bret = true;

		$error_string="";
		$error_to_display = "";

        
		if(strcmp($_SERVER['REQUEST_METHOD'],'POST')==0)
		{
			$form_variables = $_POST;
		}
		else
		{
			$form_variables = $_GET;
		}

        $vcount = count($this->validator_array);
        

		foreach($this->validator_array as $val_obj)
		{
			if(!$this->ValidateObject($val_obj,$form_variables,$error_string))
			{
				$bret = false;
                $this->error_hash[$val_obj->variable_name] = $error_string;
			}
		}

		if(count($this->custom_validators) > 0)
		{
			foreach( $this->custom_validators as $custom_val)
			{
				if(false == $custom_val->DoValidate($form_variables,$this->error_hash))
				{
					$bret = false;
				}
			}
		}
		return $bret;
	}


	function ValidateObject($validatorobj,$formvariables,&$error_string)
	{
		$bret = true;

		$splitted = explode("=",$validatorobj->validator_string);
		$command = $splitted[0];
		$command_value = '';

		if(isset($splitted[1]) && strlen($splitted[1])>0)
		{
			$command_value = $splitted[1];
		}

		$default_error_message="";
		
		$input_value ="";

		if(isset($formvariables[$validatorobj->variable_name]))
		{
		 $input_value = $formvariables[$validatorobj->variable_name];
		}

		$bret = $this->ValidateCommand($command,$command_value,$input_value,$default_error_message,$validatorobj->variable_name,$formvariables,$validatorobj->field_label);

		
		if(false == $bret)
		{
			if(isset($validatorobj->error_string) &&
				strlen($validatorobj->error_string)>0)
			{
				$error_string = $validatorobj->error_string;
			}
			else
			{
				$error_string = $default_error_message;
			}

		}//if
		return $bret;
	}
    	
	function validate_req($input_value, &$default_error_message,$variable_name)
	{
	  $bret = true;
      	if(!isset($input_value) ||
			strlen($input_value) <=0)
		{
			$bret=false;
			$default_error_message = sprintf(E_VAL_REQUIRED_VALUE,$variable_name);
		}	
	  return $bret;	
	}

	function validate_maxlen($input_value,$max_len,$variable_name,&$default_error_message)
	{
		$bret = true;
		if(isset($input_value) )
		{
			$input_length = strlen($input_value);
			if($input_length > $max_len)
			{
				$bret=false;
				$default_error_message = sprintf(E_VAL_MAXLEN_EXCEEDED,$max_len,$variable_name);
			}
		}
		return $bret;
	}

	function validate_minlen($input_value,$min_len,$variable_name,&$default_error_message)
	{
		$bret = true;
		if(isset($input_value) )
		{
			$input_length = strlen($input_value);
			if($input_length < $min_len)
			{
				$bret=false;
				$default_error_message = sprintf(E_VAL_MINLEN_CHECK_FAILED,$min_len,$variable_name);
			}
		}
		return $bret;
	}

	function test_datatype($input_value,$reg_exp)
	{
		if(ereg($reg_exp,$input_value))
		{
			return false;
		}
		return true;
	}

	function validate_email($email) 
	{
		return eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $email);
	}

	function validate_for_numeric_input($input_value,&$validation_success)
	{
		
		$more_validations=true;
		$validation_success = true;
		if(strlen($input_value)>0)
		{
			
			if(false == is_numeric($input_value))
			{
				$validation_success = false;
				$more_validations=false;
			}
		}
		else
		{
			$more_validations=false;
		}
		return $more_validations;
	}

	function validate_lessthan($command_value,$input_value,$variable_name,&$default_error_message)
	{
		$bret = true;
		if(false == $this->validate_for_numeric_input($input_value,$bret))
		{
			return $bret;
		}
		if($bret)
		{
			$lessthan = doubleval($command_value);
			$float_inputval = doubleval($input_value);
			if($float_inputval >= $lessthan)
			{
				$default_error_message = sprintf(E_VAL_LESSTHAN_CHECK_FAILED,$lessthan,$variable_name);
				$bret = false;
			}//if
		}
		return $bret ;
	}

	function validate_greaterthan($command_value,$input_value,$variable_name,&$default_error_message)
	{
		$bret = true;
		if(false == $this->validate_for_numeric_input($input_value,$bret))
		{
			return $bret;
		}
		if($bret)
		{
			$greaterthan = doubleval($command_value);
			$float_inputval = doubleval($input_value);
			if($float_inputval <= $greaterthan)
			{
				$default_error_message = sprintf(E_VAL_GREATERTHAN_CHECK_FAILED,$greaterthan,$variable_name);
				$bret = false;
			}//if
		}
		return $bret ;
	}

	function validate_lessthaneq($command_value,$input_value,$variable_name,&$default_error_message)
	{
		$bret = true;
		if(false == $this->validate_for_numeric_input($input_value,$bret))
		{
			return $bret;
		}
		if($bret)
		{
			$lessthan = doubleval($command_value);
			$float_inputval = doubleval($input_value);
			if($float_inputval > $lessthan)
			{
				$default_error_message = sprintf(E_VAL_LESSTHAN_CHECK_FAILED,$lessthan,$variable_name);
				$bret = false;
			}//if
		}
		return $bret ;
	}

	function validate_greaterthaneq($command_value,$input_value,$variable_name,&$default_error_message)
	{
		$bret = true;
		if(false == $this->validate_for_numeric_input($input_value,$bret))
		{
			return $bret;
		}
		if($bret)
		{
			$greaterthan = doubleval($command_value);
			$float_inputval = doubleval($input_value);
			if($float_inputval < $greaterthan)
			{
				$default_error_message = sprintf(E_VAL_GREATERTHAN_CHECK_FAILED,$greaterthan,$variable_name);
				$bret = false;
			}//if
		}
		return $bret ;
	}

    function validate_select($input_value,$command_value,&$default_error_message,$variable_name)
    {
	    $bret=false;
		if(is_array($input_value))
		{
			foreach($input_value as $value)
			{
				if($value == $command_value)
				{
					$bret=true;
					break;
				}
			}
		}
		else
		{
			if($command_value == $input_value)
			{
				$bret=true;
			}
		}
        if(false == $bret)
        {
            $default_error_message = sprintf(E_VAL_SHOULD_SEL_CHECK_FAILED,$variable_name,$command_value);
        }
	    return $bret;
    }

	function validate_dontselect($input_value,$command_value,&$default_error_message,$variable_name)
	{
	   $bret=true;
		if(is_array($input_value))
		{
			foreach($input_value as $value)
			{
				if($value == $command_value)
				{
					$bret=false;
					$default_error_message = sprintf(E_VAL_DONTSEL_CHECK_FAILED,$variable_name);
					break;
				}
			}
		}
		else
		{
			if($command_value == $input_value)
			{
				$bret=false;
				$default_error_message = sprintf(E_VAL_DONTSEL_CHECK_FAILED,$variable_name);
			}
		}
	  return $bret;
	}
	//check if field is a date by specified format
	//acceptable separators are "/" "." "-" 
	//acceptable formats use "m" for month, "d" for day, "y" for year
	function validate_date($input_value) 
	{
	  	$bret = true;
		$month = false;
		$day = false;
		$year = false;
		$monthPos = null;
		$dayPos = null;
		$yearPos = null;
		$monthNum = null;
		$dayNum = null;
		$yearNum = null;
		$separator = null;
		$separatorCount = null;
		$fieldSeparatorCount = null;
		$formatArray = array();
		$dateArray = array();
		$format=$this->date_format;

		//determine the separator
		if(strstr($format, "-") && substr_count($format, '-')==2) {
			$separator = "-";
			$bret = true;
		} elseif (strstr($format, ".")&& substr_count($format, '.')==2) {
			$separator = ".";
			$bret = true;
		} elseif (strstr($format, "/")&& substr_count($format, '/')==2) {
			$separator = "/";
			$bret = true;
		}	else {
			$bret = false;
		}
		
		if($bret){
			//determine the number of separators in $format and $field
			$separatorCount = substr_count($format, $separator);
			$fieldSeparatorCount = substr_count($input_value, $separator);
			
			//if number of separators in $format and $field don't match return false
			if(!strstr($input_value, $separator) || $fieldSeparatorCount != $separatorCount) {
				$bret = false;
			} 
		}
		
		if($bret) {
			//explode $format into $formatArray and get the index of the day, month, and year
			//then get the number of occurances of either m, d, or y
			$formatArray = explode($separator, $format);
			for($i = 0; $i < sizeof($formatArray); $i++) {
				if(strstr($formatArray[$i], "m")) {
					$monthPos = $i;
					$monthNum = substr_count($formatArray[$i], "m");
					$monthval=$formatArray[$i];
				} elseif (strstr($formatArray[$i], "d")) {
					$dayPos = $i;
					$dayNum = substr_count($formatArray[$i], "d");
					$dayval=$formatArray[$i];
				} elseif (strstr($formatArray[$i], "y")) {
					$yearPos = $i;
					$yearNum = substr_count($formatArray[$i], "y");
					$yearval=$formatArray[$i];
				} else {
					$bret = false;
				}
			}
			
			//set whether $format uses day, month, year
			if($monthNum) {
				$month = true;
			} else {
				$month = false;
			}
			if($dayNum) {
				$day = true;
			} else {
				$day = false;
			}
			if($yearNum) {
				$year = true;
			} else {
				$year = false;
			}
			
			//explode date field into $dateArray
			//check if the monthNum, dayNum, and yearNum match appropriately to the $dateArray
			$dateArray = explode($separator, $input_value);
			if($month) {
				if(!ereg("^[0-9]{".$monthNum."}$", $dateArray[$monthPos]) || $dateArray[$monthPos] > 12) {
					$bret = false;
				}
			}
			if($day) {
				if(!ereg("^[0-9]{".$dayNum."}$", $dateArray[$dayPos]) || $dateArray[$dayPos] > 31) {
					$bret = false;
				}
			}
			if($year) {
				if (!ereg("^[0-9]{".$yearNum."}$", $dateArray[$yearPos])) {
					$bret = false;
				}
			}
		}
		if($bret)
		{
			if(checkdate($dateArray[$monthPos],$dateArray[$dayPos],$dateArray[$yearPos]))
			{
				$bret = true;
			}
			else
			{
				$bret = false;
			}
		}
		return $bret;
	}
	
	function validate_url($url) 
	{
		$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
		return eregi($urlregex, $url);
	}
	
	function validate_CreditCard ($cardname,$cardnumber ,&$default_error_message,$field_label) {
	
	  // Define the cards we support. You may add additional card types.
	  
	  //  Name:      As in the selection box of the form - must be same as user's
	  //  Length:    List of possible valid lengths of the card number for the card
	  //  prefixes:  List of possible prefixes for the card
	  //  checkdigit Boolean to say whether there is a check digit
	  
	  // Don't forget - all but the last array definition needs a comma separator!
	  
	  $cards = array (  array ('name' => 'American Express', 
							  'length' => '15', 
							  'prefixes' => '34,37',
							  'checkdigit' => true
							 ),
					   array ('name' => 'Carte Blanche', 
							  'length' => '14', 
							  'prefixes' => '300,301,302,303,304,305,36,38',
							  'checkdigit' => true
							 ),
					   array ('name' => 'Diners Club', 
							  'length' => '14',
							  'prefixes' => '300,301,302,303,304,305,36,38',
							  'checkdigit' => true
							 ),
					   array ('name' => 'Discover', 
							  'length' => '16', 
							  'prefixes' => '6011',
							  'checkdigit' => true
							 ),
					   array ('name' => 'Enroute', 
							  'length' => '15', 
							  'prefixes' => '2014,2149',
							  'checkdigit' => true
							 ),
					   array ('name' => 'JCB', 
							  'length' => '15,16', 
							  'prefixes' => '3,1800,2131',
							  'checkdigit' => true
							 ),
					   array ('name' => 'Maestro', 
							  'length' => '12,13,14,15,16,18', 
							  'prefixes' => '5018,5020,5038,6304,6759,6761',
							  'checkdigit' => true
							 ),
					   array ('name' => 'MasterCard', 
							  'length' => '16', 
							  'prefixes' => '51,52,53,54,55',
							  'checkdigit' => true
							 ),
					   array ('name' => 'Solo', 
							  'length' => '16,18,19', 
							  'prefixes' => '6334,6767',
							  'checkdigit' => true
							 ),
					   array ('name' => 'Switch', 
							  'length' => '16,18,19', 
							  'prefixes' => '4903,4905,4911,4936,564182,633110,6333,6759',
							  'checkdigit' => true
							 ),
					   array ('name' => 'Visa', 
							  'length' => '13,16', 
							  'prefixes' => '4',
							  'checkdigit' => true
							 ),
					   array ('name' => 'Visa Electron', 
							  'length' => '16', 
							  'prefixes' => '417500,4917,4913',
							  'checkdigit' => true
							 )
					);
	
	  $ccErrorNo = 0;
	
	  $ccErrors [0] = E_VAL_CCTYPE_CHECK_FAILED;
	  $ccErrors [1] = E_VAL_CCNO_REQUIRED;
	  $ccErrors [2] = E_VAL_CCNO_FORMAT_CHECK_FAILED;
	  $ccErrors [3] = E_VAL_CCNO_CHECK_FAILED;
	  $ccErrors [4] = E_VAL_CCNO_LENGTH_CHECK_FAILED;
				   
	  // Establish card type
	  $cardType = -1;
	  for ($i=0; $i<sizeof($cards); $i++) {
	
		// See if it is this card (ignoring the case of the string)
		if (strtolower($cardname) == strtolower($cards[$i]['name'])) {
		  $cardType = $i;
		  break;
		}
	  }
	  
	  // If card type not found, report an error
	  if ($cardType == -1) {
		 $errornumber = 0;     
		 $default_error_message = sprintf($ccErrors [$errornumber],$field_label);
		 return false; 
	  }
	   
	  // Ensure that the user has provided a credit card number
	  if (strlen($cardnumber) == 0)  {
		 $errornumber = 1;     
		 $default_error_message = sprintf($ccErrors [$errornumber],$field_label);
		 return false; 
	  }
	  
	  // Remove any spaces from the credit card number
	  $cardNo = str_replace (' ', '', $cardnumber);  
	   
	  // Check that the number is numeric and of the right sort of length.
	  if (!eregi('^[0-9]{13,19}$',$cardNo))  {
		 $errornumber = 2;     
		 $default_error_message = sprintf($ccErrors [$errornumber],$field_label);
		 return false; 
	  }
		   
	  // Now check the modulus 10 check digit - if required
	  if ($cards[$cardType]['checkdigit']) {
		$checksum = 0;                                  // running checksum total
		$mychar = "";                                   // next char to process
		$j = 1;                                         // takes value of 1 or 2
	  
		// Process each digit one by one starting at the right
		for ($i = strlen($cardNo) - 1; $i >= 0; $i--) {
		
		  // Extract the next digit and multiply by 1 or 2 on alternative digits.      
		  $calc = $cardNo{$i} * $j;
		
		  // If the result is in two digits add 1 to the checksum total
		  if ($calc > 9) {
			$checksum = $checksum + 1;
			$calc = $calc - 10;
		  }
		
		  // Add the units element to the checksum total
		  $checksum = $checksum + $calc;
		
		  // Switch the value of j
		  if ($j ==1) {$j = 2;} else {$j = 1;};
		} 
	  
		// All done - if checksum is divisible by 10, it is a valid modulus 10.
		// If not, report an error.
		if ($checksum % 10 != 0) {
		 $errornumber = 3;     
		 $default_error_message =sprintf($ccErrors [$errornumber],$field_label);
		 return false; 
		}
	  }  
	
	  // The following are the card-specific checks we undertake.
	
	  // Load an array with the valid prefixes for this card
	  $prefix = split(',',$cards[$cardType]['prefixes']);
		  
	  // Now see if any of them match what we have in the card number  
	  $PrefixValid = false; 
	  for ($i=0; $i<sizeof($prefix); $i++) {
		$exp = '^' . $prefix[$i];
		if (ereg($exp,$cardNo)) {
		  $PrefixValid = true;
		  break;
		}
	  }
		  
	  // If it isn't a valid prefix there's no point at looking at the length
	  if (!$PrefixValid) {
		 $errornumber = 3;     
		 $default_error_message = sprintf($ccErrors [$errornumber],$field_label);
		 return false; 
	  }
		
	  // See if the length is valid for this card
	  $LengthValid = false;
	  $lengths = split(',',$cards[$cardType]['length']);
	  for ($j=0; $j<sizeof($lengths); $j++) {
		if (strlen($cardNo) == $lengths[$j]) {
		  $LengthValid = true;
		  break;
		}
	  }
	  
	  // See if all is OK by seeing if the length was valid. 
	  if (!$LengthValid) {
		 $errornumber = 4;     
		 $default_error_message = sprintf($ccErrors [$errornumber],$field_label);
		 return false; 
	  };   
	  
	  // The credit card is in the required format.
	  return true;
	}
	
	function ValidateCommand($command,$command_value,$input_value,&$default_error_message,$variable_name,$formvariables,$field_label)
	{
		$bret=true;
		if(empty($field_label))
		$field_label=$variable_name;
		switch($command)
		{
			case 'req':
						{
							$bret = $this->validate_req($input_value, $default_error_message,$field_label);
							break;
						}

			case 'maxlen':
						{
							$max_len = intval($command_value);
							$bret = $this->validate_maxlen($input_value,$max_len,$field_label,$default_error_message);
							break;
						}

			case 'minlen':
						{
							$min_len = intval($command_value);
							$bret = $this->validate_minlen($input_value,$min_len,$field_label,$default_error_message);
							break;
						}

			case 'alnum':
						{
							$bret= $this->test_datatype($input_value,"[^A-Za-z0-9]");
							if(false == $bret)
							{
								$default_error_message = sprintf(E_VAL_ALNUM_CHECK_FAILED,$field_label);
							}
							break;
						}

			case 'alnum_s':
						{
							$bret= $this->test_datatype($input_value,"[^A-Za-z0-9 ]");
							if(false == $bret)
							{
								$default_error_message = sprintf(E_VAL_ALNUM_S_CHECK_FAILED,$field_label);
							}
							break;
						}

			case 'num':
            case 'numeric':
						{
							$bret= $this->test_datatype($input_value,"[^0-9]");
							if(false == $bret)
							{
								$default_error_message = sprintf(E_VAL_NUM_CHECK_FAILED,$field_label);
							}
							break;							
						}

            case 'float':
						{
							$bret= $this->test_datatype($input_value,"[^0-9.0-9]");
							if(false == $bret)
							{
								$default_error_message = sprintf(E_VAL_FLOAT_CHECK_FAILED,$field_label);
							}
							break;							
						}

			case 'alpha':
						{
							$bret= $this->test_datatype($input_value,"[^A-Za-z]");
							if(false == $bret)
							{
								$default_error_message = sprintf(E_VAL_ALPHA_CHECK_FAILED,$field_label);
							}
							break;
						}
			case 'alpha_s':
						{
							$bret= $this->test_datatype($input_value,"[^A-Za-z ]");
							if(false == $bret)
							{
								$default_error_message = sprintf(E_VAL_ALPHA_S_CHECK_FAILED,$field_label);
							}
							break;
						}
			case 'email':
						{
							if(isset($input_value) && strlen($input_value)>0)
							{
								$bret= $this->validate_email($input_value);
								if(false == $bret)
								{
									$default_error_message = sprintf(E_VAL_EMAIL_CHECK_FAILED,$field_label);
								}
							}
							break;
						}
			case "lt": 
			case "lessthan": 
						{
							$bret = $this->validate_lessthan($command_value,$input_value,$field_label,$default_error_message);
							break;
						}
			case "gt": 
			case "greaterthan": 
						{
							$bret = $this->validate_greaterthan($command_value,$input_value,$field_label,$default_error_message);
							break;
						}

			case "lteq": 
			case "lessthaneq": 
						{
							$bret = $this->validate_lessthaneq($command_value,$input_value,$field_label,$default_error_message);
							break;
						}
			case "gteq": 
			case "greaterthaneq": 
						{
							$bret = $this->validate_greaterthaneq($command_value,$input_value,$field_label,$default_error_message);
							break;
						}

			case "regexp":
						{
							if(isset($input_value) && strlen($input_value)>0)
							{
								if(!preg_match("$command_value",$input_value))
								{
									$bret=false;
									$default_error_message = sprintf(E_VAL_REGEXP_CHECK_FAILED,$field_label);
								}
							}
							break;
						}
		  case "dontselect": 
		  case "dontselectchk":
          case "dontselectradio":
						{
							//echo $input_value.'||'.$command_value.'||'.$default_error_message.'||'.$field_label;
							$bret = $this->validate_dontselect($input_value,$command_value,$default_error_message,$field_label);
							break;
						}//case

          case "shouldselchk":
          case "selectradio":
                      {
							$bret = $this->validate_select($input_value,$command_value,$default_error_message,$field_label);
                            break;
                      }//case
		  case "selmin":
						{
							$min_count = intval($command_value);

							if(isset($input_value) && is_array($input_value))
                            {
							    if($min_count > 0)
							    {
							        $bret = (count($input_value) >= $min_count )?true:false;
									if($bret==false)
									{
										$default_error_message = sprintf(E_VAL_SELMIN_CHECK_FAILED,$min_count,$field_label);
									}
							    }
                                else
                                {
                                  $bret = true;
                                }
                            }
							else
							{
								$bret= false;
								$default_error_message = sprintf(E_VAL_SELMIN_CHECK_FAILED,$min_count,$field_label);
							}

							break;
						}//case
		  case "selmax":
						{
							$min_count = intval($command_value);

							if(isset($input_value) && is_array($input_value))
                            {
							    if($min_count > 0)
							    {
							        $bret = (count($input_value) >= $min_count )?false:true;
									if($bret==false)
									{
										$default_error_message = sprintf(E_VAL_SELMAX_CHECK_FAILED,$min_count,$field_label);
									}
							    }
                                else
                                {
                                  $bret = true;
                                }
                            }
							else
							{
								$bret= false;
								$default_error_message = sprintf(E_VAL_SELMAX_CHECK_FAILED,$min_count,$field_label);
							}

							break;
						}//case
		  case "selbetween":
						{
							$valarr=explode("*",$command_value);
							$min_count = intval($valarr[0]);
							$max_count = intval($valarr[1]);
							if($max_count<$min_count)
							{
								$bret= false;
								$default_error_message ="Max should be greater than Min";
								break;
							}

							if(isset($input_value) && is_array($input_value))
                            {
							    if($min_count > 0 && $max_count>0)
							    {
							        $bret = (count($input_value) >= $min_count && count($input_value) <= $max_count)?true:false;
									if($bret==false)
									{
										$default_error_message = sprintf(E_VAL_SELMAXMIN_CHECK_FAILED,$min_count,$max_count,$field_label);
									}
							    }
                                else
                                {
                                  $bret = true;
                                }
                            }
							else
							{
								$bret= false;
								$default_error_message = sprintf(E_VAL_SELMAXMIN_CHECK_FAILED,$min_count,$max_count,$field_label);
							}

							break;
						}//case
		 case "eqelmnt":
						{

							if(isset($formvariables[$command_value]) && strcmp($input_value,$formvariables[$command_value])==0 )
							{
								$bret=true;
							}
							else
							{
								$bret= false;
								$default_error_message = sprintf(E_VAL_EQELMNT_CHECK_FAILED,$field_label,$command_value);
							}
						break;
						}
		  case "neelmnt":
						{
							if(isset($formvariables[$command_value]) && strcmp($input_value,$formvariables[$command_value]) !=0 )
							{
								$bret=true;
							}
							else
							{
								$bret= false;
								$default_error_message = sprintf(E_VAL_NEELMNT_CHECK_FAILED,$field_label,$command_value);
							}
							break;
						}
			case 'datestr':
						{
							$bret= $this->validate_date($input_value);
							if(false == $bret)
							{
								$default_error_message = sprintf(E_VAL_DATEFORMAT_CHECK_FAILED,$field_label);
							}
							break;
						}
			case 'url':
						{
							if(isset($input_value) && strlen($input_value)>0)
							{
								$bret= $this->validate_url($input_value);
								if(false == $bret)
								{
									$default_error_message = sprintf(E_VAL_URL_CHECK_FAILED,$field_label);
								}
							}
							break;
						}
			case "ccinfo": 
						{
							$valarr=explode("*",$command_value);
							$input_value_cctype = $valarr[0];
							$input_value_ccno = $valarr[1];
							$bret = $this->validate_CreditCard($input_value_cctype,$input_value_ccno,$default_error_message,$field_label);
							break;
						}
		 
		}//switch
		return $bret;
	}//validdate command

}
?>