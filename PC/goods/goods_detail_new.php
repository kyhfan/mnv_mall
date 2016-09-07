<?
	include_once $_SERVER['DOCUMENT_ROOT']."/mnv_mall/config.php";
	include_once $_mnv_PC_dir."header.php";

	$goods_code	= $_REQUEST['goods_code'];
	// 상품 정보
	$goods_info	= select_goods_info($goods_code);
	// 카테고리 1 정보 가져오기 ( goods_info.cate_1 )
	$cate1			= select_cate1_info($goods_info['cate_1']);
	// 카테고리 2 정보 가져오기 ( goods_info.cate_1, goods_info.cate_2 )
	$cate2			= select_cate2_info($goods_info['cate_1'],$goods_info['cate_2']);
	$goods_info['goods_img_url']		= str_replace("../../","../",$goods_info['goods_img_url']);
	$current_cnt	= $goods_info['goods_stock'] - $goods_info['goods_sales_cnt'];
	if ($goods_info['goods_optionYN'] == "Y")
	{
		$goods_option_arr	= explode("||",$goods_info['goods_option_txt']);
	}

	// 리뷰 리스트
	if(isset($_REQUEST['pg']) == false)
		$pg = "1";
	else
		$pg = $_REQUEST['pg'];

	if (!$pg) {
		$pg = "1";
	}
	$page_size = 10;  // 한 페이지에 나타날 개수
	$block_size = 10; // 한 화면에 나타낼 페이지 번호 개수
?>
  <body>
    <div id="wrap_page">
      <div id="header">
        <div class="area_top">
          <div class="head_bar clearfix">
            <ul class="user_status">
<?
	if ($_SESSION['ss_chon_id'])
	{
?>
              <li><a href="#" id="mb_logout"><span>로그아웃</span></a></li>
              <li><a href="http://localhost/mnv_mall/PC/member/modify_form.php"><span>정보수정</span></a></li>
<?
	}else{
?>
              <li><a href="http://localhost/mnv_mall/PC/member/member_login.php"><span>로그인</span></a></li>
              <li><a href="http://localhost/mnv_mall/PC/member/join_form.php"><span>회원가입</span></a></li>
<?
	}
?>
              <li><a href="#"><span>마이페이지</span></a></li>
              <li><a href="#"><span>장바구니</span></a></li>
              <li><a href="#"><span>주문조회</span></a></li>
            </ul>
          </div>
        </div>
        <div class="logo_area">
          <a href="#"><img src="../images/logo.jpg"></a>
        </div>
        <div class="area_nav">
          <div class="nav clearfix">
<?
	// 상단 카테고리 영역
	include_once $_mnv_PC_dir."cate_navi.php";
