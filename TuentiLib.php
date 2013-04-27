<?php

/*
	Tuenti Programming Challenge 3
	https://contest.tuenti.net/
	
	@author: shoghicp
	@email: shoghicp@gmail.com
	
	This must be run using at least PHP 5.4,
	enabling BCMath and the pthreads extension.
	TuentiLib: http://bit.ly/14jgAPV
	Windows binaries: http://bit.ly/14j6jTO
*/

define("ENDIANNESS", (pack("d", 1) === "\77\360\0\0\0\0\0\0" ? BIG_ENDIAN:LITTLE_ENDIAN));

class TuentiLib{
	public static $input = "";
	public static $inputLines = "";
	public static $start = 0;
	public static $debug = "";
	
	public static function debug($line, $description = ""){
		if(defined("CHALLENGE")){
			TuentiLib::$debug .= "[".(microtime(true) - TuentiLib::$start)."] $description ".(memory_get_usage(true) / 1048576)."MB -- $line".PHP_EOL;
			if($description === "end"){
				@file_put_contents("debug/".CHALLENGE.".debug", TuentiLib::$debug);
			}
		}
	}
	
	public static function catchInput(){ //Yay ;)
		TuentiLib::$start = microtime(true);
		@file_put_contents("debug/".CHALLENGE.".debug", "");
		TuentiLib::debug(0, "start");
		@register_shutdown_function("TuentiLib::debug", 0, "end");
		TuentiLib::$input = stream_get_contents(STDIN);
		TuentiLib::$inputLines = array_map("rtrim", explode("\n", TuentiLib::$input));
		if(defined("CHALLENGE")){
			if(!file_exists("input/".CHALLENGE.".test")){
				@file_put_contents("input/".CHALLENGE.".test", TuentiLib::$input);
			}else{
				@file_put_contents("input/".CHALLENGE.".submit", TuentiLib::$input);
			}
		}
	}
	
	public static function getLine(){
		if(count(TuentiLib::$inputLines) === 0){
			return false;
		}
		return array_shift(TuentiLib::$inputLines);
	}
	
	public static function hexdump($bin){
		$output = "";
		$bin = str_split($bin, 16);
		foreach($bin as $counter => $line){
			$hex = chunk_split(chunk_split(str_pad(bin2hex($line), 32, " ", STR_PAD_RIGHT), 2, " "), 24, " ");
			$ascii = preg_replace('#([^\x20-\x7E])#', ".", $line);
			$output .= str_pad(dechex($counter << 4), 4, "0", STR_PAD_LEFT). "  " . $hex . " " . $ascii . PHP_EOL;
		}
		return $output;
	}
	
	public static function printable($str){
		if(!is_string($str)){
			return gettype($str);
		}
		return preg_replace('#([^\x20-\x7E])#', '.', $str);
	}

	public static function strToHex($str){
		return bin2hex($str);
	}

	public static function hexToStr($hex){
		return hex2bin($hex);
	}

	public static function readBool($b){
		return TuentiLib::readByte($b, false) === 0 ? false:true;
	}

	public static function writeBool($b){
		return TuentiLib::writeByte($b === true ? 1:0);
	}

	public static function readByte($c, $signed = true){
		$b = ord($c{0});
		if($signed === true and ($b & 0x80) === 0x80){ //calculate Two's complement
			$b = -0x80 + ($b & 0x7f);
		}
		return $b;
	}

	public static function writeByte($c){
		if($c > 0xff){
			return false;
		}
		if($c < 0 and $c >= -0x80){
			$c = 0xff + $c + 1;
		}
		return chr($c);
	}

	public static function readShort($str, $signed = true){
		list(,$unpacked) = unpack("n", $str);
		if($unpacked > 0x7fff and $signed === true){
			$unpacked -= 0x10000; // Convert unsigned short to signed short
		}
		return $unpacked;
	}

	public static function writeShort($value){
		if($value < 0){
			$value += 0x10000;
		}
		return pack("n", $value);
	}

