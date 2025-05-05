<?php
require __DIR__ . '/vendor/autoload.php';

use Supabase\CreateClient; // Use the class name you found

// $url = 'https://grccvmzwgzqdjvdrtfbd.supabase.co';
// $key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImdyY2N2bXp3Z3pxZGp2ZHJ0ZmJkIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDYxMTYzNzQsImV4cCI6MjA2MTY5MjM3NH0.GkZsfUfdOi9Xvdu2muYi6_rDipEGhLW6eTj0W6RMvr4';

$supabase_url = getenv('supabase_url');
$supabase_key = getenv('supabase_key');

$supabase = new CreateClient($url, $key);
?>


<?php
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

$supabase_url = 'https://grccvmzwgzqdjvdrtfbd.supabase.co';
$supabase_key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImdyY2N2bXp3Z3pxZGp2ZHJ0ZmJkIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDYxMTYzNzQsImV4cCI6MjA2MTY5MjM3NH0.GkZsfUfdOi9Xvdu2muYi6_rDipEGhLW6eTj0W6RMvr4'; // Or anon key if only public

$client = new Client([
    'base_uri' => $supabase_url . '/rest/v1/',
    'headers' => [
        'apikey' => $supabase_key,
        'Authorization' => 'Bearer ' . $supabase_key,
        'Content-Type' => 'application/json'
    ]
]);