?>
            <div class="right_cate">
              <a href="#">
                <span class="cate_name">매거진, 촌</span>
              </a>
              <span class="bar2"></span>
              <a href="#">
                <span class="cate_name">이벤트</span>
              </a>
              <span class="bar2"></span>
              <a href="#">
                <span class="cate_name">제휴문의</span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div id="wrap_content">
        <div class="contents l2 clearfix">
          <div class="section main">
            <div class="area_main_top">
              <div class="area_product_detail clearfix">
                <div class="product_head img">
                  <!-- 제품 이미지 -->
                  <img src="<?=$goods_info['goods_img_url']?>" style="width:100%;height:100%;">
                </div>
                <div class="product_head info">
                  <div class="block_info_top">
                    <div class="block_line cate">
                        <span class="cate1"><?=$cate1?></span>
                        <span>></span>
                        <span class="cate2 current_cate"><?=$cate2?></span>
                    </div>
                    <div class="block_line">
                      <span class="product_name left_text">제품명</span>
                      <span class="stt_icon1 new">new</span>
                      <span class="stt_icon1 best">best</span>
                      <span class="stt_icon1 restock">재입고</span>
                    </div>
                    <div class="block_line">
                      <span class="left_text">판매가</span>
                      <span class="price sale">19,000원</span>
                      <span class="sale_price">10,000원</span>
                      <span class="sale_pctg">[50%]</span>
                    </div>
                    <div class="block_line">
                      <span class="left_text">상품요약</span>
                      <span class="desc">실용적인 사이즈의 머그컵</span>
                    </div>
                    <div class="block_line">
                      <span class="left_text">코드</span>
                      <span class="code">MUG1234512</span>
                    </div>
                    <div class="block_line">
                      <span class="left_text">수량</span>
                      <input type="text" name="select_amount" id="amount_val" value="1">
                      <span class="polygon_double">
                        <img src="../images/polygon_double.png" usemap="#amount">
                        <map name="amount" id="amount">
                          <area shape="rect" coords="0,0,9,9" href="#" onclick="amount_change('up');return false;";>
                          <area shape="rect" coords="0,10,9,19" href="#" onclick="amount_change('down');return false;";>
                        </map>
                      </span>
                    </div>
                    <div class="block_line">
                        <span class="left_text">
                          색상
                        </span>
                        <div class="select_box">
                          <label for="option_change" class="select_label">[필수]옵션을 선택해주세요</label>
                          <select name="select_option" id="option_change">
                            <option value="default" selected="selected">[필수]옵션을 선택해주세요</option>
                            <option value="red">red</option>
                            <option value="green">green</option>
                            <option value="orange">orange</option>
                          </select>
                        </div>
                    </div>
                  </div>
                  <div class="block_info_bottom">
                    <div class="block_line total clearfix">
                      <span class="left_text">총 상품금액(수량)</span>
                      <div class="right_text">
                        <span class="total_price">58,000원</span><span class="total_amount">(2개)</span>
                      </div>
                    </div>
                    <div class="block_btn clearfix">
                      <input type="button" class="pr_btn active" value="바로구매">
                      <input type="button" class="pr_btn" value="장바구니">
                      <input type="button" class="pr_btn" value="위시리스트">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="area_main_middle">
              <div class="product_logo">
                <img src="../images/product_logo.png">
              </div>
              <div class="product_dt_img">
                <img src="../images/product_detail1.jpg">
              </div>
              <div class="product_desc title">
                <p class="product_name">
                  제품명
                </p>
                <p class="product_summary">
                  디저트접시로, 앞접시로, 반찬접시로 전천후로 활용가능한 접시에요.<br>
                  테두리에 홈이 파진 모양새가 단조롭지 않고 귀엽답니다!
                </p>
              </div>
              <div class="product_dt_img">
                <img src="../images/product_detail2.jpg">
              </div>
              <div class="product_dt_img">
                <img src="../images/product_detail3.jpg">
              </div>
              <div class="product_desc">
                <p class="product_ment">
                  다른 테이블 셋팅과도 잘 어울려요
                </p>
              </div>
              <div class="product_dt_img">
                <img src="../images/product_detail4.jpg">
              </div>
              <div class="product_dt_size">
                <img src="../images/detail_size.jpg">
              </div>
              <div class="product_dt_info">
                <img src="../images/detail_info.jpg">
              </div>
              <div class="product_dt_branding">
                <img src="../images/branding_img.jpg">
              </div>
            </div>
            <div class="area_main_bottom">
              <div class="related_block">
                <p class="head_txt mb14">관련상품</p>
                <div class="list_product clearfix">
                  <div class="product n4 rt">
                    <a href="#"><img src="../images/relate1.jpg"></a>
                    <div class="prd_info">
                      <span class="prd_name">에디슨 스탠드 조명</span>
                      <span class="stt_icon2 new">NEW</span>
                      <span class="prd_price">2,500원</span>
                      <span class="prd_desc">
                      디저트접시로, 앞접시로,<br>
                      반찬접시로 전천후로 활용가능한<br>
                      접시에요.<br>
                      테두리에 홈이 파진 모양새가<br>
                      단조롭지 않고 귀엽답니다!
                      </span>
                    </div>
                  </div>
                  <div class="product n4 rt">
                    <a href="#"><img src="../images/relate2.jpg"></a>
                    <div class="prd_info">
                      <span class="prd_name">에디슨 스탠드 조명</span>
                      <span class="stt_icon2 restock">재입고</span>
                      <span class="prd_price">2,500원</span>
                      <span class="prd_desc">
                        디저트접시로, 앞접시로,<br>
                        반찬접시로 전천후로 활용가능한<br>
                        접시에요.<br>
                        테두리에 홈이 파진 모양새가<br>
                        단조롭지 않고 귀엽답니다!
                      </span>
                    </div>
                  </div>
                  <div class="product n4 rt">
                    <a href="#"><img src="../images/relate3.jpg"></a>
                    <div class="prd_info">
                      <span class="prd_name">에디슨 스탠드 조명</span>
                      <span class="stt_icon2 best">BEST</span>
                      <span class="prd_price">2,500원</span>
                      <span class="prd_desc">
                        디저트접시로, 앞접시로,<br>
                        반찬접시로 전천후로 활용가능한<br>
                        접시에요.<br>
                        테두리에 홈이 파진 모양새가<br>
                        단조롭지 않고 귀엽답니다!
                      </span>
                    </div>
                  </div>
                  <div class="product n4 rt">
                    <a href="#"><img src="../images/relate4.jpg"></a>
                    <div class="prd_info">
                      <span class="prd_name">에디슨 스탠드 조명</span>
                      <span class="stt_icon2 best">BEST</span>
                      <span class="prd_price">2,500원</span>
                      <span class="prd_desc">
                        디저트접시로, 앞접시로,<br>
                        반찬접시로 전천후로 활용가능한<br>
                        접시에요.<br>
                        테두리에 홈이 파진 모양새가<br>
                        단조롭지 않고 귀엽답니다!
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="related_block clearfix">
                <p class="head_txt">리뷰</p>
                <table class="pr_view_table">
                  <tr>
                    <td class="num">4</td>
                    <td class="subject">[제품 이름] 예뻐요 마음에 들어요!</td>
                    <td class="writer">miniver</td>
                    <td class="date">2016-01-01</td>
                  </tr>
                  <tr>
                    <td class="num">3</td>
                    <td class="subject">[제품 이름] 예뻐요 마음에 들어요!</td>
                    <td class="writer">miniver</td>
                    <td class="date">2016-01-01</td>
                  </tr>
                  <tr>
                    <td class="num">2</td>
                    <td class="subject">[제품 이름] 예뻐요 마음에 들어요!</td>
                    <td class="writer">miniver</td>
                    <td class="date">2016-01-01</td>
                  </tr>
                  <tr>
                    <td class="num">1</td>
                    <td class="subject">[제품 이름] 예뻐요 마음에 들어요!</td>
                    <td class="writer">miniver</td>
                    <td class="date">2016-01-01</td>
                  </tr>
                </table>
                <div class="block_board_btn">
                  <input type="button" value="작성하기" class="board_btn">
                  <input type="button" value="목록으로" class="board_btn">
                </div>
                <div class="block_board_pager">
                  <span>
                    <a href="#"><img src="../images/arrow_left_double.png"></a>
                  </span>
                  <span>
                    <a href="#"><img src="../images/arrow_left_single.png"></a>
                  </span>
                  <a href="#"><span>1</span></a>
                  <a href="#"><span>2</span></a>
                  <a href="#"><span>3</span></a>
                  <span>
                    <a href="#"><img src="../images/arrow_right_single.png"></a>
                  </span>
                  <span>
                    <a href="#"><img src="../images/arrow_right_double.png"></a>
                  </span>
                </div>
              </div>
              <div class="related_block clearfix">
                <p class="head_txt">상품문의</p>
                <table class="pr_view_table">
                  <tr>
                    <td class="num">4</td>
                    <td class="subject">문의합니다[2]</td>
                    <td class="writer">손님</td>
                    <td class="date">2016-01-01</td>
                  </tr>
                  <tr>
                    <td class="num">3</td>
                    <td class="subject">문의합니다[2]</td>
                    <td class="writer">손님</td>
                    <td class="date">2016-01-01</td>
                  </tr>
                  <tr>
                    <td class="num">2</td>
                    <td class="subject">문의합니다[2]</td>
                    <td class="writer">손님</td>
                    <td class="date">2016-01-01</td>
                  </tr>
                  <tr>
                    <td class="num">1</td>
                    <td class="subject">문의합니다[2]</td>
                    <td class="writer">손님</td>
                    <td class="date">2016-01-01</td>
                  </tr>
                </table>
                <div class="block_board_btn">
                  <input type="button" value="작성하기" class="board_btn">
                  <input type="button" value="목록으로" class="board_btn">
                </div>
                <div class="block_board_pager">
                  <span>
                    <a href="#"><img src="../images/arrow_left_double.png"></a>
                  </span>
                  <span>
                    <a href="#"><img src="../images/arrow_left_single.png"></a>
                  </span>
                  <a href="#"><span>1</span></a>
                  <a href="#"><span>2</span></a>
                  <a href="#"><span>3</span></a>
                  <span>
                    <a href="#"><img src="../images/arrow_right_single.png"></a>
                  </span>
                  <span>
                    <a href="#"><img src="../images/arrow_right_double.png"></a>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="section side">
            <div class="side_full_img">
              <img src="../images/side_full_img1.jpg">
            </div>
          </div>
        </div>
      </div>
      <div id="footer">
        <div class="area_infoChon">
          <div class="inner infoC clearfix">
            <div class="box_info">
              <span class="customerC">고객센터</span>
              <span class="telNum">070-000-0000</span>
              <span>운영시간 10:30-18:00 / 점심시간 13:00-2:30</span>
              <span>신한은행 11-111-11111 예금주 미니버타이징(주)</span>
            </div>
            <div class="box_info">
              <span>이메일 : SERVICE@STORE-CHON.COM</span>
              <span>토/일 법정공휴일, 임시공휴일 전화상담 휴무<br/>Q&A 게시판을 이용해주세요</span>
            </div>
            <div class="box_info clearfix">
              <a href="#"><span class="about_chon">ABOUT 촌의감각</span></a>
              <a href="#"><span class="sugg">입점문의</span></a>
              <a href="#"><span class="sugg">제휴문의</span></a>
              <a href="#"><span class="sugg last">대량구매</span></a>
            </div>
            <div class="box_info sns clearfix">
              <a href="#"><span>인스타그램</span></a>
              <a href="#"><span>페이스북</span></a>
              <a href="#"><span>블로그</span></a>
            </div>
          </div>
        </div>
        <div class="address">
          <p>company  미니버타이징(주)  address  서울특별시  서초구  방배동  931-9  2F</p>
          <p>owner  양선혜    business  license  114  87  11622   privacy policy | terms of use</p>
          <br>
          <p>@chon all rights reserved</p>
        </div>
      </div>
    </div>
  </body>
<script>
	var cnt=1;
	jQuery(document).ready(function(){
		var select = $("select#option_change");

		select.change(function(){
			var select_name = $(this).children("option:selected").text();
			$(this).siblings("label").text(select_name);
		});
	});

	function amount_change(type) {
		var amount = $('#amount_val');
		if(type=='up'){
			cnt = cnt+1;
			$('#amount_val').val(cnt);
		}else if(type=='down' && cnt>1){
			cnt = cnt-1;
			$('#amount_val').val(cnt);
		}else{
			cnt = 1;
		}
	}
</script>
</html>