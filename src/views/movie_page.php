<!-- arreglar el tema del directorio -->
<?php require('partials/nav.php')?>

<?php 
    #dd($Ratings[0]->get_user()->get_username());
?>

<html lang="en">
    <body>

        <!-- movie descripction-->
        <div class="p-8 text-white">
            <form class="bg-gradient-to-r from-purple-900 via-purple-700 to-purple-900 max-w-4xl mx-auto my-8 px-10 py-8 text-white shadow-lg rounded-lg flex items-center">
                <div class="flex flex-col justify-start">
                    <div class="poster mr-8">
                        <!-- HERE SHOULD BE CHANGED TO DYNAMIC FUNCTIONS -->
                        <img src="https://image.tmdb.org/t/p/w780<?= $Movie->get_poster_path()?>" alt="Movie Poster" class="max-w-full min-fit">
                    </div>
                    <div>
                        <button type="button" onclick="toggleDiv()" class="text-white hover:text-purple border border-white hover:bg-white hover:text-black focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-8 py-2.5 text-center mt-2 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">Rate Movie</button>
                    </div>
                </div>
                <div class="details">
                    <h1 class="text-4xl text-white font-bold mb-4"><?= $Movie->get_original_title()?></h1>
                    <p class="text-lg text-gray-100 mb-2"><?= $Movie->get_overview() ?></p>
                    <p class="text-sm text-gray-100">Release: <?php 
                        $date = $Movie->get_release_date();
                        $year = substr($date, 0, 4);
                        echo $year;
                    ?></p>
                    <p class="text-sm text-gray-100">Director: <?=
                        $Movie->get_director();
                    ?></p>
                </div>
            </form>
        </div>

        <hr>
        
            <div class="bg-white max-w-4xl mx-auto my-8 px-10 py-8 text-black bg-opacity-50 shadow-md border-white border-4">
                
                <span>Reviews</u></span>
                
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
                    } ?>
                    <?php $crrt = 1; ?>
                    
                    <?php
                    
                    foreach ($Ratings as $rating):
                        //echo $rating->get_rating();
                        //$starsGiven = $rating->rating;
                        //print_r("movie");
                        
                        $crrt++;
                        $starsGiven = $rating->get_rating();?>

            

                <div class="max-w-screen-md mx-auto mt-8">
                    
                    <!-- Single Review Component -->
                    
                    <div class="bg-white shadow-md rounded-lg p-4 mb-4 flex items-start">
                        <article>

                            <?php print_r($page); ?>

                            <div class="flex items-center mb-4">
                                <img class="w-10 h-10 me-4 rounded-full" src="https://cdn.discordapp.com/attachments/324358291561906186/1172908205068800160/image.png?ex=656206e3&is=654f91e3&hm=ca8e71b36e8f7c2afb64674c51780e94bca641beb6adb0a7ede617da1e3a5d1c&" alt="">
                                <div class="font-medium dark:text-white">
                                    <p class= "ml-1.5"><?= $rating->get_user()->get_username()?></p>
                                </div>
                            </div>
                
                            <div class="flex items-center mb-1 space-x-1 rtl:space-x-reverse">
                                <?php displaystar($starsGiven); ?>
                            </div>
                            
                            <p class="mb-2 text-gray-500 dark:text-gray-400"></p>
                        
                            
                        </article>
                    </div>
                    <?php endforeach; ?>
                    
                </div>

                <div align="center">
                    <nav aria-label="Page navigation example">
                    <ul class="inline-flex -space-x-px text-base h-10">
                        
                        <!-- Return to the previous page -->
                        <li>
                            <a href="<?= $page - 1?>" class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                        </li>

                        <!-- Actual page -->
                        <li>
                            <a class="z-10 flex items-center justify-center px-4 h-10 leading-tight text-purple-600 border border-purple-300 bg-purple-50 hover:bg-purple-100 hover:text-purple-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white"><?= $page?></a>
                        </li>

                        <!-- Go to the next page -->
                        <li>
                            <a href="<?= $page + 1?>" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                        </li>
                    </ul>
                    </nav>
                </div>
            </div>
    </body>
    
</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>