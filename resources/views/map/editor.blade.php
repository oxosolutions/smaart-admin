<!DOCTYPE HTML>
<html lang="en">

	<head>
    <title>SVG Editor</title>
		<script type="text/javascript" src="{{asset('js/map/jquery.min.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/map/svg-editor.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/map/editor-interface.js')}}"></script>
        <link type="text/css" rel="stylesheet" href="{{asset('js/map/main.css')}}" />
         <script type="text/javascript">
          $(function(){
              $('#map-IN-AN').click(function(e)
              {
                 $('table tr:last').hide();
              });

          });
          // $(document).ready(function(){
          //   $(".svg-element-div").click(function(e){
          //     alert(123);
          //       $('table tr:last').hide();

          //     });
            
          //   alert(123);

          // });

    </script>
	<head>

	<body>
  
	<div id="header">
        <h1>Smaart SVG Editor</h1>
        <span class="toolbar-item enabled selected" id="toolbar-input">Input</span>
        <span class="toolbar-item" id="toolbar-optimise">Optimise</span>
        <span class="toolbar-item" id="toolbar-edit">Edit</span>
        <span class="toolbar-item" id="toolbar-output">Output</span>
        <div style="float: right; margin-right: 2%; font-size: 14px; padding-top: 5px;">
          <a href="javascript:;" id="closeEditor">Save & Close</a>
        </div>
    </div>
    
    <div class="page-content">
        <div id="input-area" class="content-area">
            <textarea id="input-svg" class="io-box" title="Paste SVG code here" placeholder="Paste SVG code here and click the Load button" >{{$mapData}}</textarea>
            <div>
                <input type="submit" id="load-button" class="button" value="Load" />
                <!-- <input type="submit" id="load-example-button" class="button" value="Load Example" /> -->
            </div>
        </div>
      
        <div id="optimise-area" class="content-area" style="display:none;">
            <div>Decimal places:
                <select id="decimal-places" name="decimal-places">
                    <option value="unchanged" selected>Unchanged</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
            <h3>Remove namespaces:</h3>
            <div id="remove-namespaces"></div>
        </div>
      
        <div id="edit-area" style="display:none;">
            <div id="svg-code-container">
                <div id="svg-tree" class="io-box"></div>
                <div id="element-analysis">
                    <div id="breadcrumbs"></div>
                    <div id="element-attributes"></div>
                </div>
            </div>
            
            <div id="svg-image-container">
                <div class="svg-image" id="full-svg">
                    <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.0" width="320" height="320">
                        <script type="text/ecmascript"><![CDATA[
                            function sendClickToParentDocument(evt) {
                                var target = evt.target;
                                console.log(target);
                                if (target.correspondingUseElement) {
                                    target = target.correspondingUseElement;
                                }
                                if (window.parent.selectElement) {
                                    window.parent.selectElement(target.id);
                                }
                            }
                        ]]></script>
                        <g id="full-svg-transform">
                            <g id="full-svg-wrapper"></g>
                            <rect id="highlight-rect" fill="none" stroke="#f00" stroke-width="2"/>
                        </g>
                    </svg>
                </div>
                <div class="svg-image" id="sub-svg">
                    <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.0" width="320" height="320">
                        <g fill="#bbb">
                            <rect x="40"  y="0"   width="40" height="40"/>
                            <rect x="120" y="0"   width="40" height="40"/>
                            <rect x="200" y="0"   width="40" height="40"/>
                            <rect x="280" y="0"   width="40" height="40"/>
                            <rect x="0"   y="40"  width="40" height="40"/>
                            <rect x="80"  y="40"  width="40" height="40"/>
                            <rect x="160" y="40"  width="40" height="40"/>
                            <rect x="240" y="40"  width="40" height="40"/>
                            <rect x="40"  y="80"  width="40" height="40"/>
                            <rect x="120" y="80"  width="40" height="40"/>
                            <rect x="200" y="80"  width="40" height="40"/>
                            <rect x="280" y="80"  width="40" height="40"/>
                            <rect x="0"   y="120" width="40" height="40"/>
                            <rect x="80"  y="120" width="40" height="40"/>
                            <rect x="160" y="120" width="40" height="40"/>
                            <rect x="240" y="120" width="40" height="40"/>
                            <rect x="40"  y="160" width="40" height="40"/>
                            <rect x="120" y="160" width="40" height="40"/>
                            <rect x="200" y="160" width="40" height="40"/>
                            <rect x="280" y="160" width="40" height="40"/>
                            <rect x="0"   y="200" width="40" height="40"/>
                            <rect x="80"  y="200" width="40" height="40"/>
                            <rect x="160" y="200" width="40" height="40"/>
                            <rect x="240" y="200" width="40" height="40"/>
                            <rect x="40"  y="240" width="40" height="40"/>
                            <rect x="120" y="240" width="40" height="40"/>
                            <rect x="200" y="240" width="40" height="40"/>
                            <rect x="280" y="240" width="40" height="40"/>
                            <rect x="0"   y="280" width="40" height="40"/>
                            <rect x="80"  y="280" width="40" height="40"/>
                            <rect x="160" y="280" width="40" height="40"/>
                            <rect x="240" y="280" width="40" height="40"/>
                        </g>
                        <g id="sub-svg-transform">
                            <g id="sub-svg-wrapper"></g>
                        </g>
                    </svg>
                </div>
            </div>
            
        </div>
        
        <div id="output-area" class="content-area" style="display:none;">
            <textarea id="output-svg-code" class="io-box">Output</textarea>
        </div>

    <div id="scientist-example" style="display:none;">
        
