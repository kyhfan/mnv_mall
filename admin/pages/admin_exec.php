<?php
	include_once "../../config.php";

	switch ($_REQUEST['exec'])
	{
		
		case "login_admin" :
			$admin_id	= $_REQUEST['admin_id'];
			$admin_pw	= $_REQUEST['admin_pw'];
			$admin_query		= "SELECT * FROM ".$_gl['admin_info_table']." WHERE admin_id='".$admin_id."'";
			$admin_result		= mysqli_query($my_db, $admin_query);
			$admin_data		= mysqli_fetch_array($admin_result);
			// 암호 검증
			if (validate_password($admin_pw,$admin_data['admin_pw']))
			{
				$update_query		= "UPDATE ".$_gl['admin_info_table']." SET admin_lastdate='".date("Y-m-d H:i:s")."' WHERE admin_id='".$admin_data['admin_id']."'";
				$update_result		= mysqli_query($my_db, $update_query);
				// 관리자 아이디, 이름 세션 생성
				$_SESSION['ss_admin_id']			= $admin_data['admin_id'];
				$_SESSION['ss_admin_name']		= $admin_data['admin_name'];
				$flag	= "Y";
			}else{
				$flag	= "N";
			}
			echo $flag;
		break;

		case "join_admin" :
			$admin_id			= $_REQUEST['admin_id'];
			$admin_pw			= $_REQUEST['admin_pw'];
			$admin_name		= $_REQUEST['admin_name'];
			$admin_pw = create_hash($admin_pw);
			$dupli_query		= "SELECT * FROM ".$_gl['admin_info_table']." WHERE admin_id='".$admin_id."'";
			$dupli_result		= mysqli_query($my_db, $dupli_query);
			$dupli_num		= mysqli_fetch_array($dupli_result);
			if ($dupli_num > 0)
			{
				$flag	= "D";
			}else{
				$admin_query		= "INSERT INTO ".$_gl['admin_info_table']."(admin_id,admin_pw,admin_name,admin_regdate) values('".$admin_id."','".$admin_pw."','".$admin_name."','".date('Y-m-d H:i:s')."')";
				$admin_result		= mysqli_query($my_db, $admin_query);
				if ($admin_result > 0)
					$flag	= "Y";
				else
					$flag	= "N";
			}
			echo $flag;
		break;

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
			$cate_1		= $_REQUEST['cate_1'];
			$cate2_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_1='".$cate_1."' AND cate_2<>0 AND cate_3=0";
			$cate2_result		= mysqli_query($my_db, $cate2_query);
			$innerHTML	= "<option value=''>선택하세요</option>";
			while ($cate2_data	= mysqli_fetch_array($cate2_result))
			{
				$innerHTML	.= "<option value='".$cate2_data['cate_2']."'>".$cate2_data['cate_name']."</option>";
			}
			echo $innerHTML;
		break;

		case "show_select_cate3" :
			$cate_1		= $_REQUEST['cate_1'];
			$cate_2		= $_REQUEST['cate_2'];
			$cate3_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_1='".$cate_1."' AND cate_2='".$cate_2."' AND cate_3<>0";
			$cate3_result		= mysqli_query($my_db, $cate3_query);
			$innerHTML	= "<option value=''>선택하세요</option>";
			while ($cate3_data	= mysqli_fetch_array($cate3_result))
			{
				$innerHTML	.= "<option value='".$cate3_data['cate_3']."'>".$cate3_data['cate_name']."</option>";
			}
			echo $innerHTML;
		break;

		case "show_select_banner_type" :
			$banner_query		= "SELECT * FROM ".$_gl['banner_config_info_table']."";
			$banner_result		= mysqli_query($my_db, $banner_query);
			$innerHTML	= "<option value=''>선택하세요</option>";
			while ($banner_data	= mysqli_fetch_array($banner_result))
			{
				$innerHTML	.= "<option value='".$banner_data['banner_type']."'>".$banner_data['banner_type']."</option>";
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
			$access_specific		= $_REQUEST['access_specific'];
			if ($cate_accessYN == "SPECIFIC")
				$accessYN	= $access_specific;
			else
				$accessYN	= $cate_accessYN;
			if ($cate_1 == "")
			{
				$care1_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_1 <> 0 and cate_2 = 0 and cate_3 = 0";
				$care1_result		= mysqli_query($my_db, $care1_query);
				$cate1_num		= @mysqli_num_rows($care1_result);
				$cate_1				= $cate1_num + 1;
			}else{
				if ($cate_2 == "")
				{
					$cate2_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_1='".$cate_1."' AND cate_2 <> 0 and cate_3 = 0";
					$cate2_result		= mysqli_query($my_db, $cate2_query);
					$cate2_num		= @mysqli_num_rows($cate2_result);
					$cate_2				= $cate2_num + 1;
				}else{
					if ($cate_3 == "")
					{
						$cate3_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_1='".$cate_1."' AND cate_2='".$cate_2."' AND cate_3 <> 0";
						$cate3_result		= mysqli_query($my_db, $cate3_query);
						$cate3_num		= @mysqli_num_rows($cate3_result);
						$cate_3				= $cate3_num + 1;
					}
				}
			}
			$cate_query		= "INSERT INTO ".$_gl['category_info_table']."(cate_1, cate_2, cate_3, cate_name, cate_pcYN, cate_mobileYN, cate_accessYN, cate_date) values('".$cate_1."','".$cate_2."','".$cate_3."','".$cate_name."','".$cate_pcYN."','".$cate_mobileYN."','".$accessYN."','".date("Y-m-d H:i:s")."')";
			$cate_result		= mysqli_query($my_db, $cate_query);
			if ($cate_result)
				$flag	= "Y";
			else
				$flag	= "N";
			echo $flag;
		break;

		case "update_cate_info" :
			$idx						= $_REQUEST['idx']; 
			$cate_name			= $_REQUEST['cate_name']; 
			$cate_1					= $_REQUEST['cate_1'];
			$cate_2					= $_REQUEST['cate_2'];
			$cate_3					= $_REQUEST['cate_3'];
			$cate_pcYN				= $_REQUEST['cate_pcYN'];
			$cate_mobileYN		= $_REQUEST['cate_mobileYN'];
			$cate_accessYN		= $_REQUEST['cate_accessYN'];
			$access_specific		= $_REQUEST['access_specific'];
			if ($cate_accessYN == "SPECIFIC")
				$accessYN	= $access_specific;
			else
				$accessYN	= $cate_accessYN;
			if ($cate_1 == "")
			{
				$care1_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_1 <> 0 and cate_2 = 0 and cate_3 = 0";
				$care1_result		= mysqli_query($my_db, $care1_query);
				$cate1_num		= @mysqli_num_rows($care1_result);
				$cate_1				= $cate1_num + 1;
			}else{
				if ($cate_2 == "")
				{
					$cate2_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_1='".$cate_1."' AND cate_2 <> 0 and cate_3 = 0";
					$cate2_result		= mysqli_query($my_db, $cate2_query);
					$cate2_num		= @mysqli_num_rows($cate2_result);
					$cate_2				= $cate2_num + 1;
				}else{
					if ($cate_3 == "")
					{
						$cate3_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE cate_1='".$cate_1."' AND cate_2='".$cate_2."' AND cate_3 <> 0";
						$cate3_result		= mysqli_query($my_db, $cate3_query);
						$cate3_num		= @mysqli_num_rows($cate3_result);
						$cate_3				= $cate3_num + 1;
					}
				}
			}
			$cate_query		= "UPDATE ".$_gl['category_info_table']." SET cate_1='".$cate_1."', cate_2='".$cate_2."', cate_3='".$cate_3."', cate_name='".$cate_name."', cate_pcYN='".$cate_pcYN."', cate_mobileYN='".$cate_mobileYN."', cate_accessYN='".$accessYN."' WHERE idx='".$idx."'";
			$cate_result		= mysqli_query($my_db, $cate_query);
			if ($cate_result)
				$flag	= "Y";
			else
				$flag	= "N";
			echo $flag;
		break;

		case "insert_goods_info" :
			$showYN						= $_REQUEST['showYN'];
			$salesYN						= $_REQUEST['salesYN'];
			$cate_1							= $_REQUEST['cate_1'];
			$cate_2							= $_REQUEST['cate_2'];
			$cate_3							= $_REQUEST['cate_3'];
			$goods_name					= $_REQUEST['goods_name'];
			$goods_eng_name			= $_REQUEST['goods_eng_name'];
			$goods_model				= $_REQUEST['goods_model'];
			$goods_brand					= $_REQUEST['goods_brand'];
			$goods_status					= $_REQUEST['goods_status'];
			$goods_small_desc			= $_REQUEST['goods_small_desc'];
			$goods_middle_desc		= $_REQUEST['goods_middle_desc'];
			$goods_big_desc				= $_REQUEST['goods_big_desc'];
			$m_goods_big_descYN		= $_REQUEST['m_goods_big_descYN'];
			$m_goods_big_desc			= $_REQUEST['m_goods_big_desc'];
			$supply_price					= $_REQUEST['supply_price'];
			$sales_price					= $_REQUEST['sales_price'];
			$saved_priceYN				= $_REQUEST['saved_priceYN'];
			$saved_price					= $_REQUEST['saved_price'];
			$goods_optionYN			= $_REQUEST['goods_optionYN'];
			$goods_option_txt			= $_REQUEST['goods_option_txt'];
			$goods_stock					= $_REQUEST['goods_stock'];
			$goods_code					= create_goodscode();
			$goods_query		= "INSERT INTO ".$_gl['goods_info_table']."(showYN,salesYN,cate_1,cate_2,cate_3,goods_name,goods_eng_name,goods_code,goods_model,goods_brand,goods_status,goods_small_desc,goods_middle_desc,goods_big_desc,m_goods_big_descYN,m_goods_big_desc,supply_price,sales_price,saved_priceYN,saved_price,goods_optionYN,goods_option_txt,goods_stock,goods_regdate) values('".$showYN."','".$salesYN."','".$cate_1."','".$cate_2."','".$cate_3."','".$goods_name."','".$goods_eng_name."','".$goods_code."','".$goods_model."','".$goods_brand."','".$goods_status."','".$goods_small_desc."','".$goods_middle_desc."','".$goods_big_desc."','".$m_goods_big_descYN."','".$m_goods_big_desc."','".$supply_price."','".$sales_price."','".$saved_priceYN."','".$saved_price."','".$goods_optionYN."','".$goods_option_txt."','".$goods_stock."','".date("Y-m-d H:i:s")."')";
			$goods_result		= mysqli_query($my_db, $goods_query);
			if ($goods_result)
				$flag	= "Y||".$goods_code;
			else
				$flag	= "N||".$goods_code;
			echo $flag;
		break;

		case "update_goods_info" :
			$showYN						= $_REQUEST['showYN'];
			$salesYN						= $_REQUEST['salesYN'];
			$cate_1							= $_REQUEST['cate_1'];
			$cate_2							= $_REQUEST['cate_2'];
			$cate_3							= $_REQUEST['cate_3'];
			$goods_name					= $_REQUEST['goods_name'];
			$goods_eng_name			= $_REQUEST['goods_eng_name'];
			$goods_model				= $_REQUEST['goods_model'];
			$goods_brand					= $_REQUEST['goods_brand'];
			$goods_status					= $_REQUEST['goods_status'];
			$goods_small_desc			= $_REQUEST['goods_small_desc'];
			$goods_middle_desc		= $_REQUEST['goods_middle_desc'];
			$goods_big_desc				= $_REQUEST['goods_big_desc'];
			$m_goods_big_descYN		= $_REQUEST['m_goods_big_descYN'];
			$m_goods_big_desc			= $_REQUEST['m_goods_big_desc'];
			$supply_price					= $_REQUEST['supply_price'];
			$sales_price					= $_REQUEST['sales_price'];
			$saved_priceYN				= $_REQUEST['saved_priceYN'];
			$saved_price					= $_REQUEST['saved_price'];
			$goods_optionYN			= $_REQUEST['goods_optionYN'];
			$goods_option_txt			= $_REQUEST['goods_option_txt'];
			$goods_stock					= $_REQUEST['goods_stock'];
			$goods_code					= $_REQUEST['goods_code'];
			$goods_query		= "UPDATE ".$_gl['goods_info_table']." SET showYN='".$showYN."',salesYN='".$salesYN."',cate_1='".$cate_1."',cate_2='".$cate_2."',cate_3='".$cate_3."',goods_name='".$goods_name."',goods_eng_name='".$goods_eng_name."',goods_model='".$goods_model."',goods_brand='".$goods_brand."',goods_status='".$goods_status."',goods_small_desc='".$goods_small_desc."',goods_middle_desc='".$goods_middle_desc."',goods_big_desc='".$goods_big_desc."',m_goods_big_descYN='".$m_goods_big_descYN."',m_goods_big_desc='".$m_goods_big_desc."',supply_price='".$supply_price."',sales_price='".$sales_price."',saved_priceYN='".$saved_priceYN."',saved_price='".$saved_price."',goods_optionYN='".$goods_optionYN."',goods_option_txt='".$goods_option_txt."',goods_stock='".$goods_stock."',goods_latedate='".date("Y-m-d H:i:s")."' WHERE goods_code='".$goods_code."'";
			$goods_result		= mysqli_query($my_db, $goods_query);
			if ($goods_result)
				$flag	= "Y";
			else
				$flag	= "N";
			echo $flag;
		break;

		case "show_category_list" :
			$target	= $_REQUEST['target'];
			$list_query		= "SELECT * FROM ".$_gl['category_info_table']." WHERE 1 ORDER BY cate_1 ASC, cate_2 ASC, cate_3 ASC";
			$list_result		= mysqli_query($my_db, $list_query);
			$innerHTML	= "<thead>";
			$innerHTML	.= "<tr>";
			$innerHTML	.= "<th>1번 카테고리</th>";
			$innerHTML	.= "<th>2번 카테고리</th>";
			$innerHTML	.= "<th>3번 카테고리</th>";
			$innerHTML	.= "<th>카테고리 명</th>";
			$innerHTML	.= "<th>PC 화면 노출여부</th>";
			$innerHTML	.= "<th>MOBILE 화면 노출여부</th>";
			$innerHTML	.= "<th>카테고리 접속 권한</th>";
			$innerHTML	.= "<th>카테고리 생성 날짜</th>";
			$innerHTML	.= "<th></th>";
			$innerHTML	.= "</tr>";
			$innerHTML	.= "</thead>";
			$innerHTML	.= "<tbody>";
			//$i	= 1;
			while ($list_data = mysqli_fetch_array($list_result))
			{
				$innerHTML	.= "<tr>";
				$innerHTML	.= "<td>".$list_data['cate_1']."</td>";
				$innerHTML	.= "<td>".$list_data['cate_2']."</td>";
				$innerHTML	.= "<td>".$list_data['cate_3']."</td>";
				$innerHTML	.= "<td>".$list_data['cate_name']."</td>";
				$innerHTML	.= "<td>".$list_data['cate_pcYN']."</td>";
				$innerHTML	.= "<td>".$list_data['cate_mobileYN']."</td>";
				$innerHTML	.= "<td>".$list_data['cate_accessYN']."</td>";
				$innerHTML	.= "<td>".$list_data['cate_date']."</td>";
				$innerHTML	.= "<td><a href='./category_detail.php?idx=".$list_data['idx']."'><button type='button' class='btn btn-primary'>수정</button></a> <a href='#' onclick='delete_goods(".$list_data['goods_name'].",".$list_data['goods_code'].");return false;'><button type='button' class='btn btn-danger'>삭제</button></a></td>";
				$innerHTML	.= "</tr>";
				//$i++;
			}
			$innerHTML	.= "</tbody>";
			echo $innerHTML;
		break;

		case "show_purchasing_list" :
			$target	= $_REQUEST['target'];
			$list_query		= "SELECT * FROM ".$_gl['purchasing_info_table']." WHERE 1 ORDER BY idx DESC";
			$list_result		= mysqli_query($my_db, $list_query);
			$innerHTML	= "<thead>";
			$innerHTML	.= "<tr>";
			$innerHTML	.= "<th>거래처명</th>";
			$innerHTML	.= "<th>거래처 주소</th>";
			$innerHTML	.= "<th>거래처 전화번호</th>";
			$innerHTML	.= "<th>거래처 특이사항</th>";
			$innerHTML	.= "<th>거래처 등록일자</th>";
			$innerHTML	.= "<th>거래처 최근 정보수정일자</th>";
			$innerHTML	.= "<th></th>";
			$innerHTML	.= "</tr>";
			$innerHTML	.= "</thead>";
			$innerHTML	.= "<tbody>";
			//$i	= 1;
			while ($list_data = mysqli_fetch_array($list_result))
			{
				$innerHTML	.= "<tr>";
				$innerHTML	.= "<td>".$list_data['purchasing_name']."</td>";
				$innerHTML	.= "<td>".$list_data['purchasing_addr']."</td>";
				$innerHTML	.= "<td>".$list_data['purchasing_phone']."</td>";
				$innerHTML	.= "<td>".$list_data['purchasing_desc']."</td>";
				$innerHTML	.= "<td>".$list_data['purchasing_regdate']."</td>";
				$innerHTML	.= "<td>".$list_data['purchasing_latedate']."</td>";
				$innerHTML	.= "<td><a href='./purchasing_detail.php?idx=".$list_data['idx']."'><button type='button' class='btn btn-primary'>수정</button></a> <a href='#' class='del_purchasing' data-idx='".$list_data['idx']."'><button type='button' class='btn btn-danger'>삭제</button></a></td>";
				$innerHTML	.= "</tr>";
				//$i++;
			}
			$innerHTML	.= "</tbody>";
			echo $innerHTML;
		break;

		case "show_goods_list" :
			$target	= $_REQUEST['target'];
			$list_query		= "SELECT * FROM ".$_gl['goods_info_table']." WHERE 1 ORDER BY idx DESC";
			$list_result		= mysqli_query($my_db, $list_query);
			$innerHTML	= "<thead>";
			$innerHTML	.= "<tr>";
			$innerHTML	.= "<th>순번</th>";
			$innerHTML	.= "<th>진열상태</th>";
			$innerHTML	.= "<th>판매상태</th>";
			$innerHTML	.= "<th>상품 코드</th>";
			$innerHTML	.= "<th>상품명</th>";
			$innerHTML	.= "<th>모델명</th>";
			$innerHTML	.= "<th>판매가</th>";
			$innerHTML	.= "<th>재고</th>";
			$innerHTML	.= "<th>등록일/수정일</th>";
			$innerHTML	.= "<th></th>";
			$innerHTML	.= "</tr>";
			$innerHTML	.= "</thead>";
			$innerHTML	.= "<tbody>";
			//$i	= 1;
			while ($list_data = mysqli_fetch_array($list_result))
			{
				$innerHTML	.= "<tr>";
				$innerHTML	.= "<td></td>";
				$innerHTML	.= "<td>".$list_data['showYN']."</td>";
				$innerHTML	.= "<td>".$list_data['salesYN']."</td>";
				$innerHTML	.= "<td>".$list_data['goods_code']."</td>";
				$innerHTML	.= "<td>".$list_data['goods_name']."</td>";
				$innerHTML	.= "<td>".$list_data['goods_model']."</td>";
				$innerHTML	.= "<td>".$list_data['sales_price']."</td>";
				$innerHTML	.= "<td>".$list_data['goods_stock']."</td>";
				$innerHTML	.= "<td>".$list_data['goods_regdate']."/".$list_data['goods_latedate']."</td>";
				$innerHTML	.= "<td><a href='./goods_detail.php?goodscode=".$list_data['goods_code']."'><button type='button' class='btn btn-primary'>수정</button></a> <a href='#' class='del_goods' data-goodscode='".$list_data['goods_code']."'><button type='button' class='btn btn-danger'>삭제</button></a></td>";
				$innerHTML	.= "</tr>";
				//$i++;
			}
			$innerHTML	.= "</tbody>";
			echo $innerHTML;
		break;

		case "delete_goods_info" :
			$goodscode	= $_REQUEST['goodscode'];
			$goods_query		= "DELETE FROM ".$_gl['goods_info_table']." WHERE goods_code='".$goodscode."'";
			$goods_result		= mysqli_query($my_db, $goods_query);
			if ($goods_result)
				$flag	= "Y";
			else
				$flag	= "N";
			echo $flag;
		break;

		case "show_stock_list" :
			$target	= $_REQUEST['target'];
			$list_query		= "SELECT * FROM ".$_gl['goods_info_table']." WHERE 1 ORDER BY idx DESC";
			$list_result		= mysqli_query($my_db, $list_query);
			$innerHTML	= "<thead>";
			$innerHTML	.= "<tr>";
			$innerHTML	.= "<th>순번</th>";
			$innerHTML	.= "<th>상품 코드</th>";
			$innerHTML	.= "<th>상품명</th>";
			$innerHTML	.= "<th>모델명</th>";
			$innerHTML	.= "<th>재고</th>";
			$innerHTML	.= "</tr>";
			$innerHTML	.= "</thead>";
			$innerHTML	.= "<tbody>";
			//$i	= 1;
			while ($list_data = mysqli_fetch_array($list_result))
			{
				$innerHTML	.= "<tr>";
				$innerHTML	.= "<td></td>";
				$innerHTML	.= "<td>".$list_data['goods_code']."</td>";
				$innerHTML	.= "<td>".$list_data['goods_name']."</td>";
				$innerHTML	.= "<td>".$list_data['goods_model']."</td>";
				$innerHTML	.= "<td id='".$list_data['goods_code']."' class='stock_td'>".$list_data['goods_stock']."</td>";
				$innerHTML	.= "</tr>";
				//$i++;
			}
			$innerHTML	.= "</tbody>";
			echo $innerHTML;
		break;

		case "update_stock_info" :
			$goods_code	= $_REQUEST['goods_code'];
			$goods_stock	= $_REQUEST['goods_stock'];
			$stock_query		= "UPDATE ".$_gl['goods_info_table']." SET goods_stock='".$goods_stock."' WHERE goods_code='".$goods_code."'"; 
			$stock_result		= mysqli_query($my_db, $stock_query);
			if ($stock_result)
				$flag	= "Y";
			else
				$flag	= "N";
			echo $flag;
			break;
		case "insert_purchasing_info" :
			$purchasing_name	= $_REQUEST['purchasing_name'];
			$purchasing_addr	= $_REQUEST['purchasing_addr'];
			$purchasing_phone	= $_REQUEST['purchasing_phone'];
			$purchasing_desc	= $_REQUEST['purchasing_desc'];
			$purchasing_query		= "INSERT INTO ".$_gl['purchasing_info_table']."(purchasing_name,purchasing_addr,purchasing_phone,purchasing_desc,purchasing_regdate) values('".$purchasing_name."','".$purchasing_addr."','".$purchasing_phone."','".$purchasing_desc."','".date("Y-m-d H:i:s")."');"; 
			$purchasing_result		= mysqli_query($my_db, $purchasing_query);
			if ($purchasing_result)
				$flag	= "Y";
			else
				$flag	= "N";
			echo $flag;
		break;

		case "update_purchasing_info" :
			$idx						= $_REQUEST['idx'];
			$purchasing_name	= $_REQUEST['purchasing_name'];
			$purchasing_addr	= $_REQUEST['purchasing_addr'];
			$purchasing_phone	= $_REQUEST['purchasing_phone'];
			$purchasing_desc	= $_REQUEST['purchasing_desc'];
			$purchasing_query		= "UPDATE ".$_gl['purchasing_info_table']." SET purchasing_name='".$purchasing_name."',purchasing_addr='".$purchasing_addr."',purchasing_phone='".$purchasing_phone."',purchasing_desc='".$purchasing_desc."',purchasing_latedate='".date("Y-m-d H:i:s")."' WHERE idx='".$idx."'";
			$purchasing_result		= mysqli_query($my_db, $purchasing_query);
			if ($purchasing_result)
				$flag	= "Y";
			else
				$flag	= "N";
			echo $flag;
		break;

		case "show_banner_detail" :
			$banner_type	= $_REQUEST['banner_type'];
			$list_query		= "SELECT * FROM ".$_gl['banner_config_info_table']." WHERE banner_type='".$banner_type."'";
			$list_result		= mysqli_query($my_db, $list_query);
			$list_data = mysqli_fetch_array($list_result);
			echo $list_data['banner_speed']."||".$list_data['banner_time']."||".$list_data['banner_effect'];
		break;

		// 회원가입시 아이디 중복체크
		case "duplicate_check": 
			$input = $_REQUEST['input'];
			$chk_id_query 	= "SELECT * FROM ".$_gl['member_info_table']." WHERE mb_id='".$input."'";
			$chk_id_result 	= mysqli_query($my_db, $chk_id_query);
			$chk_id_data	= mysqli_num_rows($chk_id_result);
			if($chk_id_data > 0) {
				$flag = "N";
			}else{
				$flag = "Y";
			}
			echo $flag;
		break;

		case "member_join":
			$user_id = preg_replace("/\s+/", "", $_POST['user_id']);
			$password = preg_replace("/\s+/", "", $_POST['password']);
			$username = preg_replace("/\s+/", "", $_POST['username']);
			$zipcode = $_POST['zipcode'];
			$addr1 = $_POST['addr1'];
			$addr2 = trim($_POST['addr2']);
			$email1 = preg_replace("/\s+/", "", $_POST['email1']);
			$email2 = $_POST['email2'];
			$emailYN = $_POST['emailYN'];
			$tel1 = $_POST['tel1'];
			$tel2 = preg_replace("/\s+/", "", $_POST['tel2']);
			$tel3 = preg_replace("/\s+/", "", $_POST['tel3']);
			$phone1 = $_POST['phone1'];
			$phone2 = $_POST['phone2'];
			$phone3 = $_POST['phone3'];
			$smsYN = $_POST['smsYN'];
			$birthY = preg_replace("/\s+/", "", $_POST['birthY']);
			$birthM = preg_replace("/\s+/", "", $_POST['birthM']);
			$birthD = preg_replace("/\s+/", "", $_POST['birthD']);
			$email = $email1 . '@' . $email2;
			if($tel2 == '') {
				$tel = '';
			}else{
				$tel = $tel1 . '-' . $tel2 . '-' . $tel3;
			}
			$phone = $phone1 . '-' . $phone2 . '-' . $phone3;
			if($birthY !== '' && $birthM !== '' && $birthD !== '') {
				if($birthM < 10 && strlen($birthM) < 2) {
					$birthM = "/0".$birthM;
				}else{
					$birthM = "/".$birthM;
				}
				if($birthD < 10 && strlen($birthD) < 2) {
					$birthD = "/0".$birthD;
				}else{
					$birthD = "/".$birthD;
				}
			}
			$birth = $birthY . $birthM . $birthD;
			// 등급 수정할것
			$grade = "silver";
			$insert_query    = "INSERT INTO ".$_gl['member_info_table']."(mb_id, mb_password, mb_name, mb_birth, mb_address1, mb_address2, mb_zipcode, mb_telphone, mb_handphone, mb_smsYN, mb_email, mb_emailYN, mb_grade, mb_join_date, mb_join_ipaddr) values('".$user_id."','".$password."','".$username."','".$birth."','".$addr1."','".$addr2."','".$zipcode."','".$tel."','".$phone."','".$smsYN."','".$email."','".$emailYN."','".$grade."','".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."')";
			$insert_result   = mysqli_query($my_db, $insert_query);
			// result - 메일 발송
			if($insert_result) {
				$mail_reult = sendMail("ojoonwoo2@gmail.com", "촌의감각", "회원가입을 축하합니다.", "내용", "ojoonwoo@naver.com", "$username");
				$flag = "Y";
			}else{
				$flag = "N";
			}
			echo $flag;
		break;

		case "member_modify":
			$user_id = preg_replace("/\s+/", "", $_POST['user_id']);
			$password = preg_replace("/\s+/", "", $_POST['password']);
			$username = preg_replace("/\s+/", "", $_POST['username']);
			$zipcode = $_POST['zipcode'];
			$addr1 = $_POST['addr1'];
			$addr2 = trim($_POST['addr2']);
			$email1 = preg_replace("/\s+/", "", $_POST['email1']);
			$email2 = $_POST['email2'];
			$emailYN = $_POST['emailYN'];
			$tel1 = $_POST['tel1'];
			$tel2 = preg_replace("/\s+/", "", $_POST['tel2']);
			$tel3 = preg_replace("/\s+/", "", $_POST['tel3']);
			$phone1 = $_POST['phone1'];
			$phone2 = $_POST['phone2'];
			$phone3 = $_POST['phone3'];
			$smsYN = $_POST['smsYN'];
			$birthY = preg_replace("/\s+/", "", $_POST['birthY']);
			$birthM = preg_replace("/\s+/", "", $_POST['birthM']);
			$birthD = preg_replace("/\s+/", "", $_POST['birthD']);
			$email = $email1 . '@' . $email2;
			if($tel2 == '') {
				$tel = '';
			}else{
				$tel = $tel1 . '-' . $tel2 . '-' . $tel3;
			} 
			$phone = $phone1 . '-' . $phone2 . '-' . $phone3;
			if($birthM !== '' && ($birthM < 10 && strlen($birthM) < 2)) {
				$birthM = "/0".$birthM;
			}else{
				$birthM = "/".$birthM;
			}
			if($birthD !== '' && ($birthD < 10 && strlen($birthD) < 2)) {
				$birthD = "/0".$birthD;
			}else{
				$birthD = "/".$birthD;
			}
			$birth = $birthY . $birthM . $birthD;
			$update_query = "UPDATE ".$_gl['member_info_table']." SET mb_password='".$password."',mb_name='".$username."',mb_birth='".$birth."',mb_address1='".$addr1."',mb_address2='".$addr2."',mb_zipcode='".$zipcode."',mb_telphone='".$tel."',mb_handphone='".$phone."',mb_smsYN='".$smsYN."',mb_email='".$email."',mb_emailYN='".$emailYN."',mb_update_date='".date("Y-m-d H:i:s")."' WHERE mb_id='".$user_id."'";
			$update_result   = mysqli_query($my_db, $update_query);
			if($update_result) {
				$flag = "Y";
			}else{
				$flag = "N";
			}
			echo $flag;
		break;

		// 회원수정시 회원본인인지 체크
		case "member_check":
			$m_id = $_REQUEST['m_id'];
			$m_pw = $_REQUEST['m_pw'];
			$pw_query		= "SELECT mb_id,mb_name,mb_handphone,mb_telphone,mb_zipcode,mb_address1,mb_address2,mb_birth,mb_email,mb_emailYN,mb_smsYN FROM ".$_gl['member_info_table']." WHERE mb_id='".$m_id."' AND mb_password='".$m_pw."'";
			$pw_result		= mysqli_query($my_db, $pw_query);
			$pw_data 		= mysqli_fetch_array($pw_result);
			if($pw_data) {
				$flag = "Y";
			}else{
				$flag = "N";
			}
			echo $flag;
			// $id_query		= "SELECT * FROM ".$_gl['member_info_table']." WHERE mb_id='".$m_id."'";
			// $id_result		= mysqli_query($my_db, $id_query);
			// $id_data = mysqli_fetch_array($id_result);
			// if($id_data) { // 아이디가 있을경우 입력한 비밀번호 검사
			// 	$pw_query		= "SELECT mb_id,mb_name,mb_question,mb_answer,mb_handphone,mb_telphone,mb_zipcode,mb_address1,mb_address2,mb_birth,mb_email,mb_emailYN,mb_gender,mb_smsYN FROM ".$_gl['member_info_table']." WHERE mb_id='".$id_data['mb_id']."' AND mb_password='".$m_pw."'";
			// 	$pw_result		= mysqli_query($my_db, $pw_query);
			// 	$pw_data 		= mysqli_fetch_array($pw_result);
			// 	if($pw_data) {
			// 		echo json_encode($pw_data);
			// 	}else{
			// 		echo json_encode("P");
			// 	}
			// }else{ // 입력한 아이디가 없는경우
			// 	echo json_encode("N");
			// }
		break;

		case "show_member_list" :
			$target	= $_REQUEST['target'];
			$list_query		= "SELECT * FROM ".$_gl['member_info_table']." WHERE 1 ORDER BY idx DESC";
			$list_result		= mysqli_query($my_db, $list_query);
			$innerHTML	= "<thead>";
			$innerHTML	.= "<tr>";
			$innerHTML	.= "<th><input type='checkbox' name='all_check' id='all_check'></th>";
			$innerHTML	.= "<th>순번</th>";
			$innerHTML	.= "<th>아이디</th>";
			$innerHTML	.= "<th>이름</th>";
			$innerHTML	.= "<th>등급</th>";
			$innerHTML	.= "<th>회원가입일시</th>";
			$innerHTML	.= "<th>최종로그인</th>";
			$innerHTML	.= "<th>메일/SMS 발송</th>";
			$innerHTML	.= "<th>정보수정</th>";
			$innerHTML	.= "</tr>";
			$innerHTML	.= "</thead>";
			$innerHTML	.= "<tbody>";
			//$i	= 1;
			while ($list_data = mysqli_fetch_array($list_result))
			{
				$user_id = $list_data['mb_id'];
				$innerHTML	.= "<tr>";
				$innerHTML	.= "<td><input type='checkbox' name='one_check' id='one_check'></td>";
				$innerHTML	.= "<td></td>";
				$innerHTML	.= "<td>".$list_data['mb_id']."</td>";
				$innerHTML	.= "<td>".$list_data['mb_name']."</td>";
				$innerHTML	.= "<td>".$list_data['mb_grade']."</td>";
				$innerHTML	.= "<td>".$list_data['mb_join_date']."</td>";
				$innerHTML	.= "<td>".$list_data['mb_login_date']."</td>";
				$innerHTML	.= "<td><input type='button' id='send_mail' onclick='sendMail();' value='메일'>&nbsp;
										<input type='button' id='send_sms' onclick='sendSMS();' value='SMS'></td>";
				$innerHTML	.= "<td><a href='./modify_form.php?userid=".$list_data['mb_id']."'>
											<input type='button' value='수정'><a></td>";
				$innerHTML	.= "</tr>";
				//$i++;
			}
			$innerHTML	.= "</tbody>";
			echo $innerHTML;
		break;

		case "member_modify":
			$user_id = preg_replace("/\s+/", "", $_POST['user_id']);
			$password = preg_replace("/\s+/", "", $_POST['password']);
			$username = preg_replace("/\s+/", "", $_POST['username']);
			$zipcode = $_POST['zipcode'];
			$addr1 = $_POST['addr1'];
			$addr2 = trim($_POST['addr2']);
			$email1 = preg_replace("/\s+/", "", $_POST['email1']);
			$email2 = $_POST['email2'];
			$emailYN = $_POST['emailYN'];
			$tel1 = $_POST['tel1'];
			$tel2 = preg_replace("/\s+/", "", $_POST['tel2']);
			$tel3 = preg_replace("/\s+/", "", $_POST['tel3']);
			$phone1 = $_POST['phone1'];
			$phone2 = $_POST['phone2'];
			$phone3 = $_POST['phone3'];
			$smsYN = $_POST['smsYN'];
			$birthY = preg_replace("/\s+/", "", $_POST['birthY']);
			$birthM = preg_replace("/\s+/", "", $_POST['birthM']);
			$birthD = preg_replace("/\s+/", "", $_POST['birthD']);
			$grade = preg_replace("/\s+/", "", $_POST['grade']);
			$email = $email1 . '@' . $email2;
			if($tel2 == '') {
				$tel = '';
			}else{
				$tel = $tel1 . '-' . $tel2 . '-' . $tel3;
			} 
			$phone = $phone1 . '-' . $phone2 . '-' . $phone3;
			if($birthM !== '' && ($birthM < 10 && strlen($birthM) < 2)) {
				$birthM = "/0".$birthM;
			}else{
				$birthM = "/".$birthM;
			}
			if($birthD !== '' && ($birthD < 10 && strlen($birthD) < 2)) {
				$birthD = "/0".$birthD;
			}else{
				$birthD = "/".$birthD;
			}
			$birth = $birthY . $birthM . $birthD;
			$update_query = "UPDATE ".$_gl['member_info_table']." SET mb_password='".$password."',mb_name='".$username."',mb_birth='".$birth."',mb_address1='".$addr1."',mb_address2='".$addr2."',mb_zipcode='".$zipcode."',mb_telphone='".$tel."',mb_handphone='".$phone."',mb_smsYN='".$smsYN."',mb_email='".$email."',mb_emailYN='".$emailYN."',mb_grade='".$grade."',mb_update_date='".date("Y-m-d H:i:s")."' WHERE mb_id='".$user_id."'";
			$update_result   = mysqli_query($my_db, $update_query);
			if($update_result) {
				$flag = "Y";
			}else{
				$flag = "N";
			}
			echo $flag;
		break;

		case "insert_banner_info" :
			$banner_name			= $_REQUEST['banner_name'];
			$banner_type				= $_REQUEST['banner_type'];
			$banner_value				= $_REQUEST['banner_value'];
			$banner_showYN			= $_REQUEST['banner_showYN'];
			$banner_show_order	= $_REQUEST['banner_show_order'];
			$banner_link_target		= $_REQUEST['banner_link_target'];
			$banner_query	= "INSERT INTO ".$_gl['banner_info_table']."(banner_name,banner_type,banner_showYN,banner_show_order,banner_img_link,banner_link_target,banner_regdate) values('".$banner_name."','".$banner_type."','".$banner_showYN."','".$banner_show_order."','".$banner_value."','".$banner_link_target."','".date("Y-m-d H:i:s")."')";
			$banner_result	= mysqli_query($my_db, $banner_query);
			$id_num				= mysqli_insert_id($my_db);
			if($banner_result)
				$flag = $id_num;
			else
				$flag = "0";
			echo $flag;
		break;

		case "show_banner_list" :
			$target	= $_REQUEST['target'];
			$list_query		= "SELECT * FROM ".$_gl['banner_info_table']." WHERE 1 ORDER BY idx DESC";
			$list_result		= mysqli_query($my_db, $list_query);
			$innerHTML	= "<thead>";
			$innerHTML	.= "<tr>";
			$innerHTML	.= "<th>배너 이름</th>";
			$innerHTML	.= "<th>배너 타입</th>";
			$innerHTML	.= "<th>배너 노출 여부</th>";
			$innerHTML	.= "<th>배너 표시 순서</th>";
			$innerHTML	.= "<th>배너 이미지</th>";
			$innerHTML	.= "<th>배너 링크</th>";
			$innerHTML	.= "<th>배너 타겟</th>";
			$innerHTML	.= "<th>배너 등록일</th>";
			$innerHTML	.= "<th></th>";
			$innerHTML	.= "</tr>";
			$innerHTML	.= "</thead>";
			$innerHTML	.= "<tbody>";
			//$i	= 1;
			while ($list_data = mysqli_fetch_array($list_result))
			{
				$innerHTML	.= "<tr>";
				$innerHTML	.= "<td>".$list_data['banner_name']."</td>";
				$innerHTML	.= "<td>".$list_data['banner_type']."</td>";
				$innerHTML	.= "<td>".$list_data['banner_showYN']."</td>";
				$innerHTML	.= "<td>".$list_data['banner_show_order']."</td>";
				$innerHTML	.= "<td>".$list_data['banner_img_url']."</td>";
				$innerHTML	.= "<td>".$list_data['banner_img_link']."</td>";
				$innerHTML	.= "<td>".$list_data['banner_link_target']."</td>";
				$innerHTML	.= "<td>".$list_data['banner_regdate']."</td>";
				$innerHTML	.= "<td><a href='./banner_detail.php?idx=".$list_data['idx']."'><button type='button' class='btn btn-primary'>수정</button></a> <a href='#' class='del_banner' data-idx='".$list_data['idx']."'><button type='button' class='btn btn-danger'>삭제</button></a></td>";
				$innerHTML	.= "</tr>";
				//$i++;
			}
			$innerHTML	.= "</tbody>";
			echo $innerHTML;
		break;

		case "show_review_list":

			$target	= $_REQUEST['target'];
			$list_query		= "SELECT * FROM ".$_gl['board_review_table']." WHERE 1 ORDER BY thread DESC";
			$list_result	= mysqli_query($my_db, $list_query);
			$innerHTML	= "<thead>";
			$innerHTML	.= "<tr>";
			$innerHTML	.= "<th><input type='checkbox' name='all_check' id='all_check'></th>";
			$innerHTML	.= "<th>순번</th>";
			$innerHTML	.= "<th>상품코드</th>";
			$innerHTML	.= "<th>제목</th>";
			$innerHTML	.= "<th>작성자</th>";
			$innerHTML	.= "<th>작성일시</th>";
			$innerHTML	.= "<th>조회수</th>";
			$innerHTML	.= "<th>답변</th>";
			$innerHTML	.= "</tr>";
			$innerHTML	.= "</thead>";
			$innerHTML	.= "<tbody>";
			//$i	= 1;
			while ($list_data = mysqli_fetch_array($list_result))
			{
				$innerHTML	.= "<tr>";
				$innerHTML	.= "<td><input type='checkbox' name='one_check' id='one_check'></td>";
				$innerHTML	.= "<td></td>";
				$innerHTML	.= "<td>".$list_data['goods_code']."</td>";
				$innerHTML	.= "<td>";
				if($list_data['depth']>0)
				{
					$depth = $list_data['depth']*7;
					$innerHTML	.= "<img height='1' width=$depth>";
				}
				$innerHTML	.= "<a href='./read_review.php?idx=".$list_data['idx']."'>
										".$list_data['subject']."
										</td>";
				$innerHTML	.= "<td>".$list_data['user_id']."</td>";
				$innerHTML	.= "<td>".$list_data['date']."</td>";
				$innerHTML	.= "<td>".$list_data['hit']."</td>";
				$innerHTML	.= "<td>
										<a href='./reply_review.php?idx=".$list_data['idx']."'>
										<input type='button' value='답변'></a>
										<a href='./delete_review.php?idx=".$list_data['idx']."'>
										<input type='button' value='삭제'></a>
									</td>";
				$innerHTML	.= "</tr>";
				//$i++;
			}
			$innerHTML	.= "</tbody>";
			echo $innerHTML;

		break;

		case "reply_review":

			$user_id = $_REQUEST['user_id'];
			$idx	 = $_REQUEST['idx'];
			$goods_code = $_REQUEST['goods_code'];
			$subject = $_REQUEST['subject'];
			$content = $_REQUEST['content'];
			$p_thread = $_REQUEST['p_thread'];
			$p_depth = $_REQUEST['p_depth'];
			$parent_gID = $_REQUEST['parent_gID'];

			$prev_parent_thread = ceil($p_thread/1000)*1000 - 1000; // 올림

			//원본글보다는 작고 위값보다는 큰 글들의 thread 값을 모두 1씩 낮춘다.
			//만약 부모글이 2000이면 prev_parent_thread는 1000이므로 2000> x >1000 인 x 글들을 모두 -1 한다.

			$u_query = "UPDATE ".$_gl['board_review_table']." SET thread=thread-1 WHERE thread > '".$prev_parent_thread."' AND thread < '".$p_thread."'";
			$result = mysqli_query ($my_db, $u_query); 

			//원본글보다는 1 작은 값으로 답글을 등록한다.
			//원본글의 바로 밑에 등록되게 된다.
			//depth는 원본글의 depth + 1 이다. 원본글이 3(이글도 답글)이면 답글은 4가된다.
			$i_query = "INSERT INTO ".$_gl['board_review_table']."(group_id, thread, depth, user_id, goods_code, subject, content, date, ipaddr) 
							VALUES ('".$parent_gID."','".$p_thread."'-1,'".$p_depth."'+1,'".$user_id."','".$goods_code."','".$subject."','".$content."','".date('Y-m-d H:i:s')."','".$_SERVER['REMOTE_ADDR']."')";
			$result = mysqli_query($my_db, $i_query);

			if($result){
				$flag = "Y";
			}else{
				$flag = "N";
			}

			echo $flag;	

		break;

		case "edit_review":

			$user_id = $_REQUEST['user_id'];
			$idx	 = $_REQUEST['idx'];
			$goods_code = $_REQUEST['goods_code'];
			$subject = $_REQUEST['subject'];
			$content = $_REQUEST['content'];

			// $s_query = "SELECT max(thread) AS thread FROM ".$_gl['board_review_table']."";
			// $max_thread_result = mysqli_query($my_db, $s_query);
			// $max_thread_fetch = mysqli_fetch_row($max_thread_result);

			// $max_thread = ceil($max_thread_fetch[0]/1000)*1000+1000;

			$u_query = "UPDATE ".$_gl['board_review_table']." SET subject='".$subject."', content='".$content."', date='".date('Y-m-d H:i:s')."', ipaddr='".$_SERVER['REMOTE_ADDR']."' WHERE idx='".$idx."'";
			$u_result = mysqli_query ($my_db, $u_query); // 글 수정
			if($u_result){
				$flag = "Y";
			}else{
				$flag = "N";
			}

			echo $flag;

		break;

		case "delete_review":

			$user_id = $_REQUEST['user_id'];
			$idx	 = $_REQUEST['idx'];
			$group_id	 = $_REQUEST['group_id'];
			$goods_code = $_REQUEST['goods_code'];

			$del_subject = "삭제된 글입니다.";
			$del_content = "삭제된 글입니다.";

			// 모든 글에서의 내용 뽑아내서 치환할경우
			// $query = "SELECT * FROM ".$_gl['board_review_table']." WHERE idx = '".$idx."'";
			// $result = mysqli_query($my_db, $query);
			// $target = mysqli_fetch_array($result); // 지울 제목, 내용

			// $query = "SELECT * FROM ".$_gl['board_review_table']." WHERE group_id = '".$group_id."'";
			// $result = mysqli_query($my_db, $query);
			// while ($array = mysqli_fetch_array($result)) {
			// 	$contentArr[][] = str_replace($target['content'], $del_content, $array['content']);
			// }
			// print_r($contentArr);

			$query = "SELECT * FROM ".$_gl['board_review_table']." WHERE group_id = '".$group_id."'";
			$result = mysqli_query($my_db, $query);
			$rows = mysqli_num_rows($result);

			if($rows>1)
			{
				$query = "UPDATE ".$_gl['board_review_table']." SET subject='".$del_subject."', content='".$del_content."', date='".date('Y-m-d H:i:s')."', ipaddr='".$_SERVER['REMOTE_ADDR']."' WHERE idx='".$idx."'";
				$result = mysqli_query($my_db, $query); // 글 수정 (답변글 존재)
			}else{
				$query = "DELETE FROM ".$_gl['board_review_table']." WHERE idx='".$idx."'";
				$result = mysqli_query($my_db, $query); // 글 삭제 (답변글 X)
			}

			if($result){
				$flag = "Y";
			}else{
				$flag = "N";
			}

			echo $flag;
		break;
	}

?>