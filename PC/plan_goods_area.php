          <div class="area_list_title">
            <span class="list_title no_line">스페셜</span>
          </div>
          <div class="list_product clearfix">
<?
	$plan_goods_info		= select_plan_goods_info($site_option['plan_goods_flag'],5);
	$i	= 0;
	foreach ($plan_goods_info as $key => $val)
	{
		$val['goods_img_url']	= str_replace("../../","",$val['goods_img_url']);
?>
            <div class="product">
              <a href="<?=$_mnv_PC_goods_url?>goods_detail.php?goods_code=<?=$val['goods_code']?>"><img src="<?=$val['goods_img_url']?>" style="width:220px"></a>
              <div class="prd_info">
                <span class="prd_name"><?=$val['goods_name']?></span>
                <span class="prd_price"><?=number_format($val['sales_price'])?></span>
                <span class="prd_sale"><?=number_format($val['sales_price'])?></span>
                <span class="prd_desc"><?=$val['goods_small_desc']?></span>
              </div>
            </div>
<?
		$i++;
	}
?>
          </div>
