<?php
	echo shell_exec('
				cd .. 2>&1;
				git add . 2>&1;
				git config user.email "sgs.sandhu@gmail.com" 2>&1;
				git config user.name "SGS Sandhu" 2>&1;
				git commit -m "Shcheduled upload" 2>&1;
				git push https://sgssandhu:sgs%40oxo.9090@github.com/oxosolutions/smaart-admin.git --all 2>&1;
			');
?>