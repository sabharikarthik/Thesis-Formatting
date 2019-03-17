<?php
// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');
session_start();

	


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Set font
        $this->SetFont('times', '', 14);
        // Title
        $this->Cell(0, 0, $this->getAliasNumPage(), 0, false, 'R', 0, '', 0, false, 'M', 'M');
    }
}

function add_element($ele,$position1,$position2)
{
	add_title_in_array($ele,$position1);
	$htmlString = "";
	$htmlString .= construct_html();
	$htmlString_multireturn=construct_html_for_word("","","",$position1,$position2,"","","");
	$htmlString.="~".$htmlString_multireturn[0]."~".$htmlString_multireturn[1]."~".$htmlString_multireturn[2];
	return $htmlString;
}
function add_title_in_array($ele,$position)
{
	if(empty($_SESSION['t_array']))
	{
		$sub_array = array(0 => $ele);
		$_SESSION['t_array'] = $sub_array;
	}
	else
	{
		$sub_array = array_slice($_SESSION['t_array'], 0, $position+1, true) +
		array($position+1 => $ele);
	
		$new_array = array_slice($_SESSION['t_array'], $position+1,COUNT($_SESSION['t_array']),true) ;
    
		$new_array1 = array();
		foreach($new_array as $key => $value){
			$new_array1[$key+1] = $value;
		}
		$new_array = $new_array1;
		$_SESSION['t_array'] = $sub_array + $new_array; 
	}
}
function add_para($para_name,$content,$position1,$position2,$add_numbering)
{
	$int_position1 = (int)$position1;
	$int_position2 = (int)$position2;
	$para_name = $add_numbering.";".$para_name;
	add_para_in_array("paragraph",$para_name,$content,$int_position1,$int_position2,"","");
	$htmlString = "";
	$htmlString .= construct_html();
	$htmlString_multireturn=construct_html_for_word("","","",$int_position1,$int_position2,"","","");
	$htmlString.="~".$htmlString_multireturn[0]."~".$htmlString_multireturn[1]."~".$htmlString_multireturn[2];
	return $htmlString;
}
function add_diagram($diagram_name,$diagram_path,$height_size,$width_size,$position1,$position2,$add_numbering)
{
	$int_position1 = (int)$position1;
	$int_position2 = (int)$position2;
	$diagram_name = $add_numbering.";".$diagram_name;
	add_para_in_array("diagram",$diagram_name,$diagram_path,$int_position1,$int_position2,$height_size,$width_size);
	$htmlString = "";
	
	$htmlString .= construct_html();
	$htmlString_multireturn=construct_html_for_word("diagram",$diagram_name,$diagram_path,$int_position1,$int_position2,$height_size,$width_size,"");
	$htmlString.="~".$htmlString_multireturn[0]."~".$htmlString_multireturn[1]."~".$htmlString_multireturn[2];
	return $htmlString;
}
function add_para_in_array($type,$para_name,$content,$position1,$position2,$height_size,$width_size)
{
	if($height_size!="" && $width_size!="")
	{
		 $size_array=trial_download_PDF($height_size,$width_size,"","","",1);
		 $height=$size_array[0];
		 $width=$size_array[1];
	}
	if(empty($_SESSION['e_array'][$position1]) )
	{
		if($height_size == "" || $width_size=="")
				$sub_array = array(0 => $type."~".$para_name."~".$content);
		else
		{
			   
				$sub_array = array(0 => $type."~".$para_name."~".$content."~".$height."~".$width);
		}
		$_SESSION['e_array'][$position1] = $sub_array;
	}
	else
	{
		if($position2 == -1)
		{
			if($height_size == "" || $width_size=="")
				$sub_array = array(0 => $type."~".$para_name."~".$content);
			else
				$sub_array = array(0 => $type."~".$para_name."~".$content."~".$height."~".$width);
			$new_array = $_SESSION['e_array'][$position1];
		}
		else
		{
			if($height_size == "" || $width_size=="")
				$sub_array = array_slice($_SESSION['e_array'][$position1], 0, $position2+1, true) +
			array($position2+1 => $type."~".$para_name."~".$content);
			else
				$sub_array = array_slice($_SESSION['e_array'][$position1], 0, $position2+1, true) +
			array($position2+1 => $type."~".$para_name."~".$content."~".$height."~".$width);
	
			$new_array = array_slice($_SESSION['e_array'][$position1], $position2+1,COUNT($_SESSION['e_array'][$position1]),true) ;
		}
		$new_array1 = array();
		foreach($new_array as $key => $value){
			$new_array1[$key+1] = $value;
		}
		$new_array = $new_array1;
		$_SESSION['e_array'][$position1] = $sub_array + $new_array; 
	}
}
function edit_element($position1,$position2)
{
	$int_position1=(int)$position1;
	$int_position2=(int)$position2;
	
}
function remove_element($position1,$position2)
{
	$int_position1 = (int)$position1;
	$int_position2 = (int)$position2;
	if($int_position2 == -1)
	{
		 array_splice($_SESSION['t_array'],$int_position1,1);
		 array_splice($_SESSION['e_array'],$int_position1,1);
	}
	else
	{
		array_splice($_SESSION['e_array'][$position1],$int_position2,1);
	}
	$htmlString = "";
	$htmlString .= construct_html();
	$htmlString_multireturn=construct_html_for_word("","","",$int_position1,$int_position2,"","","");
	$htmlString.="~".$htmlString_multireturn[0]."~".$htmlString_multireturn[1]."~".$htmlString_multireturn[2];
	return $htmlString;
}
function construct_html()
{
	$htmlString = "";
	$htmlString .= "<dl>";
	foreach($_SESSION['t_array'] as $k => $val)
	{
		$para_count = 1;
		$diagram_count = 1;
		$htmlString .= "<dt>";
		$htmlString .= "<table>";
		$htmlString .="<tr>";
		$htmlString .= "<th><input type='radio' id ='".$k."r' name='select' onchange='selection_change(\"".$k."\")'/></th>";
		$htmlString .= "<th><button id = '".$k."ebutton' disabled='disabled' onclick = 'expand(\"".$k."\")'>e</button></th>";
		$htmlString .= "<th>".($k+1).". ".$val."</th>";
		$htmlString .= "<th><button id ='".$k."rbutton' disabled='disabled' onclick='remove(\"".$k."\")'>remove</button></th>";
		$htmlString .= "<th><button id= '".$k."edbutton' disabled='disabled' onclick='edit(\"" .$k. "\")'>Edit</button></th>";
		$htmlString .= "<th><button id = '".$k."pbutton'  style='visibility: hidden;' onclick = 'add_para()'>add paragraph</button></th>";
		$htmlString .= "<th><button id = '".$k."dbutton' style='visibility: hidden;' onclick ='add_diagram()'>add diagram</button></th>";
		$htmlString .= "</tr>";
		$htmlString .= "</table>";
		$htmlString .= "</dt>";
		if(count($_SESSION['e_array']) > $k)
		{
		foreach($_SESSION['e_array'][$k] as $key => $value)
		{
			$element_content = array();
			$add_numbering = array();
			$element_content = explode("~",$value);
			$htmlString .= "<dd>";
			$htmlString .= "<table>";
			$htmlString .= "<tr>";
			
			$htmlString .= "<th><input type='radio' id ='".$k."_".$key."r' name='select' onchange='selection_change(\"".$k."_".$key."\")'/></th>";
			$htmlString .= "<th><button id = '".$k."_".$key."ebutton' disabled='disabled' onclick = 'expand(\"".$k."_".$key."\")'>e</button></th>";
			$type = $element_content[0];
			if($type == "paragraph")
			{
				$add_numbering = explode(";",$element_content[1]);
				if($add_numbering[0] == "ny")
				{
					$htmlString .= "<th>".($k+1).".".$para_count." ".$add_numbering[1]."</th>";
					$para_count += 1;
				}
				else
					$htmlString .= "<th>".$add_numbering[1]."</th>";
			}
			if($type == "diagram")
			{
				$add_numbering = explode(";",$element_content[1]);
				if($add_numbering[0] == "ny")
				{
					$htmlString .= "<th>Fig. ".($k+1).".".$diagram_count." ".$add_numbering[1]."</th>";
					$diagram_count += 1;
				}
				else
					$htmlString .= "<th>".$add_numbering[1]."</th>";
			}
			
			$htmlString .= "<th><button id ='".$k."_".$key."rbutton' disabled='disabled' onclick='remove(\"".$k."_".$key."\")'>Remove</button></th>";
			$htmlString .= "<th><button id ='".$k."_".$key."edbutton' disabled='disabled' onclick='edit(\"".$k."_".$key."\")'>Edit</button></th>";
			$htmlString .= "<th><button id = '".$k."_".$key."pbutton'  style='visibility: hidden;' onclick = 'add_para()'>add paragraph</button></th>";
			$htmlString .= "<th><button id = '".$k."_".$key."dbutton' style='visibility: hidden;' onclick ='add_diagram()'>add diagram</button></th>";
			$htmlString .= "</tr>";
			$htmlString .= "</table>";
			$htmlString .= "</dd>";
		}
		}
	}
	$htmlString .= "</dl>";
	return $htmlString;
}
function construct_html_for_word($element_type,$diagram_name,$diagram_path,$position1,$position2,$height_size,$width_size,$thesis_reload)
{
	$htmlString = "";
	$checking="";
	
	$for_resize=$element_type."?".$diagram_name."?".$diagram_path."?";
	
	foreach($_SESSION['t_array'] as $k => $val)
	{
		
		$para_count = 1;
		$diagram_count = 1;
		$title = $val;
		$htmlString .= "<div id ='".$k."d'><h2 style ='page-break-before: always;text-align: center;margin-top: 1cm;font-size: 25px;' ><b>".($k+1).". ".$title."</b></h2></div>";
		
		if(count($_SESSION['e_array']) > $k)
		{
			
			foreach($_SESSION['e_array'][$k] as $key => $value)
			{
				$add_numbering = array();
				$element_content = array();
				$element_content = explode("~",$value);
				$type = $element_content[0];
				if($type == "paragraph")
				{
					$name = $element_content[1];
					$content = $element_content[2];
					$add_numbering = explode(";",$element_content[1]);
					if($add_numbering[0] == "ny")
					{
						$htmlString .= "<div id ='".$k."_".$key."d'><h3 style ='text-align:left;margin-top: 0.5cm;font-size: 20px;' ><b>".($k+1).".".$para_count." ".$add_numbering[1]."</b></h3><p style='line-height: 100%;font-weight:normal;text-align:justify;margin-top: 0.5cm;font-size: 20px;'>&emsp;".$content."</p></div>";
						$para_count += 1;
					}
					else
						$htmlString .= "<div id ='".$k."_".$key."d'><h3 style ='text-align:left;margin-top: 0.5cm;font-size: 20px;' ><b>".$add_numbering[1]."</b></h3><p style='line-height: 100%;font-weight:normal;text-align:justify;margin-top: 0.5cm;font-size: 20px;'>&emsp;".$content."</p></div>";
						
				}
				if($type == "diagram")
				{
					$name = $element_content[1];
					$path = $element_content[2];
					$height_trail=$element_content[3];
					$width_trail=$element_content[4];
					if($thesis_reload=="")
					{
					if($position2==-1 &&$position1==$k)
					{
						$trial=trial_download_PDF($element_type,$diagram_name,$diagram_path,$position1,$position2,"");
						$checking.=$trial;
						$position1=$position1+1;
						
					}
					else
					{
						if($k==$position1 && $key==($position2+1))
						{
	                   	$trial=trial_download_PDF($element_type,$diagram_name,$diagram_path,$position1,$position2,"");
						$checking.=$trial;
						}
					     
					}
				}
					
					$add_numbering = explode(";",$element_content[1]);
					if($add_numbering[0] == "ny")
					{
						$htmlString .= "<div id ='".$k."_".$key."d'><img src='".$path."' align ='center' style='margin-top: 0.5cm;width:".$width_trail."px;height:".$height_trail."px'/><h4 style ='text-align:center;margin-top: 0.5cm;font-size: 20px;' ><b>Fig. ".($k+1).".".$diagram_count." ".$add_numbering[1]."</b></h4></div>";
						$diagram_count += 1;
					}
					else
						$htmlString .= "<div id ='".$k."_".$key."d'><img src='".$path."' align ='center' style='margin-top: 0.5cm;width:".$width_trail."px;height:".$height_trail."px'/><h4 style ='text-align:center;margin-top: 0.5cm;font-size: 20px;' ><b>".$add_numbering[1]."</b></h4></div>";
						
				}
			}
		}
	}
	if($thesis_reload!="")
	{
		return $htmlString;
	}
	return array($htmlString,$checking,$for_resize);
}
function resize_image($element_type,$para_name,$content,$height,$width,$k,$key)
{
	$element_type=$_POST['element_type'];
	$para_name=$_POST['para_name'];
	$content=$_POST['content'];
	$height=$_POST['height'];
	$width=$_POST['width'];
    $k=$_POST['k'];
	$key=$_POST['key'];
	
	$_SESSION['e_array'][$k][$key]=$element_type."~".$para_name."~".$content."~".$height."~".$width;
    echo $height.$width."added to old page";

	}

