<?php

/**
 * class.FastTemplate.php
 * ------------------
 * @version 5.4.0
 * @copyright 2002-2010 Melbis Company
 * @link http://www.melbis.com
 * @author Dmitriy Kasyanov
 **/

/** 
 * Class FastTemplate
 * Parsing information to html-pages
 **/
class FastTemplate 
{

	/**
	 * Vars 
	 **/
	var $gFileList = array();
    var $gParseVars = array();
    var $gRoot = '';


	/**
	 * Constructor
	 **/
	function FastTemplate ($mPathToTemplates = "")
	{
    	if( !empty($mPathToTemplates) ) 
		{
			if ( realpath($mPathToTemplates) === false ) 
			{
				die('PHP_ERROR: templates path is wrong. Run MShop.exe and go to server setup '.$mPathToTemplates);
			} 
			else 
			{
				$this->SetRoot($mPathToTemplates);
			}
        }
    }       



	/**
	 * SetRoot
	 **/
    function SetRoot ($mRoot) 
	{
    	$trailer = substr($mRoot,-1);
        if ( (ord($trailer)) != 47 ) 
		{
        	$mRoot .= chr(47);
        }                 
        $this->gRoot = $mRoot;
    }       


	/**
	 * DefineTemplate
	 **/
    function DefineTemplate ($mFileList) 
	{
		foreach ( $mFileList as $tag => $name ) 
		{
			$this->gFileList[$tag] = realpath($this->gRoot.$name); 
        }
	}



	/**
	 * Assign
	 **/
    function Assign ($mTplArray, $mTrailer = "") 
	{
        if ( is_array($mTplArray) ) 
		{
			foreach ( $mTplArray as $key => $val) 
			{
            	if ( !empty($key) ) 
				{
                	$this->gParseVars[$key] = $val;
                }
            }
        } 
		else 
		{
        	if ( !empty($mTplArray) ) 
			{
            	$this->gParseVars[$mTplArray] = $mTrailer;
            }
        }
	}



	/**
	 * Append
	 **/
    function Append ($mVar, $mValue) 
	{
       	if ( !empty($mVar) ) 
       	{
            	$this->gParseVars[$mVar] .= $mValue;
        }
	}


	/**
	 * Parse
	 **/
    function Parse ($mReturnVar, $mFileTags ) 
	{
    	$append = FALSE;
		$append_end = FALSE;
        $template = $mFileTags;

        if ( substr($template, 0, 1) == '.' ) 
		{
        	$append = TRUE;
            $template = substr($template, 1);
        }
        if ( substr($template, 0, 1) == ',' ) 
		{
        	$append_end = TRUE;
            $template = substr($template, 1);
        }
		
        if ( !isset($this->$template) ) 
		{
        	if ( is_file($this->gFileList[$template]) ) 
			{
		        $this->$template = @implode("", (file($this->gFileList[$template])));
          	}
			else
			{
				$this->$template = '';
			}
        }

        if ( $append ) 
		{
			$this->gParseVars[$mReturnVar] .= $this->ParseTemplate($this->$template);
        } 
		else 
		{
            if ($append_end) 
			{
                $this->gParseVars[$mReturnVar] = $this->ParseTemplate($this->$template).$this->gParseVars[$mReturnVar];
            } 
			else 
			{
				$this->gParseVars[$mReturnVar] = $this->ParseTemplate($this->$template);
			}
        }
	}


	/**
	 * ParseTemplate
	 **/
    function ParseTemplate ($mTemplate) 
	{
		foreach ( $this->gParseVars as $key => $value ) 
		{
	        if ( !empty($key) ) 
			{
            	$key = '{'.$key.'}';
                $mTemplate = str_replace($key, $value, $mTemplate);
            }
        }
	return $mTemplate;
    }        


	/**
	 * FastPrint
	 **/
    function FastPrint ($mTemplate = "") 
	{
		echo $this->gParseVars[$mTemplate];
    }



	/**
	 * Fetch
	 **/
    function Fetch ($mTemplate = "") 
	{
		return $this->gParseVars[$mTemplate];
    }

        

	/**
	 * FetchTpl
	 **/
    function FetchTpl ($mFileTag) 
	{
        if ( is_file($this->gFileList[$mFileTag]) ) 
		{
        	return @implode("", (file($this->gFileList[$mFileTag])));
        }
	}


	/**
	 * Clear
	 **/
    function Clear ($mReturnVar = "")  
	{
        if ( !empty($mReturnVar) ) 
		{
        	if ( is_array($mReturnVar) ) 
			{
            	foreach ( $mReturnVar as $value ) 
				{
					unset($this->gParseVars[$value]);
                }
				return TRUE;
            } 
			else 
			{
				unset($this->gParseVars[$mReturnVar]);
                return TRUE;
            }
        }
	}       


	/**
	 * ClearTpl
	 **/
    function ClearTpl ($mFileHandle = "") 
	{
       	if ( is_array($mFileHandle) ) 
		{
           	foreach ($mFileHandle as $value)
			{
            	unset($this->$value);
            }
        return TRUE;
        } 
		else 
		{
            unset($this->$mFileHandle);
            return TRUE;
        }
	}
} 
?>
