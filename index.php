<style>
.row {
	text-align:center;
	margin-bottom:7px;
}
.cell {
	display:inline-block;

	width:30px;
	padding:2px;
	margin:0 1px;
	background-color:#eee;
	position:relative;
	border:1px solid #999;
	border-width:0 1px;	
}
.cell:before {
	border-style:solid;
	border-width:5px 17px;
	border-color:transparent transparent #eee transparent;
	content:'';
	position:absolute;
	bottom:100%;
	left:0;
}
.cell:after {
	border-style:solid;
	border-width:5px 17px;
	border-color:#eee transparent transparent transparent;
	content:'';
	position:absolute;
	top:100%;
	left:0;
}
.cell.solved{background-color:#acFF20;}
.cell.solved:before{border-bottom-color:#acFF20;}
.cell.solved:after{border-top-color:#acFF20;}

.cell input {
	width:100%;
	text-align:center;
	box-sizing:border-box;
}

.toTheTop{
top:-80px;
left:-15px;
transform:rotate(120deg);
}
</style>
<div style="height:150px; width:100%;"></div>
<?php

buildHexagonPuzzle(7);


function buildHexagonPuzzle($sideLength)
{
	$ROWCLASS = 'row';
	$CELLCLASS = 'cell';

	$curWidth = $sideLength-1;
	$height = 0;
	$index = 0;
	while (++$height <= $sideLength) {
		echo '<div class="'.$ROWCLASS.'">';

		++$curWidth;
		for ($i = 0; $i< $curWidth; $i++) {
			echo '<div class="'.$CELLCLASS.'"><input type="text" id="hex'.$index++.'"/></div>';
		}
		echo "</div>";
	}

	while (--$height > 1) {
		--$curWidth;
		echo '<div class="'.$ROWCLASS.'">';
		for ($i = 0; $i< $curWidth; $i++) {
			echo '<div class="'.$CELLCLASS.'"><input type="text" id="hex'.$index++.'"/></div>';
		}
		echo '</div>';
	}
}
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" ></script>

<script>
var data = [
	{
		regex:'.*H.*H.*', pinTo:0, howMany: 7, toThe:'left'
	},
									{
		regex:'(DI|NS|TH|OM)*', pinTo:7, howMany:8,toThe:'left'
	},
{
		regex:'F.*[AO].*[AO].*', howMany:9, pinTo:15,toThe:'left'
	},
{
		regex:'(O|RHH|MM)*', howMany:10, pinTo:'24',toThe:'left'
	},
{
		regex:'.*', howMany:11, pinTo:'34',toThe:'left'
	},
{
		regex:'C*MC(CCC|MM)*', howMany:12, pinTo:'45',toThe:'left'
	},
{
		regex:'[^C]*[^R]*III.*', howMany:13, pinTo:'57',toThe:'left'
	},
{
		regex:'(...?)\1*', howMany:12, pinTo:'70',toThe:'left'
	},
{
		regex:'([^X]|XCC)*', howMany:11, pinTo:'82',toThe:'left'
	},
{
		regex:'(RR|HHH)*.?', howMany:10, pinTo:'93',toThe:'left'
	},{

		regex:'N.*X.X.X.*E', howMany:9, pinTo:'103',toThe:'left'
	},{

		regex:'R*D*M*', howMany:8, pinTo:'112',toThe:'left'
	},{

		regex:'.(C|HH)*', howMany:7, pinTo:'120',toThe:'left'
	},
	{
		regex:'(ND|ET|IN)[^X]*',cells:[], pinTo:'0', toThe:'top'
	}

	]

var Line = function (pattern) {
		this.pattern = new RegExp('^'+pattern+'$');
		this.cells = [];
	}
	Line.prototype.addCell = function (cell) {
		cell.addLine(this);
		this.cells.push(cell);
	}

	Line.prototype.validate = function () {
		var ret = '';
		var cell;
		for (var i = 0; i < this.cells.length -1; i++) {
			cell = this.cells[i];
			if (matches = cell.dom.val().match(/^[A-Z]$/)) {
				ret+=matches[0];

			} else {
				return false;
			}
		}
		return ret.match(this.pattern);
	}
	var Cell = function ($dom) {
		this.dom = $dom;
		this.lines = [];
		var that = this;
	}
	Cell.prototype.addLine = function (line) {
		this.lines.push(line);
	}

function redrawBoard(cells) {
	var cell;
	for (var i in cells) {
		var bad =false;
		cell = cells[i];
		cell.dom.parent().removeClass('solved');
		if (cell.dom && cell.dom.length > 0 && cell.dom.val().length === 1) {
			for (var j = 0; j < cell.lines.length; j++) {
				if (!cell.lines[j].validate()) {
					bad = true;;
				}				
			}
			if(!bad) {
				cell.dom.parent().addClass('solved');
			}
		}
	}
}

function buildForm(data) {
	var cells = {};
	for (var i = 0; i < data.length; i++) {
		var info = data[i];
		var reg = $('<div>').html(info.regex).css({position:'absolute'});;
		if (info.toThe === 'left') {
			info.cells = [];
			info.pinTo = parseInt(info.pinTo, 10);
			for (var j=0;j<info.howMany; j++) {
				
				info.cells.push(info.pinTo + j);
			}



			reg.css('top','5px').css('right','100%').css('text-align','right').css('padding-right','10px').css('width','150px');
		} else if(info.toThe === 'top') {
			reg.addClass('toTheTop');
		}
		$('#hex' + info.pinTo).parent('.cell').append(reg);
		
		var line = new Line(info.regex);
		for (var j = 0; j <= info.cells.length; j++) {
			var $input = $('#hex' + info.cells[j]);

			if (cells[info.cells[j]]) {
				cell = cells[info.cells[j]];
			} else {
				var cell = new Cell($input);
				cells[info.cells[j]] = cell;
			}
			line.addCell(cell);
		}
	}
	return cells;
}

var cells = buildForm(data);

	$('.cell').find('input').change(function () {
		this.value = this.value.toUpperCase();
		redrawBoard(cells);
	});


</script>
