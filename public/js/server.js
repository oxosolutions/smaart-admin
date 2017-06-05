(function(){
	'use strict'
	var everythingLoaded = setInterval(function() {
		if (/loaded|complete/.test(document.readyState)) {
		    clearInterval(everythingLoaded);
		    google.charts.load('current');
    		google.charts.setOnLoadCallback(drawVisualization);
		}
	}, 10);

	var processData = function(res, selector, wrapper){
		var chartWrapper = {};
		var index = 1;
    	for(var key in res.chart_data){
    		var baseTag = document.querySelectorAll('.smaart-charts');
    		var chart = document.createElement('div');
			chart.id = 'chart_wrapper_'+wrapper+'_'+index;
			baseTag[selector].appendChild(chart);
			var chartTypes = JSON.parse(res.chart_types);
			if(chartTypes[key] != 'CustomMap'){
				var settings = res.settings;
	    		chartWrapper['chart_'+index] = new google.visualization.ChartWrapper({
	    			chartType: chartTypes[key],
	                dataTable: res.chart_data[key],
	                options: JSON.parse(settings[key][0]),
	                containerId: 'chart_wrapper_'+wrapper+'_'+index,
	    		});
	    		chartWrapper['chart_'+index].draw();
			}else{
				window.chrtData = res.chart_data[key];
                var settings = res.settings[key];
                settings = JSON.parse(settings[0]);
                var haxColor = settings['chartColor']['colors'];
                var hex = haxColor.replace('#','');
                var r = parseInt(hex.substring(0,2), 16);
                var g = parseInt(hex.substring(2,4), 16);
                var b = parseInt(hex.substring(4,6), 16);
                var stateCode = chrtData.map(function(i,ind){
                    if(ind != 0){
                        return i[1];
                    }else{
                        return null;
                    }
                });
                
                stateCode.shift();
                var highest_value = Math.max.apply(Math, stateCode);
                document.getElementById('chart_wrapper_'+wrapper+'_'+index).innerHTML = res.maps[key];
                var fill_ind = 0;
                
                for(var ikey in chrtData){
                	if(fill_ind != 0){
                		if(document.getElementById(chrtData[ikey][0]) != null){
                			var currentClass = document.getElementById(chrtData[ikey][0]).getAttribute('class');
	                		document.getElementById(chrtData[ikey][0]).style.fill = 'rgba('+r+','+g+','+b+','+chrtData[ikey][1]/highest_value +')';
	                		document.getElementById(chrtData[ikey][0]).setAttribute('class',currentClass+' mapArea');
	                		document.getElementById(chrtData[ikey][0]).setAttribute('onmouseover','show(this);');
	                		document.getElementById(chrtData[ikey][0]).setAttribute('onmouseleave','hide();');
	                		document.getElementById(chrtData[ikey][0]).setAttribute('onmousemove','popup(this,event);');
	                		var ii_ind = 0;
	                        for(var iikey in chrtData[ikey]){
	                        	if(ii_ind > 0){
	                        		var attID = (chrtData[0][ii_ind]).replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g,'_');
	                        		document.getElementById(chrtData[ikey][0]).setAttribute(attID, chrtData[ikey][iikey]);
	                        	}
	                        	ii_ind++;
	                        }
                		}
                	}
                	fill_ind++;
                }
                
                var elems = document.getElementsByClassName('mapArea');
                var sizeClass = elems.length;
                for(var i = 0; i < sizeClass; i++){
                	elems[i].onmouseover = function(e){
                		var elm = this;
                		var title = this.getAttribute('title');
                		var html = '';
                		html += '<div class="inf">';
                		html += title + '<br/>';
                		var k_ind = 0;
                		for(var key in chrtData[0]){
                			if(k_ind > 0){
                				html += +chrtData[0][key]+': '+ elm.getAttribute((chrtData[0][key]).replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g,'_'))+'<br/>';
                			}
                			k_ind++;
                		}
                		html += '</div>';

                		document.body.innerHTML += html;
                	}
                }
			}
    		index++;
    	};
    	
	}

	var drawVisualization = function(){
		window.chartID = 1;
		var basePath = '//192.168.0.101/smaartframework.com/smaart-admin/public/api/v1/';
		var url = basePath+'singlevisual?api_token=584a3f86a56161.75429142';
		var smaartTags = document.querySelectorAll('.smaart-charts');
		for (var i = 0, length = smaartTags.length; i < length; i++) {
			(function (i){
				var http = new XMLHttpRequest();
				http.open('POST',url,true);
				var params = new FormData();
				params.append('id',smaartTags[i].getAttribute('data-id'));
				params.append('type','non-filter');
				http.onreadystatechange = function() {
				    if(http.readyState == 4 && http.status == 200) {
				    	var res = JSON.parse(http.responseText);
				    	processData(res, i, smaartTags[i].getAttribute('data-id'));
				    	var filtersContent = {};
				    	setTimeout(function(){
				    		processFilters(res,i);
				    	},3000);
				    }
				}
				http.send(params);
				
			})(i);
		}
	}

	var processFilters = function(res, selector){
		
		var filterData = res.filters;
		var index = 1;
		for(var key in filterData){
			drawHtml(filterData[key], selector, index);
			index++;
		}

	}

	var drawHtml = function(data, divId, index){
		switch(data['column_type']){
			case'mdropdown':
				var baseTag = document.querySelectorAll('.smaart-charts');
				var filter = document.createElement('div');
				filter.id = 'filter_'+index;
				baseTag[divId].appendChild(filter);
				var appendDv = document.getElementById('filter_'+index);
				var label = document.createElement('label');
				label.innerHTML = data['column_name'];
				appendDv.appendChild(label);
				var dropdown = document.createElement('select')
				dropdown.multiple = 'multiple';
				dropdown.id = 'filter_'+index+'_dropdown';
				var options = '';
				var dataLength = Object.keys(data['column_data']).length;
				for(var i = 0;i<dataLength;i++){
					options = options + '<option value="'+data['column_data'][i]+'">'+data['column_data'][i]+'</option>';
				}
				dropdown.innerHTML = options;
				appendDv.appendChild(dropdown);

			break;

			case'range':
				var baseTag = document.querySelectorAll('.smaart-charts');
				var filter = document.createElement('div');
				filter.id = 'filter_'+index;
				baseTag[divId].appendChild(filter);
				var appendDv = document.getElementById('filter_'+index);
				var label = document.createElement('label');
				label.innerHTML = data['column_name'];
				appendDv.appendChild(label);
				var dropdown = document.createElement('select')
				dropdown.multiple = 'multiple';
				dropdown.id = 'filter_'+index+'_dropdown';
				var options = '';
				var dataLength = Object.keys(data['column_data']).length;
				for(var i = 0;i<dataLength;i++){
					options = options + '<option value="'+data['column_data'][i]+'">'+data['column_data'][i]+'</option>';
				}
				dropdown.innerHTML = options;
				appendDv.appendChild(dropdown);
			break;
		}
	}
})();
function popup(x,event){
	var elem = document.getElementsByClassName('inf')[0];
	elem.style.top = (event.clientY+50)+'px';
    elem.style.left = (event.clientX+50)+'px';
	elem.style.position ="absolute";
    elem.style.background="#ffffff";
    elem.style.border="1px solid #e8e8e8";
    elem.style.width="250px";
    elem.style.margin="0 0 0 -125px";
    elem.style.padding="8px";
    elem.style['z-index']="9999";
    elem.style['border-radius']="4px";
    elem.style['font-size']="15px";
    elem.style['line-height']="18px";
    setTimeout(function(){
    	hide();
    },5000);
}
function show(elm){
	hide();
	var title = elm.getAttribute('title');
	var html = '';
	html += '<div class="inf">';
	html += '<span class="title">'+title + '</span>';
	var k_ind = 0;
	for(var key in chrtData[0]){
		if(k_ind > 0){
			html += '<span class="data">'+chrtData[0][key]+': '+ elm.getAttribute(chrtData[0][key])+'</span>';
		}
		k_ind++;
	}
	html += '</div>';

	document.body.innerHTML += html;
}
function hide(){
	try{
		var element = document.getElementsByClassName("inf");
   		element[0].parentNode.removeChild(element[0]);
	}catch(e){
	}
}