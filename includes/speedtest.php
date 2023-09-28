<?php # Last modified by Frank Bauer:  2019/07/28

if (isset($_COOKIE["hasSpeedTestDEV0719"]) || (isset($_GET["hasSpeedTestDEV0719"]) && $_GET["hasSpeedTestDEV0719"] == 1)) {
	$speedTestEnabled = 1;
	$totalSpeedTestStart = microtime(true);
} else {
	$speedTestEnabled = 0;
}

function startElementTest($section) {
	global $speedTestEnabled, $sectionName, $sectionStartTime;
	if ($speedTestEnabled == 1) {
		$name = preg_replace('/^a-z0-9/i', '', $section);
		$sectionName[$name] = $section;
		$sectionStartTime[$name] = microtime(true);
	}
}

function endElementTest($section) {
	global $speedTestEnabled, $sectionName, $sectionStartTime, $sectionTime;
	if ($speedTestEnabled == 1) {
		$name = preg_replace('/^a-z0-9/i', '', $section);
		$sectionName[$name] = $section;
		$sectionTime[$name] = microtime(true) - $sectionStartTime[$name];
	}
}

function endSpeedTest() {
	global $speedTestEnabled, $totalSpeedTestStart, $sectionName, $sectionTime;
	if ($speedTestEnabled == 1) {
		echo '<script>';
		$totalTime = microtime(true) - $totalSpeedTestStart;
		echo "console.info('Total Run Time: ".$totalTime." s');";
		foreach ($sectionName as $name => $section) {
			echo "console.info('Section ".$section." Run Time: ".$sectionTime[$name]." s');";
			$totalTime -= $sectionTime[$name];
		}
		echo "console.info('Other Run Time: ".$totalTime." s');";
echo '</script>';
	}
}
?>