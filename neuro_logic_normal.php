<?PHP
$learning_rate = 0.005;
//текущий input
$input = [1, 0, 0];
//train input
$allinput = [
    [0, 0, 0, 0],
    [0, 0, 1, 0],
    [0, 1, 0, 1],
    [0, 1, 1, 0],
    [1, 0, 0, 1],
    [1, 0, 1, 1],
    [1, 1, 0, 0],
    [1, 1, 1, 0]
];
function sigmoid(float $val)
{
    return (1 / (1 + exp(-$val*2)));
}

function decide(float $val)
{
    if ($val >= 0.5) {
        return 1;
    }
    return 0;
}

$hidden_layer1 = array(
    "brain" => [
        [stats_rand_gen_normal(0, 1), stats_rand_gen_normal(0, 1), stats_rand_gen_normal(0, 1)],
        [stats_rand_gen_normal(0, 1), stats_rand_gen_normal(0, 1), stats_rand_gen_normal(0, 1)],
        [stats_rand_gen_normal(0, 1), stats_rand_gen_normal(0, 1), stats_rand_gen_normal(0, 1)]],
    "data" => [0, 0, 0]
);
$output_layer = array(
    "brain" => [stats_rand_gen_normal(0, 1), stats_rand_gen_normal(0, 1), stats_rand_gen_normal(0, 1)],
    "data" => 0
);
function calculate()
{
    global $output_layer;
    global $hidden_layer1;
    global $input;
    $hidden_layer1["data"][0] = sigmoid($input[0] * $hidden_layer1["brain"][0][0] + $input[1] * $hidden_layer1["brain"][0][1] + $input[2] * $hidden_layer1["brain"][0][2]);
    $hidden_layer1["data"][1] = sigmoid($input[0] * $hidden_layer1["brain"][1][0] + $input[1] * $hidden_layer1["brain"][1][1] + $input[2] * $hidden_layer1["brain"][1][2]);
    $hidden_layer1["data"][2] = sigmoid($input[0] * $hidden_layer1["brain"][2][0] + $input[1] * $hidden_layer1["brain"][2][1] + $input[2] * $hidden_layer1["brain"][2][2]);
    $output_layer["data"] = sigmoid($hidden_layer1["data"][0] * $output_layer["brain"][0] + $hidden_layer1["data"][1] * $output_layer["brain"][1] + $hidden_layer1["data"][2] * $output_layer["brain"][2]);
}

function train()
{
    global $allinput;
    global $input;
    global $output_layer;
    global $hidden_layer1;
    global $learning_rate;
    shuffle($allinput);
    foreach ($allinput as $current) {

        $input[0] = $current[0];
        $input[1] = $current[1];
        $input[2] = $current[2];
        calculate();
        $error = $output_layer["data"] - $current[3];
//        if ($input == [0, 1, 0])
//            echo $error . PHP_EOL;

        //умножаем на производную, получаем насколько надо корректировать последний слой
        $delta_weight = $error * $output_layer["data"] * (1 - $output_layer["data"]);
        $output_layer["brain"][0] = $output_layer["brain"][0] - $hidden_layer1["data"][0] * $delta_weight * $learning_rate;
        $output_layer["brain"][1] = $output_layer["brain"][1] - $hidden_layer1["data"][1] * $delta_weight * $learning_rate;
        $output_layer["brain"][2] = $output_layer["brain"][2] - $hidden_layer1["data"][2] * $delta_weight * $learning_rate;
        //теперь посчитаем для скрытого слоя. Верхний нейрон.
        $errorUp = $output_layer["brain"][0] * $delta_weight;
        $delta_weightUp = $errorUp * $hidden_layer1["data"][0] * (1 - $hidden_layer1["data"][0]);
        $hidden_layer1["brain"][0][0] = $hidden_layer1["brain"][0][0] - $current[0] * $delta_weightUp * $learning_rate;
        $hidden_layer1["brain"][0][1] = $hidden_layer1["brain"][0][1] - $current[1] * $delta_weightUp * $learning_rate;
        $hidden_layer1["brain"][0][2] = $hidden_layer1["brain"][0][2] - $current[2] * $delta_weightUp * $learning_rate;
        //теперь посчитаем для скрытого слоя. Средний нейрон.
        $errorDown = $output_layer["brain"][1] * $delta_weight;
        $delta_weightDown = $errorDown * $hidden_layer1["data"][1] * (1 - $hidden_layer1["data"][1]);
        $hidden_layer1["brain"][1][0] = $hidden_layer1["brain"][1][0] - $current[0] * $delta_weightDown * $learning_rate;
        $hidden_layer1["brain"][1][1] = $hidden_layer1["brain"][1][1] - $current[1] * $delta_weightDown * $learning_rate;
        $hidden_layer1["brain"][1][2] = $hidden_layer1["brain"][1][2] - $current[2] * $delta_weightDown * $learning_rate;
        //теперь посчитаем для скрытого слоя. Нижний нейрон.
        $errorDown2 = $output_layer["brain"][2] * $delta_weight;
        $delta_weightDown2 = $errorDown2 * $hidden_layer1["data"][2] * (1 - $hidden_layer1["data"][2]);
        $hidden_layer1["brain"][2][0] = $hidden_layer1["brain"][2][0] - $current[0] * $delta_weightDown2 * $learning_rate;
        $hidden_layer1["brain"][2][1] = $hidden_layer1["brain"][2][1] - $current[1] * $delta_weightDown2 * $learning_rate;
        $hidden_layer1["brain"][2][2] = $hidden_layer1["brain"][2][2] - $current[2] * $delta_weightDown2 * $learning_rate;
    }
}

function check_all()
{
    global $allinput;
    global $input;
    global $output_layer;
    foreach ($allinput as $current) {
        $input[0] = $current[0];
        $input[1] = $current[1];
        $input[2] = $current[2];
        calculate();
        echo $input[0] . " " . $input[1] . " " . $input[2] . " expected: " . $current[3] . " got: " . decide($output_layer["data"]) . " weight: " . $output_layer["data"] . PHP_EOL;
    }
}

echo "Before train: random weight" . PHP_EOL;
check_all();
for ($i = 0; $i < 200000; ++$i) {
    train();
    //echo PHP_EOL.$i.PHP_EOL;
}

$allinput = [
    [0, 0, 0, 0],
    [0, 0, 1, 0],
    [0, 1, 0, 1],
    [0, 1, 1, 0],
    [1, 0, 0, 1],
    [1, 0, 1, 1],
    [1, 1, 0, 0],
    [1, 1, 1, 0]
];

echo PHP_EOL . "After train" . PHP_EOL;
check_all();

//раскладываем в днф, смотрим сколько слагаемых
