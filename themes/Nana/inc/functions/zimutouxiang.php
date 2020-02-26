<?php
function tt_touxiang_generate_first_letter_uri($name){
$name2=tt_touxiang_Getzimu($name);//新加
		// get picture filename (and lowercase it) from commenter name:
		if (empty($name)){  // if, for some reason, the name is empty, set file_name to default unknown image

			$file_name = 'mystery';

		} else { // name is not empty, so we can proceed
if(empty($name2)){
//空的表示有英文
$name=$name;	
}else{
//汉字	
$name=$name2;
}
			$file_name = substr($name, 0, 1); // get one letter counting from letter_index
			//$file_name=tt_touxiang_Getzimu($file_name);//新加
			
			 // $str= iconv("UTF-8","gb2312", $file_name);//如果程序是gbk的，此行就要注释掉 
			  
				
				

			$file_name = strtolower($file_name); // lowercase it...

			if (extension_loaded('mbstring')){ // check if mbstring is loaded to allow multibyte string operations
				$file_name_mb = mb_substr($name, 0, 1); // repeat, this time with multibyte functions
				
				
				$file_name_mb=tt_touxiang_Getzimu($file_name_mb);//新加
				
				$file_name_mb = mb_strtolower($file_name_mb); // and again...
				
			}
			else { // mbstring is not loaded - we're not going to worry about it, just use the original string
				$file_name_mb = $file_name;
			}

			// couple of exceptions:
			if ($file_name_mb == 'ą'){
				$file_name = 'a';
				$file_name_mb = 'a';
			} else if ($file_name_mb == 'ć'){
				$file_name = 'c';
				$file_name_mb = 'c';
			} else if ($file_name_mb == 'ę'){
				$file_name = 'e';
				$file_name_mb = 'e';
			} else if ($file_name_mb == 'ń'){
				$file_name = 'n';
				$file_name_mb = 'n';
			} else if ($file_name_mb == 'ó'){
				$file_name = 'o';
				$file_name_mb = 'o';
			} else if ($file_name_mb == 'ś'){
				$file_name = 's';
				$file_name_mb = 's';
			} else if ($file_name_mb == 'ż' || $file_name_mb == 'ź'){
				$file_name = 'z';
				$file_name_mb = 'z';
			}

			// create arrays with allowed character ranges:
			$allowed_numbers = range(0, 9);
			foreach ($allowed_numbers as $number){ // cast each item to string (strict param of in_array requires same type)
				$allowed_numbers[$number] = (string)$number;
			}
			$allowed_letters_latin = range('a', 'z');
			$allowed_letters_cyrillic = range('а', 'ё');
			$allowed_letters_arabic = range('آ', 'ی');
			// check if the file name meets the requirement; if it doesn't - set it to unknown
			$charset_flag = ''; // this will be used to determine whether we are using latin chars, cyrillic chars, arabic chars or numbers
			// check whther we are using latin/cyrillic/numbers and set the flag, so we can later act appropriately:
			if (in_array($file_name, $allowed_numbers, true)){
				$charset_flag = 'number';
			} else if (in_array($file_name, $allowed_letters_latin, true)){
				$charset_flag = 'latin';
			} else if (in_array($file_name, $allowed_letters_cyrillic, true)){
				$charset_flag = 'cyrillic';
			} else if (in_array($file_name, $allowed_letters_arabic, true)){
				$charset_flag = 'arabic';
			} else { // for some reason none of the charsets is appropriate
				$file_name = 'mystery'; // set it to uknknown
			}

			if (!empty($charset_flag)){ // if charset_flag is not empty, i.e. flag has been set to latin, number or cyrillic...
				switch ($charset_flag){ // run through various options to determine the actual filename for the letter avatar
					case 'number':
						$file_name = 'number_' . $file_name;
						break;
					case 'latin':
						$file_name = 'latin_' . $file_name;
						break;
					case 'cyrillic':
						$temp_array = unpack('V', iconv('UTF-8', 'UCS-4LE', $file_name_mb)); // beautiful one-liner by @bobince from SO - http://stackoverflow.com/a/27444149/4848918
						$unicode_code_point = $temp_array[1];
						$file_name = 'cyrillic_' . $unicode_code_point;
						break;
					case 'arabic':
						$temp_array = unpack('V', iconv('UTF-8', 'UCS-4LE', $file_name_mb));
						$unicode_code_point = $temp_array[1];
						$file_name = 'arabic_' . $unicode_code_point;
						break;
					default:
						$file_name = 'mystery'; // set it to uknknown
						break;
				}
			}

		}

		// create file path - $avatar_uri variable will look something like this:
		// http://yourblog.com/wp-content/plugins/wp-first-letter-avatar/images/default/96/k.png):
		$avatar_uri =get_bloginfo('template_url'). '/images/48/'. $file_name . '.png';

		// return the final first letter image url:
		return $avatar_uri;

	}

function tt_touxiang_Getzimu($str) 
{ 
    $str= iconv("UTF-8","gb2312", $str);//如果程序是gbk的，此行就要注释掉 
    if (preg_match("/^[\x7f-\xff]/", $str)) 
    { 
        $fchar=ord($str{0}); 
        if($fchar>=ord("A") and $fchar<=ord("z") )return strtoupper($str{0}); 
        $a = $str; 
        $val=ord($a{0})*256+ord($a{1})-65536; 
        if($val>=-20319 and $val<=-20284)return "A"; 
        if($val>=-20283 and $val<=-19776)return "B"; 
        if($val>=-19775 and $val<=-19219)return "C"; 
        if($val>=-19218 and $val<=-18711)return "D"; 
        if($val>=-18710 and $val<=-18527)return "E"; 
        if($val>=-18526 and $val<=-18240)return "F"; 
        if($val>=-18239 and $val<=-17923)return "G"; 
        if($val>=-17922 and $val<=-17418)return "H"; 
        if($val>=-17417 and $val<=-16475)return "J"; 
        if($val>=-16474 and $val<=-16213)return "K"; 
        if($val>=-16212 and $val<=-15641)return "L"; 
        if($val>=-15640 and $val<=-15166)return "M"; 
        if($val>=-15165 and $val<=-14923)return "N"; 
        if($val>=-14922 and $val<=-14915)return "O"; 
        if($val>=-14914 and $val<=-14631)return "P"; 
        if($val>=-14630 and $val<=-14150)return "Q"; 
        if($val>=-14149 and $val<=-14091)return "R"; 
        if($val>=-14090 and $val<=-13319)return "S"; 
        if($val>=-13318 and $val<=-12839)return "T"; 
        if($val>=-12838 and $val<=-12557)return "W"; 
        if($val>=-12556 and $val<=-11848)return "X"; 
        if($val>=-11847 and $val<=-11056)return "Y"; 
        if($val>=-11055 and $val<=-10247)return "Z"; 
    }  
    else 
    { 
        return false; 
    } 
}