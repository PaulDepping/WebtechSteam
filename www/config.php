<?php
// Basic connection settings
$baseUrl = 'http://localhost:5000';


function GetUserData($username)
{
    global $baseUrl;
    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => ($baseUrl . '/db/rest/user/' . urlencode($username)),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        )
    );

    $response = curl_exec($curl);

    curl_close($curl);

    $json = json_decode($response, associative: true);
    if (is_null($json)) {
        throw new Exception('Unknown Response from REST-API! Could not decode to JSON!');
    }
    if (!($json['success'])) {
        throw new Exception('REST-API was not successful!');
    }
    return $json;
}

function PostUserData($username, $password_hash)
{
    global $baseUrl;
    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => 'http://localhost:5000/db/rest/user',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array('username' => $username, 'password_hash' => $password_hash)),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        )
    );

    $response = curl_exec($curl);

    curl_close($curl);

    $json = json_decode($response, associative: true);

    if (is_null($json)) {
        throw new Exception('Unknown Response from REST-API! Could not decode to JSON!');
    }
    if (!($json['success'])) {
        throw new Exception('REST-API was not successful!');
    }
    return $json;
}

function GetWatchlist($user_id, $filter_data = array())
{
    global $baseUrl;
    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => ($baseUrl . '/db/rest/watchlist/' . urlencode($user_id) . '?' . http_build_query($filter_data)),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        )
    );

    $response = curl_exec($curl);

    curl_close($curl);

    $json = json_decode($response, associative: true);
    if (is_null($json)) {
        throw new Exception('Unknown Response from REST-API! Could not decode to JSON!');
    }
    if (!($json['success'])) {
        throw new Exception('REST-API was not successful!');
    }
    return $json;

}

function AddToWatchlist($user_id, $title, $seasons, $genre, $platform, $rating)
{
    global $baseUrl;
    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => ($baseUrl . '/db/rest/watchlist/' . urlencode($user_id)),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array('title' => $title, 'seasons' => $seasons, "genre" => $genre, 'platform' => $platform, 'rating' => $rating)),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),

        )
    );

    $response = curl_exec($curl);

    curl_close($curl);

    $json = json_decode($response, associative: true);
    if (is_null($json)) {
        throw new Exception('Unknown Response from REST-API! Could not decode to JSON!');
    }
    if (!($json['success'])) {
        throw new Exception('REST-API was not successful!');
    }
    return $json;

}

function ChangeWatchlist($series_id, $user_id = null, $title = null, $seasons = null, $genre = null, $platform = null, $rating = null)
{
    global $baseUrl;

    $arguments = array();
    if (!is_null($user_id)) {
        $arguments['user_id'] = $user_id;
    }
    if (!is_null($title)) {
        $arguments['title'] = $title;
    }
    if (!is_null($seasons)) {
        $arguments['seasons'] = $seasons;
    }
    if (!is_null($platform)) {
        $arguments['platform'] = $platform;
    }
    if (!is_null($rating)) {
        $arguments['rating'] = $rating;
    }


    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => ($baseUrl . '/db/rest/watchlist/' . urlencode($series_id)),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'SET',
            CURLOPT_POSTFIELDS => json_encode($arguments),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        )
    );

    $response = curl_exec($curl);

    curl_close($curl);

    $json = json_decode($response, associative: true);
    if (is_null($json)) {
        throw new Exception('Unknown Response from REST-API! Could not decode to JSON!');
    }
    if (!($json['success'])) {
        throw new Exception('REST-API was not successful!');
    }
    return $json;

}

function DeleteWatched($series_id)
{
    global $baseUrl;

    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => ($baseUrl . '/db/rest/watchlist/' . urlencode($series_id)),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',

        )
    );

    $response = curl_exec($curl);

    curl_close($curl);

    $json = json_decode($response, associative: true);
    if (is_null($json)) {
        throw new Exception('Unknown Response from REST-API! Could not decode to JSON!');
    }
    if (!($json['success'])) {
        throw new Exception('REST-API was not successful!');
    }
    return $json;

}

