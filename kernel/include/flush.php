<?php
# Определить сообщение
function setFlush($flush)
{		
	$_SESSION['Flush']['Data'] = $flush;
}
 

# Показать сообщение
function showFlush()
{
	@$flush = $_SESSION['Flush']['Data'];
	clearFlush();

	return $flush;			
}
		
# Проверка на содержание
function isFlush()
{		
	return !empty($_SESSION['Flush']);	
}

// Очистить flush
function clearFlush()
{	
	unset($_SESSION['Flush']);
}
?>
