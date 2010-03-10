xoad.controls.PanelHelper = {};
xoad.controls.PanelHelper.dragObject = null;
xoad.controls.PanelHelper.moveX = 0;
xoad.controls.PanelHelper.moveY = 0;
xoad.controls.PanelHelper.zIndex = 99;

xoad.controls.PanelHelper.fadeObjects = [];

xoad.controls.PanelHelper.fadePanel = function(index, step) {

	var panel = xoad.controls.PanelHelper.fadeObjects[index];

	panel.style.MozOpacity = (step < 10) ? ('0.' + step) : 1;
	panel.style.filter = 'alpha(opacity=' + (step * 10) + ')';

	if (step < 10) {

		window.setTimeout('xoad.controls.PanelHelper.fadePanel(' + index +', ' + (step + 1) + ')', 75);
	}
};

xoad.controls.Panel = function(element, controlData)
{
	return {

		OnInit	:	function() {

			this.bindStyle('backgroundColor', '#000');
			this.bindStyle('border', '2px solid #f00');
			this.bindStyle('display', 'block');
			this.bindStyle('left', '10px');
			this.bindStyle('position', 'absolute');
			this.bindStyle('top', '10px');
			this.bindStyle('width', '200px');

			this.element.style.zIndex = xoad.controls.PanelHelper.zIndex ++;

			this.element.onmousedown = function() {

				this.style.zIndex = xoad.controls.PanelHelper.zIndex ++;
			};
		},

		OnLoad	:	function() {

			this.element.style.display = 'block';
		}
	}
};

xoad.controls.PanelTitle = function(element, controlData)
{
	return {

		OnInit	:	function() {

			this.bindStyle('backgroundColor', '#ff0');
			this.bindStyle('borderBottom', '2px solid #f00');
			this.bindStyle('color', '#f00');
			this.bindStyle('cursor', 'move');
			this.bindStyle('display', 'block');
			this.bindStyle('fontSize', '1em');
			this.bindStyle('fontWeight', 'bold');
			this.bindStyle('padding', '5px');

			this.element.onmousedown = function(e) {

				if (typeof(e) == 'undefined') {

					e = event;
				}

				if ((e.which ? e.which : e.button) != 1) {

					return true;
				}

				xoad.controls.PanelHelper.dragObject = this.attachedControl.parentElement.attachedControl.element;

				xoad.controls.PanelHelper.moveX = e.clientX;
				xoad.controls.PanelHelper.moveY = e.clientY;

				return false;
			};
		}
	}
};

xoad.controls.PanelContent = function(element, controlData)
{
	return {

		OnInit	:	function() {

			this.bindStyle('color', '#fff');
			this.bindStyle('display', 'block');
			this.bindStyle('lineHeight', '1.5em');
			this.bindStyle('fontSize', '1em');
			this.bindStyle('fontWeight', 'normal');
			this.bindStyle('padding', '10px');
		}
	}
};

xoad.controls.PanelFade = function(element, controlData)
{
	return {

		OnLoad	:	function() {

			var index = xoad.controls.PanelHelper.fadeObjects.length;

			xoad.controls.PanelHelper.fadeObjects[index] = this.parentElement;

			xoad.controls.PanelHelper.fadePanel(index, 1);
		}
	}
};

xoad.controls.PanelHelper.documentMouseMove = function(e)
{
	if (xoad.controls.PanelHelper.dragObject != null) {

		if (typeof(e) == 'undefined') {

			e = event;
		}

		var panel = xoad.controls.PanelHelper.dragObject;

		panel.style.MozOpacity = '0.75';
		panel.style.filter = 'alpha(opacity=75)';

		var offsetX = e.clientX - xoad.controls.PanelHelper.moveX;
		var offsetY = e.clientY - xoad.controls.PanelHelper.moveY;

		var dragObject = xoad.controls.PanelHelper.dragObject;

		dragObject.style.left = (parseInt(dragObject.style.left) + offsetX) + 'px';
		dragObject.style.top = (parseInt(dragObject.style.top) + offsetY) + 'px';

		xoad.controls.PanelHelper.moveX = e.clientX;
		xoad.controls.PanelHelper.moveY = e.clientY;

		return false;
	}

	return true;
};

xoad.controls.PanelHelper.documentMouseUp = function(e)
{
	if (xoad.controls.PanelHelper.dragObject != null) {

		var panel = xoad.controls.PanelHelper.dragObject;

		panel.style.MozOpacity = '1';
		panel.style.filter = 'alpha(opacity=100)';

		xoad.controls.PanelHelper.dragObject = null;

		xoad.controls.PanelHelper.moveX = 0;
		xoad.controls.PanelHelper.moveY = 0;
	}

	return true;
};

if (document.all) {

	document.attachEvent('onmousemove', xoad.controls.PanelHelper.documentMouseMove);
	document.attachEvent('onmouseup', xoad.controls.PanelHelper.documentMouseUp);

} else {

	document.addEventListener('mousemove', xoad.controls.PanelHelper.documentMouseMove, true);
	document.addEventListener('mouseup', xoad.controls.PanelHelper.documentMouseUp, true);
}