	public static function readLShort($str, $signed = true){
		list(,$unpacked) = unpack("v", $str);
		if($unpacked > 0x7fff and $signed === true){
			$unpacked -= 0x10000; // Convert unsigned short to signed short
		}
		return $unpacked;
	}

	public static function writeLShort($value){
		if($value < 0){
			$value += 0x10000;
		}
		return pack("v", $value);
	}

	public static function readInt($str){
		list(,$unpacked) = unpack("N", $str);
		if($unpacked >= 2147483648){
			$unpacked -= 4294967296;
		}
		return (int) $unpacked;
	}

	public static function writeInt($value){
		if($value < 0){
			$value += 0x100000000;
		}
		return pack("N", $value);
	}

	public static function readLInt($str){
		list(,$unpacked) = unpack("V", $str);
		if($unpacked >= 2147483648){
			$unpacked -= 4294967296;
		}
		return (int) $unpacked;
	}

	public static function writeLInt($value){
		if($value < 0){
			$value += 0x100000000;
		}
		return pack("V", $value);
	}

	public static function readFloat($str){
		list(,$value) = ENDIANNESS === BIG_ENDIAN ? unpack("f", $str):unpack("f", strrev($str));
		return $value;
	}

	public static function writeFloat($value){
		return ENDIANNESS === BIG_ENDIAN ? pack("f", $value):strrev(pack("f", $value));
	}

	public static function readLFloat($str){
		list(,$value) = ENDIANNESS === BIG_ENDIAN ? unpack("f", strrev($str)):unpack("f", $str);
		return $value;
	}

	public static function writeLFloat($value){
		return ENDIANNESS === BIG_ENDIAN ? strrev(pack("f", $value)):pack("f", $value);
	}

	public static function printFloat($value){
		return preg_replace("/(\.\d+?)0+$/", "$1", sprintf("%F", $value));
	}

	public static function readDouble($str){
		list(,$value) = ENDIANNESS === BIG_ENDIAN ? unpack("d", $str):unpack("d", strrev($str));
		return $value;
	}

	public static function writeDouble($value){
		return ENDIANNESS === BIG_ENDIAN ? pack("d", $value):strrev(pack("d", $value));
	}

	public static function readLDouble($str){
		list(,$value) = ENDIANNESS === BIG_ENDIAN ? unpack("d", strrev($str)):unpack("d", $str);
		return $value;
	}

	public static function writeLDouble($value){
		return ENDIANNESS === BIG_ENDIAN ? strrev(pack("d", $value)):pack("d", $value);
	}

	public static function readLong($x, $signed = true){
		$value = "0";
		if($signed === true){
			$negative = ((ord($x{0}) & 0x80) === 0x80) ? true:false;
			if($negative){
				$x = ~$x;
			}
		}else{
			$negative = false;
		}

		for($i = 0; $i < 8; $i += 4){
			$value = bcmul($value, "4294967296", 0); //4294967296 == 2^32
			$value = bcadd($value, 0x1000000 * ord($x{$i}) + ((ord($x{$i + 1}) << 16) | (ord($x{$i + 2}) << 8) | ord($x{$i + 3})), 0);
		}
		return ($negative === true ? "-".$value:$value);
	}

	public static function writeLong($value){
		$x = "";
		if($value{0} === "-"){
			$negative = true;
			$value = bcadd($value, "1");
			if($value{0} === "-"){
				$value = substr($value, 1);
			}
		}else{
			$negative = false;
		}
		while(bccomp($value, "0", 0) > 0){
			$temp = bcmod($value, "16777216");
			$x = chr($temp >> 16) . chr($temp >> 8) . chr($temp) . $x;
			$value = bcdiv($value, "16777216", 0);
		}
		$x = str_pad(substr($x, 0, 8), 8, "\x00", STR_PAD_LEFT);
		if($negative === true){
			$x = ~$x; 
		}
		return $x;
	}

	public static function readLLong($str){
		return TuentiLib::readLong(strrev($str));
	}

	public static function writeLLong($value){
		return strrev(TuentiLib::writeLong($str));
	}
}

TuentiLib::catchInput();