function trial_download_PDF($element_type,$para_name,$content,$position1,$position2,$size_assign)
{
	
	
	$pdf = new MYPDF("P", PDF_UNIT, "A4",true, 'UTF-8', false);
	$pdf->SetHeaderMargin(5);
	$pdf->Header();
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetAutoPageBreak(TRUE,0);// set auto page breaks
	$pdf->AddPage();
	$height_trail=$pdf->getPageHeight();
    $width_trail=$pdf->getPageWidth();

	if($size_assign==1)
	{
					
					$height=($element_type/10)*$height_trail;
					$width=($para_name/10)*$width_trail;
					return array($height,$width);
	}
	
	foreach($_SESSION['t_array'] as $k => $val)
	{
		$para_count = 1;
		$diagram_count = 1;
		if ($k != 0)
			$pdf->AddPage();
		$pdf->Ln(30);
		$pdf->SetFont('times', 'B', 22);
		$pdf->MultiCell(0, 0,($k+1).". " .$val,0, 'C');
		$pdf->Ln(15);
		if(count($_SESSION['e_array']) > $k)
		{
			foreach($_SESSION['e_array'][$k] as $key => $value)
			{
				$add_numbering = array();
				$element_content = array();
				$element_content = explode("~",$value);
				$type = $element_content[0];
				if($type == "paragraph")
				{
					$name = $element_content[1];
					$content = "\t\t\t\t\t".$element_content[2];
					$add_numbering = explode(";",$name);
					if($add_numbering[1] != "")
					{
						$pdf->SetFont('times', 'B', 18);
						if($add_numbering[0] == "ny")
						{
							$name = ($k+1).".".$para_count." ".$add_numbering[1];
							$para_count += 1;
						}
						else
							$name = $add_numbering[1];
						$pdf->MultiCell(0, 0, $name,0, 'L');
						$pdf->Ln(15);
					}
					$pdf->SetFont('times', '', 18);
					$pdf->MultiCell(0, 0, $content,0, 'J');
					$pdf->Ln(15);
				}//if type="para"
				if($type == "diagram")
				{
					$name = $element_content[1];
					$path = $element_content[2];
					$add_numbering = explode(";",$name);
					
					$output_file = 'temp'.$k."_".$key.'.jpg';
					if(file_exists($output_file)) 
						unlink($output_file);
					$ifp = fopen( $output_file, 'w' ); 
					$path_content = explode( ',', $path );
					fwrite( $ifp, base64_decode( $path_content[ 1 ] ) );
					fclose( $ifp );
					
					$current=0;//variable used to check current image
					if($position2==-1 &&$position1==$k)//to check current element if 1st one
					{
					    $current=1;
      					$position1=$position1+1;
					}
					else
					{
						if($k==$position1 && $key==($position2+1))
						{
	                    $current=1;
						}
						else// not a current element
						{
						$current=0;
						}
					     
					}//end of else
                    					
					
					$height=$element_content[3];
					$width=$element_content[4];
					
					if($current==0)//if not a current Image
					{
					  
					  $pdf->Image($output_file, '', '', $width, $height, '', '', '', true, 300, 'C', false, false, 1, false, false, false);
					  $pdf->Ln();
					if($add_numbering[1] != "")
					{
						if($add_numbering[0] == "ny")
						{
							$name = "Fig. ".($k+1).".".$diagram_count." ".$add_numbering[1];
							$diagram_count += 1;
						}
						else
							$name = $add_numbering[1];
						$pdf->SetFont('times', 'B', 16);
						$pdf->MultiCellCell(0, 0, $name, 0, 'C');
					}
					$pdf->Ln(15);	
					}//end of If
					else//current image
					{
						            
					$start_y=$pdf->GetY();
                    $start_page=$pdf->getPage();					
					$pdf->Image($output_file, '', '', $width, $height, '', '', '', true, 300, 'C', false, false, 1, false, false, false);
					$pdf->Ln();
					if($add_numbering[1] != "")
					{
						if($add_numbering[0] == "ny")
						{
							$name = "Fig. ".($k+1).".".$diagram_count." ".$add_numbering[1];
							$diagram_count += 1;
						}
						else
							$name = $add_numbering[1];
						$pdf->SetFont('times', 'B', 16);
						$pdf->MultiCellCell(0, 0, $name, 0, 'C');
					}
					$pdf->Ln(15);
					$end_y=$pdf->GetY();
					$end_page=$pdf->getPage();
					if($start_page==$end_page)
					{
                    $checking="old_page";	
				    $result="old"."?".$checking."?".$k."?".$key;
					return $result;
					}
					else
					{
						//$img_and_current=$start_y+$height;//GetYposition+height of image
						$margins=$pdf->getMargins();//margin of a image
						$margin_bottom=$margins['bottom'];
						$page_minus_bottom=$height_trail-$margin_bottom;
						$remaining_space=$page_minus_bottom-$start_y;
						if($height>$remaining_space)
						{
						
								$height=$remaining_space-10;
								
								if($height<$remaining_space && $height>0)
								{
								    $result="new"."?".$height."?".$k."?".$key;
          					        return $result;	
							    }
								else
								{
									$res="not_able";
									$result="new"."?".$res."?".$k."?".$key;
								    return $result ;
								}
							
						}//end of if used to check if image size >available
					}//end of else (new Page)
				
					}//end of else
					
				}//end of if used for diagram
			}//end of inner loop
		}//end of if
		
	}//end of outer loop
	 
}
function download_word()
{
	/*phpword codes
	$phpWord = new \PhpOffice\PhpWord\PhpWord();


	$fontStyleName = 'title_font';
	$phpWord->addFontStyle($fontStyleName, array('name' => 'TimesNewRoman', 'bold' => true,'size' => 18));

	$fontStyleName = 'sub_title_font';
	$phpWord->addFontStyle($fontStyleName, array('name' => 'TimesNewRoman', 'bold' => true,'size' => 16));

	$fontStyleName = 'paragraph_font';
	$phpWord->addFontStyle($fontStyleName, array('name' => 'TimesNewRoman', 'size' => 14));

	$fontStyleName = 'diagram_font';
	$phpWord->addFontStyle($fontStyleName, array('name' => 'TimesNewRoman', 'bold' => true,'size' => 12));

	$paragraphStyleName = 'title_p_style';
	$phpWord->addParagraphStyle($paragraphStyleName, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(1.0),'pageBreakBefore' => true));

	$paragraphStyleName = 'sub_title_p_style';
	$phpWord->addParagraphStyle($paragraphStyleName, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, 'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(.2)));

	$paragraphStyleName = 'paragraph_p_style';
	$phpWord->addParagraphStyle($paragraphStyleName, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::JUSTIFY, 'lineHeight' => 1.0, 'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(.2)));

	$paragraphStyleName = 'diagram_p_style';
	$phpWord->addParagraphStyle($paragraphStyleName, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH));

	// New portrait section
	$section = $phpWord->addSection();

	$sectionStyle = $section->getStyle();
	$sectionStyle->setMarginLeft(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(.2));
	$sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(.2));
	$sectionStyle->setMarginTop(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(.2));
	$sectionStyle->setMarginBottom(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(.2));
	$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
	
	foreach($_SESSION['t_array'] as $k => $val)
	{
		
		$title = $val;
		$section->addText($title,'title_font', 'title_p_style');
		if(count($_SESSION['e_array']) > $k)
		{
			foreach($_SESSION['e_array'][$k] as $key => $value)
			{
				$element_content = array();
				$element_content = explode("~",$value);
				$type = $element_content[0];
				if($type == "paragraph")
				{
					$name = $element_content[1];
					$content = $element_content[2];
					$content = "       ".$content;
					if($name != "")
						$section->addText($name,'sub_title_font', 'sub_title_p_style');
					if($content != "")
						$section->addText($content,'paragraph_font', 'paragraph_p_style');
				}
				if($type == "diagram")
				{
					$name = $element_content[1];
					$path = $element_content[2];
					$output_file = 'temp'.$k."_".$key.'.jpg';
					if(file_exists($output_file)) 
						unlink($output_file);
					$ifp = fopen( $output_file, 'w' ); 
					$path_content = explode( ',', $path );
					fwrite( $ifp, base64_decode( $path_content[ 1 ] ) );
					fclose( $ifp ); 
					$section->addImage($output_file,array( 'wrappingStyle' => 'inline','align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ,'width'=>\PhpOffice\PhpWord\Shared\Converter::inchToTwip(3.2), 'height'=>\PhpOffice\PhpWord\Shared\Converter::inchToTwip(3.2)));
					if($name != "")
					{
						$section->addText($name,'diagram_font','diagram_p_style');
					}
					
				}
			}
		}
	}

	

	//$section->addTextBreak();
	ob_clean();
	$objWriter->save('helloWorld.docx');
	$file_name = 'helloWorld.docx';
	/*\PhpOffice\PhpWord\Settings::setPdfRendererPath('vendor/dompdf/dompdf');
	\PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
	$temp = \PhpOffice\PhpWord\IOFactory::load('helloworld.docx');
	$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($temp , 'PDF');
	$xmlWriter->save('helloworld.pdf', TRUE);*/
	return "1";
}
function download_PDF()
{
	/*// create new PDF document wkhtmltopdf method
	$htmlString = "";
	foreach($_SESSION['t_array'] as $k => $val)
	{
		$title = $val;
		$htmlString .= "<div id ='".$k."d'><h2 style ='page-break-before: always;text-align: center;margin-top: 4cm;font-size: 30px;' ><b>".$title."</b></h2></div>";
		if(count($_SESSION['e_array']) > $k)
		{
			foreach($_SESSION['e_array'][$k] as $key => $value)
			{
				$element_content = array();
				$element_content = explode("~",$value);
				$type = $element_content[0];
				if($type == "paragraph")
				{
					$name = $element_content[1];
					$content = $element_content[2];
					$htmlString .= "<div id ='".$k."_".$key."d'><h3 style ='text-align:left;margin-top: 1cm;font-size: 25px;' ><b>".$name."</b></h3><p style='line-height: 100%;font-weight:normal;text-align:justify;margin-top: 1cm;font-size: 25px;'>&emsp;".$content."</p></div>";
				}
				if($type == "diagram")
				{
					$name = $element_content[1];
					$path = $element_content[2];
					$htmlString .= "<div id ='".$k."_".$key."d'><img src='".$path."' align ='center' style='margin-top: 1cm;width:200px;height:200px'/><h4 style ='text-align:center;margin-top: 0.5cm;font-size: 20px;' ><b>".$name."</b></h4></div>";
				}
			}
		}
	}*/

	
	$pdf = new MYPDF("P", PDF_UNIT, "A4",true, 'UTF-8', false);
	$pdf->SetHeaderMargin(10);
	$pdf->Header();
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE,0);
	$pdf->AddPage();
	
	foreach($_SESSION['t_array'] as $k => $val)
	{
		$para_count = 1;
		$diagram_count = 1;
		if ($k != 0)
		{  
	        $pdf->AddPage();
			
		}
		$pdf->Ln(10);
		$pdf->SetFont('times', 'B', 22);
		$pdf->MultiCell(0, 0,($k+1).". " .$val,0, 'C');
		$pdf->Ln(5);
		if(count($_SESSION['e_array']) > $k)
		{
			foreach($_SESSION['e_array'][$k] as $key => $value)
			{
				$add_numbering = array();
				$element_content = array();
				$element_content = explode("~",$value);
				$type = $element_content[0];
				if($type == "paragraph")
				{
					$name = $element_content[1];
					$content = "\t\t\t\t\t".$element_content[2];
					$add_numbering = explode(";",$name);
					$pdf->SetFont('times', 'B', 18);
					if($add_numbering[0] == "ny")
					{
						$name = ($k+1).".".$para_count." ".$add_numbering[1];
						$para_count += 1;
					}
					else
						$name = $add_numbering[1];
					$pdf->MultiCell(0, 0, $name, 0, 'L');
					$pdf->Ln();
					$pdf->SetFont('times', '', 18);
					$pdf->MultiCell(0, 0, $content, 0, 'J');
					$pdf->Ln(8);
				}
				if($type == "diagram")
				{
					$name = $element_content[1];
					$path = $element_content[2];
					$add_numbering = explode(";",$name);
					if($add_numbering[0] == "ny")
					{
						$name = "Fig. ".($k+1).".".$diagram_count." ".$add_numbering[1];
						$diagram_count += 1;
					}
					else
						$name = $add_numbering[1];
					$output_file = 'temp'.$k."_".$key.'.jpg';
					if(file_exists($output_file)) 
						unlink($output_file);
					$ifp = fopen( $output_file, 'w' ); 
					$path_content = explode( ',', $path );
					fwrite( $ifp, base64_decode( $path_content[ 1 ] ) );
					fclose( $ifp );
                    $height_trail=$pdf->getPageHeight();
                    $width_trail=$pdf->getPageWidth();					
					//Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
					$height=$element_content[3];
					$width=$element_content[4];
					
					$pdf->Image($output_file, '', '', $width, $height, '', '', '', true, 300, 'C', false, false, 1, false, false, false);
					//$htmlString = "<div id ='".$k."_".$key."d' style ='text-align:center;'><img src='".$path."' align ='center' style='margin-top: 1cm;width:200px;height:200px'/></div>";
					//$pdf->writeHTML($htmlString, false, false, false, false, '');
					$pdf->Ln();
					$pdf->SetFont('times', 'B', 16);
					$pdf->MultiCell(0, 0,$name, 0, 'C');
					$pdf->Ln(5);
				}
			}
		}
		
	}
	 $pdf->Output("C:\\wamp\\www\\thesis\\example1.pdf", 'FD');

	return "1";
}
function save_thesis()
{
$name=$_SESSION['thesis_name'].".txt";
$my_file=fopen($name,"w")or die("unable to open");
$text=array();
foreach($_SESSION['t_array'] as $k => $val)
	{
		$text[$k]=$val;
		$text[$k].="*";
		if(count($_SESSION['e_array']) > $k)
		{
		foreach($_SESSION['e_array'][$k] as $key=>$value)
		{
			$text[$k].=$value;
			$text[$k].="$";
	    }
		
		}
		
	}
	$encoded_string=json_encode($text);
    file_put_contents($name,$encoded_string);
	fclose($my_file);
}
function view_thesis($filename)
{
	$name=$filename.".txt";
	$file=file_get_contents($name);
	$decoded=json_decode($file,true);
	$text_load=array();
	$position1=0;
	$position2=0;
	$array=array(array());
	$sub=array();
	$htmlString="";
	foreach($decoded as $key=>$value)
	{
		$text_load=explode("*",$value);
		$_SESSION['t_array'][$position1]=$text_load[0];
		if($text_load[1]!="")
		{
		$sub_array=$text_load[1];
		$sub=explode("$",$sub_array);	
		foreach($sub as $k=>$val)
		{
			if($val!="")
			{
			$_SESSION['e_array'][$position1][$position2]=$val;
			$position2=$position2+1;
			}
		}
		}
		$position1=$position1+1;
	}
	$thesis_reload=1;
	$htmlString.=construct_html();
	$htmlString.="~".construct_html_for_word("","","","","","","",$thesis_reload);
	return $htmlString;
}
function register_user($name,$email,$phone_no,$user_name,$user_password)
{
include('dbconnection.php');	
$sql_insert=mysqli_query($dbconnect,"INSERT into signup(name,email,phone_no,user_name,user_password)VALUES('".$name."','".$email."','".$phone_no."','".$user_name."','".$user_password."')");
if($sql_insert)
	return "Registered succesfully";
}

