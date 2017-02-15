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
			var settings = res.settings;
    		chartWrapper['chart_'+index] = new google.visualization.ChartWrapper({
    			chartType: chartTypes[key],
                dataTable: res.chart_data[key],
                options: JSON.parse(settings[key][0]),
                containerId: 'chart_wrapper_'+wrapper+'_'+index,
    		});
    		chartWrapper['chart_'+index].draw();
    		index++;
    	};
    	
	}
	var drawVisualization = function(){
		window.chartID = 1;
		var basePath = '//192.168.0.101/smaart-angular/public/api/v1/';
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