<!-- Created with Inkscape (http://www.inkscape.org/) -->
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://web.resource.org/cc/"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://inkscape.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="684"
   height="640"
   id="svg2"
   sodipodi:version="0.32"
   inkscape:version="0.43"
   version="1.0"
   sodipodi:docbase="/home/robin/Desktop/inkscape"
   sodipodi:docname="mad_scientist.svg">
  <defs
     id="defs4" />
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0"
     inkscape:pageshadow="2"
     inkscape:zoom="1.789889"
     inkscape:cx="368.1647"
     inkscape:cy="275.38021"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     inkscape:window-width="1280"
     inkscape:window-height="946"
     inkscape:window-x="0"
     inkscape:window-y="25" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1">
    <rect
       style="opacity:1;color:#000000;fill:#269276;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0;stroke-linecap:round;stroke-linejoin:round;marker:none;marker-start:none;marker-mid:none;marker-end:none;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;visibility:visible;display:inline;overflow:visible"
       id="rect4380"
       width="719.25134"
       height="660.68384"
       x="-4.1275554"
       y="-2.4652486" />
    <rect
       style="opacity:1;color:#000000;fill:#1a7e66;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0;stroke-linecap:round;stroke-linejoin:round;marker:none;marker-start:none;marker-mid:none;marker-end:none;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;visibility:visible;display:inline;overflow:visible"
       id="rect4368"
       width="643.18622"
       height="449.52936"
       x="22.494247"
       y="18.444132" />
    <path
       style="fill:#faf6e6;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:14.93623066;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 356.50051,402.28541 C 356.50051,402.28541 381.09878,406.15155 381.49401,430.40307 C 381.50486,431.08575 437.72934,446.64885 459.59864,445.39917 C 481.46791,444.1495 508.96074,424.77954 516.45879,418.53117 C 523.95684,412.28281 536.45358,402.28541 540.2026,389.16383 C 543.95161,376.04225 545.82613,361.04618 545.82613,361.04618 L 578.31764,366.04486 C 578.31764,366.04486 579.56733,369.16907 577.06798,380.41613 C 574.56863,391.66319 569.56993,406.65928 562.07188,420.40568 C 554.57384,434.1521 543.28245,456.92157 530.20519,466.01879 C 515.83395,476.01618 493.96467,484.7639 482.71758,486.63842 C 463.52194,489.83769 433.98031,487.26325 433.98031,487.26325 C 433.98031,487.26325 436.47965,518.5051 433.98031,544.1234 C 431.48095,569.74172 427.1071,602.85808 423.9829,615.97966 C 420.85872,629.10124 417.10971,641.59799 417.10971,641.59799 L 410.2365,657.21891 L 298.39067,662.21759 L 302.13971,639.09863 C 302.13971,639.09863 300.89002,559.74433 299.64036,542.24888 C 298.39067,524.75346 301.51486,502.88417 301.51486,502.88417 C 301.51486,502.88417 261.5253,530.377 227.7841,526.62797 C 185.3319,521.91109 142.18143,494.7613 142.18143,494.7613 L 171.54877,462.26976 C 171.54877,462.26976 201.54094,486.63842 227.15927,486.01355 C 252.77758,485.38873 267.14884,479.14037 279.02073,471.64232 C 290.89263,464.14428 308.38807,452.89722 310.88742,447.89852 C 313.38677,442.89981 314.63643,434.1521 314.63643,434.1521"
       id="path3374"
       sodipodi:nodetypes="cssssccsssscssccccscsccsssc" />
    <path
       style="opacity:1;fill:#72caba;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:12.94473362;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 45.566037,283.86992 L 45.566037,225.4997 C 45.566037,225.4997 37.373727,215.25931 38.397767,211.67517 C 39.421804,208.09103 38.909787,197.85066 56.318447,195.29055 C 73.72711,192.73046 90.623753,187.61027 100.35212,193.7545 C 110.0805,199.89873 110.0805,204.50691 108.03242,209.62709 C 105.98434,214.74729 99.328085,219.35548 99.328085,219.35548 L 95.743949,281.30983 L 89.087696,386.27381 L 79.359326,421.60315 L 57.342487,420.57912 L 48.638156,402.65844 L 45.566037,283.86992 z "
       id="path3438" />
    <path
       style="fill:#c6c2a3;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 179.67165,514.13123 C 185.29519,509.75737 197.16709,482.26454 197.16709,482.26454 L 170.29909,462.26976 L 149.67947,491.01226 L 172.79845,512.25672 L 179.67165,514.13123 z "
       id="path3412" />
    <path
       style="fill:#d6d6d6;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:14.93623066;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 315.16414,432.34235 C 315.16414,432.34235 313.18907,424.44211 294.42602,425.42964 C 275.66296,426.41716 261.83758,439.25505 249.98723,446.16776 C 237.19212,453.63157 212.4611,455.05551 206.53595,443.20518 C 200.61076,431.35483 206.53595,402.71647 206.53595,402.71647 C 206.53595,402.71647 186.78536,395.80377 186.78536,382.9659 C 186.78536,370.12801 201.59829,364.20285 201.59829,364.20285 L 208.51099,345.4398 C 208.51099,345.4398 192.71053,335.56448 188.76041,323.71415 C 184.8103,311.86378 185.79783,312.85132 191.723,310.87625 C 197.64817,308.90122 199.62324,311.86378 204.56087,306.92614 C 209.49852,301.9885 214.43617,272.36264 214.43617,272.36264 C 214.43617,272.36264 199.62324,260.51229 203.57335,238.78664 C 207.52347,217.061 207.52347,217.061 211.47358,212.12335 C 215.42369,207.18569 222.33641,200.27299 222.33641,200.27299 C 222.33641,200.27299 200.61076,193.3603 186.78536,198.29794 C 172.95995,203.23558 148.27172,217.061 148.27172,217.061 C 148.27172,217.061 155.18441,196.32289 163.08465,181.50995 C 170.98489,166.69701 212.4611,134.10855 212.4611,134.10855 L 145.30913,161.75936 C 145.30913,161.75936 155.18441,141.02124 163.08465,133.12102 C 170.98489,125.22079 181.8477,109.42032 192.71053,103.49514 C 203.57335,97.569967 219.37382,92.63232 219.37382,92.63232 C 219.37382,92.63232 165.05971,90.657262 182.83523,90.657262 C 200.61076,90.657262 153.20936,93.61985 153.20936,93.61985 C 153.20936,93.61985 155.18441,78.806912 179.87265,65.969035 C 204.56087,53.131156 238.13686,46.218451 238.13686,46.218451 L 177.89759,41.280806 C 177.89759,41.280806 197.64817,20.542693 222.33641,20.542693 C 247.02464,20.542693 263.81262,23.505281 275.66296,29.430456 C 287.51333,35.35563 294.42602,45.230923 294.42602,45.230923 C 294.42602,45.230923 305.28885,29.430456 318.12673,23.505281 C 330.9646,17.580106 357.62789,16.592578 357.62789,16.592578 L 317.13921,46.218453 C 317.13921,46.218453 354.66532,33.380573 371.4533,33.380573 C 388.2413,33.380573 406.01684,40.293276 406.01684,40.293276 L 367.50318,52.143628 C 367.50318,52.143628 418.85471,53.131156 437.61775,59.05633 C 456.38081,64.981505 470.20623,70.906681 483.04409,80.781973 C 495.88197,90.657262 499.83209,104.48267 499.83209,104.48267 L 450.45564,97.569967 C 450.45564,97.569967 484.03163,105.4702 492.91939,125.22079 C 501.80714,144.97137 499.83209,159.78431 499.83209,159.78431 L 463.29351,142.00877 C 463.29351,142.00877 481.06904,161.75936 483.04409,177.55982 C 485.01915,193.3603 482.05657,203.23558 482.05657,203.23558 L 451.44317,180.52242 C 451.44317,180.52242 460.33091,199.28547 459.34339,205.21065 C 458.35587,211.13582 457.36833,215.08593 457.36833,215.08593 L 434.65516,197.31042 L 445.51799,222.98617 L 442.5554,228.91134 L 427.74246,214.0984 L 423.79234,229.89887 C 423.79234,229.89887 440.58035,239.77416 443.54294,253.59958 C 446.5055,267.42497 441.56787,275.32522 436.63023,287.17557 C 431.69257,299.0259 428.73,300.01343 428.73,300.01343 C 428.73,300.01343 431.69257,314.82638 424.77986,320.75156 C 417.86716,326.67673 405.02929,325.68919 405.02929,325.68919 C 405.02929,325.68919 399.10412,361.24025 388.2413,372.10307 C 377.37848,382.9659 353.67777,402.71647 353.67777,402.71647"
       id="path1317"
       sodipodi:nodetypes="cssscsccssscsscscsccsscscsccsscsccsccssccsccsccsccccccsscscsc" />
    <path
       style="fill:#f2d6c6;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 218.26602,201.45124 L 202.59478,237.49508 L 211.47515,270.92704 L 205.72903,298.61289 L 201.02766,309.58276 L 187.44593,309.58276 L 191.62493,330.47774 L 203.63953,345.10422 L 201.55004,363.38735 L 189.01305,377.49145 L 188.49067,394.20744 L 204.68429,406.22204 L 205.20665,431.8184 L 213.56465,452.19101 L 238.63863,451.14627 L 273.11535,432.86315 L 304.4578,428.16178 L 319.60668,432.34077 L 325.87517,435.9974 L 329.5318,442.78827 L 333.7108,448.01201 L 341.02404,449.57914 L 347.8149,445.40014 L 360.87427,432.86315 L 352.51627,418.23666 L 348.33729,402.04305 L 351.9939,393.68505 L 380.20213,372.79008 L 398.48522,339.88048 L 409.45509,326.82113 L 422.51446,321.075 L 431.39484,300.7024 L 439.23046,266.22567 L 437.14096,236.45031 L 417.81309,240.10694 L 402.66422,242.71882 L 395.87336,247.4202 L 395.87336,234.88321 L 396.91812,224.95809 L 393.78386,204.06311 L 383.85875,198.83935 L 355.65053,203.54072 L 300.27883,224.95809 L 290.35371,230.18184 L 273.63771,223.91334 L 237.59388,199.88411 L 219.31076,195.70511 L 218.26602,201.45124 z "
       id="path3356" />
    <path
       style="fill:#fefefe;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 284.27782,231.80654 C 284.27782,231.80654 272.50533,246.52218 265.44182,254.17428 C 258.37833,261.82641 248.3717,274.77615 248.3717,274.77615 C 248.3717,274.77615 239.54235,280.07378 233.6561,276.54203 C 227.76985,273.01028 213.64286,268.8899 213.64286,268.8899 L 200.69312,242.99041 L 215.40872,204.7298 L 224.23811,196.48906 C 224.23811,196.48906 238.95372,198.84356 246.60585,203.55257 C 254.25796,208.26156 267.79634,217.67955 273.68259,221.79992 C 279.56881,225.92029 285.45506,232.39517 284.27782,231.80654 z "
       id="path3349" />
    <path
       style="fill:none;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:14.93623066;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 315.16414,432.34235 C 315.16414,432.34235 313.18907,424.44211 294.42602,425.42964 C 275.66296,426.41716 261.83758,439.25505 249.98723,446.16776 C 237.19212,453.63157 212.4611,455.05551 206.53595,443.20518 C 200.61076,431.35483 206.53595,402.71647 206.53595,402.71647 C 206.53595,402.71647 186.78536,395.80377 186.78536,382.9659 C 186.78536,370.12801 201.59829,364.20285 201.59829,364.20285 L 208.51099,345.4398 C 208.51099,345.4398 192.71053,335.56448 188.76041,323.71415 C 184.8103,311.86378 185.79783,312.85132 191.723,310.87625 C 197.64817,308.90122 199.62324,311.86378 204.56087,306.92614 C 209.49852,301.9885 214.43617,272.36264 214.43617,272.36264 C 214.43617,272.36264 199.62324,260.51229 203.57335,238.78664 C 207.52347,217.061 207.52347,217.061 211.47358,212.12335 C 215.42369,207.18569 222.33641,200.27299 222.33641,200.27299 C 222.33641,200.27299 200.61076,193.3603 186.78536,198.29794 C 172.95995,203.23558 148.27172,217.061 148.27172,217.061 C 148.27172,217.061 155.18441,196.32289 163.08465,181.50995 C 170.98489,166.69701 212.4611,134.10855 212.4611,134.10855 L 145.30913,161.75936 C 145.30913,161.75936 155.18441,141.02124 163.08465,133.12102 C 170.98489,125.22079 181.8477,109.42032 192.71053,103.49514 C 203.57335,97.569967 219.37382,92.63232 219.37382,92.63232 C 219.37382,92.63232 165.05971,90.657262 182.83523,90.657262 C 200.61076,90.657262 153.20936,93.61985 153.20936,93.61985 C 153.20936,93.61985 155.18441,78.806912 179.87265,65.969035 C 204.56087,53.131156 238.13686,46.218451 238.13686,46.218451 L 177.89759,41.280806 C 177.89759,41.280806 197.64817,20.542693 222.33641,20.542693 C 247.02464,20.542693 263.81262,23.505281 275.66296,29.430456 C 287.51333,35.35563 294.42602,45.230923 294.42602,45.230923 C 294.42602,45.230923 305.28885,29.430456 318.12673,23.505281 C 330.9646,17.580106 357.62789,16.592578 357.62789,16.592578 L 317.13921,46.218453 C 317.13921,46.218453 354.66532,33.380573 371.4533,33.380573 C 388.2413,33.380573 406.01684,40.293276 406.01684,40.293276 L 367.50318,52.143628 C 367.50318,52.143628 418.85471,53.131156 437.61775,59.05633 C 456.38081,64.981505 470.20623,70.906681 483.04409,80.781973 C 495.88197,90.657262 499.83209,104.48267 499.83209,104.48267 L 450.45564,97.569967 C 450.45564,97.569967 484.03163,105.4702 492.91939,125.22079 C 501.80714,144.97137 499.83209,159.78431 499.83209,159.78431 L 463.29351,142.00877 C 463.29351,142.00877 481.06904,161.75936 483.04409,177.55982 C 485.01915,193.3603 482.05657,203.23558 482.05657,203.23558 L 451.44317,180.52242 C 451.44317,180.52242 460.33091,199.28547 459.34339,205.21065 C 458.35587,211.13582 457.36833,215.08593 457.36833,215.08593 L 434.65516,197.31042 L 445.51799,222.98617 L 437.12913,237.95513 L 427.74246,214.0984 L 423.79234,229.89887 C 423.79234,229.89887 440.58035,239.77416 443.54294,253.59958 C 446.5055,267.42497 441.56787,275.32522 436.63023,287.17557 C 431.69257,299.0259 428.73,300.01343 428.73,300.01343 C 428.73,300.01343 431.69257,314.82638 424.77986,320.75156 C 417.86716,326.67673 405.02929,325.68919 405.02929,325.68919 C 405.02929,325.68919 399.10412,361.24025 388.2413,372.10307 C 377.37848,382.9659 353.67777,402.71647 353.67777,402.71647"
       id="path3360"
       sodipodi:nodetypes="cssscsccssscsscscsccsscscsccsscsccsccssccsccsccsccccccsscscsc" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:5.97449255;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 270.15082,287.7259 C 275.16461,280.01462 281.37546,272.55352 289.55131,268.13521 C 299.42699,265.07059 309.33566,274.29614 309.8605,283.98778 C 312.05557,294.2993 300.09304,297.48274 292.27448,297.54928 C 281.67864,299.8587 272.51806,305.87794 263.64823,311.78348 C 255.12304,316.49246 245.06588,319.11201 238.24894,326.3488 C 231.95348,331.90474 225.09851,325.23928 229.08306,318.4931 C 232.55509,301.367 240.73269,285.3893 251.96037,272.06476 C 260.56945,259.30823 272.06848,248.66622 280.09597,235.53851 C 283.13341,231.37092 284.62352,224.4998 278.13284,224.47057 C 273.00556,216.03772 280.23289,209.7938 279.3532,197.91107 C 276.22736,189.50426 274.53198,183.38562 268.70683,178.80434 C 260.21687,169.7795 252.26797,166.79306 237.24626,159.16725 C 218.26467,155.4031 211.74964,154.26706 231.76887,163.38204 C 233.18419,172.3556 217.02149,165.11534 213.05423,171.08331 C 205.11218,174.66054 223.92868,172.66761 219.92099,178.73114 C 215.61855,177.65843 196.73394,191.8475 211.53082,186.78126 C 218.67969,189.14659 224.29481,194.91609 231.25932,197.89285 C 238.1055,200.68645 245.6366,202.06576 251.47445,206.97424 C 259.0014,212.6582 266.12563,218.89427 274.20199,223.80491 C 275.48884,224.80193 276.71285,225.88642 277.80296,227.09755"
       id="path2211"
       sodipodi:nodetypes="ccccccccccccccccccccc" />
    <path
       style="fill:#d6d6d6;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:5.97449255;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 270.15082,248.28803 C 278.28474,242.89369 284.18427,234.94476 291.51676,228.61802 C 299.37125,227.15562 307.24157,223.0332 309.56359,214.84625 C 314.68465,206.45766 321.62095,199.37649 327.13686,191.25024 C 331.29656,185.39995 336.93994,180.94675 342.66719,176.77145 C 347.90051,171.93338 353.30428,166.86443 360.30856,164.81258 C 372.93734,159.34523 386.92959,156.55532 400.68044,158.10416 C 394.62422,160.70235 385.3248,160.82533 381.88787,166.4818 C 387.8483,166.29793 393.83117,167.33861 399.90102,167.84555 C 405.7836,167.62099 403.71468,169.12558 415.56425,175.23242 C 412.3058,180.87657 404.56796,173.6991 399.79412,177.16583 C 397.69595,180.24421 406.53068,182.13325 408.15649,185.54045 C 411.73653,188.25566 415.71833,196.40404 407.78278,193.69855 C 397.8674,190.30986 396.5479,192.24314 402.8795,200.55833 C 408.6624,209.12558 393.86126,199.95206 389.6015,200.10092 C 380.53363,197.85413 371.16593,199.5643 362.01916,200.34618 C 352.33809,204.08505 345.32665,206.43036 337.50613,210.33029 C 330.36773,214.38755 323.53919,216.84038 316.02409,220.00055 C 310.46086,220.65949 306.72171,226.00354 301.05944,226.3296"
       id="path3102"
       sodipodi:nodetypes="ccccccccccccccccccc" />
    <path
       style="fill:#fefefe;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:5.97449255;stroke-linecap:butt;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 301.93655,225.33168 C 296.06771,227.80858 293.83863,234.61746 293.52672,240.50724 C 291.05233,250.27338 297.22382,259.3452 302.88986,266.70365 C 306.81738,271.74536 313.19744,274.65411 319.56358,274.41767 C 329.68429,276.11622 339.37761,274.26402 350.09365,271.12275 C 357.6945,265.50859 367.2429,261.86869 372.29485,253.41718 C 376.5737,246.11931 379.99495,238.07703 381.10614,229.64036 C 382.22609,221.69559 379.62855,213.25977 373.48459,207.92457 C 370.96205,205.91887 365.96511,200.82217 363.74219,201.78669 L 301.93655,225.33168 z "
       id="path3108"
       sodipodi:nodetypes="ccccccccc" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 351.38103,245.34493 C 342.10393,247.63309 339.73042,235.62229 341.61948,228.92048 C 341.81398,220.79109 350.78757,213.49657 358.42512,218.16178 C 366.45709,221.56473 364.74689,235.04911 355.82265,235.7331 C 346.49342,238.00687 348.84852,221.75185 356.39694,228.02064 C 357.26669,229.05927 357.4686,230.50346 357.26728,231.80656"
       id="path3124" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:4.97874355;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 229.53573,216.5023 C 226.70039,216.64668 226.06246,220.15101 227.76985,223.5658 C 229.40062,226.82734 233.39813,221.55525 233.6561,221.2113 C 236.11315,217.93524 231.12841,216.82084 229.53573,216.5023 z "
       id="path3126" />
    <path
       style="fill:none;fill-opacity:1;fill-rule:evenodd;stroke:#929291;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 297.81619,170.00094 C 297.07262,157.37633 297.20111,151.23075 301.26921,139.30825 C 302.79906,135.36869 304.31845,129.72388 307.01613,127.03234 C 307.63403,126.36373 312.02456,121.31352 312.5318,120.55649"
       id="path3128"
       sodipodi:nodetypes="cccc" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#929291;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 304.87969,168.82372 C 307.6226,162.34066 309.33958,155.10688 314.34958,149.88272 C 317.91749,146.1605 321.12521,141.96924 325.49437,139.12918 C 326.3829,138.60829 327.38552,138.23419 328.42466,138.21523"
       id="path3136" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#929291;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 313.70906,170.00094 C 315.63963,167.24502 317.21943,163.96332 320.31435,162.31422 C 323.25729,158.95169 325.89083,155.86877 330.165,153.30331 C 334.24794,150.84848 338.86104,150.20314 343.43116,148.70694 C 345.82481,147.67331 348.50747,148.46823 350.7924,149.3991"
       id="path3138"
       sodipodi:nodetypes="ccccc" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#929291;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 277.80296,143.51285 C 279.15213,147.74486 282.96844,150.42761 285.20212,154.12627 C 287.93125,156.87472 288.75286,160.83005 289.29244,164.524 C 289.724,167.18698 290.09258,169.92531 291.34133,172.35547"
       id="path3148" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#929291;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 263.08734,150.57634 C 266.32535,153.222 271.04202,153.50458 273.79621,156.82729 C 278.91313,162.54676 282.51139,169.4387 285.45508,176.47583"
       id="path3152" />
    <path
       style="fill:#6a526e;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 279.56883,63.459882 C 293.40151,62.7241 300.7593,69.346129 306.64556,79.35275 C 312.5318,89.359369 310.76594,105.84087 298.99344,113.49298 C 287.22095,121.1451 280.74606,122.32235 264.26457,119.96785 C 247.78308,117.61335 244.25134,95.834241 251.31484,82.295873 C 258.37833,68.757504 265.73615,64.195662 279.56883,63.459882 z "
       id="path3156"
       sodipodi:nodetypes="czsssz" />
    <path
       style="fill:#9696ce;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 277.21433,80.529998 C 277.21433,80.529998 295.46168,77.586876 294.28444,88.770745 C 293.1072,99.954614 287.80957,102.89774 278.39158,101.72049 C 268.97356,100.54324 261.32146,98.777364 262.49872,92.891117 C 263.67595,87.00487 266.61908,84.061747 270.15082,82.884498 C 273.68259,81.707248 278.9802,81.118623 277.21433,80.529998 z "
       id="path3158" />
    <path
       style="fill:#6a526e;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 374.92603,81.118623 C 363.74217,77.218984 346.08341,80.529998 340.19716,93.479742 C 334.31091,106.42949 338.43128,121.73372 356.09003,129.38585 C 373.74878,137.03797 396.70515,116.4361 394.35064,103.48636 C 391.99613,90.53662 386.1099,85.018261 374.92603,81.118623 z "
       id="path3160"
       sodipodi:nodetypes="csssz" />
    <path
       style="fill:#9696ce;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 371.39427,92.891117 C 371.39427,92.891117 358.44454,89.947994 356.09003,98.777364 C 353.73554,107.60673 361.97628,109.37261 368.45116,109.96123 C 374.92603,110.54986 380.22363,110.54986 381.40089,104.07499 C 382.57814,97.600116 374.33741,94.068367 374.33741,94.068367 L 371.39427,92.891117 z "
       id="path3162"
       sodipodi:nodetypes="cssscc" />
    <path
       style="fill:#6a526e;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 393.76201,112.31573 C 393.76201,112.31573 407.30039,121.1451 407.88901,131.15172 C 408.47763,141.15834 404.94588,149.3991 404.94588,149.3991 C 404.94588,149.3991 402.59138,137.62659 397.88238,132.9176 C 393.17339,128.2086 384.93266,122.32235 384.93266,122.32235"
       id="path3168"
       sodipodi:nodetypes="cscsc" />
    <path
       style="fill:#ffffff;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 210.69973,339.52487 C 210.69973,339.52487 224.23811,339.52487 233.6561,345.41112 C 243.07409,351.29737 247.78308,355.41773 259.55557,355.41773 C 271.32807,355.41773 284.27782,343.64523 290.75269,338.34762 C 297.22756,333.04999 300.17069,337.75901 310.76594,334.81587 C 321.36118,331.87276 326.65881,325.9865 333.13366,318.92301 C 339.60854,311.8595 340.19716,304.20739 343.7289,294.7894 C 347.26066,285.37138 360.79902,268.8899 366.68527,268.8899 C 372.57152,268.8899 386.6985,287.7259 387.28714,293.02352 C 387.87576,298.32114 387.28714,330.6955 383.16677,340.70212 C 379.0464,350.70873 358.44454,374.25374 350.20378,373.6651 C 341.96304,373.07648 334.31091,368.36748 329.01329,368.95611 C 323.71567,369.54473 298.40481,393.67834 291.92994,394.26697 C 285.45506,394.85559 273.68259,387.79209 268.38497,388.96934 C 263.08734,390.1466 259.55557,396.62145 253.0807,396.62145 C 246.60585,396.62145 240.7196,396.62145 236.5992,393.08971 C 232.47884,389.55798 234.24472,384.26035 226.59259,381.90585 C 218.94048,379.55135 214.82012,385.4376 208.93386,381.31722 C 203.04761,377.19684 197.16136,370.13334 197.16136,370.13334"
       id="path3170" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 236.5992,347.76563 L 225.41537,380.13999"
       id="path3172" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 257.20109,356.00636 L 254.25796,395.44423"
       id="path3174" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 283.10058,346.58837 L 288.39819,393.67834"
       id="path3176" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 310.76594,335.99313 L 319.59531,373.07648"
       id="path3178" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 334.31091,320.10025 L 356.67866,368.95611"
       id="path3180" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 346.67204,293.02352 L 381.98951,339.52487"
       id="path3182" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 380.22366,263.59227 C 380.22366,263.59227 393.17339,270.65578 396.70515,276.54203 C 400.23689,282.42828 400.82551,296.55526 400.82551,303.03014 C 400.82551,309.50502 402.59138,327.16374 402.59138,327.16374"
       id="path3184" />
    <path
       style="fill:#e2b28a;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 405.27612,281.37453 C 405.27612,281.37453 404.75372,287.12067 409.45509,288.68777 C 414.15648,290.25489 418.33548,287.64303 421.46972,282.41929 C 424.60395,277.19554 426.69345,268.31517 424.60395,264.65854 C 422.51446,261.00194 416.76834,254.21106 412.58934,255.2558 C 408.41035,256.30056 399.00763,262.04667 399.52999,265.7033 C 400.05236,269.35993 406.32085,273.53891 406.32085,276.67317 C 406.32085,279.80741 405.79848,280.32979 405.27612,281.37453 z "
       id="path3353" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 394.93926,232.98379 C 394.93926,232.98379 393.76203,248.87666 391.99615,251.81977 C 390.23026,254.76291 404.35727,238.87004 413.77526,239.45867 C 423.19326,240.0473 424.37049,241.81316 432.61125,248.87666 C 440.85199,255.94017 444.38374,262.41503 444.38374,262.41503"
       id="path3186"
       sodipodi:nodetypes="csssc" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 424.95911,266.53541 C 424.95911,266.53541 420.83875,255.94017 415.54112,255.94017 C 410.24351,255.94017 397.88238,258.29465 399.05964,267.12403 C 400.23689,275.95339 406.71177,274.77615 406.71177,280.66239 C 406.71177,286.54864 402.59138,291.84627 402.59138,291.84627 L 400.85545,301.5835"
       id="path3188"
       sodipodi:nodetypes="cssscc" />
    <path
       style="fill:#6a526e;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 343.7289,89.359369 C 343.7289,89.359369 330.77917,81.118623 325.48156,81.118623 C 320.18393,81.118623 310.17732,84.650372 310.17732,84.650372 L 310.76594,95.834241 C 310.76594,95.834241 320.77256,94.656991 324.89292,96.422867 C 329.01329,98.188739 337.84267,103.48636 337.84267,103.48636 L 343.7289,89.359369 z "
       id="path3208" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:4.97874355;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 205.99075,397.21008 L 219.52911,402.5077"
       id="path3367" />
    <path
       style="fill:none;fill-opacity:1;fill-rule:evenodd;stroke:#e6b994;stroke-width:4.97874355;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 314.29768,280.07378 C 314.29768,280.07378 318.82563,283.5331 325.30048,282.94449 C 331.77536,282.35587 338.80266,280.1462 338.80266,280.1462"
       id="path3369"
       sodipodi:nodetypes="csc" />
    <path
       style="fill:#f2d6c6;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:3;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 348.37764,401.66058 C 320.88481,432.90241 326.50835,434.1521 328.38286,437.90111 C 330.25736,441.65016 326.50835,444.77433 332.75672,446.64885 C 339.0051,448.52334 344.00378,449.77303 352.7515,440.40047 C 361.49922,431.02792 367.30728,426.41471 364.6234,422.90504 L 348.37764,401.66058 z "
       id="path3410"
       sodipodi:nodetypes="cssssc" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:5.97449255;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 320.88481,432.90241 C 320.88481,432.90241 326.50835,434.1521 328.38286,437.90111 C 330.25736,441.65016 326.50835,444.77433 332.75672,446.64885 C 339.0051,448.52334 344.00378,449.77303 352.7515,440.40047 C 361.49922,431.02792 367.30728,426.41471 364.6234,422.90504 L 348.37764,401.66058"
       id="path3371"
       sodipodi:nodetypes="cssssc" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 328.38286,469.7678 L 310.26259,448.52334"
       id="path3380" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 374.6208,449.1482 L 379.6195,425.40439"
       id="path3382" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 311.51225,453.52204 C 311.51225,453.52204 304.01421,471.64232 303.38937,480.39003 C 302.76454,489.13776 305.88872,508.5077 305.88872,508.5077"
       id="path3384" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 305.88872,481.01489 C 305.88872,481.01489 325.25867,491.63711 349.00247,490.38743 C 372.74628,489.13776 398.98944,466.64362 402.11362,466.01879 C 405.2378,465.39395 415.2352,482.88938 413.36068,514.75607 C 411.48619,546.62276 395.86525,644.09733 395.86525,644.09733"
       id="path3386" />
    <path
       style="fill:#020202;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 390.86656,492.26195 C 403.3633,492.26195 397.11492,514.75607 387.74237,512.88155 C 378.36981,511.00706 380.24432,496.01096 390.86656,492.26195 z "
       id="path3390" />
    <path
       style="fill:#020202;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 390.86656,540.99922 C 402.73845,541.62406 397.11492,566.61755 387.11754,566.61755 C 377.12014,566.61755 377.12014,538.49987 390.86656,540.99922 z "
       id="path3400" />
    <path
       style="fill:#020202;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 387.11754,599.10907 C 397.73977,600.35873 390.86656,624.10254 383.99334,622.85288 C 377.12014,621.6032 373.99596,599.10907 387.11754,599.10907 z "
       id="path3402" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:14.93623066;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 300.26518,577.23975 L 296.51617,532.87634 L 302.76454,504.13384"
       id="path3405"
       sodipodi:nodetypes="ccc" />
    <path
       style="fill:#c6c2a3;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 538.32808,389.78866 C 541.45225,391.66319 570.8196,400.41089 570.8196,400.41089 L 577.06798,367.91938 L 545.20129,364.79519 L 538.32808,389.78866 z "
       id="path3418" />
    <path
       style="fill:none;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:14.93623066;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 356.50051,402.28541 C 356.50051,402.28541 381.09878,406.15155 381.49401,430.40307 C 381.50486,431.08575 437.72934,446.64885 459.59864,445.39917 C 481.46791,444.1495 508.96074,424.77954 516.45879,418.53117 C 523.95684,412.28281 536.45358,402.28541 540.2026,389.16383 C 543.95161,376.04225 545.82613,361.04618 545.82613,361.04618 L 578.31764,366.04486 C 578.31764,366.04486 579.56733,369.16907 577.06798,380.41613 C 574.56863,391.66319 569.56993,406.65928 562.07188,420.40568 C 554.57384,434.1521 543.28245,456.92157 530.20519,466.01879 C 515.83395,476.01618 493.96467,484.7639 482.71758,486.63842 C 463.52194,489.83769 433.98031,487.26325 433.98031,487.26325 C 433.98031,487.26325 436.47965,518.5051 433.98031,544.1234 C 431.48095,569.74172 427.1071,602.85808 423.9829,615.97966 C 420.85872,629.10124 417.10971,641.59799 417.10971,641.59799 L 410.2365,657.21891 L 298.39067,662.21759 L 302.13971,639.09863 C 302.13971,639.09863 300.89002,559.74433 299.64036,542.24888 C 298.39067,524.75346 301.51486,502.88417 301.51486,502.88417 C 301.51486,502.88417 261.5253,530.377 227.7841,526.62797 C 185.3319,521.91109 142.18143,494.7613 142.18143,494.7613 L 171.54877,462.26976 C 171.54877,462.26976 201.54094,486.63842 227.15927,486.01355 C 252.77758,485.38873 267.14884,479.14037 279.02073,471.64232 C 290.89263,464.14428 308.38807,452.89722 310.88742,447.89852 C 313.38677,442.89981 314.63643,434.1521 314.63643,434.1521"
       id="path3416"
       sodipodi:nodetypes="cssssccsssscssccccscsccsssc" />
    <path
       style="opacity:1;fill:#020202;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:14.93623066;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 120.31215,524.12863 C 120.31215,524.12863 145.93045,501.00966 162.17621,482.26454 C 178.42198,463.51943 195.91741,437.27628 195.91741,437.27628 C 195.91741,437.27628 171.54877,427.90371 139.05725,387.91415 C 106.56572,347.92457 106.56572,323.55596 106.56572,323.55596 C 106.56572,323.55596 117.81279,311.68405 122.81149,299.1873 C 127.81019,286.69058 130.93436,279.19254 120.93698,274.81866 C 110.93959,270.4448 89.695126,269.81996 82.197082,278.56767 C 74.699038,287.31541 76.573551,296.68796 82.82192,298.56247 C 89.070289,300.43699 105.94089,298.56247 105.94089,298.56247 C 105.94089,298.56247 112.81409,299.1873 110.31474,306.68535 C 107.8154,314.18339 86.570943,312.30888 85.321268,315.43306 C 84.071596,318.55727 78.448062,329.80433 80.94741,336.67751 C 83.446756,343.55074 92.819312,351.04878 92.819312,351.04878 C 92.819312,351.04878 92.194474,376.66709 89.695128,389.78866 C 87.195778,402.91025 89.070289,414.78216 82.82192,418.53117 C 76.573551,422.28021 73.449366,429.77823 77.198388,440.40047 C 80.94741,451.0227 82.197084,463.51943 93.44415,482.26454 C 104.69121,501.00966 124.686,523.50378 120.31215,524.12863 z "
       id="path3421"
       sodipodi:nodetypes="cscscsssscssscssssc" />
    <path
       style="fill:#f6c612;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:1.9914974px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 162.17621,417.28152 C 162.17621,417.28152 143.4311,446.02399 130.93436,456.02139 C 118.43763,466.01879 99.067682,484.7639 99.067682,484.7639 L 112.81409,504.75867 C 112.81409,504.75867 139.68208,477.89068 151.55399,463.51943 C 163.4259,449.1482 175.92263,429.1534 175.92263,429.1534 L 162.17621,417.28152 z "
       id="path3423" />
    <path
       style="opacity:1;fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:14.93623066;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 66.833795,289.87778 C 66.833795,289.87778 57.799043,285.36043 47.760429,287.36813 C 37.721816,289.37586 31.80784,291.92276 27.18127,301.92412 C 24.135428,308.50839 33.70637,312.46466 33.70637,317.48397 C 33.70637,322.50328 26.679341,323.50715 24.671619,327.52259 C 21.753505,333.35881 21.864435,339.39663 24.671619,340.57279 C 32.334625,343.78346 34.710232,352.11717 34.710232,352.11717 C 34.710232,352.11717 35.7973,357.42313 28.687064,360.65001 C 27.768062,361.06709 29.690926,366.17123 33.204441,369.68476 C 37.343467,373.82379 45.752704,376.20986 45.752704,376.20986 C 45.752704,376.20986 41.73726,394.78129 46.254638,403.31412 C 50.772013,411.84693 48.764291,416.36433 57.799043,420.8817 C 66.833795,425.39907 76.370478,427.40679 76.370478,427.40679 C 76.370478,427.40679 74.362756,430.92029 75.366616,434.43383 C 76.370478,437.94734 77.37434,442.46472 77.37434,442.46472 L 79.382063,448.98981 L 86.409091,452.50333 L 126.56355,404.81991 L 108.49404,347.09788 L 95.945775,338.06312 L 92.43226,370.18669 L 87.914884,405.82379 L 79.382063,416.86624 L 67.335726,424.89713 L 51.273944,418.37206 L 48.26236,400.80446 L 46.254638,378.7195 L 45.250777,376.20986 C 45.250777,376.20986 57.799043,375.20601 61.814491,373.19828 C 65.829935,371.19055 69.34345,370.68861 70.849241,364.66544 C 72.355031,358.64228 71.853104,359.1442 67.837657,355.63071 C 63.822211,352.11717 62.31642,344.08628 64.826073,340.57279 C 67.335726,337.05927 69.845379,340.07085 69.845379,334.04769 C 69.845379,328.02452 73.860826,313.46854 72.355031,308.95116 C 70.849241,304.43378 65.829935,290.3797 66.833795,289.87778 z "
       id="path3428"
       sodipodi:nodetypes="cssssscsscsscsccccccccccccccssssssc" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#ffffff;stroke-width:1.9914974px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 35.714094,353.12106 C 35.714094,353.12106 52.779736,353.12106 58.300973,352.11717 C 63.822211,351.11333 66.833795,348.60368 66.833795,348.60368"
       id="path3432" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#000000;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 53.246331,208.60307 C 53.246331,208.60307 65.534799,211.67519 72.191053,210.65114 C 78.847306,209.62711 89.599715,203.99488 89.599715,203.99488"
       id="path3440"
       sodipodi:nodetypes="csc" />
    <path
       style="fill:#fefefe;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 63.99874,225.4997 C 69.118934,225.4997 68.606916,232.15595 68.606916,232.15595 L 68.606916,254.68482 C 68.606916,254.68482 68.606916,258.26894 65.534799,258.78098 C 62.462681,259.29299 59.390565,255.19683 59.390565,255.19683 L 60.414604,229.08384 C 60.414604,229.08384 60.414604,225.4997 63.99874,225.4997 z "
       id="path3442" />
    <path
       sodipodi:type="arc"
       style="opacity:0;color:#000000;fill:#fefefe;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;marker:none;marker-start:none;marker-mid:none;marker-end:none;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;visibility:visible;display:inline;overflow:visible"
       id="path3446"
       sodipodi:cx="30.209574"
       sodipodi:cy="134.75746"
       sodipodi:rx="1.9282707"
       sodipodi:ry="2.4424763"
       d="M 32.137844,134.75746 A 1.9282707,2.4424763 0 1 1 32.126326,134.4909 L 30.209574,134.75746 z"
       sodipodi:start="0"
       sodipodi:end="6.1738297"
       transform="matrix(1.991498,0,0,1.991498,4.092461,2.444287)" />
    <path
       sodipodi:type="arc"
       style="opacity:1;color:#000000;fill:#fefefe;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;marker:none;marker-start:none;marker-mid:none;marker-end:none;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;visibility:visible;display:inline;overflow:visible"
       id="path3449"
       sodipodi:cx="29.952471"
       sodipodi:cy="134.37181"
       sodipodi:rx="2.4424763"
       sodipodi:ry="2.5710275"
       d="M 32.394947 134.37181 A 2.4424763 2.5710275 0 1 1  27.509995,134.37181 A 2.4424763 2.5710275 0 1 1  32.394947 134.37181 z"
       transform="matrix(1.991498,0,0,1.991498,4.092461,2.444287)" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#69c5b3;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 57.890501,162.33114 C 68.336722,162.85345 70.425967,175.38893 60.502058,177.47816 C 50.578147,179.5674 50.578147,173.82199 50.578147,170.16581 C 50.578147,166.50964 57.368189,163.37576 57.890501,162.33114 z "
       id="path3452" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#69c5b3;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 87.139921,157.63034 C 87.139921,157.63034 94.974588,163.89807 93.407656,166.50964 C 91.840721,169.12119 89.229165,171.73274 85.050676,171.21043 C 80.872189,170.68811 76.693701,168.59888 79.305255,162.85345 C 81.916811,157.10803 82.439123,157.63034 87.139921,157.63034 z "
       id="path3454" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#69c5b3;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 102.80926,111.14466 C 110.12161,111.66696 122.65707,133.60403 102.80926,134.64866 C 82.961433,135.69328 89.751478,111.14466 102.80926,111.14466 z "
       id="path3456" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#69c5b3;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 65.725168,122.1132 C 81.3945,122.1132 85.253475,148.22875 68.336724,148.22875 C 51.62277,148.22875 50.578147,124.72475 65.725168,122.1132 z "
       id="path3458"
       sodipodi:nodetypes="csc" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#69c5b3;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 76.171388,80.328305 C 89.229165,80.328305 87.662233,100.17613 76.171388,99.653815 C 64.680546,99.131504 62.06899,85.551415 76.171388,80.328305 z "
       id="path3460" />
    <path
       style="fill:none;fill-opacity:1;fill-rule:evenodd;stroke:#69c5b3;stroke-width:3.98299479;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 84.528367,40.632663 C 98.108453,41.154973 93.929966,58.91355 84.528367,60.480484 C 75.126767,62.047416 67.814412,44.811152 84.528367,40.632663 z "
       id="path3464" />
    <path
       style="fill:#020202;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:5.97449255;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 495.97714,350.57136 C 495.97714,350.57136 503.0429,357.63712 560.65591,365.78988 C 618.26893,373.94268 630.22635,370.13804 630.22635,370.13804 L 616.63838,221.75737 C 616.63838,221.75737 624.79116,225.56199 641.09673,224.47494 C 657.4023,223.38791 674.25139,214.14809 682.40418,208.7129 C 690.55697,203.27769 699.79678,173.92769 699.79678,173.92769 C 699.79678,173.92769 689.46993,172.29713 682.94769,177.1888 C 676.42545,182.08047 661.20693,196.21196 651.96711,196.75548 C 642.7273,197.299 630.76986,197.299 630.76986,192.40733 C 630.76986,187.51566 645.44489,178.27584 653.59766,167.40545 C 661.75046,156.53508 665.55509,141.86007 665.55509,136.9684 C 665.55509,132.07672 661.20693,117.94522 654.6847,115.22763 C 648.16247,112.51004 644.35785,110.33596 640.55321,114.68412 C 636.74857,119.03226 640.0097,123.38041 641.09673,130.44616 C 642.18378,137.51191 644.35785,150.55637 635.66154,159.79619 C 626.96522,169.03601 619.35597,177.1888 614.46429,178.27584 C 609.57262,179.36288 601.96334,180.44991 601.96334,176.64528 C 601.96334,172.84064 610.11614,159.25267 611.7467,150.01285 C 613.37726,140.77302 618.26893,128.81561 606.3115,116.31467 C 594.3541,103.81373 583.48371,100.55262 576.41798,101.63966 C 569.35222,102.7267 555.76426,105.4443 556.30778,113.05356 C 556.85127,120.66282 558.48184,119.57579 572.61334,125.01097 C 586.74482,130.44616 590.00595,137.51191 591.6365,146.20821 C 593.26706,154.90452 591.6365,167.94897 581.85315,175.01472 C 572.06982,182.08047 562.28646,188.05917 551.95962,186.42863 C 541.63275,184.79807 538.37164,178.27584 540.5457,172.29713 C 542.71978,166.31842 548.15498,162.51379 549.24202,159.25267 C 550.32906,155.99156 551.4161,149.46933 540.5457,152.18693 C 529.67534,154.90452 522.06606,162.51379 520.97903,171.2101 C 519.89199,179.90639 516.63087,187.51566 521.52255,196.75548 C 526.41422,205.9953 538.37164,208.7129 550.32906,209.25642 C 562.28646,209.79994 566.0911,211.43049 567.17815,215.77865 C 568.26519,220.1268 556.85127,241.86756 548.6985,258.17313 C 540.5457,274.4787 514.4568,314.6991 505.7605,329.37413 C 497.0642,344.04914 494.89011,344.04914 495.97714,350.57136 z "
       id="path4361" />
    <path
       style="fill:#f6c612;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:1.9914974px;stroke-linecap:round;stroke-linejoin:round;stroke-opacity:1"
       d="M 529.13182,304.37224 L 517.71791,325.02597 C 517.71791,325.02597 533.47998,337.5269 560.65591,341.87505 C 587.83187,346.2232 617.72542,343.5056 617.72542,343.5056 L 617.72542,322.85189 C 617.72542,322.85189 597.61521,327.74357 569.35222,320.13429 C 541.08924,312.52504 528.58831,306.00281 529.13182,304.37224 z "
       id="path4363" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#ffffff;stroke-width:1.9914974;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"
       d="M 573.70038,196.75548 C 573.70038,196.75548 583.48371,190.23325 599.78929,195.66844 C 616.09486,201.10364 615.00782,207.08234 615.00782,207.08234"
       id="path4365" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#ffffff;stroke-width:1.9914974px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 38.535308,316.94479 C 38.535308,316.94479 49.360201,318.91297 60.185098,317.43683 C 71.00999,315.96071 80.358763,306.11988 80.358763,306.11988"
       id="path5255"
       sodipodi:nodetypes="csc" />
    <path
       style="fill:none;fill-opacity:0.75;fill-rule:evenodd;stroke:#ffffff;stroke-width:1.9914974px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 73.962235,291.35868 C 73.962235,291.35868 72.978154,299.72338 78.882641,303.65968 C 84.787127,307.59602 87.739374,307.10398 87.739374,307.10398 C 87.739374,307.10398 81.342846,306.61194 79.866721,311.53233 C 78.390599,316.45275 78.390599,324.81744 78.390599,324.81744"
       id="path5257" />
  </g>
</svg>

    </div>
        
    </div>
    <div id="footer">
        This website was created by <a href="http://www.petercollingridge.co.uk">Peter Collingridge</a>.
        <span id="example-svg-attribution" style="display:none;">
            Mad scientist SVG from <a href="http://commons.wikimedia.org/wiki/File:Mad_scientist.svg">Wikimedia</a>,
            drawn by J.J. and converted to SVG by <a href="http://commons.wikimedia.org/wiki/User:Antilived">Antilived</a>.
        </span>
    </div>

  </body>
 
</html>