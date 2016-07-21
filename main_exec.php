<?php
	include_once "config.php";

switch ($_REQUEST['exec'])
{
	case "show_select_grade" :
		$grade_query		= "SELECT * FROM ".$_gl['grade_info_table']."";
		$grade_result		= mysqli_query($my_db, $grade_query);

		$innerHTML	= "<option value=''>선택하세요</option>";
		while ($grade_data	= mysqli_fetch_array($grade_result))
		{
			$innerHTML	.= "<option value='".$grade_data['grade_name']."'>".$grade_data['grade_name']."</option>";
		}

		echo $innerHTML;
	break;

	case "show_select_cate1" :
		$cate1_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_2=0 AND cate_3=0";
		$cate1_result		= mysqli_query($my_db, $cate1_query);

		$innerHTML	= "<option value=''>선택하세요</option>";
		while ($cate1_data	= mysqli_fetch_array($cate1_result))
		{
			$innerHTML	.= "<option value='".$cate1_data['cate_1']."'>".$cate1_data['cate_name']."</option>";
		}

		echo $innerHTML;
	break;

	case "show_select_cate2" :
		$cate2_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_3=0";
		$cate2_result		= mysqli_query($my_db, $cate2_query);

		$innerHTML	= "<option value=''>선택하세요</option>";
		while ($cate2_data	= mysqli_fetch_array($cate2_result))
		{
			$innerHTML	.= "<option value='".$cate2_data['cate_2']."'>".$cate1_data['cate_name']."</option>";
		}

		echo $innerHTML;
	break;

	case "insert_cate_info" :
		$cate_name			= $_REQUEST['cate_name'];
		$cate_1					= $_REQUEST['cate_1'];
		$cate_2					= $_REQUEST['cate_2'];
		$cate_3					= $_REQUEST['cate_3'];
		$cate_pcYN				= $_REQUEST['cate_pcYN'];
		$cate_mobileYN		= $_REQUEST['cate_mobileYN'];
		$cate_accessYN		= $_REQUEST['cate_accessYN'];

		if ($cate_1 == "")
		{
			$care1_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_1 <> 0 and cate_2 = 0 and cate_3 = 0";
			$care1_result		= mysqli_query($my_db, $care1_query);
			$cate1_num		= mysqli_num_rows($care1_result);
			$cate_1				= $cate1_num + 1;
		}else{
			if ($cate_2 == "")
			{
				$care2_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_2 <> 0 and cate_3 = 0";
				$care2_result		= mysqli_query($my_db, $care2_query);
				$cate2_num		= mysqli_num_rows($care2_result);
				$cate_2				= $cate2_num + 1;
			}else{
				if ($cate_3 == "")
				{
					$care3_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_3 <> 0";
					$care3_result		= mysqli_query($my_db, $care3_query);
					$cate3_num		= mysqli_num_rows($care3_result);
					$cate_3				= $cate3_num + 1;
				}
			}
		}
		$cate_query		= "INSERT INTO ".$_gl['category_info_table']."(cate_1, cate_2, cate_3, cate_name, cate_pcYN, cate_mobileYN, cate_accessYN, cate_date) values('".$cate_1."','".$cate_2."','".$cate_3."','".$cate_name."','".$cate_pcYN."','".$cate_mobileYN."','".$cate_accessYN."','".date("Y-m-d H:i:s")."')";
		$cate_result		= mysqli_query($my_db, $cate_query);

		if ($cate_result)
			$flag	= "Y";
		else
			$flag	= "N";

		echo $flag;
	break;
}
?>