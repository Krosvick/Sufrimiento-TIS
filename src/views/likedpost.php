   <!-- header -->
   <?php require('partials/nav.php') ?>
   <!-- end header -->

<div>
    <div class="flex justify-center mt-5 mx-5">
        <h1 class="text-3xl font-bold pb-5 inline">Reviewed Movies</h1>
    </div>

<div class="flex flex-wrap justify-center">
    <?php foreach ($user_movies as $movie): ?>
        <div class="max-w-sm rounded overflow-hidden shadow-lg m-5">
            <a href="/movie/<?= $movie->get_id() ?>">
                <img class="w-full" src="<?= $movie->get_poster_path() ? "https://image.tmdb.org/t/p/w500" . $movie->get_poster_path() : '/views/images/PLACEHOLDER.png' ?>" alt="Movie page">
            </a>
            <div class="px-6 py-4 bg-slate-100 border-slate-200 h-full">
                <div class="font-bold text-xl mb-2 text-black"><?= $movie->get_original_title() ?></div>
                <p class="text-gray-700 text-base">
                    <?= $movie->get_overview() ?>
                </p>
                <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2"><?= $movie->get_release_date() ?></span>
                <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2"><?= $movie->get_director() ?></span>
                <p>&nbsp</p>
                <hr class="color-black">
                <p>&nbsp</p>
                <div class="flex justify-right">
                <button class="btn btn-primary bg-purple-700 text-white border-2 border-purple-800 hover:border-purple-800 hover:bg-white hover:text-black">Vist Page</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>