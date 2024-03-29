<!doctype html>
<html lang="<?php echo $this->_['language']; ?>">

	<head>
<?php echo $this->_['headhtml']; ?>
	</head>

<?php
	$classes = array();
	if (isset($_REQUEST["embed"])) $classes[] = "embed";
	if (isset($this->_["privacy"]) && $this->_["privacy"] < 10) $classes[] = "noprivacy";
	$classstr = sizeof($classes) ? ' class="' . implode(' ', $classes) . '"' : "";
?>
	<body<?php echo $classstr; ?>>
<?php echo $this->_['bodyhtml']; ?>
	</body>

</html>
