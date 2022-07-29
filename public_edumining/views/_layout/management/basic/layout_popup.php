<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title><?php echo html_escape(element('page_title', $layout)); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/css.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.css'); ?>" />
    <script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/placeholders.min.js"></script>
    <script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/design.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/analysis.js'); ?>"></script>

    <!-- amChart4 -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/plugins/wordCloud.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/plugins/forceDirected.js"></script>
    <script type="text/javascript">am4core.addLicense("CH307906479");</script>
</head>
<body>
<div class="wrap_pop">
	<?php if (isset($yield)) echo $yield; ?>
</div>

<script>
    $(function() {

    });

</script>

</body>
</html>