<?php

function func_sim_distance($matrix, $target_user, $other_user)
{
    $similar = array();
    $sum = 0;

    foreach ($matrix[$target_user] as $key => $data) {
        //check if there's item/data in matrix that matches between these two user.
        if (array_key_exists($key, $matrix[$other_user])) {
            $similar[$key] = 1;
        }
    }

    //if no similarity/no item data that matches
    if ($similar == 0) {
        return 0;
    }

    //if have similarity/item data that matches
    foreach ($matrix[$target_user] as $key => $data) {
        if (array_key_exists($key, $matrix[$other_user])) {
            //use Euclidean Distance Formula to calculate similarity distance
            $sum = $sum + pow($data - $matrix[$other_user][$key], 2);
        }
    }
    return 1 / (1 + sqrt($sum));
}

function func_recommendation($matrix, $target_user)
{
    $total_arr = array();
    $sim_sums_arr = array();
    $ranking_arr = array();
    if (array_key_exists($target_user, $matrix)) {
        // Go through all the matrix
        foreach ($matrix as $other_user => $data) {
            //check if same user
            if ($other_user != $target_user) {
                $similarity = func_sim_distance($matrix, $target_user, $other_user);
            }

            foreach ($matrix[$other_user] as $key => $data) {
                //check if the other user's item rating exist in target user or not, if exist no need to recommend
                // Use collaborative method formula to calculate and predict
                if (!array_key_exists($key, $matrix[$target_user])) {
                    if (!array_key_exists($key, $total_arr)) {
                        //define initial value for total_arr if key does not exist
                        $total_arr[$key] = 0;
                    }

                    $total_arr[$key] += $matrix[$other_user][$key] * $similarity;

                    if (!array_key_exists($key, $sim_sums_arr)) {
                        //define initial value for sim_sums_arr if key does not exist
                        $sim_sums_arr[$key] = 0;
                    }
                    $sim_sums_arr[$key] += $similarity;
                }
            }
        }
        foreach ($total_arr as $key => $data) {
            // var_dump($key);
            // var_dump($data);
            $ranking_arr[$key] = $data / $sim_sums_arr[$key];
        }
        return $ranking_arr;
    }
}