function login_user($login_name,$login_password,$thesis_name)
{
	include('dbconnection.php');
    $sql_fetch=mysqli_query($dbconnect,"SELECT login_id from signup where user_name='".$login_name."' && user_password='".$login_password."'");
    $sql_count=mysqli_num_rows($sql_fetch);
	if($thesis_name!="")
	{
		$sql=mysqli_fetch_array($sql_fetch);
		$login_id=$sql['login_id'];
		$thesis_name=$thesis_name.$login_id;
		$username=$_SESSION['session_user'];
		$_SESSION['thesis_name']=$thesis_name;
		$sql_insert=mysqli_query($dbconnect,"INSERT INTO thesesfile(loginid,loginuser,theses_name)VALUES('".$login_id."','".$username."','".$thesis_name."')");
	}
	if($sql_count==1)
		return "Login Successfully";
	else
		return "Incorrect User_name or Password" ;	
}
function logout()
{
	
	if(isset($_SESSION['session_user']))
	{
		unset($_SESSION['session_user']);
		unset($_SESSION['thesis_name']);
		session_destroy();
		return "Logged out";
	
	}
}
if (isset($_POST['add_element'])) {
		unset($_POST['add_element']);
        $return = add_element($_POST['element'],$_POST['position1'],$_POST['position2']);
		echo $return;
}
else if (isset($_POST['add_para'])) {
		unset($_POST['add_para']);
        $return = add_para($_POST['para_name'],$_POST['para_content'],$_POST['position1'],$_POST['position2'],$_POST['add_numbering']);
		echo $return;
}
else if (isset($_POST['add_diagram'])) {
		unset($_POST['add_diagram']);
        $return = add_diagram($_POST['diagram_name'],$_POST['diagram_path'],$_POST['height_size'],$_POST['width_size'],$_POST['position1'],$_POST['position2'],$_POST['add_numbering']);
		echo $return;
}
else if (isset($_POST['remove_element'])) {
		unset($_POST['remove_element']);
        $return = remove_element($_POST['position1'],$_POST['position2']);
		echo $return;
}
else if (isset($_POST['download_word'])) {
	unset($_POST['download_word']);
        $return = download_PDF();
		echo $return;
}
else if (isset($_POST['download_PDF'])) {
	unset($_POST['download_PDF']);
        $return = download_PDF();
		echo $return;
}
else if(isset($_POST['resize_image']))
{
	unset($_POST['resize_image']);
	$return=resize_image($_POST['element_type'],$_POST['para_name'],$_POST['content'],$_POST['height'],$_POST['width'],$_POST['k'],$_POST['key']);
	echo $return;
}
else if(isset($_POST['save_thesis'])){
unset($_POST['save_thesis']);
$return=save_thesis();
echo $return;
}
else if(isset($_POST['view_thesis']))
{
	unset($_POST['view_thesis']);
	$return=view_thesis($_POST['filename']);
	echo $return;
}
else if(isset($_POST['edit_element']))
{
	unset($_POST['edit_element']);
	$return=edit_element();
	echo $return;
}
else if(isset($_POST['register_user']))
{
	unset($_POST['register_user']);
	$return=register_user($_POST['name'],$_POST['email'],$_POST['phone_no'],$_POST['user_name'],$_POST['user_password']);
	echo $return;
}
else if(isset($_POST['login_user']))
{
	unset($_POST['login_user']);
	$_SESSION['session_user']=$_POST['login_name'];
	$_SESSION['thesis_name']=$_POST['thesis_name'];
	$return=login_user($_POST['login_name'],$_POST['login_password'],$_POST['thesis_name']);
    echo $return;
}
else if(isset($_POST['logout']))
{
	unset($_POST['logout']);
	$return=logout();
	echo $return;
}
	?>