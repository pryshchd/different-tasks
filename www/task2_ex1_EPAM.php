<pre>
<?php
// масссив для тестирования
$input_data = array
 (
  array('ZZZ', 'BBB', 'CCC'), // Sub-array with strings only!
  array
  (
   array (5, 2, 7), // Sub-array with numbers only!
   array ('C', 56, TRUE), // Combined sub-array.
   array
   (
    array ('C', 'V', 'lalala'), // Sub-array with strings only!
    array (30, 15, 45), // Sub-array with numbers only!
    array (78, 34, 'F') // Combined sub-array.
   )
  ),
  array(12, 95, 1.1, 0.8, 4) // Sub-array with numbers only! 
 );

print_r ($input_data);
$countstr = 0;
$countnum = 0;
checkarray($input_data);
print_r ($input_data);

function checkarray(array &$array)
	{
	foreach ($array as &$data)
		{
		if (is_int($data) || is_float($data)) $countnum +=1;
		if (is_string($data)) $countstr +=1;
		if (is_array($data)) checkarray($data);
		}
	if (($countnum == 0 && $countstr >= 1) || ($countnum >=1 && $countstr == 0))
		{
		sort($array);
		$countnum = 0;
		$countstr = 0;
		}
	}