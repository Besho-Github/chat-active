<?php
	/**
	 * readyChat 2.2.0 release
	 * Software by DesignSkate
	 */
	
	$execQuery = $mysqli->query("SELECT COUNT(*) as num FROM $table $extra");
	$query = $execQuery->fetch_array(MYSQLI_BOTH) or die($mysqli->error);
	$total_pages = $query["num"];
	
    $stages = 3;
	
	if (isset($_GET["list"]))
	{
		$page = $mysqli->real_escape_string($_GET["list"]);
	}
	else
	{
		$page = 1;
	}
	
	if ($page) 
	{
		$start = ($page - 1) * $limit;
	} 
	else 
	{
		$start = 0;
	}
	
	$prev = $page - 1;
	$next = $page + 1;
	$lastpage = ceil($total_pages/$limit);
	$LastPagem1 = $lastpage - 1;

	$paginate = "";
	if($lastpage > 1)
	{
		$paginate .= "<div class='paginate'>";
		if ($page > 1)
		{
			$paginate.= "<a href='$list&list=$prev'>Back</a>";
		} 
		else 
		{
			$paginate.= "<span class='disabled'>Back</span>";
		}

		if ($lastpage < 7 + ($stages * 2))
		{
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
				{
					$paginate.= "<span class='current'>$counter</span>";
				} 
				else 
				{
					$paginate.= "<a href='$list&list=$counter'>$counter</a>";
				}
			}
		} 
		elseif($lastpage > 5 + ($stages * 2)) 
		{
			if($page < 1 + ($stages * 2)) 
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page)
					{
						$paginate.= "<span class='current'>$counter</span>";
					} 
					else 
					{
						$paginate.= "<a href='$list&list=$counter'>$counter</a>";
					}
				}
						
				$paginate.= "... <a href='$list&list=$LastPagem1'>$LastPagem1</a>";
				$paginate.= "<a href='$list&list=$lastpage'>$lastpage</a>";
			} 	
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<a href='$list&list=1'>1</a>";
				$paginate.= "<a href='$list&list=2'>2</a>";
				$paginate.= "...";
						
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page)
					{
						$paginate.= "<span class='current'>$counter</span>";
					}
					else 
					{
						$paginate.= "<a href='$list&list=$counter'>$counter</a>";
					}
				}
							
				$paginate.= "...";
				$paginate.= "<a href='$list&list=$LastPagem1'>$LastPagem1</a>";
				$paginate.= "<a href='$list&list=$lastpage'>$lastpage</a>";							
			} 
			else 
			{
				$paginate.= "<a href='$list&list=1'>1</a>";
				$paginate.= "<a href='$list&list=2'>2</a>";
				$paginate.= "...";
							
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
					{
						$paginate.= "<span class='current'>$counter</span>";
					} 
					else 
					{
						$paginate.= "<a href='$list&list=$counter'>$counter</a>";
					}
				}
			}
		}

		if ($page < $counter - 1)
		{
			$paginate.= "<a href='$list&list=$next'>Next</a>";
		} 
		else 
		{
			$paginate.= "<span class='disabled'>Next</span>";
		}
			
		$paginate.= "</div><br />";
    }
?>