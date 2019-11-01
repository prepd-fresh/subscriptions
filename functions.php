<?php
function quiz_scripts()
{
    $scripts = "
    <script>

	function getCostPerMeal(quantity, subscriptionSize)
	{
	    var price = 8.99;
	    if (subscriptionSize == 'large')
	    {
		price += 2;
	    }
	    if (quantity > 9)
	    {
		price -= 2;
	    }
	    else if (quantity > 4)
	    {
		price -= 1;
	    }
	    return price;
	}

	function updateCostPerMeal(costPerMeal)
	{
	    var cost = costPerMeal.toFixed(2);
	    document.getElementById(\"pricePerMeal\").innerHTML = 'Price per meal: ' + cost;
	    document.getElementById(\"pricePerMeal2\").innerHTML = 'Price per meal: ' + cost;
	}

	function updateTotalMealCost(quantity, costPerMeal)
	{
	    var cost = (costPerMeal * quantity).toFixed(2);
	    document.getElementById(\"totalMealCost\").innerHTML = 'Total cost: ' + cost;
	    document.getElementById(\"totalMealsNumberCost\").innerHTML = 'Total meals: ' + quantity;
	    document.getElementById(\"totalMealsNumberCost2\").innerHTML = 'Total meals: ' + quantity;
	    document.getElementById(\"totalMealCost2\").innerHTML = 'Total cost: ' + cost;
	}

	function updateSubscriptionLink()
	{
	    var note = \"\";
	    var quantity = 0;
	    var regularProductId = 4726;
	    var largeProductId = 4781;
	    
	    var meal1 = document.getElementById(\"meal1\").textContent;
	    var meal2 = document.getElementById(\"meal2\").textContent;
	    var meal3 = document.getElementById(\"meal3\").textContent;
	    var meal4 = document.getElementById(\"meal4\").textContent;

	    var allMeals = [meal1, meal2, meal3, meal4];

	    for (var i = 0; i < allMeals.length; i++)
	    {
		var num = document.getElementById(allMeals[i].replace(/\\s+/g, '')).innerHTML;
		var val = parseInt(num);
		if (isNaN(num)) {val = 0;}
		
		if (val != 0)
		{
		    quantity += val;
		    note += allMeals[i] + ' x ' + num;
		}
	    }	
	    var subscriptionElement = document.getElementById(\"sizeOfSubscriptionPlan\")
	    var subscriptionSize = subscriptionElement.options[subscriptionElement.selectedIndex].value;
	    var costPerMeal = getCostPerMeal(quantity, subscriptionSize);
	    
	    var productId = regularProductId;
	    if (subscriptionSize == \"large\")
	    {
		productId = largeProductId;
	    }            

	    note = note + ' - ' + costPerMeal + ' / meal';

	    note = note.replace(/&/g, '');	
	    var url = \"<a href='https://prepdfresh.com/check-out?clear-cart&add-to-cart=\" + productId + \"&quantity=\" + quantity + \"&note=\" + note + \"'><button>Subscribe</button></a>\";

	    document.getElementById(\"subscriptionLink\").innerHTML = url;

	    updateCostPerMeal(costPerMeal);

	    updateTotalMealCost(quantity, costPerMeal);

	}

        function addOne(id)
        {
            var x = document.getElementById(id).innerHTML;
            
	    var num = parseInt(x);
            if (isNaN(num))
	    {
		num = 0;
   	    }

	    var result = num + 1;

            if (result >= 99)
            {
                result = 99;
            }

            var newString = ' NUM  '.replace(\"NUM\", result);
            document.getElementById(id).innerHTML = newString;
	    updateSubscriptionLink();
        }
        function minusOne(id)
        {
            var x = document.getElementById(id).innerHTML;
  	    
	    var num = parseInt(x);
	    if (isNaN(num)) {
		num = 0;
	    }            

	    var result = num - 1;

            if (result <= 0)
            {
                result = 0;
            }

            var newString = ' NUM '.replace(\"NUM\", result);
            document.getElementById(id).innerHTML = newString;
	    updateSubscriptionLink();
        }
    </script>
    ";

    return $scripts;
}

function show_subscribe_button($totalMeals, $mealSize, $orderNote)
{
    if ($mealSize == "regular")
    {
	$productId = 4726;
    }
    else
    {
	$productId = 4781;
    }

    $button = "<br><div id=\"subscriptionLink\" style=\"text-align: center\"><a href=\"https://prepdfresh.com/check-out?clear-cart&add-to-cart=" . $productId . "&quantity=" . $totalMeals . "&note=" . $orderNote ."\"><button>Subscribe</button></a></div>";

    return $button;
}

function show_size_information($mealSize)
{
    $text = "<h3 style=\"display: inline\">Meal sizes: </h3>";

    $filler = "";
    if ($mealSize == "regular")
    {
        $filler = "
        <option value=\"regular\" selected>Regular</option>
        <option value=\"large\">Large</option>
        ";
    }
    else
    {
        $filler = "
        <option value=\"regular\">Regular</option>
        <option value=\"large\" selected>Large</option>
        ";
    }

    $toggle = "
    <select id=\"sizeOfSubscriptionPlan\" onChange=\"updateSubscriptionLink()\">
        " . $filler .
        "
    </select> 
    ";
    return $text . $toggle;
}

function prepdfreshAlgorithm($meals, $preferences, $totalMeals)
{
    /* Ommited due to intellectual property rights */
}

function show_quiz_results()
{
    // Product Ids
    $regular_meat = [4149, 4163, 4167];
    $large_meat = [4150, 4164, 4168];
    $regular_veggie = [4158];
    $large_veggie = [4159];

    if ( isset($_GET['totalMeals']) && ! empty($_GET['totalMeals']) ) {
	$totalMeals = sanitize_text_field($_GET["totalMeals"]);
	}
    else
	{
	$totalMeals = 0;
	}

    if ($totalMeals > 999)
    {
	$totalMeals = 999;
    }

    $mealSize = sanitize_text_field($_GET["mealSize"]);
    $isVegetarian = sanitize_text_field($_GET["isVegetarian"]);

    $fishMealPref = 0;
    $pastaMealPref = 0;
    $beefMealPref = 0;
    $veggieMealPref = 0;

if ( isset($_GET['fish']) ) {
        $fishMealPref = sanitize_text_field($_GET["fish"]);
        }
if ( isset($_GET['pasta']) ) {
        $pastaMealPref = sanitize_text_field($_GET["pasta"]);
        }
if ( isset($_GET['beef']) ) {
        $beefMealPref = sanitize_text_field($_GET["beef"]);
        }
if ( isset($_GET['veggie']) ) {
        $veggieMealPref = sanitize_text_field($_GET["veggie"]);
        }

    $firstMeal = rand(0, $totalMeals);
    $remainingMeals = $totalMeals - $firstMeal;
    $secondMeal = rand(0, $remainingMeals);
    $remainingMeals = $remainingMeals - $secondMeal;
    $thirdMeal = rand(0, $remainingMeals);
    $remainingMeals = $remainingMeals - $thirdMeal;
    $fourthMeal = $remainingMeals;

    $meal_quantities = [$firstMeal, $secondMeal, $thirdMeal, $fourthMeal];

    shuffle($meal_quantities);

    $tableStart = quiz_scripts() ."
    <table style=\"width:100%\">
        <tr>
        <th style=\"text-align: center;\">Meal</th>
        <th style=\"text-align: left;\">Quantity</th>
        </tr>
        ";
    
    $tableEnd =  "
        </table>" . "";

    if ($isVegetarian == "true")
    {
        $meal = "";
        if ($mealSize == "regular")
        {
            $meal = $regular_veggie[0];
            $extraMeals = $regular_meat;
        }
        else
        {
            $meal = $large_veggie[0];
            $extraMeals = $large_meat;
        }
        $mealRows = add_meal_row(1, $meal, $totalMeals) .
        add_meal_row(2, $extraMeals[0], 0) . 
        add_meal_row(3, $extraMeals[1], 0) . 
        add_meal_row(4, $extraMeals[2], 0);

	$notesMealArray = [
		$extraMeals[0] => 0,
		$extraMeals[1] => 0,
		$extraMeals[2] => 0,
		$meal => $totalMeals
	];
    }
    else
    {
        if ($mealSize == "regular")
        {
	    $meals = $regular_meat;
            array_push($meals, $regular_veggie[0]);
        }
        else
        {
	    $meals = $large_meat;
            array_push($meals, $large_veggie[0]);
        }

	$mealsArr = [];
	foreach ($meals as $m)
	{
  	    $mealsArr[$m] = 0;
	}
	
	$prefArr = array($meals[0]=>$pastaMealPref, $meals[1]=>$beefMealPref, $meals[2]=>$fishMealPref, $meals[3]=>$veggieMealPref);
	$algorithmResults = prepdfreshAlgorithm($mealsArr, $prefArr, $totalMeals);

	$mealRows = "";
	$iter = 1;
	foreach ($algorithmResults as $mealId=>$val) 
	{
		$mealRows = $mealRows . add_meal_row($iter, $mealId, $val); 
		$iter += 1;
	}
	
	/*
        $mealRows = add_meal_row(1, $meals[0], $meal_quantities[0]) . 
        add_meal_row(2, $meals[1], $meal_quantities[1]) . 
        add_meal_row(3, $meals[2], $meal_quantities[2]) . 
        add_meal_row(4, $meals[3], $meal_quantities[3]);
	*/

	$notesMealArray = [
		$meals[0] => $meal_quantities[0],
		$meals[1] => $meal_quantities[1],
		$meals[2] => $meal_quantities[2],
		$meals[3] => $meal_quantities[3],
	];
    }

    $sizeInformation = show_size_information($mealSize);
    
    $costPerMeal = getCostPerMeal($totalMeals, $mealSize);
    $totalCost = $costPerMeal * $totalMeals;
    $prices_top = "<div id=\"pricePerMeal\">Price per meal: " . number_format((float)$costPerMeal, 2, '.', '') . "</div><div id=\"totalMealsNumberCost\">Total meals: $totalMeals</div><div id=\"totalMealCost\">Total cost: " . number_format((float)$totalCost, 2, '.', '') . "</div>";
    $prices_bottom = "<div id=\"pricePerMeal2\">Price per meal: " . number_format((float)$costPerMeal, 2, '.', '') . "</div><div id=\"totalMealsNumberCost2\">Total meals: $totalMeals</div><div id=\"totalMealCost2\">Total cost: " . number_format((float)$totalCost, 2, '.', '') . "</div>";
    
    $customNote = getNoteText($notesMealArray, $costPerMeal);
    
    $submitButton = show_subscribe_button($totalMeals, $mealSize, $customNote);

    return $prices_top . $sizeInformation . $tableStart . $mealRows . $tableEnd . $prices_bottom . $submitButton;
}

function getCostPerMeal($totalMeals, $mealSize)
{
    $price = 8.99;
    
    if ($mealSize == "large")
    {
	$price += 2;
    }    

    if ($totalMeals > 9)
    {
	return $price - 1;
    }
    else if ($totalMeals > 4)
    {
        return $price - 0.5;
    }
    else
    {
	return $price;
    }
}

function getNoteText($mealsArray, $costPerMeal)
{
    $note = "";
    foreach ($mealsArray as $mealId => $quantity)
    {
	$product = wc_get_product( $mealId );
	$mealName = $product->get_title();
	
	if ($quantity > 0)
	{
	    $note = $note . str_replace("&","",$mealName) . " x " . $quantity . " ";
	}
    }
	
    return $note . " - " . $costPerMeal . " / meal";
}

function add_meal_row($mealNum, $mealId, $quantity)
{
    $product = wc_get_product( $mealId );
    $img_url =  wp_get_attachment_url( $product->get_image_id() );

    $mealIdNum = "a" . rand(0,999) . rand(1,100) . "b";
    $mealName = $product->get_title();

    $mealId = str_replace(' ', '', $mealName);

    if (true)//($quantity != 0)
    {
	/*
        return "
        <tr>
        <td id=\"meal$mealNum\">$mealName</td>
        <td><img src=\"$img_url\" height=\"200\" width=\"300\"></td>
        <td>" . plusButton($mealId) . "<div id=\"$mealId\"style=\"display: inline\"> $quantity </div>" . minusButton($mealId) ."</td>
        </tr>
        ";
	*/
	return "
        <tr style=\"text-align: center\">
        <td><p id=\"meal$mealNum\">$mealName</p><img src=\"$img_url\" height=\"400\" width=\"200\"></td>
        <td>" . minusButton($mealId) . "<div id=\"$mealId\"style=\"display: inline\"> $quantity </div>" . plusButton($mealId) ."</td>
        </tr>
	";
    }

    return "";
}

function minusButton($mealId)
{
    return "<input type=\"button\" value=\"-\" class=\"minus\" style=\"background-color: rgba(255, 0, 0, 0.6); border: none; color: white;\" onclick='minusOne(\"$mealId\")'>";
}

function plusButton($mealId)
{
    return "<input type=\"button\" value=\"+\" class=\"plus\" style=\"background-color: #4CAF50; border: none; color: white;\" onclick='addOne(\"$mealId\")'>";
}

function update_meal_price()
{
	$message = "";

	$price = "8.99 / Meal";

	$message = "<h2 id=\"mealPrice\" style=\"color:rgb(248, 149, 29); text-align: center; margin: 0; padding: 0;\">" . $price . "</h2><br>";

	$meals = "<div style=\"text-align: center\">Total meals: <input onclick=\"updateMealPrice()\" type=\"number\" id=\"myNumber\" min=\"1\" max=\"999\" value=\"1\"></div>";
	
	$updateMealScript = "<script>
	function updateMealPrice() {

		if (document.getElementById(\"myNumber\").value <= 4)
		{
			document.getElementById(\"mealPrice\").innerHTML = \"8.99 / Meal\";
		}
		else if (document.getElementById(\"myNumber\").value <= 9)
		{
			document.getElementById(\"mealPrice\").innerHTML = \"7.99 / Meal\"
		}
		else
		{
			document.getElementById(\"mealPrice\").innerHTML = \"6.99 / Meal\";
		}
	}
	</script>";

	$message = $message . $meals . $updateMealScript;

	return $message;
}

function start_quiz()
    {
        $code = "<form action=\"results\">";
        return $code;
    }

    function submit_quiz()
    {
        $code = "<div style=\"text-align: center\"><br><input type=\"Submit\" value=\"Submit\"></div></form>";

        return $code;
    }

    function subscription_quiz()
    {
        $quiz = start_quiz();

        $quiz = $quiz . subscription_question_one() . "<br><br>" . subscription_question_two() . "<br><br>";
        $quiz = $quiz . subscription_question_three() . "<br><br>" . subscription_question_four() . "<br><br>";
        
        $quiz = $quiz . submit_quiz();

        return $quiz;
    }

    function subscription_question_one()
    {
        $question = "Question 1: How many meals would you like to order each week?<br>";
        $question = $question . "<input type=\"number\" id=\"totalMeals\" name=\"totalMeals\" min=\"1\" max=\"25\" value=\"1\">";
        return $question;
    }

    function subscription_question_two()
    {
        $question = "Question 2: What size would you like your meals to be?<br>";
        $question = $question . "<select name=\"mealSize\">
        <option value=\"regular\">Regular</option>
        <option value=\"large\">Large</option>
        </select>";
        
        return $question;
    }

    function subscription_question_three()
    {
        $question = "Question 3: Are you vegetarian?<br>";
        $question = $question . "<select name=\"isVegetarian\" value=\"false\">
        <option value=\"false\">No</option>
        <option value=\"true\">Yes</option>
        </select>";
        
        return $question;
    }

    function subscription_question_four()
    {
        $question = "Question 4: For each meal, please select a rating of: Would Definitely Order, Might Order, or Wouldn't Order<br><br>";

        $question = $question . quiz_image("https://prepdfresh.com/wp-content/uploads/2019/09/Teriyaki-Salmon-and-Broccoli-Bowls-1-1.jpg");
        $question = $question . quiz_rank_meal("Premium Salmon", "fish");

        $question = $question . quiz_image("https://prepdfresh.com/wp-content/uploads/2018/07/Healthy-chicken-Alfredo-with-broccoli-4.jpg");
        $question = $question . quiz_rank_meal("Chicken Alfredo Pasta", "pasta");

        $question = $question . quiz_image("https://prepdfresh.com/wp-content/uploads/2019/10/Mongolian-Beef-Recipe-2.jpg");
        $question = $question . quiz_rank_meal("Mongolian Beef", "beef");

        $question = $question . quiz_image("https://prepdfresh.com/wp-content/uploads/2019/09/Sweet-Potato-Burrito-Bowl.jpg");
        $question = $question . quiz_rank_meal("(Veggie) Burrito Bowl", "veggie");
        
        return $question;
    }

    function quiz_image($url)
    {
        $image = "<div style=\"display:inline-block;vertical-align:top;\">
        <img src= \"$url\" height=\"150\" width=\"150\"></div>";

        return $image;
    }

    function quiz_rank_meal($mealName, $category)
    {
        $question = "
        <div style=\"display:inline-block;\">
        $mealName
        <br>
        <select name=\"$category\">
        <option value=\"2\">Would definitely order</option>
        <option value=\"1\">Might order</option>
        <option value=\"0\">Would not order</option>
        </select>
        </div>
        ";

        return  $question;
    }
    ?>
