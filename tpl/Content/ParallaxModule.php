		<section style="margin:0; background-image:url(<?php echo $this->_["image"]; ?>); height:<?php echo $this->_["height"]; ?>; background-attachment:fixed; background-position:center; background-repeat:no-repeat; background-size:cover;">

<?php if (strlen($this->_["content"])) { ?>
			<div class="frame">
<?php echo $this->_["content"]; ?>
			</div>
<?php } ?>

		</section>
