<?php
//////////////////////////////
//Paging class 				//
//Developed by Kaustav Ghosh//
//www.sleekinfosolutions.com//
//////////////////////////////
//This css class must be there to display properly
define('CSSFL','pagenextprev');
define('CSSNP','pagenextprev');
define('CSSPN','pagenextprev');
define('CSSPNS','pagenumberselected');
define('CSSNF','recordnotfound');
define('CSSDD','limitdropdown');
define('CSSPC','pagecounter');
define('CSSNFE','notfounderror');
define('LCSSDDTXT','limitdropdowntext');
define('PCSSDDTXT','pagedropdowntext');
define('CSSDDP','dropdownpaging');


//Below 5 can be set by setPageText()
define('FIRST','FIRST');
define('LAST','LAST');
define('PREV','&laquo;PREV');
define('NEXT','NEXT&raquo;');
define('NO_RESULT','<span class="'.CSSNFE.'">No Record Found!</span><br>');//change text for no result
define('LIMITDROPDOWNTEXT','<span class="'.LCSSDDTXT.'">Number of Record Per Page: </span>');
define('PAGEDROPDOWNTEXT','<span class="'.PCSSDDTXT.'">Jump to Page: </span>');
define('PAGE_LIMITS','5,10,25,50,100');//Value under limit drop down,must be comma separeted can be set by limitDropDown()

define('PAGE_COUNTER_TEXT','Page #sel# of #tp#');//Page counter text E.G. Page 3 of 10
define('PAGE_PROBLEM','<span class="'.CSSNFE.'">A problem has been occured to create paging</span><br>');//Error text, mainly printed when there is some error raiseed from sql.
define('SHOWERROR',1);

//Below 8 can be set by setPagingParam() Atleast and atmost any one of below 4 must be 1
define('YAHOOPAGING',0);//1 to set yahoo paging on #1st argc==y
define('GOOGLEPAGING',1);//default paging set to google #1st argc==g
define('NORMALPAGING',0);//1 to set normal paging on #1st argc==n
define('SIMPLEPAGING',0);//1 to set simple paging on #1st argc==s
define('DROPDOWNPAGING',0);//1 to set simple paging on #1st argc==d
define('NOPAGESHOW',10);//Default how many page link will dispaly at max in the page #2nd argc==int number
define('LIMIT',10);//Default page limit #3rd argc==int number
define('FIRSTLAST',1);//Display first and last link(0=off,1=on) #4th argc==1/0
define('NEXTPREV',1);//Display next and prev link(0=off,1=on) #5th argc==1/0

