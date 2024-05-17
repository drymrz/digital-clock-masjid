<?php
$file    = 'db/database.json';
if (!file_exists($file)) {
    echo "<h1>Jalankan admin terlebih dahulu</h1>";
    die;
}
$json     = file_get_contents($file);
$db        = json_decode($json, true);
$showDb    = $db;
unset($showDb['akses']);

$info_timer            = $db['timer']['info']         * 1000;    //detik
$wallpaper_timer    = $db['timer']['wallpaper'] * 1000;
$adzan_timer        = $db['timer']['adzan']     * 1000 * 60; //menit
// $iqomah_timer		= $db['timer']['iqomah'] 	* 1000 * 60;
$sholat_timer        = $db['timer']['sholat']     * 1000 * 60;

//optional
$khutbah_jumat        = $db['jumat']['duration']     * 1000 * 60;
$sholat_tarawih        = $db['tarawih']['duration']     * 1000 * 60;

//Logo
// nge trik ==> kalo replace file, di display logo yang lama masih kesimpen di cache ==> solusi ganti logo ganti nama file 
$dirLogo    = 'logo/';
$filesLogo    = array_diff(scandir($dirLogo), array('.', '..', 'Thumbs.db'));
$filesLogo    = array_values($filesLogo); //re index
$logo        = $filesLogo[0];


$dir    = 'wallpaper/';
$files    = array_diff(scandir($dir), array('.', '..', 'Thumbs.db'));
$wallpaper    = '';
$i    = 0;
foreach ($files as $v) {
    $active    = $i == 0 ? 'active' : '';
    $wallpaper    .= '<div class="item slides ' . $active . '"><div style="background-image: url(wallpaper/' . $v . ');"></div></div>';
    $i++;
}
// print_r($files);die;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Clock</title>
    <link rel="stylesheet" href="./css/style_new.css">
</head>

<body class="overflow-hidden">
    <header class="fixed z-[10] flex w-screen">
        <div id="left-header" class="w-[66vw] h-[100px] bg-[#3D712F] rounded-br-[74px] flex items-center px-[24px] z-10">
            <img src="./img/logo-mds.png" alt="" class="w-[90px]">
            <div class="flex flex-col pl-[2rem]">
                <h2 class="font-Montagu font-bold text-white text-[2rem]"><span class="text-[#A8BF02]">MASJID</span> DARUSSALAM CENDANA</h2>
                <!-- <p class="text-white font-thin text-[16px]">PERUMAHAN CENDANA RT 2 RW 36 KEL.BELIAN KEC.BATAM KOTA - KOTA BATAM</p> -->
            </div>
        </div>
        <div id="right-header" class="w-[35vw] h-[66px] bg-[#000000bf] flex items-center pr-[24px] justify-end absolute right-0">
            <p class="font-Montserrat text-white font-semibold text-[1.1rem]">Rabu <span class="text-[#E0E0E0]">15 Mei 2024 / 7 Dhu al-Qi'dah 1445</span></p>
        </div>
    </header>
    <main class="w-screen h-screen bg-[url('./img/masjid.png')] bg-cover bg-no-repeat bg-center">
        <div class="overlay bg-black w-screen h-screen opacity-[0.23]"></div>
    </main>
    <footer class="absolute bottom-0 w-screen h-[220px] flex ">
        <div id="current-time" class="w-[25vw] bg-[#3D712F] h-[100%] flex flex-col text-[#FFB800] font-Montserrat items-center justify-center pt-[1.2%]">
            <p class="text-[20px] font-thin">Waktu Saat Ini</p>
            <h1 class="font-bold text-[64px]">17:36:11</h1>
            <img class="w-[75%] mt-[-1.2%]" src="./img/clock-line.png" alt="">
        </div>
        <div class="right-wrapper w-[75vw] flex flex-col font-Montserrat">
            <div class="prayerTimes grid grid-flow-col auto-cols-auto divide-x divide-neutral-900/[.3]">
                <div class="bg-[#3D712F] h-[150px] flex flex-col justify-center">
                    <p class="text-white font-thin text-[20px] pl-[20%]">Subuh</p>
                    <h1 class="font-bold text-white text-[48px] text-center">04:36</h1>
                </div>
                <div class="bg-[#3D712F] h-[150px] flex flex-col justify-center">
                    <p class="text-white font-thin text-[20px] pl-[20%]">Dzuhur</p>
                    <h1 class="font-bold text-white text-[48px] text-center">12:36</h1>
                </div>
                <div class="bg-[#3D712F] h-[150px] flex flex-col justify-center">
                    <p class="text-white font-thin text-[20px] pl-[20%]">Ashar</p>
                    <h1 class="font-bold text-white text-[48px] text-center">15:36</h1>
                </div>
                <div class="bg-[#3D712F] h-[150px] flex flex-col justify-center">
                    <p class="text-white font-thin text-[20px] pl-[20%]">Maghrib</p>
                    <h1 class="font-bold text-white text-[48px] text-center">18:36</h1>
                </div>
                <div class="bg-[#3D712F] h-[150px] flex flex-col justify-center">
                    <p class="text-white font-thin text-[20px] pl-[20%]">Isya</p>
                    <h1 class="font-bold text-white text-[48px] text-center">19:36</h1>
                </div>
            </div>
            <marquee class="bg-[#000]/[.80] h-[45px] text-white flex items-center">
                SELAMAT DATANG DI MASJID DARUSSALAM CENDANA RW 36 • HARAP MEMATIKAN ATAU MENONAKTIFKAN TELEPON GENGGAM AGAR TIDAK MENGGANGU KEKHUSYUKAN IBADAH
            </marquee>
        </div>
    </footer>
    <script src="./js/tailwind.js"></script>
    <script>

    </script>
</body>

</html>