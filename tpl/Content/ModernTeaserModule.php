		<style>
			.modernteasermodule { background-repeat:none; background-position:center; margin-bottom:5.5em; background-size:cover; }
			.modernteasermodule > div > div { position:relative; display:inline-block; width:50%; margin:4em 2em -4em 0; text-align:left; }
			@media (max-width: 48em) {
				.modernteasermodule > div > div { width:auto; }
			}
			.modernteasermodule > div > div > div { position:relative; margin:-2em -2em 2em 2em; padding:4em; }
			.modernteasermodule > div > div > div * { color:#fff; }
			.modernteasermodule > div > div > div > a { display:inline-block; width:auto; padding:.3em .8em; border:2px solid #fff; text-decoration:none; color:#fff; font-weight:bold; }
		</style>

		<section class="modernteasermodule" style="background-image:url(<?php echo $this->_["image"]; ?>); text-align:<?php echo $this->_["align"]; ?>;">
			<div class="frame">
				<div style="background:<?php echo $this->_["color2"]; ?>;">
					<div style="background:<?php echo $this->_["color1"]; ?>;">
<h3><?php echo $this->_["headline"]; ?></h3>
<p><?php echo $this->_["text"]; ?></p>
<a href="<?php echo $this->_["url"]; ?>"><?php echo $this->_["link"]; ?></a>
					</div>
				</div>
			</div>
		</section>