/////////////////////////////Do not need any changes below this//////////////////////
class pagingRecord
{
	var $yahoopaging;
	var $googlepaging;
	var $normalpaging;
	var $simplepaging;
	var $limit;
	var $nopageshow;
	var $error;
	var $showerror;
	var $firstlast;
	var $nextprev;
	var $noRecordText;
	var $firstText;
	var $lastText;
	var $prevText;
	var $nextText;
	var $totPages;
	var $selPage;
	var $linksrc;
	var $pageingHTML;
	var $pageCounterStat;
	var $limitDropDownHTML;
	var $SQL;
	var $pageLimits;
	var $limitDropDownText;
	var $pageDropDownText;
	var $curLinkParam;
	var $pageDropDownHTML;
	var $limitJS;
	var $pageJS;
	var $totrecord;
	var $limitStr;
	var $thispagetotrecord;
	function pagingRecords()
	{
		$this->yahoopaging=YAHOOPAGING;
		$this->googlepaging=GOOGLEPAGING;
		$this->normalpaging=NORMALPAGING;
		$this->simplepaging=SIMPLEPAGING;
		$this->dropdownpaging=DROPDOWNPAGING;
		$this->limit=LIMIT;
		$this->nopageshow=NOPAGESHOW;
		$this->firstlast=FIRSTLAST;
		$this->nextprev=NEXTPREV;
		$this->noRecordText=NO_RESULT;
		$this->firstText=FIRST;
		$this->lastText=LAST;
		$this->prevText=PREV;
		$this->nextText=NEXT;
		$this->pageLimits=PAGE_LIMITS;
		$this->totPages=0;
		$this->selPage=1;
		$this->linksrc=basename($_SERVER['PHP_SELF']);
		$this->pageingHTML='';
		$this->pageCounterStat='';
		$this->limitDropDownHTML='';
		$this->error=0;
		$this->limitDropDownText=LIMITDROPDOWNTEXT;
		$this->pageDropDownText=PAGEDROPDOWNTEXT;
		$this->showerror=SHOWERROR;
		$this->curLinkParam='';
		$this->pageDropDownHTML='';
		$this->limitJS=0;
		$this->pageJS=0;
		$this->totrecord=0;
		$this->limitStr='';
		$this->thispagetotrecord=0;
	}
	function setPagingParam($pagingType="",$pgshownum="",$limnum="",$pgfl="",$pgnp="")
	{
		$this->googlepaging=0;
		$this->yahoopaging=0;
		$this->normalpaging=0;
		$this->simplepaging=0;
		switch($pagingType)
		{
			case 'g':
			$this->googlepaging=1;
			break;
			
			case 'y':
			$this->yahoopaging=1;
			break;

			case 'n':
			$this->normalpaging=1;
			break;

			case 'd':
			$this->dropdownpaging=1;
			break;

			case 's':
			$this->simplepaging=1;
			$this->firstlast=0;
			$this->nextprev=1;
			break;
			
			default:
			$this->googlepaging=1;
			break;
		}
		if(!empty($pgshownum))
		{
			$this->nopageshow=(int)abs($pgshownum);
		}
		if(!empty($limnum))
		{
			$this->limit=(int)abs($limnum);
		}
		if($pgfl==1)
		{
			$this->firstlast=(int)$pgfl;
		}
		if($pgfl=='0')
		{
			$this->firstlast=(int)$pgfl;
		}
		if($pgnp==1)
		{
			$this->nextprev=(int)$pgnp;
		}
		if($pgnp=='0')
		{
			$this->nextprev=(int)$pgnp;
		}
	}
	function setPageText($nrt="",$lddtxt="",$pddtxt="",$ft="",$lt="",$pt="",$nt="")//No Record,limit drop down text,page drop down text,First,Last,Prev,Next
	{
		if(!empty($nrt))
		{
			$this->noRecordText='<span class="'.CSSNFE.'">'.$nrt.'</span><br>';
		}
		if(!empty($lddtxt))
		{
			$this->limitDropDownText='<span class="'.LCSSDDTXT.'">'.$lddtxt.'</span>';
		}
		if(!empty($pddtxt))
		{
			$this->pageDropDownText='<span class="'.PCSSDDTXT.'">'.$pddtxt.'</span>';
		}
		if(!empty($ft))
		{
			$this->firstText=$ft;
		}
		if(!empty($lt))
		{
			$this->lastText=$lt;
		}
		if(!empty($pt))
		{
			$this->prevText=$pt;
		}
		if(!empty($nt))
		{
			$this->nextText=$nt;
		}
	}
	function getPageCounter()
	{
		if($this->totPages>0)
		{
			return '<span class="'.CSSPC.'">'.str_replace('#tp#',$this->totPages,str_replace('#sel#',$this->selPage,PAGE_COUNTER_TEXT)).'<span>';
		}
		else
		{
			return false;
		}
	}
	function makeLnkParam($lnkParam,$incpgno=1)
	{
		if(is_array($lnkParam))
		{
			$qString='';
			$keyarr=array();
			if($incpgno)
			{
				$keyarr=array("pageno");
			}
			foreach ($lnkParam as $key => $value)
			{ 
			   if(!in_array($key,$keyarr))
			   {
				   $keyarr[]=$key;
				   $qString.="&".$key."=".$value;
			   }
			}
			return $qString;
		}
		else
		{
			return $lnkParam;
		}
	}
	function makeURL()
	{
		$baseURL=basename($this->linksrc);
		unset($_GET['limit']);
		$qS=$this->makeLnkParam($_GET);
		return $baseURL."?".$qS;
	}
	function limitDropDown($newPageLimits="")
	{
		$drdn='';
		if(!empty($newPageLimits))
		{
			$this->pageLimits=$newPageLimits;
		}
		if(!$this->error)
		{
			if(!empty($_GET['limit']))
			$this->limit=$_GET['limit'];
			if($this->limitJS!=1)
			{
				$drdn.='<script language="javascript">
				function setLimitOfPaging(val)
				{
					window.location.href="'.$this->makeURL().'&pageno=1&limit="+val;
				}
				</script>';
			}
			$drdn.=$this->limitDropDownText.'<select name="limit" class="'.CSSDD.'" onchange="setLimitOfPaging(this.value)">';
			$ddop=explode(",",$this->pageLimits);
			for($d=0;$d<count($ddop);$d++)
			{
				$drdn.='<option value="'.$ddop[$d].'"';
				if($this->limit==$ddop[$d])
				{
					$drdn.=' selected="selected"';
				}
				$drdn.='>'.$ddop[$d].'</option>';
			}
			$drdn.='</select>';
			$this->limitJS=1;
		}
		$this->limitDropDownHTML=$drdn;
		return $drdn;
	}
	function pageDropDown($showTxt=1)
	{
		$drdn='';
		if(!$this->error)
		{
			if($this->pageJS!='1')
			{
				$drdn.='<script language="javascript">
				function jumpToPage(cururl)
				{
					window.location.href=cururl;
				}
				</script>';
			}
			if($showTxt==1)
			{
				$drdn.=$this->pageDropDownText;
			}
			$drdn.='<select name="pagedrdn" class="'.CSSDD.'" onchange="jumpToPage(this.value)">';
			for($d=1;$d<=$this->totPages;$d++)
			{
				$drdn.='<option value="'.$this->linksrc."?pageno=".$d.$this->curLinkParam.'"';
				if($this->selPage==$d)
				{
					$drdn.=' selected="selected"';
				}
				$drdn.='>'.$d.'</option>';
			}
			$drdn.='</select>';
			$this->pageJS=1;
		}
		if($this->totPages==0)
		{
			$drdn='';
		}
		$this->pageDropDownHTML=$drdn;
		return $drdn;
	}
	function queryError($sql="")
	{
		if(!$sql)
		$sql=$this->SQL;
		if($this->showerror)
		{
			echo 'ERROR:'.mysql_error().'<br>ERRNO:'.mysql_errno().'<br>SQL:'.$sql.'<br>';
		}
		else
		{
			echo PAGE_PROBLEM;
		}
		$this->error=1;
	}
	function getPos($haystack, $needle, $offset=0)
	{
		if(!function_exists('stripos'))
		{
			function stripos($haystack, $needle, $offset)
			{ 
				return strpos(strtoupper($haystack), strtoupper($needle), $offset);
			}
		}
		else
		{
			return stripos($haystack, $needle, $offset);
		}
	}
	function runQueryPaging($sql,$pageno="",$lnkParam="",$lnkScr="")
	{
		$pageno=abs($_GET['pageno']);
		$limit=$_GET['limit'];
		if(empty($pageno))
		{
			$pageno=1;
		}
		if(!empty($limit))
		{
			$this->limit=$limit;
		}
		$lnkParam=$this->makeLnkParam($lnkParam);
		$this->SQL=$sql;
		$enCount=0;
		if(!$this->getPos($this->SQL,"count("))
		{
			$frompos=$this->getPos($this->SQL,"from");
			$sql_1=substr($this->SQL,$frompos);    
			$sql="SELECT count(*) as totC ".$sql_1;
			$enCount=1;
		}
		$res=mysql_query($sql) or $this->queryError($sql);
		if(!$this->error)
		{
			if(!$enCount)
			{
				$numrows=@mysql_num_rows($res);
			}
			else
			{
				$new_row=@mysql_fetch_array($res);
				$numrows=$new_row['totC'];
			}
			$this->totrecord=$numrows;
			$pages=intval($numrows/$this->limit);
			if ($numrows % $this->limit)
			{ 
				$pages++;
			}
			if(abs($pageno)>$pages)
			{
				$pageno=$pages;
			}
			if($numrows>0)
			{
				$appendLmt=" limit ".(($this->limit)*(abs($pageno)-1)).",".abs($this->limit);
				$sql=$this->SQL.$appendLmt;
				$this->SQL=$sql;
				$res=mysql_query($sql) or $this->queryError();
				$numrowsl=@mysql_num_rows($res);
				$this->thispagetotrecord=$numrowsl;
				if(!$this->error && $numrowsl>0)
				{
					$pageHtml=$this->pagewise($numrows,$pageno,$lnkParam,abs($this->limit),$lnkScr);
					$this->limitStr=$appendLmt;
				}
				else
				{
					$this->error=1;
					$this->pageingHTML=PAGE_PROBLEM;
				}
			}
			else
			{
				$this->error=1;
				$pageHtml=$this->pagewise($numrows,$pageno,$lnkParam,abs($this->limit),$lnkScr);
			}
		}
		else
		{
			$this->error=1;
			$this->pageingHTML=PAGE_PROBLEM;
		}
		return $res;
	}
	function pagewise($numrows,$pageno=1,$lnkParam="",$limit="",$lnkScr="")
	{
		$pgStr='';
		if(!empty($limit))
		{
			$this->limit=abs($limit);
		}
		if($this->nopageshow % 2) 
		{
			$tgp=$this->nopageshow-1;
			$gp=$tgp/2;
			$yp=$tgp;
			$even=0;
		} 
		else 
		{
			$tgp=$this->nopageshow;
			$gp=$tgp/2;
			$yp=$tgp-1;
			$even=1;
		}
		$lnkParam=$this->makeLnkParam($lnkParam);
		$this->curLinkParam=$lnkParam;
		if(empty($lnkScr))
		$lnkScr=basename($_SERVER['PHP_SELF']);
		$this->linksrc=$lnkScr;
		if(($numrows>$this->limit)&&($numrows>0)){
		$pages=intval($numrows/$this->limit);
		if ($numrows % $this->limit)
		{ 
			$pages++;
		}
		$this->totPages=$pages;
		$pgStr.="<div style=\"text-align:center\">";
		$pgStr.="<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">";
		$offset=$this->limit*($pageno-1);
		$currenthit = $offset + 1;
		
		if (($numrows - $currenthit) >= $this->limit )
		{
			$lasthit = $currenthit + ($this->limit - 1); 
		}
		else
		{ 
			$lasthit=$numrows; 
		}

		if($this->limit > 2) 
		{ 
			$quo = ($currenthit/$this->limit) + 1;
			$selectedPg = sprintf("%.0f", $quo);
		}
		else 
		{ 
			$quo = $currenthit/$this->limit;
			$selectedPg = ceil($quo);
		}
		$this->selPage=abs($selectedPg);
		$pgStr.="<tr><td width=\"100%\" valign=\"top\" align=\"left\" class=\"".CSS."\">";
		$st_page=1;
		$end_page=$pages;
		if(!$this->normalpaging)
		{
			if(($selectedPg-$gp)<1)
			{
				$st_page=1;
			}
			else
			{
				$st_page=$selectedPg-$gp;
			}
			if(($selectedPg+$gp)>$pages)
			{
				$end_page=$pages;
			}
			else
			{
				$end_page=$selectedPg+$gp;
				if(!$this->yahoopaging)
				{
					$st_page=$end_page-$this->nopageshow+1;
					if($st_page<1)
					$st_page=1;
				}
			}
			if($this->yahoopaging)
			{
				if($end_page-$st_page<$yp)
				{
					if($st_page+$yp>$pages)
						$end_page=$pages;
					else
						$end_page=$st_page+$yp;
					if($end_page-$yp<1)
						$st_page=1;
					else
						$st_page=$end_page-$yp;
				}
				else
				{
					$st_page=$end_page-$yp;
				}
			}
		}
		if($selectedPg!=1)
		{
			if(!$this->normalpaging && $this->firstlast==1)
			{
				//$pgStr.="<a class='".nextprevious."' href=\"".$this->linksrc."?pageno=1".$lnkParam."\">".$this->firstText."</a>&nbsp;  ";
			}
			if($this->nextprev==1)
			{
				$newpageno = abs($selectedPg - 1);
				$pgStr.="<a class='".nextpreviousa."' href=\"".$this->linksrc."?pageno=".$newpageno.$lnkParam."\">".$this->prevText."</a>";
				$this->prev1=$newpageno; // add chenges to know the previous page no
			}
		}
		if(!$this->simplepaging && !$this->dropdownpaging)
		{
			for ($i=$st_page; $i<=$end_page; $i++)
			{
				if($selectedPg == $i)
				{
					$pgStr.="<span class='".page_nev_search_currenta."'>".$i."</span>&nbsp;|&nbsp;";
				}
				else
				{
					$pgStr.="<a class='".page_nev_searcha."' href=\"".$this->linksrc."?pageno=".$i.$lnkParam."\">".$i."</a>&nbsp;|&nbsp;";
				}
			}
		}
		if($this->dropdownpaging)
		{
			$pgStr.='<span class="'.CSSDDP.'">'.str_replace('#tp#',$this->totPages,str_replace('#sel#',$this->pageDropDown(0),PAGE_COUNTER_TEXT)).'</span>';
		}
		if($selectedPg!=$pages)
		{
			
			if($this->nextprev==1)
			{
				$newpageno = abs($selectedPg+1);
				$pgStr.="&nbsp;<a class='".nextpreviousa."' href=\"".$this->linksrc."?pageno=".$newpageno.$lnkParam."\">".$this->nextText."</a>";
				$this->next1=$newpageno; // add chenges to know the next page no
			}
			if(!$this->normalpaging && $this->firstlast==1)
			{
				//$pgStr.=" &nbsp;<a class='".nextprevious."' href=\"".$this->linksrc."?pageno=".$pages.$lnkParam."\">".$this->lastText."</a>";
			}
		}
		$pgStr.="</td></tr>";
		$pgStr.="</table>";
		$pgStr.="</div>";
		}
		if($numrows==0)
		{
			$pgStr.=$this->noRecordText;
		}
		$this->pageingHTML=$pgStr;
		$this->pageCounterStat=$this->getPageCounter();
	}
}
?>