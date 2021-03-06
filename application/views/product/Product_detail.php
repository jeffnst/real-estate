<!DOCTYPE html>
<html lang = "en">

<head>
	<meta charset = "utf-8">
	<meta name="description" content="<?=$product->Title?>">
	<meta name="keywords" content="Bất động sản, bán nhà, chung cư, mua đất, bán đất, real estate">
	<title>Tin Đất Đai | <?php echo $product->Title?></title>
	<?php $this->load->view('common_header')?>
	<link rel="stylesheet" href="<?=base_url('/css/jquery.mCustomScrollbar.min.css')?>" />
	<link rel="stylesheet" href="<?=base_url('/css/carousel-custom.css')?>" />
	<script src="<?=base_url('/js/jquery.mCustomScrollbar.min.js')?>"></script>
	<?php $this->load->view('/common/googleadsense')?>
</head>

<body>
<?php $this->load->view('/common/analyticstracking')?>
<div class="container">
<?php $this->load->view('/theme/header')?>

<ul class="breadcrumb">
	<?php
		if(isset($category->Parent)){
			echo '<li><a href="'.base_url().seo_url($category->Parent->CatName).'-c'.$category->Parent->CategoryID.'.html">'.$category->Parent->CatName.'</a></li>';
		}
	?>
	<li><a href="<?php echo base_url().seo_url($category->CatName).'-c'.$category->CategoryID?>.html"><?php echo $category->CatName?></a></li>
	<li class="active"><?php echo $product->Title?></li>
</ul>
<div class="row no-margin">
	<div class="col-md-9 no-margin no-padding product-detail">
		<div class="product-title"><?php echo $product->Title?></div>
		<div class="row">
			<div class="col-md-5">Giá: <span class="color bold"><?php echo $product->PriceString?></span><span class="margin-left-10">Diện tích: <span class="color bold"><?php echo $product->Area?></span></span></div>
			<div class="col-md-7 text-right">
				<span class="color bold glyphicon glyphicon-map-marker"></span><span class="color bold">
				<?php
				if(isset($product->Street)){
					echo $product->Street;
				}
				if(isset($product->Ward)){
					echo ' - ';
					echo $product->Ward->WardName;
				}
				if(isset($product->District)){
					echo ' - ';
					echo $product->District->DistrictName;
				}
				if(isset($product->City)){
					echo ' - ';
					echo $product->City->CityName;
				}
				?>
				</span>
			</div>
		</div>

		<?php
		if($product->Assets != null && count($product->Assets) > 0) {
			?>
			<div class="product-assets">
				<div id='carousel-custom' class='carousel slide' data-interval="false" data-ride='carousel'>
					<div class='carousel-outer'>
						<!-- Wrapper for slides -->
						<div class='carousel-inner'>
							<?php
							$isFirst = true;
							foreach ($product->Assets as $asset) {
								if ($isFirst) {
									echo '<div class="item active">';
									$isFirst = false;
								} else {
									echo '<div class="item">';
								}
								echo '<img src="' . str_replace('resize/200x200/', '', $asset->OrgUrl) . '" alt=\'\' />';
								echo '</div>';
							}
							?>
						</div>

						<!-- Controls -->
						<a class='left carousel-control' href='#carousel-custom' data-slide='prev'>
							<span class='glyphicon glyphicon-chevron-left'></span>
						</a>
						<a class='right carousel-control' href='#carousel-custom' data-slide='next'>
							<span class='glyphicon glyphicon-chevron-right'></span>
						</a>
					</div>

					<!-- Indicators -->
					<ol class='carousel-indicators mCustomScrollbar'>
						<?php
						$i = 0;
						foreach ($product->Assets as $asset) {
							if ($i == 0) {
								echo '<li data-target="#carousel-custom" data-slide-to="' . $i . '" class="active"><img src="' . $asset->Url . '" /></li>';
							} else {
								echo '<li data-target="#carousel-custom" data-slide-to="' . $i . '"><img src="' . $asset->Url . '" /></li>';
							}

							$i++;
						}
						?>
					</ol>
				</div>
			</div>
			<?php
		}
		?>

		<h2 class="h2title">Chi Tiết
			<hr/>
		</h2>

		<div class="product-detail content"><?php echo $product->Detail?></div>

		<div class="row">
			<div class="col-md-8">
				<table class="table tableBorder">
					<tr class="tbHeader">
						<td colspan="2">Đặc Điểm</td>
					</tr>
					<tr>
						<td>Chiều rộng</td>
						<td><?=$product->WidthSize != null ? $product->WidthSize : '-'?></td>
					</tr>
					<tr>
						<td>Chiều dài</td>
						<td><?=$product->LongSize != null ? $product->LongSize : '-'?></td>
					</tr>
					<tr>
						<td>Số tầng</td>
						<td><?=$product->Floor != null ? $product->Floor : '-'?></td>
					</tr>
					<tr>
						<td>Số phòng</td>
						<td><?=$product->Room != null ? $product->Room : '-'?></td>
					</tr>
					<tr>
						<td>Nhà vệ sinh</td>
						<td><?=$product->Toilet != null ? $product->Toilet : '-'?></td>
					</tr>
					<tr>
						<td>Hướng</td>
						<td><?=(isset($product->Direction) && $product->Direction) ? $product->Direction->DirectionName : 'KXĐ'?></td>
					</tr>
					<?php
						if(isset($product->Brand) && $product->Brand != null){
							echo '<td>Thuộc dự án</td>';
							echo '<td>'.$product->Brand->BrandName.'</td>';
						}
					?>
				</table>
			</div>
			<div class="col-md-4">
				<table class="table tableBorder">
					<tr class="tbHeader">
						<td colspan="2">Liên Hệ</td>
					</tr>
					<tr>
						<td class="width100">Liên hệ</td>
						<td><?=$product->ContactName != null ? $product->ContactName : '-'?></td>
					</tr>
					<tr>
						<td class="width100">Số ĐT</td>
						<td><?=$product->ContactPhone != null ? $product->ContactPhone : '-'?></td>
					</tr>
					<tr>
						<td class="width100">Di động</td>
						<td><?=$product->ContactMobile != null ? $product->ContactMobile : '-'?></td>
					</tr>
					<tr>
						<td class="width100">Địa chỉ</td>
						<td><?=$product->ContactAddress != null ? $product->ContactAddress : '-'?></td>
					</tr>
					<tr>
						<td class="width100">Email</td>
						<td><?=$product->ContactEmail != null ? $product->ContactEmail : '-'?></td>
					</tr>
				</table>
			</div>
		</div>


		<h2 class="h2title">Bản Đồ
			<hr/>
		</h2>
		<?php $this->load->view('/Map_view')?>

		<?php
			if(isset($product->Source)){
				echo '<div class="copy-source row color-gray no-margin no-padding">Nguồn: '.$product->Source.'</div>';
			}
		?>

	</div>
	<div class="col-md-3 no-margin-right no-padding-right">
		<?php $this->load->view('/SocialShare') ?>
		<?php $this->load->view('/Subscrible') ?>
		<div class="clear-both"></div>
		<?php $this->load->view('/common/Search_filter') ?>
	</div>

</div>


<?php $this->load->view('/theme/footer')?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$(".mCustomScrollbar").mCustomScrollbar({axis:"x"});
	});
</script>

<!-- Place this tag in your head or just before your close body tag. -->
<script src="https://apis.google.com/js/platform.js" async defer></script>
</body>

</html>
