<?php require_once('../../xoad.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xoad="http://www.xoad.org/controls" xml:lang="en-US">
	<head>
		<title>XOAD Controls</title>
		<?= XOAD_Utilities::header('../..') . "\n" ?>
		<?= XOAD_Controls::register('xoad', array('Panel', 'PanelTitle', 'PanelContent', 'PanelFade'), 'xoad.controls.panel.js') . "\n" ?>
	</head>
	<body style="font: normal 0.7em tahoma, verdana, arial, serif; margin: 0; padding: 10px;">
		<xoad:Panel id="panel1" top="50px" left="10px" style="display: none">
			<xoad:PanelTitle>Hi There!</xoad:PanelTitle>
			<xoad:PanelContent>
				Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
				Aliquam ipsum nunc, sodales a, posuere in, ullamcorper a, massa.
				Fusce nonummy lectus non tellus. Fusce nonummy elit at nulla.
				Phasellus tristique est nec felis. Curabitur lacus purus,
				vestibulum vel, imperdiet ut, placerat a, libero. In a tortor
				sit amet lorem mollis placerat. Proin sed lectus. Aliquam
				hendrerit mattis ipsum. Donec iaculis. Vivamus rhoncus.
				Phasellus metus velit, feugiat eget, feugiat eu, tincidunt sit
				amet, diam. Vivamus porta quam nec neque porttitor tempus. Fusce
				lacus. Pellentesque turpis diam, rhoncus ultricies, sagittis id,
				euismod sed, libero. Duis orci dolor, rutrum id, gravida vitae,
				viverra quis, neque. Cras commodo consequat diam. Nulla
				facilisi.
			</xoad:PanelContent>
			<xoad:PanelFade></xoad:PanelFade>
		</xoad:Panel>
		<xoad:Panel id="panel2" top="50px" left="250px" style="display: none">
			<xoad:PanelTitle>How are you?</xoad:PanelTitle>
			<xoad:PanelContent>
				Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
				Nullam ultrices purus a risus. Donec purus magna, vulputate
				sagittis, egestas vitae, placerat eu, leo. Suspendisse potenti.
				Etiam quis felis. Suspendisse sed purus eu est dapibus commodo.
				Phasellus laoreet, lectus sit amet posuere eleifend, quam nisl
				varius urna, nec condimentum magna mauris in ipsum. Quisque
				pretium pellentesque nibh. Pellentesque congue. In eu massa.
				Proin magna justo, laoreet a, sollicitudin vel, tincidunt sed,
				orci. Sed dignissim leo. Mauris tincidunt nisl. Cum sociis
				natoque penatibus et magnis dis parturient montes, nascetur
				ridiculus mus.
			</xoad:PanelContent>
			<xoad:PanelFade></xoad:PanelFade>
		</xoad:Panel>
		<xoad:Panel id="panel3" top="50px" left="500px" style="display: none">
			<xoad:PanelTitle>I'm fine, thanks.</xoad:PanelTitle>
			<xoad:PanelContent>
				Morbi gravida. Vivamus et mauris sed mi aliquam adipiscing.
				Curabitur varius cursus elit. Donec vestibulum congue ipsum.
				Pellentesque varius porttitor leo. Vestibulum ante ipsum primis
				in faucibus orci luctus et ultrices posuere cubilia Curae; Donec
				varius facilisis nisl. Vivamus mollis, neque vitae viverra
				ornare, nulla diam semper urna, quis euismod erat erat vitae
				tellus. Quisque volutpat, turpis et ornare laoreet, justo
				justo tincidunt velit, quis rutrum diam nibh eu urna. Etiam
				faucibus eros ut magna.
			</xoad:PanelContent>
			<xoad:PanelFade></xoad:PanelFade>
		</xoad:Panel>

		<a xoad:action="alert" xoad:value="Hello World!">Click Me.</a>

		Show/Hide Panel:
		<a xoad:action="show-hide" xoad:target="#panel1">&nbsp;1&nbsp;</a>
		<a xoad:action="show-hide" xoad:target="#panel2">&nbsp;2&nbsp;</a>
		<a xoad:action="show-hide" xoad:target="#panel3">&nbsp;3&nbsp;</a>
	</body>
</html>