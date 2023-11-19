<!-- arreglar el tema del directorio -->
<?php require('nav.php')?>

<?php 
    #dd($base_url);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $data['Movie']['original_title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles-movie.css">
</head>
<body>
    <!-- movie descripction-->
    <div class="p-8 text-white">
        <form class="bg-gradient-to-r from-purple-900 via-purple-700 to-purple-900 max-w-4xl mx-auto my-8 px-10 py-8 text-white shadow-lg rounded-lg flex items-center">
            <div class="poster mr-8">
                <!-- HERE SHOULD BE CHANGED TO DYNAMIC FUNCTIONS -->
                <img src="https://image.tmdb.org/t/p/w780<?php echo $data['Movie']['poster_path']?>" alt="Movie Poster" class="max-w-full min-fit">
            </div>
            <div class="details">
                <h1 class="text-4xl text-white font-bold mb-4"><?php echo $data['Movie']['original_title']?></h1>
                <p class="text-lg text-gray-100 mb-2"><?php echo $data['Movie']['overview']?></p>
                <p class="text-sm text-gray-100">Release: <?php 
                    $date = $data['Movie']['release_date'];
                    $year = substr($date, 0, 4);
                    echo $year;
                ?></p>
                <p class="text-sm text-gray-100">Director: <?php 
                    echo $data['Movie']['director'];
                ?></p>
            </div>
        </form>
    </div>
    <!-- commentary -->

    <!-- funcion foreach para los comentarios -->
    <!-- HERE SHOULD BE CHANGED TO DYNAMIC FUNCTIONS -->
    <div class="max-w-screen-md mx-auto mt-8">
        <!-- Single Review Component -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-4 flex items-start">
            <article>

            <?php

            //funcion para mostrar cuantas estrellas le dio el suaer a la pelicula
            
            function displaystar($numStars) {
                $maxStars = 5;
                
                for ($i = 1; $i <= $maxStars; $i++) {
                    if ($i <= $numStars) {
                        
                        echo '<svg class="w-4 h-4 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                </svg>';
                    } else {
                        
                        echo '<svg class="w-4 h-4 text-gray-300 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                </svg>';
                    }
                }
            }
            $starsGiven = ['User']['user_rating']
            
            ?>
                <div class="flex items-center mb-4">
                    <img class="w-10 h-10 me-4 rounded-full" src="https://cdn.discordapp.com/attachments/324358291561906186/1172908205068800160/image.png?ex=656206e3&is=654f91e3&hm=ca8e71b36e8f7c2afb64674c51780e94bca641beb6adb0a7ede617da1e3a5d1c&" alt="">
                    <div class="font-medium dark:text-white">
                        <p class= "ml-1.5"><?php ['User']['username']?></p>
                    </div>
                </div>
                <div class="flex items-center mb-1 space-x-1 rtl:space-x-reverse">
                    <?php displaystar($starsGiven); ?>
                </div>
                
                <p class="mb-2 text-gray-500 dark:text-gray-400">user_rating</p>
              
                
            </article>
        </div>

 
        <!-- Add more review components as needed -->
    </div>
</body>
</html>